<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDAO Reports</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1, h2, h3 { margin: 0 0 6px 0; }
        .section { margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        th, td { border: 1px solid #dddddd; padding: 4px 6px; text-align: left; }
        th { background-color: #f3f4f6; }
        .small { font-size: 10px; color: #555; }
    </style>
</head>
<body>

    <h1>PDAO Reports &amp; Analytics</h1>
    <p class="small">Period: {{ $periodLabel }} | Generated: {{ now()->format('Y-m-d H:i') }}</p>

    {{-- Summary --}}
    @php $summary = $report['summary'] ?? []; @endphp
    <div class="section">
        <h2>Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Registered PWDs</td>
                    <td>{{ $summary['totalPwds'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Total Finalized PWD IDs</td>
                    <td>{{ $summary['totalFinalizedPwds'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>New Registrations</td>
                    <td>{{ $summary['newRegistrations'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Open Applications</td>
                    <td>{{ $summary['openApplications'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Finalized ID Applications</td>
                    <td>{{ $summary['finalizedIdApplications'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Finalized Booklet Requests</td>
                    <td>{{ $summary['finalizedBookletRequests'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Finalized Device Requests</td>
                    <td>{{ $summary['finalizedDeviceRequests'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Finalized Financial Requests</td>
                    <td>{{ $summary['finalizedFinancialRequests'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Applications by Category --}}
    <div class="section">
        <h2>Applications by Category</h2>
        @php $cat = $report['applications_by_category'] ?? ['labels' => [], 'values' => []]; @endphp
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Applications</th>
                </tr>
            </thead>
            <tbody>
            @foreach($cat['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $cat['values'][$i] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Status Breakdown --}}
    <div class="section">
        <h2>Status Breakdown</h2>
        @php $st = $report['status_breakdown'] ?? ['labels' => [], 'values' => []]; @endphp
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
            @foreach($st['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $st['values'][$i] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Gender --}}
    <div class="section">
        <h2>Gender Distribution</h2>
        @php $gd = $report['gender_distribution'] ?? ['labels' => [], 'values' => []]; @endphp
        <table>
            <thead>
                <tr>
                    <th>Sex</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
            @foreach($gd['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $gd['values'][$i] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Disability --}}
    <div class="section">
        <h2>Disability Types (Top 10)</h2>
        @php $ds = $report['disability_breakdown'] ?? ['labels' => [], 'values' => []]; @endphp
        <table>
            <thead>
                <tr>
                    <th>Disability Type</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
            @foreach($ds['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $ds['values'][$i] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Barangay --}}
    <div class="section">
        <h2>Applications by Barangay (Top 10)</h2>
        @php $loc = $report['location_counts'] ?? ['labels' => [], 'values' => []]; @endphp
        <table>
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Applications</th>
                </tr>
            </thead>
            <tbody>
            @foreach($loc['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $loc['values'][$i] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Monthly Trend --}}
    <div class="section">
        <h2>Monthly Submission Trend ({{ $report['monthly_trend']['year'] ?? '' }})</h2>
        @php $mt = $report['monthly_trend'] ?? ['labels' => [], 'values' => []]; @endphp
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Submissions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($mt['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $mt['values'][$i] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
