<div class="max-w-screen-2xl mx-auto space-y-10 px-4">

    {{-- Header + Filter + Export --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

            {{-- Section 1: Title & Context --}}
            <div>
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Reports &amp; Analytics</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                        Period: {{ $periodLabel }}
                    </span>
                </div>
            </div>

            {{-- Section 2: Controls Toolbar --}}
            <div class="flex flex-col sm:flex-row flex-wrap items-start sm:items-center gap-3">

                {{-- Filter Group --}}
                <div class="flex items-center gap-2 p-1 bg-gray-50 border border-gray-200 rounded-lg">

                    {{-- Date Range --}}
                    <div class="flex items-center gap-2">
                        <input
                            type="date"
                            wire:model="startDate"
                            wire:change="refreshAllCharts"
                            class="px-2 py-1.5 text-sm text-gray-700 bg-transparent border-none rounded-md focus:ring-0 focus:bg-white cursor-pointer"
                        />
                        <span class="text-xs text-gray-400">to</span>
                        <input
                            type="date"
                            wire:model="endDate"
                            wire:change="refreshAllCharts"
                            class="px-2 py-1.5 text-sm text-gray-700 bg-transparent border-none rounded-md focus:ring-0 focus:bg-white cursor-pointer"
                        />
                    </div>

                    <div class="w-px h-4 bg-gray-300"></div>

                    {{-- Age Filter --}}
                    <div class="relative">
                        <select
                            wire:model="ageFilter"
                            wire:change="refreshAllCharts"
                            class="pl-3 pr-8 py-1.5 text-sm text-gray-600 bg-transparent border-none rounded-md focus:ring-0 focus:bg-white cursor-pointer"
                        >
                            @foreach($ageOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Divider on Desktop --}}
                <div class="hidden sm:block w-px h-8 bg-gray-200 mx-1"></div>

                {{-- Export Group --}}
                <div class="flex items-center gap-2">
                    <button
                        wire:click="startExport('pdf')"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-100 rounded-lg hover:bg-red-50 transition-colors shadow-sm"
                        title="Export PDF"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        PDF
                    </button>

                    <button
                        wire:click="startExport('excel')"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-emerald-700 bg-white border border-emerald-100 rounded-lg hover:bg-emerald-50 transition-colors shadow-sm"
                        title="Export Excel"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary cards --}}
    @include('livewire.reports-summary', [
        'summary' => $reportData['summary'] ?? [],
    ])

    {{-- Row 1: Age Distribution / Device Requests --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold">Age Distribution (Histogram)</h3>
                <select
                    wire:model="ageBarangayFilter"
                    wire:change="refreshAllCharts"
                    class="text-xs px-2 py-1 border border-gray-200 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="all">All Barangays</option>
                    @foreach($barangayOptions as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>
            <div class="h-80">
                <canvas id="ageChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-semibold">Device Requests</h3>
                    <p class="text-xs text-gray-500">
                        Top device types within the selected period.
                    </p>
                </div>
                <select
                    wire:model="deviceBarangayFilter"
                    wire:change="refreshAllCharts"
                    class="text-xs px-2 py-1 border border-gray-200 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="all">All Barangays</option>
                    @foreach($barangayOptions as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>

            <div class="h-80">
                <canvas id="deviceChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 2: Disability Cause / Applications by Barangay --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold">Common Causes of Disability</h3>
                <select
                    wire:model="causeBarangayFilter"
                    wire:change="refreshAllCharts"
                    class="text-xs px-2 py-1 border border-gray-200 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                    <option value="all">All Barangays</option>
                    @foreach($barangayOptions as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>
            <div class="h-80">
                <canvas id="causeChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">Applications by Barangay</h3>
            <div class="h-80">
                <canvas id="locationChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 3: Monthly Trend (full width) --}}
    <div class="grid grid-cols-1 gap-10">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">
                Monthly Submission Trend
                <span class="text-xs font-normal text-gray-500">
                    ({{ $reportData['monthly_trend']['periodLabel'] ?? $periodLabel }})
                </span>
            </h3>
            <div class="h-80">
                <canvas id="trendsChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    @if($showExportModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">
                    Select Data to Include
                </h2>
                <p class="text-xs text-gray-500 mb-4">
                    The export will still follow your current date and age filters.
                    Choose which sections you want to include in the
                    {{ $exportFormat === 'excel' ? 'Excel file' : 'PDF report' }}.
                </p>

                <div class="space-y-2 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            class="rounded border-gray-300"
                            wire:model="exportSections.summary">
                        <span>Executive Summary</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            class="rounded border-gray-300"
                            wire:model="exportSections.age">
                        <span>Age Distribution</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            class="rounded border-gray-300"
                            wire:model="exportSections.device">
                        <span>Device Requests</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            class="rounded border-gray-300"
                            wire:model="exportSections.cause">
                        <span>Common Causes of Disability</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            class="rounded border-gray-300"
                            wire:model="exportSections.location">
                        <span>Applications by Barangay</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            class="rounded border-gray-300"
                            wire:model="exportSections.trend">
                        <span>Monthly Submission Trend</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click="cancelExport"
                        class="px-3 py-1.5 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="confirmExport"
                        class="px-3 py-1.5 text-sm rounded-md
                            {{ $exportFormat === 'excel' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700' }}
                            text-white"
                    >
                        {{ $exportFormat === 'excel' ? 'Export Excel' : 'Export PDF' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
