<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Carbon\Carbon;


class StaffView extends Component
{
    public string $section = 'dashboard';
    public bool $sidebarOpen = true;
    public string $appTab = 'id'; // Default sub-tab for Applications

    // CONFIRM MODAL FOR PERSONAL APPLICATION ACCEPT
    public bool $showConfirmAcceptPersonal = false;
    public ?int $confirmApplicantId = null;

    // CONFIRM MODAL FOR REQUEST ACCEPT
    public bool $showConfirmAcceptRequest = false;
    public ?int $confirmRequestId = null;

    // CONFIRM MODAL FOR REQUEST FINALIZE
    public bool $showConfirmFinalizeRequest = false;
    public ?int $confirmRequestFinalizeId = null;


    // ---- PERSONAL APPLICATION CONFIRM HANDLERS ----
    public function openConfirmAcceptPersonal(int $applicantId): void
    {
        $this->confirmApplicantId = $applicantId;
        $this->showConfirmAcceptPersonal = true;
    }

    public function confirmAcceptPersonal(): void
    {
        if (!$this->confirmApplicantId) return;

        $this->acceptApplication($this->confirmApplicantId);
        $this->showConfirmAcceptPersonal = false;
        $this->confirmApplicantId = null;

        $this->dispatch('toast', message: 'Application accepted successfully.');
    }

    // ---- REQUEST CONFIRM HANDLERS (NEW) ----
    public function openConfirmAcceptRequest(int $requestId): void
    {
        $this->confirmRequestId = $requestId;
        $this->showConfirmAcceptRequest = true;
    }

    public function confirmAcceptRequest(): void
    {
        if (!$this->confirmRequestId) return;

        $this->acceptRequest($this->confirmRequestId);
        $this->showConfirmAcceptRequest = false;
        $this->confirmRequestId = null;

        $this->dispatch('toast', message: 'Request accepted successfully.');
    }

    public function openConfirmFinalizeRequest(int $requestId): void
    {
        $this->confirmRequestFinalizeId = $requestId;
        $this->showConfirmFinalizeRequest = true;
    }

    public function confirmFinalizeRequest(): void
    {
        if (!$this->confirmRequestFinalizeId) return;

        $this->finalizeRequest($this->confirmRequestFinalizeId);
        $this->showConfirmFinalizeRequest = false;
        $this->confirmRequestFinalizeId = null;

        $this->dispatch('toast', message: 'Request finalized successfully.');
    }

    public function openRequirements(int $applicantId): void
    {
        $this->dispatch('open-requirements', id: $applicantId);
    }

    public function openRequestDetails(int $requestId): void
    {
        $this->dispatch('open-request-details', id: $requestId);
    }

    public function acceptApplication(int $applicantId): void
    {
        $application = FormPersonal::find($applicantId);

        if ($application) {
            $application->update([
                'status' => 'Under Final Review',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => null
            ]);
        }
    }
    
    // public function rejectApplication(int $applicantId, string $remarks): void
    // {
    //     $application = FormPersonal::find($applicantId);

    //     if ($application) {
    //         $application->update([
    //             'status' => 'Rejected',
    //             'reviewed_by' => auth()->user()->identifier,
    //             'reviewed_at' => now(),
    //             'remarks' => $remarks
    //         ]);
    //     }
    // }

    public function finalizeApplication(int $applicantId): void
    {
        $this->dispatch('openIdPreview', $applicantId);
    }

    public function acceptRequest(int $requestId): void
    {
        $request = FormRequest::find($requestId);

        if ($request) {
            $request->update([
                'status' => 'Under Final Review',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => null
            ]);
        }
    }

    public function rejectRequest(int $requestId, string $remarks): void
    {
        $request = FormRequest::find($requestId);

        if ($request) {
            $request->update([
                'status' => 'Rejected',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => $remarks
            ]);
        }
    }

