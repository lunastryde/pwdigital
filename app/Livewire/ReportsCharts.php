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

    protected function buildSummaryData(Carbon $start, Carbon $end): array
    {
        // Statuses considered "open"
        $openStatuses = [
            'Pending',
            'Under Final Review',
            'Needs Revision',
        ];

        // 1. Totals (all-time, NOT affected by filter)
        $totalPwds = FormPersonal::count();

        $totalFinalizedPwds = FormPersonal::where('status', 'Finalized')
            ->count();

        // 2. Filter-based metrics (respect current date range)
        $newRegistrations = FormPersonal::whereBetween('submitted_at', [$start, $end])
            ->count();

        $openPersonal = FormPersonal::whereIn('status', $openStatuses)
            ->whereBetween('submitted_at', [$start, $end])
            ->count();

        $openRequests = FormRequest::whereIn('status', $openStatuses)
            ->whereBetween('submitted_at', [$start, $end])
            ->count();

        $openApplications = $openPersonal + $openRequests;

        $finalizedIdApplications = FormPersonal::where('status', 'Finalized')
            ->whereBetween('submitted_at', [$start, $end])
            ->count();

        $finalizedBookletRequests = FormRequest::where('status', 'Finalized')
            ->where('request_type', 'booklet')
            ->whereBetween('submitted_at', [$start, $end])
            ->count();

        $finalizedDeviceRequests = FormRequest::where('status', 'Finalized')
            ->where('request_type', 'device')
            ->whereBetween('submitted_at', [$start, $end])
            ->count();

        $finalizedFinancialRequests = FormRequest::where('status', 'Finalized')
            ->where('request_type', 'financial')
            ->whereBetween('submitted_at', [$start, $end])
            ->count();

        return [
            // not filtered
            'totalPwds'                  => $totalPwds,
            'totalFinalizedPwds'         => $totalFinalizedPwds,

            // filtered by current range
            'newRegistrations'           => $newRegistrations,
            'openApplications'           => $openApplications,
            'finalizedIdApplications'    => $finalizedIdApplications,
            'finalizedBookletRequests'   => $finalizedBookletRequests,
            'finalizedDeviceRequests'    => $finalizedDeviceRequests,
            'finalizedFinancialRequests' => $finalizedFinancialRequests,
        ];
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
        $base = FormRequest::whereBetween('submitted_at', [$start, $end]);

        $labels = ['PWD ID', 'Booklets', 'Devices', 'Financial'];
        $values = [
            (clone $base)->where('request_type', 'pwd_id')->count(),
            (clone $base)->where('request_type', 'booklet')->count(),
            (clone $base)->where('request_type', 'device')->count(),
            (clone $base)->where('request_type', 'financial')->count(),
        ];

        $this->reportData['applications_by_category'] = [
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
                    'backgroundColor' => ['#2563eb', '#10b981', '#f59e0b', '#ef4444'],
                ]],
            ],
        ];

        $this->dispatch('render-applications-chart', $chartData);
    }

    /* 2. Status breakdown (Pending / Approved / Rejected) */
    protected function statusBreakdown(Carbon $start, Carbon $end): void
    {
        $rows = FormRequest::select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('status')
            ->get();

        // Map your internal statuses to the 3 groups
        $pending = $rows->whereIn('status', ['Pending', 'Under Final Review'])->sum('total');
        $approved = $rows->where('status', 'Finalized')->sum('total');
        $rejected = $rows->whereIn('status', ['Rejected', 'Needs Revision'])->sum('total');

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
        $rows = FormPersonal::select('sex', DB::raw('COUNT(*) as total'))
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
        $rows = FormPersonal::select('disability_type', DB::raw('COUNT(*) as total'))
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
        $rows = FormPersonal::select('barangay', DB::raw('COUNT(*) as total'))
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

    /* 6. Monthly submission trends (Line, chosen year) */
    protected function monthlyTrend(int $year): void
    {
        $rows = FormRequest::select(
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
            $row      = $rows->firstWhere('month', $m);
            $values[] = $row?->total ?? 0;
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
            'report'      => $this->reportData,
        ];

        $pdf = Pdf::loadView('exports.reports-pdf', $data)
            ->setPaper('a4', 'landscape');

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
