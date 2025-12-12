<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDAO Reports</title>
    <style>
        /* DOMPDF COMPATIBILITY SETTINGS */
        @page {
            /* Top margin set to 120px to prevent header overlap */
            margin: 120px 25px 50px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #374151; /* Gray-700 */
            line-height: 1.5;
            padding-top: 10px;
        }

        /* FIXED HEADER */
        .header-container {
            position: fixed;
            top: -100px; /* Adjusted to sit correctly in the margin */
            left: 0;
            right: 0;
            height: 80px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 25px;
            background-color: white;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            height: 60px;
            width: auto;
        }

        .company-info {
            padding-left: 15px;
            vertical-align: middle;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
        }

        .report-name {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            text-align: right;
            vertical-align: middle;
        }

        .meta-info {
            text-align: right;
            font-size: 9px;
            color: #6b7280;
            line-height: 1.3;
        }

        /* FIXED FOOTER */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            text-align: center;
        }

        .page-number:before {
            content: "Page " counter(page);
        }

        /* SECTIONS */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            border-left: 4px solid #2563eb;
            padding-left: 10px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1;
            padding-top: 2px;
        }

        /* DATA TABLES */
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .styled-table thead tr {
            background-color: #2563eb; /* Header Blue */
            color: #ffffff;
        }

        .styled-table th,
        .styled-table td {
            padding: 8px 12px;
            text-align: center;       /* ⬅ center horizontally */
            vertical-align: middle;   /* ⬅ center vertically in the row */
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f9fafb;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #2563eb;
        }

        /* SUMMARY TEXT TABLE (Replaces Cards) */
        .summary-table {
            width: 100%;
            font-size: 11px;
            margin-bottom: 10px;
        }
        .summary-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .summary-label {
            color: #6b7280;
            font-weight: normal;
        }
        .summary-value {
            color: #111827;
            font-weight: bold;
            font-size: 12px;
        }

        /* UTILITIES */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .badge {
            background: #e5e7eb;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
    </style>
</head>
<body>

    @php
        $ageLabelMap = [
            'all'     => 'All Ages',
            'under18' => 'Under 18',
            '18to29'  => '18–29',
            '30to59'  => '30–59',
            '60plus'  => '60 and above',
        ];

        $summary = $report['summary'] ?? [];
        $age     = $report['age_distribution']   ?? ['labels' => [], 'values' => []];
        $dev     = $report['device_requests']    ?? ['labels' => [], 'values' => []];
        $cause   = $report['disability_cause']   ?? ['labels' => [], 'values' => []];
        $loc     = $report['location_counts']    ?? ['labels' => [], 'values' => []];
        $mt      = $report['monthly_trend']      ?? ['labels' => [], 'values' => [], 'periodLabel' => $periodLabel ?? ''];

        // Default: if sections not passed for some reason, include everything
        $sections = $sections ?? [
            'summary'  => true,
            'age'      => true,
            'device'   => true,
            'cause'    => true,
            'location' => true,
            'trend'    => true,
        ];
    @endphp

    {{-- FIXED HEADER --}}
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 60px;">
                    {{-- Make sure the path is correct for your system --}}
                    <img src="{{ public_path('images/pdao_logo.png') }}" alt="Logo" class="logo">
                </td>
                <td class="company-info">
                    <div class="company-name">Persons with Disability Affairs Office</div>
                    <div style="font-size: 11px; margin-top:2px;">City of Calapan, Oriental Mindoro</div>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <div class="report-name">ANALYTICS REPORT</div>
                    <div class="meta-info">
                        <strong>Period:</strong> {{ $periodLabel }}<br>
                        <strong>Generated:</strong> {{ now()->format('M d, Y h:i A') }}<br>
                        <strong>Exported by:</strong> {{ $exportedBy ?? 'Unknown User' }}<br>
                        @isset($filter)
                            <span class="badge">Filter: {{ ucfirst($filter) }}</span>
                        @endisset
                        @isset($ageFilter)
                            <span class="badge">
                                Age: {{ $ageLabelMap[$ageFilter] ?? $ageFilter }}
                            </span>
                        @endisset
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- FIXED FOOTER --}}
    <div class="footer">
        System Generated Report | PDAO Information System | <span class="page-number"></span>
    </div>

    {{-- 1. EXECUTIVE SUMMARY --}}
    @if($sections['summary'] ?? false)
        <div class="section">
            <div class="section-title">Executive Summary</div>

            <table style="width: 100%; border-spacing: 0;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                        <table class="summary-table">
                            <tr>
                                <td class="summary-label">Total Registered PWDs:</td>
                                <td class="summary-value text-center">{{ number_format($summary['totalPwds'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td class="summary-label">Total Finalized IDs:</td>
                                <td class="summary-value text-center">{{ number_format($summary['totalFinalizedPwds'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td class="summary-label">New Registrations:</td>
                                <td class="summary-value text-center">{{ number_format($summary['newRegistrations'] ?? 0) }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; padding-left: 20px; border-left: 1px solid #e5e7eb;">
                        <table class="summary-table">
                            <tr>
                                <td class="summary-label">Encoded Applications:</td>
                                <td class="summary-value text-center">{{ number_format($summary['encodedApplications'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td class="summary-label">Finalized ID Applications:</td>
                                <td class="summary-value text-center">{{ number_format($summary['finalizedIdApplications'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td class="summary-label">Finalized Financial Requests:</td>
                                <td class="summary-value text-center">{{ number_format($summary['finalizedFinancialRequests'] ?? 0) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    {{-- 2. AGE DISTRIBUTION --}}
    @if($sections['age'] ?? false)
        <div class="section">
            <div class="section-title">Age Distribution</div>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Age Range</th>
                        <th class="text-center">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($age['labels'] as $i => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-center">{{ number_format($age['values'][$i] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- 3. DEVICE REQUESTS --}}
    @if($sections['device'] ?? false)
        <div class="section">
            <div class="section-title">Device Requests</div>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th class="text-center">Requests</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dev['labels'] as $i => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-center">{{ number_format($dev['values'][$i] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- 4. COMMON CAUSES OF DISABILITY --}}
    @if($sections['cause'] ?? false)
        <div class="section">
            <div class="section-title">Common Causes of Disability</div>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Cause</th>
                        <th class="text-center">Total Cases</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cause['labels'] as $i => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-center">{{ number_format($cause['values'][$i] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- 5. LOCATION DATA --}}
    @if($sections['location'] ?? false)
        <div class="section">
            <div class="section-title">Applications by Barangay</div>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Barangay</th>
                        <th class="text-center">Applications</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loc['labels'] as $i => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-center">{{ number_format($loc['values'][$i] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- 6. TRENDS --}}
    @if($sections['trend'] ?? false)
        <div class="section">
            <div class="section-title">
                Monthly Trend ({{ $mt['periodLabel'] ?? $periodLabel }})
            </div>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Submissions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mt['labels'] as $i => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-center">{{ number_format($mt['values'][$i] ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

</body>
</html>
