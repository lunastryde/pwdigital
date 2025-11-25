<?php

namespace App\Livewire;

use Livewire\Component;

class ReportsSummary extends Component
{
    public array $summary = [
        'totalPwds'            => 0,
        'totalFinalizedPwdIds' => 0,
        'newRegistrations'     => 0,
        'openApplications'     => 0,
        'finalizedPwdId'       => 0,
        'finalizedBooklet'     => 0,
        'finalizedDevice'      => 0,
        'finalizedFinancial'   => 0,
    ];

    protected $listeners = [
        'update-summary-cards' => 'updateSummary',
    ];

    public function updateSummary($summary): void
    {
        // Merge to keep defaults if some keys are missing
        $this->summary = array_merge($this->summary, $summary ?? []);
    }

    public function render()
    {
        return view('livewire.reports-summary');
    }
}
