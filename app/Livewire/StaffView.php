<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;

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
        $application = FormPersonal::find($applicantId);

        if ($application) {
            $application->update([
                'status' => 'Finalized',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => null
            ]);
        }
    }

    public function releaseApplication(int $applicantId): void
    {
        
    }

    public function rejectApplication(int $applicantId, string $remarks): void
    {
        $application = FormPersonal::find($applicantId);

        if ($application) {
            $application->update([
                'status' => 'Needs Revision',
                'reviewed_by' => auth()->user()->identifier,
                'reviewed_at' => now(),
                'remarks' => $remarks
            ]);
        }
    }

    public function render(): View
    {
        $applications = new Collection();

        // Define which tabs correspond to FormRequest model
        $requestTabs = ['device', 'financial', 'booklet'];
        $isRequestType = in_array($this->appTab, $requestTabs);

        if ($this->section === 'applications') {
            if ($isRequestType) {
                // Handle tabs that query the FormRequest model
                $requestMap = [
                    'device'    => 'Device',
                    'financial' => 'Financial',
                    'booklet'   => 'Booklet',
                ];
                $requestType = $requestMap[$this->appTab] ?? null;

                if ($requestType) {
                    $applications = FormRequest::with(['applicant'])
                        ->where('request_type', $requestType)
                        ->orderByDesc('submitted_at')
                        ->get();
                }
            } else {

                $userRole = auth()->user()->identifier;

                // Handle tabs that query the FormPersonal model
                $personalMap = [
                    'id'      => 'ID Application',
                    'renewal' => 'ID Renewal',
                    'loss'    => 'Loss ID',
                ];
                $type = $personalMap[$this->appTab] ?? 'ID Application';

                // $applications = FormPersonal::query()
                //     ->where('applicant_type', $type)
                //     ->orderByDesc('date_applied')
                //     ->get();

                $query = FormPersonal::query()->where('applicant_type', $type);

                if ($userRole == 1) { // IF USER IS ADMIN
                    $query->where('status', 'Awaiting Admin Approval');
                    $query->orderByDesc('reviewed_at'); // Order by when staff reviewed it

                } elseif ($userRole == 2) { // IF USER IS STAFF
                    $query->where('status', 'Pending');
                    $query->orderByDesc('date_applied'); // Order by when it was submitted
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

