<div> {{-- Component wrapper --}}

    <div class="mb-4">
        <label for="filterPeriod" class="text-sm font-medium text-gray-700">Filter by:</label>
        <select wire:model.live="filterPeriod" id="filterPeriod" class="ml-2 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="all">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
        </select>
    </div>

    <div wire:poll.30s class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Registered PWDs</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ number_format($totalPwds) }}</div>
                </div>
                <div class="text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12zM4.5 21c0-3.75 3-6 7.5-6s7.5 2.25 7.5 6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">ID Applications (Total)</div>
                        <div class="text-2xl font-semibold text-gray-800">{{ number_format($idApplications) }}</div>
                    </div>
                    <div class="text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        </svg>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-4 gap-1 text-center text-sm">
                    <div>
                        <div class="text-gray-500">Pending</div>
                        <div class="font-semibold text-gray-800">{{ number_format($idStatusCounts['Pending'] ?? 0) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Approved</div>
                        <div class="font-semibold text-gray-800">{{ number_format($idStatusCounts['Approved'] ?? 0) }}</div>
                    </div>
                    <div>
                        <div class="text-green-600">Issued</div>
                        <div class="font-semibold text-green-600">{{ number_format($idStatusCounts['Finalized'] ?? 0) }}</div>
                    </div>
                    <div>
                        <div class="text-red-600">Rejected</div>
                        <div class="font-semibold text-red-600">{{ number_format($idStatusCounts['Rejected'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm text-gray-500">Requests (total)</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ number_format($totalRequests) }}</div>
                    <div class="mt-2 text-sm text-gray-600 space-y-1">
                        <div>Booklet: <span class="font-medium text-gray-800">{{ number_format($bookletRequests) }}</span></div>
                        <div>Financial: <span class="font-medium text-gray-800">{{ number_format($financialRequests) }}</span></div>
                        <div>Device: <span class="font-medium text-gray-800">{{ number_format($deviceRequests) }}</span></div>
                    </div>
                </div>
                <div class="text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">User Feedback</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ number_format($feedbackCount) }}</div>
                    <div class="text-sm text-gray-600">Total Submissions</div>
                </div>
                <div class="text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">Gender Distribution</div>
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <div>Male: <span class="font-medium text-gray-800">{{ number_format($genderDistribution['male'] ?? 0) }}</span></div>
                <div>Female: <span class="font-medium text-gray-800">{{ number_format($genderDistribution['female'] ?? 0) }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">Top Disabilities</div>
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                @forelse($topDisabilities as $type => $count)
                    <div>
                        <span>{{ $type }}:</span> 
                        <span class="font-medium text-gray-800">{{ number_format($count) }}</span>
                    </div>
                @empty
                    <div class="text-gray-500">No data for this period.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">Top Locations (Barangay)</div>
            </div>
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                @forelse($topLocations as $location => $count)
                    <div>
                        <span>{{ $location }}:</span> 
                        <span class="font-medium text-gray-800">{{ number_format($count) }}</span>
                    </div>
                @empty
                    <div class="text-gray-500">No data for this period.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>