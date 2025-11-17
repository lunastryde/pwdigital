<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\FormPersonal;
use App\Models\FormRequest;
use App\Models\User; // <-- 1. ADDED THIS

class ReportsSummary extends Component
{
    public $filterPeriod = 'all';

    private function setDateRange($query, $column = 'submitted_at')
    {
        switch ($this->filterPeriod) {
            case 'today':
                return $query->whereDate($column, today());
            case 'week':
                return $query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]);
            case 'month':
                return $query->whereMonth($column, now()->month)->whereYear($column, now()->year);
            case 'year':
                return $query->whereYear($column, now()->year);
            default: // 'all'
                return $query;
        }
    }

    public function render()
    {
        // === QUERIES ===

        // 2. CHANGED: Now counts total users as per your definition
        $totalPwds = $this->setDateRange(User::query(), 'created_at')->count();

        // Total ID Applications (this is the base number for the ID card)
        $idApplications = $this->setDateRange(FormPersonal::where('applicant_type', 'ID Application'))->count();

        // Requests breakdown
        $totalRequests = $this->setDateRange(FormRequest::query())->count();
        $bookletRequests = $this->setDateRange(FormRequest::where('request_type', 'booklet'))->count();
        $financialRequests = $this->setDateRange(FormRequest::where('request_type', 'financial'))->count();
        $deviceRequests = $this->setDateRange(FormRequest::where('request_type', 'device'))->count();

        // 3. REPLACED: New query for ID Application statuses ONLY
        $idStatusCounts = $this->setDateRange(FormPersonal::query())
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Gender distribution
        $genderDistribution = $this->setDateRange(FormPersonal::query())
            ->selectRaw("LOWER(sex) as gender, COUNT(*) as count")
            ->whereNotNull('sex')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        // Disability type breakdown
        $topDisabilities = $this->setDateRange(FormPersonal::query())
            ->select('disability_type', DB::raw('COUNT(*) as count'))
            ->whereNotNull('disability_type')
            ->groupBy('disability_type')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('count', 'disability_type');

        // Application counts by location
        $topLocations = $this->setDateRange(FormPersonal::query())
            ->select('barangay', DB::raw('COUNT(*) as count'))
            ->whereNotNull('barangay')
            ->groupBy('barangay')
            ->orderByDesc('count')
            ->limit(3)
            ->pluck('count', 'barangay');

        // User feedback survey results
        $feedbackCount = 0;
        if (Schema::hasTable('user_feedback')) {
            $feedbackCount = $this->setDateRange(DB::table('user_feedback'))->count();
        }

        return view('livewire.reports-summary', [
            'totalPwds' => $totalPwds,
            'idApplications' => $idApplications,
            'totalRequests' => $totalRequests,
            'bookletRequests' => $bookletRequests,
            'financialRequests' => $financialRequests,
            'deviceRequests' => $deviceRequests,
            'idStatusCounts' => $idStatusCounts, // <-- 4. Pass new variable
            'genderDistribution' => $genderDistribution,
            'topDisabilities' => $topDisabilities,
            'topLocations' => $topLocations,
            'feedbackCount' => $feedbackCount,
        ]);
    }
}