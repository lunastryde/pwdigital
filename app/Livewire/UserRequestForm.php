<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use App\Models\FormRequestDevice;
use App\Models\FormRequestBooklet;
use App\Models\FormRequestFinancial;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserRequestForm extends Component
{
    protected $primaryKey = 'request_id';

    public $requestType = 'device'; // device, booklet, financial
    
    // User info (read-only from form_personal)
    public $pwd_number;
    public $full_name;
    public $contact_no;
    public $disability_type;
    public $address;

    // Keep applicant_id so we can re-check locks anytime
    public ?int $applicantId = null;
    
    // Booklet Request Fields
    public $booklet_type = null;
    public $booklet_reason;

    // Device Request Fields
    public $local_social_pension = null;
    public $device_requested;
    public $device_reason;
    
    // Financial Request Fields
    public $financial_reason;

    // 🔹 NEW: per-type lock flags + messages
    public bool $deviceLocked = false;
    public ?string $deviceLockMessage = null;

    public bool $bookletLocked = false;
    public ?string $bookletLockMessage = null;

    public bool $financialLocked = false;
    public ?string $financialLockMessage = null;

    public function mount()
    {
        $personal = FormPersonal::where('account_id', Auth::id())
            ->where('status', 'Finalized')
            ->whereNotNull('date_issued')
            ->latest('date_issued')
            ->first();

        if (!$personal) {
            return redirect()
                ->route('home', ['tab' => 'applications'])
                ->with('error', 'Your PWD ID must be released first before you can submit requests.');
        }

        $this->applicantId = $personal->applicant_id;

        $this->pwd_number = $personal->pwd_number;
        $this->full_name = trim(($personal->fname ?? '') . ' ' . ($personal->mname ?? '') . ' ' . ($personal->lname ?? ''));
        $this->contact_no = $personal->contact_no;
        $this->disability_type = $personal->disability_type;
        $this->address = trim(
            ($personal->house_no ? $personal->house_no . ', ' : '') .
            ($personal->street ? $personal->street . ', ' : '') .
            ($personal->barangay ?? '') .
            ($personal->municipality ? ', ' . $personal->municipality : '') .
            ($personal->province ? ', ' . $personal->province : '')
        );

        $this->updateLockStates();
    }

    public function setRequestType($type)
    {
        $this->requestType = $type;
        $this->resetValidation();

        $this->updateLockStates();
    }

    protected function updateLockStates(): void
    {
        if (!$this->applicantId) {
            return;
        }

        [$this->deviceLocked, $this->deviceLockMessage] = $this->computeLockForType('device');
        [$this->bookletLocked, $this->bookletLockMessage] = $this->computeLockForType('booklet');
        [$this->financialLocked, $this->financialLockMessage] = $this->computeLockForType('financial');
    }

    protected function computeLockForType(string $type): array
    {
        $latest = FormRequest::where('applicant_id', $this->applicantId)
            ->where('request_type', $type)
            ->orderByDesc('submitted_at')
            ->first();

        // No previous request of this type → not locked
        if (!$latest) {
            return [false, null];
        }

        $status = $latest->status;
        $label  = $type === 'device'
            ? 'device request'
            : ($type === 'booklet' ? 'booklet request' : 'financial assistance request');

        // Pending / still being processed → completely block
        if (in_array($status, ['Pending', 'In Review', 'Processing'])) {
            return [
                true,
                "You already have a pending {$label}. Please wait for it to be reviewed before submitting another."
            ];
        }

        // Explicitly allow re-submit after REJECTED
        if ($status === 'Rejected') {
            return [false, null];
        }

        // Finalized → apply cooldown (1 month or 1 year)
        if ($status === 'Finalized') {
            // Use reviewed_at as the decision date, fallback to submitted_at
            $base = $latest->reviewed_at ?: $latest->submitted_at;
            $base = $base ? Carbon::parse($base) : now();

            if ($type === 'financial') {
                $unlockAt = $base->copy()->addYear();
            } else {
                $unlockAt = $base->copy()->addMonth();
            }

            if ($unlockAt->isFuture()) {
                $formatted = $unlockAt->format('M d, Y');
                $extra = $type === 'financial'
                    ? "You can submit another financial assistance request after {$formatted}."
                    : "You can submit another {$label} after {$formatted}.";

                return [
                    true,
                    "Your last {$label} was finalized on " . $base->format('M d, Y') . ". {$extra}"
                ];
            }

            // Cooldown already passed → not locked
            return [false, null];
        }

        return [
            true,
            "You already have an active {$label}. Please wait for it to be processed before submitting another."
        ];
    }

    protected function rules()
    {
        $rules = [];
        
        switch ($this->requestType) {
            case 'device':
                $rules = [
                    'local_social_pension' => 'required|in:Y,N',
                    'device_requested' => 'required|string|max:255',
                    'device_reason' => 'required|string|max:1000',
                ];
                break;
            case 'booklet':
                $rules = [
                    'booklet_type' => 'required|in:grocery,medicine',
                    'booklet_reason' => 'required|string|max:1000',
                ];
                break;
            case 'financial':
                $rules = [
                    'financial_reason' => 'required|string|max:1000',
                ];
                break;
        }
        
        return $rules;
    }

    public function submit()
    {
        $this->updateLockStates();

        if (
            ($this->requestType === 'device'    && $this->deviceLocked) ||
            ($this->requestType === 'booklet'   && $this->bookletLocked) ||
            ($this->requestType === 'financial' && $this->financialLocked)
        ) {
            $msg = $this->requestType === 'device'
                ? ($this->deviceLockMessage ?? 'You cannot submit another device request yet.')
                : ($this->requestType === 'booklet'
                    ? ($this->bookletLockMessage ?? 'You cannot submit another booklet request yet.')
                    : ($this->financialLockMessage ?? 'You cannot submit another financial assistance request yet.')
                  );

            session()->flash('error', $msg);
            return;
        }

        $this->validate();

        $personal = FormPersonal::find($this->applicantId);

        if (!$personal) {
            session()->flash('error', 'Your PWD record could not be found. Please contact support.');
            return;
        }

        try {
            // Step 1: Save parent record
            $formRequest = FormRequest::create([
                'applicant_id' => $personal->applicant_id,
                'request_type' => $this->requestType,
                'status'       => 'Pending',
                'submitted_at' => now(),
            ]);

            // Step 2: Save child record depending on type
            switch ($this->requestType) {
                case 'device':
                    FormRequestDevice::create([
                        'request_id'          => $formRequest->request_id,
                        'reason_for_request'  => $this->device_reason,
                        'device_requested'    => $this->device_requested,
                        'local_social_pension'=> $this->local_social_pension,
                    ]);
                    session()->flash('success', 'Device request submitted successfully!');
                    break;

                case 'booklet':
                    FormRequestBooklet::create([
                        'request_id'         => $formRequest->request_id,
                        'booklet_type'       => $this->booklet_type,
                        'reason_for_request' => $this->booklet_reason,
                    ]);
                    session()->flash('success', 'Booklet request submitted successfully!');
                    break;

                case 'financial':
                    FormRequestFinancial::create([
                        'request_id'         => $formRequest->request_id,
                        'reason_for_request' => $this->financial_reason,
                    ]);
                    session()->flash('success', 'Financial assistance request submitted successfully!');
                    break;
            }

            // Reset only the form fields, keep applicant info + locks
            $this->reset([
                'local_social_pension',
                'device_requested',
                'device_reason',
                'booklet_type',
                'booklet_reason',
                'financial_reason',
            ]);

            $this->updateLockStates();

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while submitting your request. Please try again.');
            \Log::error('Request submission error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user-request-form');
    }
}
