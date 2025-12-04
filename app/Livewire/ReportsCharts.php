<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormRequest;
use App\Models\FormPersonal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportsCharts extends Component
{
    public string $timeFilter = 'month'; // week | month | year

    public int $selectedYear;
    public int $selectedMonth; // 1–12

    public array $years = [];
    public array $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
    
    public string $ageFilter = 'all'; // all | under18 | 18to29 | 30to59 | 60plus

    public array $ageOptions = [
        'all'    => 'All Ages',
        'under18'=> 'Under 18',
        '18to29' => '18–29',
        '30to59' => '30–59',
        '60plus' => '60 and above',
    ];

    // Shared numeric data for cards / exports
    public array $reportData = [];

    public function mount()
    {
        // Determine min year from data (form_requests + form_personal)
        $minReqYear = FormRequest::min(DB::raw('YEAR(submitted_at)')) ?? now()->year;
        $minPerYear = FormPersonal::min(DB::raw('YEAR(submitted_at)')) ?? now()->year;
        $minYear    = min($minReqYear, $minPerYear);

        $currentYear = now()->year;

        $this->years = range($minYear, $currentYear);
        $this->selectedYear  = $currentYear;
        $this->selectedMonth = now()->month;

        $this->refreshAllCharts();
    }

    public function updatedTimeFilter($value)
    {
        $this->refreshAllCharts();
    }

    public function updatedSelectedYear($value)
    {
        $this->refreshAllCharts();
    }

    public function updatedSelectedMonth($value)
    {
        // Only matters when filter is month, but safe always
        if ($this->timeFilter === 'month') {
            $this->refreshAllCharts();
        }
    }

    public function updatedAgeFilter($value)
    {
        $this->refreshAllCharts();
    }


    protected function buildSummaryData(Carbon $start, Carbon $end): array
    {
        $openStatuses = [
            'Pending',
            'Under Final Review',
            'Needs Revision',
        ];

        // Base personal query with age filter
        $personalBase = $this->applyAgeFilterToPersonal(
            FormPersonal::query()
        );

        // All-time totals (still all-time, but filtered by age if ageFilter != 'all')
        $totalPwds = (clone $personalBase)->count();

        $totalFinalizedPwds = (clone $personalBase)
            ->where('status', 'Finalized')
            ->count();

        // Date-range + age filtered
        $personalInRange = (clone $personalBase)
            ->whereBetween('submitted_at', [$start, $end]);

        $newRegistrations = (clone $personalInRange)->count();

        $openPersonal = (clone $personalInRange)
            ->whereIn('status', $openStatuses)
            ->count();

        // Requests (no age column, so age filter does NOT apply here)
        $requestsInRange = FormRequest::whereBetween('submitted_at', [$start, $end]);

        $openRequests = (clone $requestsInRange)
            ->whereIn('status', $openStatuses)
            ->count();

        $openApplications = $openPersonal + $openRequests;

        // Encoded Applications (within date range + age filter)
        $encodedApplications = (clone $personalInRange)
            ->where('applicant_type', 'Encoded Application')
            ->count();

        $finalizedIdApplications = (clone $personalInRange)
            ->where('status', 'Finalized')
            ->whereIn('applicant_type', ['ID Application', 'Encoded Application'])
            ->count();

        $finalizedBookletRequests = (clone $requestsInRange)
            ->where('status', 'Finalized')
            ->where('request_type', 'booklet')
            ->count();

        $finalizedDeviceRequests = (clone $requestsInRange)
            ->where('status', 'Finalized')
            ->where('request_type', 'device')
            ->count();

        $finalizedFinancialRequests = (clone $requestsInRange)
            ->where('status', 'Finalized')
            ->where('request_type', 'financial')
            ->count();

        return [
            // not filtered by time but filtered by age
            'totalPwds'                  => $totalPwds,
            'totalFinalizedPwds'         => $totalFinalizedPwds,

            // filtered by current range (+ age)
            'newRegistrations'           => $newRegistrations,
            'openApplications'           => $openApplications,
            'encodedApplications'        => $encodedApplications,
            'finalizedIdApplications'    => $finalizedIdApplications,
            'finalizedBookletRequests'   => $finalizedBookletRequests,
            'finalizedDeviceRequests'    => $finalizedDeviceRequests,
            'finalizedFinancialRequests' => $finalizedFinancialRequests,
        ];
    }


    protected function applyAgeFilterToPersonal($query)
    {
        switch ($this->ageFilter) {
            case 'under18':
                $query->where('age', '<', 18);
                break;

            case '18to29':
                $query->whereBetween('age', [18, 29]);
                break;

            case '30to59':
                $query->whereBetween('age', [30, 59]);
                break;

            case '60plus':
                $query->where('age', '>=', 60);
                break;

            case 'all':
            default:
                // no age condition
                break;
        }

        return $query;
    }

    /**
     * Compute the active date range based on filter, year, month.
     */
    protected function getDateRange(): array
    {
        $now = now();

        switch ($this->timeFilter) {
            case 'week':
                // This week (current week)
                $start = $now->copy()->startOfWeek();
                $end   = $now->copy()->endOfWeek();
                break;

            case 'year':
                // Whole selected year
                $start = Carbon::create($this->selectedYear, 1, 1)->startOfYear();
                $end   = Carbon::create($this->selectedYear, 12, 31)->endOfYear();
                break;

            case 'month':
            default:
                // Specific month & year
                $start = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
                $end   = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();
                break;
        }

        return [$start, $end];
    }

    /**
     * Human-readable label for the current period (used in views & exports).
     */
    protected function periodLabel(): string
    {
        [$start, $end] = $this->getDateRange();

        if ($this->timeFilter === 'week') {
            return 'Week of '.$start->format('M d, Y').' – '.$end->format('M d, Y');
        }

        if ($this->timeFilter === 'month') {
            return $start->format('F Y'); // e.g. "November 2025"
        }

        return (string) $this->selectedYear; // "2025"
    }

    /**
     * Main entry: compute everything + dispatch chart events.
     */
    public function refreshAllCharts(): void
    {
        [$start, $end] = $this->getDateRange();

        $this->reportData = [];

        // Summary cards data
        $this->reportData['summary'] = $this->buildSummaryData($start, $end);

        // Charts
        $this->applicationsByCategory($start, $end);
        $this->statusBreakdown($start, $end);
        $this->genderDistribution($start, $end);
        $this->disabilityBreakdown($start, $end);
        $this->locationCounts($start, $end);
        $this->monthlyTrend($this->selectedYear);
    }

    
    /* 1. Applications by Category (PWD ID / Booklet / Device / Financial) */
    protected function applicationsByCategory(Carbon $start, Carbon $end): void
    {
        // 🔹 PWD ID applications come from form_personal
        //     - includes both "ID Application" and "Encoded Application"
        //     - age filter is applied here
        $personalBase = $this->applyAgeFilterToPersonal(
            FormPersonal::whereBetween('submitted_at', [$start, $end])
        );

        $pwdIdCount = (clone $personalBase)
            ->whereIn('applicant_type', ['ID Application', 'Encoded Application'])
            ->count();

        // 🔹 Other categories (Booklets, Devices, Financial) come from form_requests
        $reqBase = FormRequest::whereBetween('submitted_at', [$start, $end]);

        $bookletCount   = (clone $reqBase)->where('request_type', 'booklet')->count();
        $deviceCount    = (clone $reqBase)->where('request_type', 'device')->count();
        $financialCount = (clone $reqBase)->where('request_type', 'financial')->count();

        $labels = ['PWD ID', 'Booklets', 'Devices', 'Financial'];
        $values = [
            $pwdIdCount,
            $bookletCount,
            $deviceCount,
            $financialCount,
        ];

        // Store for exports / summary use
        $this->reportData['applications_by_category'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        // Chart.js payload
        $chartData = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'label'           => 'Applications',
                    'data'            => $values,
                    'backgroundColor' => ['#2563eb', '#10b981', '#f59e0b', '#ef4444'],
                ]],
            ],
        ];

        $this->dispatch('render-applications-chart', $chartData);
    }


    /* 2. Status breakdown (Pending / Approved / Rejected) – ALL application types */
    protected function statusBreakdown(Carbon $start, Carbon $end): void
    {
        // Status groupings (keep same mapping as your original logic)
        $pendingStatuses  = ['Pending', 'Under Final Review'];
        $approvedStatuses = ['Finalized'];
        $rejectedStatuses = ['Rejected', 'Needs Revision'];

        // ----- FormPersonal side (ID applications), with age filter -----
        $personalBase = $this->applyAgeFilterToPersonal(
            FormPersonal::query()
        )->whereBetween('submitted_at', [$start, $end]);

        $personalRows = (clone $personalBase)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // ----- FormRequest side (booklet / device / financial / etc.) -----
        $requestRows = FormRequest::select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('status')
            ->get();

        // Sum from BOTH tables
        $pending = $personalRows->whereIn('status', $pendingStatuses)->sum('total')
                + $requestRows->whereIn('status', $pendingStatuses)->sum('total');

        $approved = $personalRows->whereIn('status', $approvedStatuses)->sum('total')
                + $requestRows->whereIn('status', $approvedStatuses)->sum('total');

        $rejected = $personalRows->whereIn('status', $rejectedStatuses)->sum('total')
                + $requestRows->whereIn('status', $rejectedStatuses)->sum('total');

        $labels = ['Pending', 'Approved', 'Rejected'];
        $values = [$pending, $approved, $rejected];

        $this->reportData['status_breakdown'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        $chartData = [
            'type' => 'doughnut',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'data'            => $values,
                    'backgroundColor' => ['#f97316', '#22c55e', '#ef4444'],
                ]],
            ],
        ];

        $this->dispatch('render-status-chart', $chartData);
    }


    /* 3. Gender distribution (Pie) – from form_personal */
    protected function genderDistribution(Carbon $start, Carbon $end): void
    {
        $rows = $this->applyAgeFilterToPersonal(
                FormPersonal::select('sex', DB::raw('COUNT(*) as total'))
            )
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('sex')
            ->get();

        $labels = $rows->pluck('sex')->map(fn ($v) => $v ?: 'Unknown')->toArray();
        $values = $rows->pluck('total')->toArray();

        $this->reportData['gender_distribution'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        $chartData = [
            'type' => 'pie',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'data'            => $values,
                    'backgroundColor' => ['#3b82f6', '#ec4899', '#6b7280'],
                ]],
            ],
        ];

        $this->dispatch('render-gender-chart', $chartData);
    }

    /* 4. Disability type breakdown (Horizontal bar) */
    protected function disabilityBreakdown(Carbon $start, Carbon $end): void
    {
        $rows = $this->applyAgeFilterToPersonal(
                FormPersonal::select('disability_type', DB::raw('COUNT(*) as total'))
            )
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('disability_type')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        $labels = $rows->pluck('disability_type')->map(fn ($v) => $v ?: 'Unknown')->toArray();
        $values = $rows->pluck('total')->toArray();

        $this->reportData['disability_breakdown'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        $chartData = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'label'           => 'PWDs',
                    'data'            => $values,
                    'backgroundColor' => '#22c55e',
                ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'responsive' => true,
                'maintainAspectRatio' => false,
            ],
        ];

        $this->dispatch('render-disability-chart', $chartData);
    }

    /* 5. Application counts by location (barangay) */
    protected function locationCounts(Carbon $start, Carbon $end): void
    {
        $rows = $this->applyAgeFilterToPersonal(
                FormPersonal::select('barangay', DB::raw('COUNT(*) as total'))
            )
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('barangay')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $labels = $rows->pluck('barangay')->map(fn ($v) => $v ?: 'Unknown')->toArray();
        $values = $rows->pluck('total')->toArray();

        $this->reportData['location_counts'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        $chartData = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'label'           => 'Applications',
                    'data'            => $values,
                    'backgroundColor' => '#0ea5e9',
                ]],
            ],
        ];

        $this->dispatch('render-location-chart', $chartData);
    }

    /* 6. Monthly submission trends (Line, ALL application types for chosen year) */
    protected function monthlyTrend(int $year): void
    {
        // ----- FormPersonal (ID applications), age-filtered -----
        $personalRows = $this->applyAgeFilterToPersonal(
            FormPersonal::select(
                DB::raw('MONTH(submitted_at) as month'),
                DB::raw('COUNT(*) as total')
            )
        )
        ->whereYear('submitted_at', $year)
        ->groupBy(DB::raw('MONTH(submitted_at)'))
        ->orderBy(DB::raw('MONTH(submitted_at)'))
        ->get();

        // ----- FormRequest (booklet / device / financial / etc.) -----
        $requestRows = FormRequest::select(
                DB::raw('MONTH(submitted_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('submitted_at', $year)
            ->groupBy(DB::raw('MONTH(submitted_at)'))
            ->orderBy(DB::raw('MONTH(submitted_at)'))
            ->get();

        $labels = [];
        $values = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create()->month($m)->format('M');

            $personalRow = $personalRows->firstWhere('month', $m);
            $requestRow  = $requestRows->firstWhere('month', $m);

            $total = ($personalRow->total ?? 0) + ($requestRow->total ?? 0);
            $values[] = $total;
        }

        $this->reportData['monthly_trend'] = [
            'year'   => $year,
            'labels' => $labels,
            'values' => $values,
        ];

        $chartData = [
            'type' => 'line',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'label'       => 'Submissions',
                    'data'        => $values,
                    'borderColor' => '#6366f1',
                    'tension'     => 0.3,
                    'fill'        => false,
                ]],
            ],
        ];

        $this->dispatch('render-trends-chart', $chartData);
    }


    /* ---------- EXPORTS ---------- */

    public function exportPdf()
    {
        // Always recalc based on current filters
        $this->refreshAllCharts();

        $data = [
            'periodLabel' => $this->periodLabel(),
            'filter'      => $this->timeFilter,
            'ageFilter'   => $this->ageFilter,
            'report'      => $this->reportData,
        ];

        $pdf = Pdf::loadView('exports.reports-pdf', $data)
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'pdao-reports-'.$this->timeFilter.'-'.$this->periodLabel().'.pdf'
        );
    }

    public function exportExcel()
    {
        // Always recalc based on current filters
        $this->refreshAllCharts();

        $data = [
            'periodLabel' => $this->periodLabel(),
            'filter'      => $this->timeFilter,
            'ageFilter'   => $this->ageFilter,
            'report'      => $this->reportData,
        ];

        return Excel::download(
            new ReportsExport($data),
            'pdao-reports-'.$this->timeFilter.'-'.$this->periodLabel().'.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.reports-charts', [
            'periodLabel' => $this->periodLabel(),
        ]);
    }
}