    public function finalizeRequest(int $requestId): void
    {
        $request = FormRequest::find($requestId);

        if (! $request) return;

        $request->update([
            'status' => 'Finalized',
            'reviewed_by' => auth()->user()->identifier,
            'reviewed_at' => now(),
            'remarks' => null
        ]);

        // Fetch applicant's personal profile
        $personal = FormPersonal::where('applicant_id', $request->applicant_id)->first();

        if ($request->request_type === 'renewal' && $personal) {
            $personal->update([
                'status' => 'Finalized',
                'date_issued' => now(),
                'expiration_date' => now()->addYears(5),
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
            ]);

            // Notification for renewal
            try {
                $accountId = $personal->account_id ?? null;
                if ($accountId) {
                    Notification::create([
                        'account_id'     => $accountId,
                        'title'          => 'Request Finalized',
                        'message'        => 'Your request has been finalized.',
                        'type'           => 'request_finalized',
                        'reference_id'   => $request->request_id,
                        'reference_type' => 'form_requests',
                        'is_read'        => false,
                        'expires_at'     => now()->addDays(7),
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error('Notification error (finalizeRequest - renewal): ' . $e->getMessage());
            }
            $this->dispatch('application-updated');
            return;
        }

        if ($request->request_type === 'loss' && $personal) {
            $personal->update([
                'status' => 'Finalized',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
            ]);

            // Notification for loss
            try {
                $accountId = $personal->account_id ?? null;
                if ($accountId) {
                    Notification::create([
                        'account_id'     => $accountId,
                        'title'          => 'Request Finalized',
                        'message'        => 'Your request has been finalized.',
                        'type'           => 'request_finalized',
                        'reference_id'   => $request->request_id,
                        'reference_type' => 'form_requests',
                        'is_read'        => false,
                        'expires_at'     => now()->addDays(7),
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error('Notification error (finalizeRequest - loss): ' . $e->getMessage());
            }
            $this->dispatch('application-updated');
            return;
        }

        // General notification for other request types
        try {
            $accountId = $personal->account_id ?? null;
            if ($accountId) {
                Notification::create([
                    'account_id'     => $accountId,
                    'title'          => 'Request Finalized',
                    'message'        => 'Your request has been finalized.',
                    'type'           => 'request_finalized',
                    'reference_id'   => $request->request_id,
                    'reference_type' => 'form_requests',
                    'is_read'        => false,
                    'expires_at'     => now()->addDays(7),
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('Notification error (finalizeRequest): ' . $e->getMessage());
        }

        $this->dispatch('application-updated');
    }

    #[On('releaseApplication')]
    public function releaseApplication(int $applicantId): void
    {
        $application = FormPersonal::find($applicantId);

        if ($application) {
            $application->update([
                'status' => 'Finalized',
                'date_issued' => now(),
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'expiration_date' => now()->addYears(5),
            ]);
        }

        // 2) Sync to user's profile
        if (!empty($application->account_id)) {
            $user = User::find($application->account_id);

            if ($user) {
                $profile = $user->profile ?: $user->profile()->create([]);

                // Only fields that depend on ID APPLICATION
                $profile->update([
                    'pwd_number'      => $application->pwd_number,
                    'civil_status'    => $application->civil_status,
                    'disability_type' => $application->disability_type,

                    // Address
                    'house_no'     => $application->house_no,
                    'street'       => $application->street,
                    'barangay'     => $application->barangay,
                    'municipality' => $application->municipality,
                    'province'     => $application->province,

                    // DO NOT overwrite (registration-owned):
                    // birthdate
                    // age
                    // sex
                    // contact_no
                ]);
            }
        }

        // Notification for release
        if ($application && isset($application->account_id)) {
            try {
                Notification::create([
                    'account_id'    => $application->account_id,
                    'title'         => 'Your PWD ID has been released',
                    'message'       => 'Your PWD ID has been finalized and is ready. You may now print or claim it at the PDAO office.',
                    'type'          => 'id_finalized',
                    'reference_id'  => $application->applicant_id,
                    'reference_type'=> 'form_personal',
                    'is_read'       => false,
                    'expires_at'    => now()->addDays(7),
                ]);
            } catch (\Throwable $e) {
                \Log::error('Notification error (releaseApplication): ' . $e->getMessage());
            }
        }

        $this->dispatch('application-updated');
    }


    #[On('application-updated')]
    public function refreshApplications()
    {
        // Simply re-render by doing nothing, this just autoloads the page.
    }

    #[On('open-id-preview')]
    public function openIdPreview(int $applicantId): void
    {
        $this->dispatch('show-id-preview', id: $applicantId);
    }

    public function render(): View
    {
        $applications = new Collection();
        $userRole = Auth::user()->identifier;

        $requestTabs = ['renewal', 'loss', 'device', 'financial', 'booklet'];
        $isRequestType = in_array($this->appTab, $requestTabs);

        if ($this->section === 'applications') {
            if ($isRequestType) {
                $requestMap = [
                    'renewal'   => 'renewal',
                    'loss'      => 'loss',
                    'device'    => 'device',
                    'financial' => 'financial',
                    'booklet'   => 'booklet',
                ];
                $requestType = $requestMap[$this->appTab] ?? null;

                if ($requestType) {
                    $query = FormRequest::with(['applicant'])
                        ->where('request_type', $requestType);

                    if ($userRole == 1) { // IF USER IS ADMIN
                        $query->where('status', 'Under Final Review');
                        $query->orderByDesc('reviewed_at');

                    } elseif ($userRole == 2) { // IF USER IS STAFF
                        $query->where('status', 'Pending');
                        $query->orderByDesc('submitted_at');
                    }
                    
                    $applications = $query->get();
                }

            } else {
                $personalMap = [
                    'id'      => 'ID Application',
                ];
                $type = $personalMap[$this->appTab] ?? 'ID Application';

                $query = FormPersonal::query()->where('applicant_type', $type);

                if ($userRole == 1) { // IF USER IS ADMIN
                    $query->where('status', 'Under Final Review');
                    $query->orderByDesc('reviewed_at');

                } elseif ($userRole == 2) { // IF USER IS STAFF
                    $query->where('status', 'Pending');
                    $query->orderByDesc('submitted_at');
                }

                $applications = $query->get();
            }
        }

        return view('livewire.staff-view', [
            'applications' => $applications,
            'isRequestType' => $isRequestType,
        ]);
    }
}

