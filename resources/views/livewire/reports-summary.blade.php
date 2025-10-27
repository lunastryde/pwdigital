<div wire:poll.30s class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card: Registered PWDs -->
    <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500">Registered PWDs</div>
                <div class="text-2xl font-semibold text-gray-800">{{ number_format($totalPwds) }}</div>
            </div>
            <div class="text-gray-400">
                <!-- icon placeholder -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12zM4.5 21c0-3.75 3-6 7.5-6s7.5 2.25 7.5 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card: ID Applications -->
    <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500">ID Applications</div>
                <div class="text-2xl font-semibold text-gray-800">{{ number_format($idApplications) }}</div>
            </div>
            <div class="text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                </svg>
            </div>
        </div>
    </div>

    <!-- Card: Requests -->
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

    <!-- Card: Issued / Status summary -->
    <div class="bg-white rounded-2xl shadow p-4">
        <div>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Issued IDs</div>
                    <div class="text-2xl font-semibold text-gray-800">{{ number_format($issuedIds) }}</div>
                </div>
                <div class="text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    </svg>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-3 gap-2 text-center text-sm">
                <div>
                    <div class="text-gray-500">Pending</div>
                    <div class="font-semibold text-gray-800">{{ number_format($statusCounts['pending'] ?? 0) }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Approved</div>
                    <div class="font-semibold text-gray-800">{{ number_format($statusCounts['approved'] ?? 0) }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Rejected</div>
                    <div class="font-semibold text-gray-800">{{ number_format($statusCounts['rejected'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
