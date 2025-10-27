<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\FormPersonal;
use App\Models\FormRequest;

class ReportsSummary extends Component
{
    // Live update interval - we will use wire:poll in blade instead of server polling
    public function render()
    {
        // Total registered PWD records
        $totalPwds = FormPersonal::count();

        // Total ID Applications (applicant_type = 'ID Application')
        $idApplications = FormPersonal::where('applicant_type', 'ID Application')->count();

        // Requests breakdown (lowercase request_type values: booklet, financial, device)
        $totalRequests = FormRequest::count();
        $bookletRequests = FormRequest::where('request_type', 'booklet')->count();
        $financialRequests = FormRequest::where('request_type', 'financial')->count();
        $deviceRequests = FormRequest::where('request_type', 'device')->count();

        // Issued IDs (Finalized)
        $issuedIds = FormPersonal::where('status', 'Finalized')->count();

        // Status counts (across both tables): Pending / Approved / Rejected
        // We normalize statuses into Pending, Approved (Finalized/Approved), Rejected
        $personalStatuses = FormPersonal::selectRaw("
                CASE
                    WHEN status IN ('Finalized','Approved') THEN 'approved'
                    WHEN status IN ('Rejected') THEN 'rejected'
                    ELSE 'pending'
                END as normalized, COUNT(*) as cnt
            ")
            ->groupBy('normalized')
            ->pluck('cnt','normalized')->toArray();

        $requestStatuses = FormRequest::selectRaw("
                CASE
                    WHEN status IN ('Finalized','Approved') THEN 'approved'
                    WHEN status IN ('Rejected') THEN 'rejected'
                    ELSE 'pending'
                END as normalized, COUNT(*) as cnt
            ")
            ->groupBy('normalized')
            ->pluck('cnt','normalized')->toArray();

        // Merge status counts
        $statusCounts = [
            'pending' => ($personalStatuses['pending'] ?? 0) + ($requestStatuses['pending'] ?? 0),
            'approved' => ($personalStatuses['approved'] ?? 0) + ($requestStatuses['approved'] ?? 0),
            'rejected' => ($personalStatuses['rejected'] ?? 0) + ($requestStatuses['rejected'] ?? 0),
        ];

        return view('livewire.reports-summary', [
            'totalPwds' => $totalPwds,
            'idApplications' => $idApplications,
            'totalRequests' => $totalRequests,
            'bookletRequests' => $bookletRequests,
            'financialRequests' => $financialRequests,
            'deviceRequests' => $deviceRequests,
            'issuedIds' => $issuedIds,
            'statusCounts' => $statusCounts,
        ]);
    }
}
