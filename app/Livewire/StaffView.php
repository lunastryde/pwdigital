<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;

class StaffView extends Component
{
    public string $section = 'dashboard';
    public bool $sidebarOpen = true;
    public string $appTab = 'id'; // sub-tab for Applications

    public function openRequirements(int $applicantId): void
    {
        $this->dispatch('open-requirements', id: $applicantId);
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

        return view('livewire.staff-view', [
            'applications' => $applications,
        ]);
    }
}
