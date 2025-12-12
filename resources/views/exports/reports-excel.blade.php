@php
    $ageLabelMap = [
        'all'     => 'All Ages',
        'under18' => 'Under 18',
        '18to29'  => '18–29',
        '30to59'  => '30–59',
        '60plus'  => '60 and above',
    ];

    $summary = $report['summary'] ?? [];
    $age   = $report['age_distribution']      ?? ['labels' => [], 'values' => []];
    $dev   = $report['device_requests']       ?? ['labels' => [], 'values' => []];
    $cause = $report['disability_cause']      ?? ['labels' => [], 'values' => []];
    $loc   = $report['location_counts']       ?? ['labels' => [], 'values' => []];
    $mt    = $report['monthly_trend']         ?? ['labels' => [], 'values' => [], 'periodLabel' => $periodLabel ?? ''];

    // Default sections
    $sections = $sections ?? [
        'summary'  => true,
        'age'      => true,
        'cause'    => true,
        'device'   => true,
        'location' => true,
        'trend'    => true,
    ];

    $ageFilterLabel = $ageFilter ? ($ageLabelMap[$ageFilter] ?? $ageFilter) : 'All Ages';
    $exporterLabel  = $exportedBy ?? 'System';
@endphp

{{-- Main Layout Table --}}
<table>
    {{-- Header Section (Rows 1-4 handled by PHP styling) --}}
    <tr>
        <td>Persons with Disability Affairs Office (PDAO)</td>
        <td></td> {{-- Merged in PHP --}}
    </tr>
    <tr>
        <td>City of Calapan, Oriental Mindoro</td>
        <td></td> {{-- Merged in PHP --}}
    </tr>
    <tr><td></td><td></td></tr> {{-- Spacer --}}
    <tr>
        <td>PDAO Reports &amp; Analytics</td>
        <td></td> {{-- Merged in PHP --}}
    </tr>

    {{-- Report Details Section --}}
    <thead>
        <tr>
            <th>Report Details</th>
            <th></th> {{-- Empty cell for PHP styling to catch the row --}}
        </tr>
        <tr>
            <th>Parameter</th>
            <th>Configuration</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Reporting Period</td>
            <td>{{ $periodLabel }}</td>
        </tr>
        <tr>
            <td>Date Generated</td>
            <td>{{ now()->format('F d, Y h:i A') }}</td>
        </tr>
        <tr>
            <td>Exported By</td>
            <td>{{ $exporterLabel }}</td>
        </tr>
        <tr>
            <td>Demographic Filter</td>
            <td>{{ $ageFilterLabel }}</td>
        </tr>
    </tbody>

    <tr><td></td><td></td></tr> {{-- Spacer --}}

    {{-- Summary Section --}}
    @if($sections['summary'] ?? false)
        <thead>
            <tr>
                <th>Summary</th>
                <th></th>
            </tr>
            <tr>
                <th>Metric</th>
                <th>Count</th>
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
                <td>New Registrations (This Period)</td>
                <td>{{ $summary['newRegistrations'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Encoded Applications</td>
                <td>{{ $summary['encodedApplications'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Finalized ID Applications</td>
                <td>{{ $summary['finalizedIdApplications'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Finalized Financial Requests</td>
                <td>{{ $summary['finalizedFinancialRequests'] ?? 0 }}</td>
            </tr>
        </tbody>
        <tr><td></td><td></td></tr>
    @endif

    {{-- Age Distribution --}}
    @if($sections['age'] ?? false)
        <thead>
            <tr>
                <th>Age Distribution</th>
                <th></th>
            </tr>
            <tr>
                <th>Age Range</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($age['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $age['values'][$i] ?? 0 }}</td>
                </tr>
            @empty
                <tr><td>No data available</td><td>0</td></tr>
            @endforelse
        </tbody>
        <tr><td></td><td></td></tr>
    @endif

    {{-- Causes --}}
    @if($sections['cause'] ?? false)
        <thead>
            <tr>
                <th>Common Causes of Disability</th>
                <th></th>
            </tr>
            <tr>
                <th>Cause / Diagnosis</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cause['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $cause['values'][$i] ?? 0 }}</td>
                </tr>
            @empty
                <tr><td>No data available</td><td>0</td></tr>
            @endforelse
        </tbody>
        <tr><td></td><td></td></tr>
    @endif

    {{-- Devices --}}
    @if($sections['device'] ?? false)
        <thead>
            <tr>
                <th>Device Requests</th>
                <th></th>
            </tr>
            <tr>
                <th>Device Type</th>
                <th>Requests</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dev['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $dev['values'][$i] ?? 0 }}</td>
                </tr>
            @empty
                <tr><td>No data available</td><td>0</td></tr>
            @endforelse
        </tbody>
        <tr><td></td><td></td></tr>
    @endif

    {{-- Locations --}}
    @if($sections['location'] ?? false)
        <thead>
            <tr>
                <th>Applications by Barangay</th>
                <th></th>
            </tr>
            <tr>
                <th>Barangay</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loc['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $loc['values'][$i] ?? 0 }}</td>
                </tr>
            @empty
                <tr><td>No data available</td><td>0</td></tr>
            @endforelse
        </tbody>
        <tr><td></td><td></td></tr>
    @endif

    {{-- Trend --}}
    @if($sections['trend'] ?? false)
        <thead>
            <tr>
                <th>Monthly Submission Trend ({{ $mt['periodLabel'] ?? $periodLabel }})</th>
                <th></th>
            </tr>
            <tr>
                <th>Month</th>
                <th>Submissions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mt['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $mt['values'][$i] ?? 0 }}</td>
                </tr>
            @empty
                <tr><td>No data available</td><td>0</td></tr>
            @endforelse
        </tbody>
    @endif
</table>