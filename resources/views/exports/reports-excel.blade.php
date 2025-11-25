<table>
    <tr>
        <td colspan="3">
            <strong>PDAO Reports &amp; Analytics</strong><br>
            Period: {{ $periodLabel }}<br>
            Generated: {{ now()->format('Y-m-d H:i') }}
        </td>
    </tr>
</table>

{{-- Summary --}}
@php $summary = $report['summary'] ?? []; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Summary</th>
        </tr>
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

{{-- Applications by Category --}}
@php $cat = $report['applications_by_category'] ?? ['labels' => [], 'values' => []]; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Applications by Category</th>
        </tr>
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

{{-- Status Breakdown --}}
@php $st = $report['status_breakdown'] ?? ['labels' => [], 'values' => []]; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Status Breakdown</th>
        </tr>
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

{{-- Gender --}}
@php $gd = $report['gender_distribution'] ?? ['labels' => [], 'values' => []]; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Gender Distribution</th>
        </tr>
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

{{-- Disability --}}
@php $ds = $report['disability_breakdown'] ?? ['labels' => [], 'values' => []]; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Disability Types (Top 10)</th>
        </tr>
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

{{-- Barangay --}}
@php $loc = $report['location_counts'] ?? ['labels' => [], 'values' => []]; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Applications by Barangay (Top 10)</th>
        </tr>
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

{{-- Monthly Trend --}}
@php $mt = $report['monthly_trend'] ?? ['labels' => [], 'values' => [], 'year' => '' ]; @endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Monthly Submission Trend ({{ $mt['year'] }})</th>
        </tr>
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
