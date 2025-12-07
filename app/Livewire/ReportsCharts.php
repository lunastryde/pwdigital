<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormRequest;
use App\Models\FormRequestDevice;
use App\Models\FormPersonal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportsCharts extends Component
{
    public string $timeFilter = 'range';

    public string $ageBarangayFilter = 'all';
    public string $deviceBarangayFilter = 'all';
    public string $causeBarangayFilter = 'all';

    public string $ageFilter = 'all';

    public array $ageOptions = [
        'all'    => 'All Ages',
        'under18'=> 'Under 18',
        '18to29' => '18–29',
        '30to59' => '30–59',
        '60plus' => '60 and above',
    ];

    public ?string $startDate = null;
    public ?string $endDate   = null;

    public string $barangayFilter = 'all';
    public array $barangayOptions = [];

    public array $reportData = [];

    // Custom Exports
    public array $exportSections = [
        'summary' => true,
        'category' => true, // Excel Only
        'status'   => true, // Excel Only
        'age'     => true,
        'device'  => true,
        'cause'   => true,
        'location'=> true,
        'trend'   => true,
    ];

    public bool $showExportModal = false;
    public string $exportFormat = 'pdf';


    public function mount()
    {
        // Determine min year from data (form_requests + form_personal) – kept for potential future use
        $minReqYear = FormRequest::min(DB::raw('YEAR(submitted_at)')) ?? now()->year;
        $minPerYear = FormPersonal::min(DB::raw('YEAR(submitted_at)')) ?? now()->year;
        $minYear    = min($minReqYear, $minPerYear);

        $currentYear = now()->year;

        $this->years         = range($minYear, $currentYear);
        $this->selectedYear  = $currentYear;
        $this->selectedMonth = now()->month;

        // NEW: default date range = current month
        $this->startDate = now()->copy()->startOfMonth()->toDateString(); // Y-m-d
        $this->endDate   = now()->copy()->endOfMonth()->toDateString();   // Y-m-d

        // NEW: barangay options for the summary card dropdown
        $this->barangayOptions = FormPersonal::select('barangay')
            ->whereNotNull('barangay')
            ->distinct()
            ->orderBy('barangay')
            ->pluck('barangay')
            ->toArray();

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

        $totalPwds = (clone $personalBase)->count();

        $totalFinalizedPwds = (clone $personalBase)
            ->where('status', 'Finalized')
            ->count();

        $barangayBase = (clone $personalBase);
        if ($this->barangayFilter !== 'all') {
            $barangayBase->where('barangay', $this->barangayFilter);
        }
        $totalPwdsByBarangay = $barangayBase->count();

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
            'totalPwdsByBarangay'        => $totalPwdsByBarangay,

            // filtered by current range (+ age)
            'newRegistrations'           => $newRegistrations,
            'openApplications'           => $openApplications,
            'encodedApplications'        => $encodedApplications,
            'finalizedIdApplications'    => $finalizedIdApplications,
            'finalizedBookletRequests'   => $finalizedBookletRequests, // kept for exports / future use
            'finalizedDeviceRequests'    => $finalizedDeviceRequests,  // kept for exports / future use
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

    protected function getDateRange(): array
    {
        try {
            $start = $this->startDate
                ? Carbon::parse($this->startDate)->startOfDay()
                : now()->copy()->startOfMonth();
        } catch (\Exception $e) {
            $start = now()->copy()->startOfMonth();
        }

        try {
            $end = $this->endDate
                ? Carbon::parse($this->endDate)->endOfDay()
                : now()->copy()->endOfMonth();
        } catch (\Exception $e) {
            $end = now()->copy()->endOfMonth();
        }

        // if user accidentally sets end < start, swap them
        if ($end->lessThan($start)) {
            [$start, $end] = [$end->copy(), $start->copy()];
        }

        return [$start, $end];
    }

    /**
     * Human-readable label for the current period (used in views & exports).
     */
    protected function periodLabel(): string
    {
        [$start, $end] = $this->getDateRange();

        return $start->format('M d, Y') . ' – ' . $end->format('M d, Y');
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

        $this->applicationsByCategory($start, $end);
        $this->statusBreakdown($start, $end);

        $this->ageDistribution($start, $end);
        $this->deviceRequestsChart($start, $end);
        $this->disabilityCauseChart($start, $end);
        $this->locationCounts($start, $end);
        $this->monthlyTrend($start, $end);
    }

    /* 1. */
    protected function applicationsByCategory(Carbon $start, Carbon $end): void
    {
        // PWD ID applications
        $personalBase = $this->applyAgeFilterToPersonal(
            FormPersonal::whereBetween('submitted_at', [$start, $end])
        );

        $pwdIdCount = (clone $personalBase)
            ->whereIn('applicant_type', ['ID Application', 'Encoded Application'])
            ->count();

        // Other categories (Booklets, Devices, Financial) come from form_requests
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

        $this->reportData['applications_by_category'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        // No dispatch here anymore (chart removed from UI)
    }

    /* 2. */
    protected function statusBreakdown(Carbon $start, Carbon $end): void
    {
        $pendingStatuses  = ['Pending', 'Under Final Review'];
        $approvedStatuses = ['Finalized'];
        $rejectedStatuses = ['Rejected', 'Needs Revision'];

        $personalBase = $this->applyAgeFilterToPersonal(
            FormPersonal::query()
        )->whereBetween('submitted_at', [$start, $end]);

        $personalRows = (clone $personalBase)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $requestRows = FormRequest::select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('status')
            ->get();

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

        // No dispatch here anymore (chart removed from UI)
    }

    /* 3. */
    protected function ageDistribution(Carbon $start, Carbon $end): void
    {
        $query = $this->applyAgeFilterToPersonal(
            FormPersonal::whereBetween('submitted_at', [$start, $end])
        );

        // Optional barangay filter for this chart only
        if ($this->ageBarangayFilter !== 'all') {
            $query->where('barangay', $this->ageBarangayFilter);
        }

        $rows = $query
            ->whereNotNull('age')
            ->get(['age']);

        // Define age bins
        $bins = [
            '0–17'   => 0,
            '18–29'  => 0,
            '30–39'  => 0,
            '40–49'  => 0,
            '50–59'  => 0,
            '60–69'  => 0,
            '70–79'  => 0,
            '80+'    => 0,
        ];

        foreach ($rows as $row) {
            $age = (int) $row->age;
            if ($age < 18) {
                $bins['0–17']++;
            } elseif ($age < 30) {
                $bins['18–29']++;
            } elseif ($age < 40) {
                $bins['30–39']++;
            } elseif ($age < 50) {
                $bins['40–49']++;
            } elseif ($age < 60) {
                $bins['50–59']++;
            } elseif ($age < 70) {
                $bins['60–69']++;
            } elseif ($age < 80) {
                $bins['70–79']++;
            } else {
                $bins['80+']++;
            }
        }

        $labels = array_keys($bins);
        $values = array_values($bins);

        $this->reportData['age_distribution'] = [
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
                    'backgroundColor' => '#3b82f6',
                ]],
            ],
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
            ],
        ];

        $this->dispatch('render-age-chart', $chartData);
    }


    /* 4. */
    protected function deviceRequestsChart(Carbon $start, Carbon $end): void
    {
        $deviceTable   = (new FormRequestDevice())->getTable();
        $requestTable  = (new FormRequest())->getTable();
        $personalTable = (new FormPersonal())->getTable();

        $query = FormRequestDevice::query()
            ->join($requestTable, $requestTable . '.request_id', '=', $deviceTable . '.request_id')
            ->join($personalTable, $personalTable . '.applicant_id', '=', $requestTable . '.applicant_id')
            ->where($requestTable . '.request_type', 'device')
            ->whereBetween($requestTable . '.submitted_at', [$start, $end])
            ->whereNotNull($deviceTable . '.device_requested');

        if ($this->deviceBarangayFilter !== 'all') {
            $query->where($personalTable . '.barangay', $this->deviceBarangayFilter);
        }

        $rows = $query
            ->select($deviceTable . '.device_requested', DB::raw('COUNT(*) as total'))
            ->groupBy($deviceTable . '.device_requested')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $labels = $rows->pluck('device_requested')
            ->map(fn ($v) => $v ? ucwords(strtolower($v)) : 'Unknown')
            ->toArray();

        $values = $rows->pluck('total')->toArray();

        $this->reportData['device_requests'] = [
            'labels' => $labels,
            'values' => $values,
        ];

        $chartData = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [[
                    'label'           => 'Device Requests',
                    'data'            => $values,
                    'backgroundColor' => '#22c55e',
                ]],
            ],
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
            ],
        ];

        $this->dispatch('render-device-chart', $chartData);
    }

    /* 5. */
    protected function disabilityCauseChart(Carbon $start, Carbon $end): void
    {
        $query = $this->applyAgeFilterToPersonal(
            FormPersonal::select('disability_cause', DB::raw('COUNT(*) as total'))
        )
        ->whereBetween('submitted_at', [$start, $end]);

        // Optional barangay filter for this chart only
        if ($this->causeBarangayFilter !== 'all') {
            $query->where('barangay', $this->causeBarangayFilter);
        }

        $rows = $query
            ->groupBy('disability_cause')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $labels = $rows->pluck('disability_cause')->map(fn ($v) => $v ?: 'Unknown')->toArray();
        $values = $rows->pluck('total')->toArray();

        $this->reportData['disability_cause'] = [
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
                    'backgroundColor' => '#f97316',
                ]],
            ],
            'options' => [
                'indexAxis'           => 'y',
                'responsive'          => true,
                'maintainAspectRatio' => false,
            ],
        ];

        $this->dispatch('render-cause-chart', $chartData);
    }

    /* 6. Application counts by location (barangay) – kept */
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
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
            ],
        ];

        $this->dispatch('render-location-chart', $chartData);
    }

    /* 7. Monthly submission trends (Line, ALL application types for chosen date range) */
    protected function monthlyTrend(Carbon $start, Carbon $end): void
    {
        // Aggregate personal applications by year-month within the date range
        $personalRows = $this->applyAgeFilterToPersonal(
            FormPersonal::select(
                DB::raw('DATE_FORMAT(submitted_at, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as total')
            )
        )
        ->whereBetween('submitted_at', [$start, $end])
        ->groupBy(DB::raw('DATE_FORMAT(submitted_at, "%Y-%m")'))
        ->get();

        // Aggregate requests by year-month within the date range
        $requestRows = FormRequest::select(
                DB::raw('DATE_FORMAT(submitted_at, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy(DB::raw('DATE_FORMAT(submitted_at, "%Y-%m")'))
            ->get();

        // Map ym => total
        $map = [];

        foreach ($personalRows as $row) {
            $map[$row->ym] = ($map[$row->ym] ?? 0) + $row->total;
        }

        foreach ($requestRows as $row) {
            $map[$row->ym] = ($map[$row->ym] ?? 0) + $row->total;
        }

        // Build ordered labels & values month-by-month within the range
        $labels = [];
        $values = [];

        $cursor = $start->copy()->startOfMonth();
        $endMonth = $end->copy()->endOfMonth();

        while ($cursor <= $endMonth) {
            $ym = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $values[] = $map[$ym] ?? 0;

            $cursor->addMonth();
        }

        $this->reportData['monthly_trend'] = [
            'labels'      => $labels,
            'values'      => $values,
            'periodLabel' => $this->periodLabel(),
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
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
            ],
        ];

        $this->dispatch('render-trends-chart', $chartData);
    }

    /* ---------- EXPORTS AREA ---------- */

    public function startExport(string $format = 'pdf'): void
    {
        $this->exportFormat   = $format;   // 'pdf' or 'excel'
        $this->showExportModal = true;
    }

    public function cancelExport(): void
    {
        $this->showExportModal = false;
        $this->refreshAllCharts();
    }

    public function confirmExport()
    {
        // Close modal
        $this->showExportModal = false;

        // Decide which export to run
        if ($this->exportFormat === 'excel') {
            return $this->exportExcel();
        }

        return $this->exportPdf();
    }


    public function exportPdf()
    {
        // Always recalc based on current filters
        $this->refreshAllCharts();

        $data = [
            'periodLabel' => $this->periodLabel(),
            'filter'      => $this->timeFilter, // now always "range" unless changed manually
            'ageFilter'   => $this->ageFilter,
            'report'      => $this->reportData,
            'sections'    => $this->exportSections, // 👈 NEW
        ];

        $pdf = Pdf::loadView('exports.reports-pdf', $data)
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'pdao-reports-' . $this->timeFilter . '-' . $this->periodLabel() . '.pdf'
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
            'sections'    => $this->exportSections, // 👈 NEW
        ];

        return Excel::download(
            new ReportsExport($data),
            'pdao-reports-' . $this->timeFilter . '-' . $this->periodLabel() . '.xlsx'
        );
    }


    public function render()
    {
        return view('livewire.reports-charts', [
            'periodLabel' => $this->periodLabel(),
        ]);
    }
}
