<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use App\Models\User;

class StaffDashboard extends Component
{
    // High-level metrics
    public int $totalRegisteredPwds = 0;
    public int $totalStaffAccounts  = 0;

    public int $totalPendingIdApps      = 0;
    public int $totalPendingRequests    = 0;
    public int $totalActiveApplications = 0;
    public int $newApplicationsToday    = 0;

    // Unified list of recent active/pending applications
    public array $recentApplications = [];

    public function mount(): void
    {
        $this->refreshData();
    }

    /**
     * Called by mount() and by wire:poll for “real-time” updates.
     */
    public function refreshData(): void
    {
        // ----- TOP CARDS -----

        // 1) Registered PWDs = finalized ID applications
        $this->totalRegisteredPwds = FormPersonal::where('status', 'Finalized')
            ->distinct('pwd_number')
            ->count('pwd_number');

        // 2) Staff accounts (identifier: 1 = admin, 2 = staff)
        $this->totalStaffAccounts = User::whereIn('identifier', [1, 2])->count();

        $pendingStatuses = ['Pending'];
        $activeStatuses  = ['Pending', 'Under Final Review'];

        // 3) Pending ID applications
        $this->totalPendingIdApps = FormPersonal::whereIn('status', $pendingStatuses)->count();

        // 4) Pending support / encoded requests
        $this->totalPendingRequests = FormRequest::whereIn('status', $pendingStatuses)->count();

        // 5) All active applications (Pending + Under Final Review)
        $this->totalActiveApplications =
              FormPersonal::whereIn('status', $activeStatuses)->count()
            + FormRequest::whereIn('status', $activeStatuses)->count();

        // 6) New submissions today (all types)
        $today = now()->startOfDay();
        $this->newApplicationsToday =
              FormPersonal::where('submitted_at', '>=', $today)->count()
            + FormRequest::where('submitted_at', '>=', $today)->count();

        // ----- LIVE RECENT APPLICATION LIST -----

        $personal = FormPersonal::whereIn('status', $activeStatuses)
            ->orderByDesc('submitted_at')
            ->take(20)
            ->get();

        $requests = FormRequest::with('applicant')
            ->whereIn('status', $activeStatuses)
            ->orderByDesc('submitted_at')
            ->take(20)
            ->get();

        $rows = collect();

        foreach ($personal as $p) {
            $rows->push([
                'id'            => $p->applicant_id,
                'source'        => 'personal',
                'type'          => 'ID Application',
                'subtype'       => 'ID Application',
                'applicant'     => trim(($p->fname ?? '') . ' ' . ($p->mname ?? '') . ' ' . ($p->lname ?? '')),
                'status'        => $p->status,
                'submitted_at'  => $p->submitted_at,
            ]);
        }

        foreach ($requests as $r) {
            $rows->push([
                'id'            => $r->request_id,
                'source'        => 'request',
                'type'          => 'Support Request',
                'subtype'       => $this->labelRequestType($r->request_type),
                'applicant'     => $r->applicant
                                    ? trim(($r->applicant->fname ?? '') . ' ' . ($r->applicant->mname ?? '') . ' ' . ($r->applicant->lname ?? ''))
                                    : '—',
                'status'        => $r->status,
                'submitted_at'  => $r->submitted_at,
            ]);
        }

        // Sort everything together by submitted_at desc and take top 15
        $this->recentApplications = $rows
            ->sortByDesc('submitted_at')
            ->values()
            ->take(15)
            ->toArray();
    }

    protected function labelRequestType(?string $type): string
    {
        if (!$type) {
            return 'Request';
        }

        switch ($type) {
            case 'renewal':
                return 'ID Renewal';
            case 'loss':
                return 'Loss ID Support';
            case 'device':
                return 'Assistive Device';
            case 'financial':
                return 'Financial Assistance';
            case 'booklet':
                return 'Booklet Request';
            default:
                return ucfirst($type);
        }
    }

    public function render()
    {
        return view('livewire.staff-dashboard');
    }
}
