<div class="space-y-3">
    <div class="flex items-baseline justify-between">
        <h3 class="text-sm font-semibold text-gray-800">
            Overview
        </h3>
        <p class="text-xs text-gray-500">
            Current filter: {{ $periodLabel ?? '' }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Registered PWDs (all time) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Total Registered PWDs
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['totalPwds'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                All time
            </p>
        </div>

        {{-- Total Finalized PWD IDs (all time) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Total Finalized PWD IDs
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['totalFinalizedPwds'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                All time
            </p>
        </div>

        {{-- New Registrations (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                New Registrations
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['newRegistrations'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Within selected period
            </p>
        </div>

        {{-- Encoded Applications (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Encoded Applications
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['encodedApplications'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Staff-encoded applications within period
            </p>
        </div>

        {{-- Finalized ID Applications (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Finalized ID Applications
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['finalizedIdApplications'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Within selected period
            </p>
        </div>

        {{-- Finalized Booklet Request (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Finalized Booklet Requests
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['finalizedBookletRequests'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Within selected period
            </p>
        </div>

        {{-- Finalized Device Request (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Finalized Device Requests
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['finalizedDeviceRequests'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Within selected period
            </p>
        </div>

        {{-- Finalized Financial Request (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Finalized Financial Requests
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['finalizedFinancialRequests'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Within selected period
            </p>
        </div>
    </div>
</div>
