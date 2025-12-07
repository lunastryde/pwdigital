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
                All time (age filter applied)
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
                All time (age filter applied)
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

        {{-- Finalized Financial Requests (filtered) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm">
            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                Finalized Financial Assistance
            </p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">
                {{ number_format($summary['finalizedFinancialRequests'] ?? 0) }}
            </p>
            <p class="mt-1 text-[11px] text-gray-500">
                Within selected period
            </p>
        </div>

        {{-- Total Registered PWDs by Barangay (all time) --}}
        <div class="rounded-xl border border-gray-100 bg-white/80 p-4 shadow-sm lg:col-span-2">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                        Total Registered PWDs (Barangay)
                    </p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ number_format($summary['totalPwdsByBarangay'] ?? 0) }}
                    </p>
                    <p class="mt-1 text-[11px] text-gray-500">
                        All time, filtered by barangay
                    </p>
                </div>

                <div>
                    <select
                        wire:model="barangayFilter"
                        wire:change="refreshAllCharts"
                        class="text-[11px] mt-1 px-2 py-1 border border-gray-200 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                        <option value="all">All</option>
                        @foreach(($barangayOptions ?? []) as $barangay)
                            <option value="{{ $barangay }}">{{ $barangay }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
