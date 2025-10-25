<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class StaffView extends Component
{
    public string $section = 'dashboard';
    public bool $sidebarOpen = true;
    public string $appTab = 'id'; // Default sub-tab for Applications

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
                'status' => 'Awaiting Admin Approval',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => null
            ]);
        }
    }

    public function finalizeApplication(int $applicantId): void
    {
        // $application = FormPersonal::find($applicantId);

        // if ($application) {
        //     $application->update([
        //         'status' => 'Finalized',
        //         'reviewed_by' => auth()->user()->identifier,
        //         'reviewed_at' => now(),
        //         'remarks' => null
        //     ]);
        // }

        $this->dispatch('openIdPreview', $applicantId);
    }

    public function acceptRequest(int $requestId): void
    {
        $request = FormRequest::find($requestId);

        if ($request) {
            $request->update([
                'status' => 'Awaiting Admin Approval',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => null
            ]);
        }
    }

    public function finalizeRequest(int $requestId): void
    {
        $request = FormRequest::find($requestId);

        if ($request) {
            $request->update([
                'status' => 'Finalized',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => null
            ]);
        }
    }

    public function rejectApplication(int $applicantId, string $remarks): void
    {
        $application = FormPersonal::find($applicantId);

        if ($application) {
            $application->update([
                'status' => 'Rejected',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => $remarks
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
            ]);
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

        $requestTabs = ['device', 'financial', 'booklet'];
        $isRequestType = in_array($this->appTab, $requestTabs);

        if ($this->section === 'applications') {
            if ($isRequestType) {
                $requestMap = [
                    'device'    => 'Device',
                    'financial' => 'Financial',
                    'booklet'   => 'Booklet',
                ];
                $requestType = $requestMap[$this->appTab] ?? null;

                if ($requestType) {
                    $query = FormRequest::with(['applicant'])
                        ->where('request_type', $requestType);

                    if ($userRole == 1) { // IF USER IS ADMIN
                        $query->where('status', 'Awaiting Admin Approval');
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
                    'renewal' => 'ID Renewal',
                    'loss'    => 'Loss ID',
                ];
                $type = $personalMap[$this->appTab] ?? 'ID Application';

                $query = FormPersonal::query()->where('applicant_type', $type);

                if ($userRole == 1) { // IF USER IS ADMIN
                    $query->where('status', 'Awaiting Admin Approval');
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

