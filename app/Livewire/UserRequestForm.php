<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use App\Models\FormRequestDevice;
use App\Models\FormRequestBooklet;
use App\Models\FormRequestFinancial;
use Illuminate\Support\Facades\Auth;

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
    
    // Booklet Request Fields
    public $booklet_type = null;
    public $booklet_reason;

    // Device Request Fields
    public $local_social_pension = null;
    public $device_requested;
    public $device_reason;
    
    // Financial Request Fields
    public $financial_reason;

    public function mount()
    {
        // Load user data from form_personal
        $personal = FormPersonal::where('account_id', Auth::id())->first();
        
        if (!$personal) {
            return redirect()->route('application.form')->with('error', 'Please complete your PWD ID application first.');
        }

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
    }

    public function setRequestType($type)
    {
        $this->requestType = $type;
        $this->resetValidation();
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
        $this->validate();

        $personal = FormPersonal::where('account_id', Auth::id())->first();

        // dd('Validation passed, trying to create record...');

        try {
            // Step 1: Save parent record
            $formRequest = \App\Models\FormRequest::create([
                'applicant_id' => $personal->applicant_id,
                'request_type' => $this->requestType,
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            // Step 2: Save child record depending on type
            switch ($this->requestType) {
                case 'device':
                    FormRequestDevice::create([
                        'request_id' => $formRequest->request_id,
                        'reason_for_request' => $this->device_reason,
                        'device_requested' => $this->device_requested,
                        'local_social_pension' => $this->local_social_pension,
                    ]);
                    session()->flash('success', 'Device request submitted successfully!');
                    break;

                case 'booklet':
                    FormRequestBooklet::create([
                        'request_id' => $formRequest->request_id,
                        'booklet_type' => $this->booklet_type,
                        'reason_for_request' => $this->booklet_reason,
                    ]);
                    session()->flash('success', 'Booklet request submitted successfully!');
                    break;

                case 'financial':
                    FormRequestFinancial::create([
                        'request_id' => $formRequest->request_id,
                        'reason_for_request' => $this->financial_reason,
                    ]);
                    session()->flash('success', 'Financial assistance request submitted successfully!');
                    break;
            }

            // Reset form
            $this->reset(['local_social_pension', 'device_requested', 'device_reason', 'booklet_type', 'booklet_reason', 'financial_reason']);

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