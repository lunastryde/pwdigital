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
            padding-bottom: 10px;
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
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 8px 12px;
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
                <td class="text-right" style="vertical-align: middle;">
                    <div class="report-name">ANALYTICS REPORT</div>
                    <div class="meta-info">
                        <strong>Period:</strong> {{ $periodLabel }}<br>
                        <strong>Generated:</strong> {{ now()->format('M d, Y h:i A') }}<br>
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

    {{-- CONTENT --}}
    @php $summary = $report['summary'] ?? []; @endphp

    {{-- 1. EXECUTIVE SUMMARY (TEXT LIST) --}}
    <div class="section">
        <div class="section-title">Executive Summary</div>
        
        {{-- Two-column layout for summary text --}}
        <table style="width: 100%; border-spacing: 0;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <table class="summary-table">
                        <tr>
                            <td class="summary-label">Total Registered PWDs:</td>
                            <td class="summary-value text-right">{{ number_format($summary['totalPwds'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label">Total Finalized IDs:</td>
                            <td class="summary-value text-right">{{ number_format($summary['totalFinalizedPwds'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label">New Registrations:</td>
                            <td class="summary-value text-right">{{ number_format($summary['newRegistrations'] ?? 0) }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 20px; border-left: 1px solid #e5e7eb;">
                    <table class="summary-table">
                        <tr>
                            <td class="summary-label">Encoded Applications:</td>
                            <td class="summary-value text-right">{{ number_format($summary['encodedApplications'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label">Finalized ID Applications:</td>
                            <td class="summary-value text-right">{{ number_format($summary['finalizedIdApplications'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label">Finalized Financial Requests:</td>
                            <td class="summary-value text-right">{{ number_format($summary['finalizedFinancialRequests'] ?? 0) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- 2. APPLICATIONS BY CATEGORY --}}
    <div class="section">
        <div class="section-title">Applications by Category</div>
        @php $cat = $report['applications_by_category'] ?? ['labels' => [], 'values' => []]; @endphp
        <table class="styled-table">
            <thead>
                <tr>
                    <th style="width: 80%;">Category</th>
                    <th style="width: 20%; text-align: right;">Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cat['labels'] as $i => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td class="text-right">{{ number_format($cat['values'][$i] ?? 0) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center">No data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 3. DEMOGRAPHICS GRID (Side by Side Tables) --}}
    <div class="section">
        <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">
            <tr>
                {{-- Gender Table --}}
                <td style="width: 48%; vertical-align: top; padding-right: 10px;">
                    <div class="section-title">Gender Distribution</div>
                    @php $gd = $report['gender_distribution'] ?? ['labels' => [], 'values' => []]; @endphp
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Sex</th>
                                <th class="text-right">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gd['labels'] as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-right">{{ $gd['values'][$i] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                {{-- Status Table --}}
                <td style="width: 48%; vertical-align: top; padding-left: 10px;">
                    <div class="section-title">Status Breakdown</div>
                    @php $st = $report['status_breakdown'] ?? ['labels' => [], 'values' => []]; @endphp
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="text-right">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($st['labels'] as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-right">{{ $st['values'][$i] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- 4. DISABILITY TYPES --}}
    <div class="section">
        <div class="section-title">Top 10 Disability Types</div>
        @php $ds = $report['disability_breakdown'] ?? ['labels' => [], 'values' => []]; @endphp
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Disability Type</th>
                    <th class="text-right">Total Cases</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ds['labels'] as $i => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td class="text-right">{{ number_format($ds['values'][$i] ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 5. LOCATION DATA --}}
    <div class="section">
        <div class="section-title">Applications by Barangay</div>
        @php $loc = $report['location_counts'] ?? ['labels' => [], 'values' => []]; @endphp
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th class="text-right">Applications</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loc['labels'] as $i => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td class="text-right">{{ number_format($loc['values'][$i] ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 6. TRENDS --}}
    <div class="section">
        <div class="section-title">
            Monthly Trend ({{ $report['monthly_trend']['year'] ?? '' }})
        </div>
        @php $mt = $report['monthly_trend'] ?? ['labels' => [], 'values' => []]; @endphp
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-right">Submissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mt['labels'] as $i => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td class="text-right">{{ number_format($mt['values'][$i] ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>