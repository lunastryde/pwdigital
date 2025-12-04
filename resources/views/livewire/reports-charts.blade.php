<div class="max-w-screen-2xl mx-auto space-y-10 px-4">

    {{-- Header + Filter + Export --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Section 1: Title & Context --}}
            <div>
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Reports & Analytics</h2>
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
                    
                    {{-- Time Filter --}}
                    <div class="relative">
                        <select 
                            wire:model="timeFilter" 
                            wire:change="refreshAllCharts"
                            class="pl-3 pr-8 py-1.5 text-sm font-medium text-gray-700 bg-transparent border-none rounded-md focus:ring-0 focus:bg-white cursor-pointer"
                        >
                            <option value="week">This Week</option>
                            <option value="month">By Month</option>
                            <option value="year">By Year</option>
                        </select>
                    </div>

                    <div class="w-px h-4 bg-gray-300"></div> {{-- Vertical Separator --}}

                    {{-- Year Selector --}}
                    <div class="relative">
                        <select 
                            wire:model="selectedYear" 
                            wire:change="refreshAllCharts"
                            class="pl-3 pr-8 py-1.5 text-sm text-gray-600 bg-transparent border-none rounded-md focus:ring-0 focus:bg-white cursor-pointer"
                        >
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Month Selector (Conditional) --}}
                    @if($timeFilter === 'month')
                        <div class="w-px h-4 bg-gray-300"></div>
                        <div class="relative">
                            <select 
                                wire:model="selectedMonth" 
                                wire:change="refreshAllCharts"
                                class="pl-3 pr-8 py-1.5 text-sm text-gray-600 bg-transparent border-none rounded-md focus:ring-0 focus:bg-white cursor-pointer"
                            >
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Age Filter --}}
                    <div class="w-px h-4 bg-gray-300"></div>
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
                        wire:click="exportPdf"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-100 rounded-lg hover:bg-red-50 transition-colors shadow-sm"
                        title="Export PDF"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        PDF
                    </button>
                    
                    <button 
                        wire:click="exportExcel"
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-emerald-700 bg-white border border-emerald-100 rounded-lg hover:bg-emerald-50 transition-colors shadow-sm"
                        title="Export Excel"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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

    {{-- Row 1: Applications by Category / Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">Applications by Category</h3>
            <div class="h-80">
                <canvas id="applicationsChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">Status Breakdown</h3>
            <div class="h-80">
                <canvas id="statusChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 2: Gender / Disability --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">Gender Distribution</h3>
            <div class="h-80">
                <canvas id="genderChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">Disability Types (Top 10)</h3>
            <div class="h-80">
                <canvas id="disabilityChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 3: Location / Monthly Trend --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">Applications by Barangay (Top 10)</h3>
            <div class="h-80">
                <canvas id="locationChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-semibold mb-4">
                Monthly Submission Trend ({{ $selectedYear }})
            </h3>
            <div class="h-80">
                <canvas id="trendsChart" class="w-full h-full"></canvas>
            </div>
        </div>

    </div>
</div>
