<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use App\Models\FormRequest;

class StaffView extends Component
{
    public string $section = 'dashboard';
    public bool $sidebarOpen = true;
    public string $appTab = 'id'; // sub-tab for Applications

    public function openRequirements(int $applicantId): void
    {
        $this->dispatch('open-requirements', id: $applicantId);
    }

    public function openRequestDetails(int $requestId): void
    {
        $this->dispatch('open-request-details', id: $requestId);
    }

    public function render()
    {
        $applications = collect();
        if ($this->section === 'applications') {
            $map = [
                'id' => 'ID Application',
                'renewal' => 'ID Renewal',
                'loss' => 'Loss ID',
                'device' => 'Request Device',
                'financial' => 'Financial Request',
            ];
            $type = $map[$this->appTab] ?? 'ID Application';
            $applications = FormPersonal::query()
                ->where('applicant_type', $type)
                ->orderByDesc('date_applied')
                ->get();
        }

        else {
            $requestMap = [
                'device' => 'Request Device',
                'financial' => 'Financial Request',
                'booklet' => 'Booklet',
            ];
            $requestType = $requestMap[$this->appTab] ?? null;
            
            if ($requestType) {
                $applications = FormRequest::with(['applicant'])
                    ->where('request_type', $requestType)
                    ->orderByDesc('date_requested')
                    ->get();
            }
        }

        return view('livewire.staff-view', [
            'applications' => $applications,
            'isRequestType' => in_array($this->appTab, ['device', 'financial', 'booklet']),
        ]);
    }
}
