<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormRequest;
use App\Models\FormRequestDevice;
use App\Models\FormPersonal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportsCharts extends Component
{
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
        $this->startDate = now()->copy()->startOfMonth()->toDateString(); // Y-m-d
        $this->endDate   = now()->copy()->endOfMonth()->toDateString();   // Y-m-d

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

        // Requests
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

    protected function periodLabel(): string
    {
        [$start, $end] = $this->getDateRange();

        return $start->format('M d, Y') . ' – ' . $end->format('M d, Y');
    }

    public function refreshAllCharts(): void
    {
        [$start, $end] = $this->getDateRange();

        $this->reportData = [];

        // Summary cards data
        $this->reportData['summary'] = $this->buildSummaryData($start, $end);

        $this->ageDistribution($start, $end);
        $this->deviceRequestsChart($start, $end);
        $this->disabilityCauseChart($start, $end);
        $this->locationCounts($start, $end);
        $this->monthlyTrend($start, $end);
    }

    /* 1. */
    protected function ageDistribution(Carbon $start, Carbon $end): void
    {
        $query = $this->applyAgeFilterToPersonal(
            FormPersonal::whereBetween('submitted_at', [$start, $end])
        )->where('status', 'Finalized');

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


    /* 2. */
    protected function deviceRequestsChart(Carbon $start, Carbon $end): void
    {
        $deviceTable   = (new FormRequestDevice())->getTable();
        $requestTable  = (new FormRequest())->getTable();
        $personalTable = (new FormPersonal())->getTable();

        $query = FormRequestDevice::query()
            ->join($requestTable, $requestTable . '.request_id', '=', $deviceTable . '.request_id')
            ->join($personalTable, $personalTable . '.applicant_id', '=', $requestTable . '.applicant_id')
            ->where($requestTable . '.request_type', 'device')
            ->where($requestTable . '.status', 'Finalized')
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

    /* 3. */
    protected function disabilityCauseChart(Carbon $start, Carbon $end): void
    {
        $query = $this->applyAgeFilterToPersonal(
            FormPersonal::select('disability_cause', DB::raw('COUNT(*) as total'))
        )
        ->whereBetween('submitted_at', [$start, $end])
        ->where('status', 'Finalized');

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

    /* 4. */
    protected function locationCounts(Carbon $start, Carbon $end): void
    {
        $rows = $this->applyAgeFilterToPersonal(
            FormPersonal::select('barangay', DB::raw('COUNT(*) as total'))
        )
        ->whereBetween('submitted_at', [$start, $end])
        ->where('status', 'Finalized')
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

    /* 5. */
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
        ->where('status', 'Finalized')
        ->groupBy(DB::raw('DATE_FORMAT(submitted_at, "%Y-%m")'))
        ->get();

        // Aggregate requests by year-month within the date range
        $requestRows = FormRequest::select(
                DB::raw('DATE_FORMAT(submitted_at, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('submitted_at', [$start, $end])
            ->where('status', 'Finalized')
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

    /* ------------------------------ EXPORTS AREA ------------------------------ */

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

    protected function getExporterName(): string
    {
        $user = Auth::user();

        if (!$user) {
            return 'System';
        }

        $profile = $user->profile;

        $parts = array_filter([
            $profile->fname ?? null,
            $profile->mname ?? null,
            $profile->lname ?? null,
        ]);

        if (!empty($parts)) {
            return implode(' ', $parts);
        }

        if (!empty($user->name)) {
            return $user->name;
        }

        if (!empty($user->username)) {
            return $user->username;
        }

        return 'Unknown User';
    }

    public function exportPdf()
    {
        $this->refreshAllCharts();

        $data = [
            'periodLabel' => $this->periodLabel(),
            'ageFilter'   => $this->ageFilter,
            'report'      => $this->reportData,
            'sections'    => $this->exportSections,
            'exportedBy'  => $this->getExporterName(),
        ];

        $pdf = Pdf::loadView('exports.reports-pdf', $data)
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'pdao-reports-' . $this->periodLabel() . '.pdf'
        );
    }

    public function exportExcel()
    {
        $this->refreshAllCharts();

        $data = [
            'periodLabel' => $this->periodLabel(),
            'ageFilter'   => $this->ageFilter,
            'report'      => $this->reportData,
            'sections'    => $this->exportSections,
            'exportedBy'  => $this->getExporterName(),
        ];

        return Excel::download(
            new ReportsExport($data),
            'pdao-reports-' . $this->periodLabel() . '.xlsx'
        );
    }


    public function render()
    {
        return view('livewire.reports-charts', [
            'periodLabel' => $this->periodLabel(),
        ]);
    }
}
