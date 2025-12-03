<div class="space-y-6" wire:poll.30s="refreshData">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Dashboard</h2>
            <p class="mt-1 text-sm text-gray-600">
                Central overview of PWD registrations and active applications.
            </p>
        </div>
    </div>

    {{-- Top metric cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Registered PWD IDs</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">
                {{ number_format($totalRegisteredPwds) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Finalized ID applications</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Staff Accounts</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">
                {{ number_format($totalStaffAccounts) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Admins + staff users</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Applications</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">
                {{ number_format($totalActiveApplications) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Pending / Under final review</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">New Today</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">
                {{ number_format($newApplicationsToday) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Submissions since midnight</p>
        </div>
    </div>

    {{-- Application overview strip --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending ID Applications</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ number_format($totalPendingIdApps) }}
                </p>
            </div>
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600">
                {{-- icon --}}
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending Support Requests</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ number_format($totalPendingRequests) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Renewal, loss, device, financial, booklet</p>
            </div>
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Queue Health</p>
                <p class="mt-2 text-sm text-gray-800">
                    {{ $totalActiveApplications > 0
                        ? 'There are active items waiting for action.'
                        : 'No active items at the moment.' }}
                </p>
            </div>
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Live pending / active applications table --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-900">Active & Pending Applications</h3>
                <p class="text-xs text-gray-500">
                    ID applications and all support requests with status Pending / Under Final Review.
                </p>
            </div>
            <span class="text-xs text-gray-400">
                Showing latest {{ count($recentApplications) }} items
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Applicant
                        </th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Category
                        </th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Type
                        </th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Status
                        </th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Submitted
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($recentApplications as $row)
                        @php
                            $status = $row['status'] ?? 'Pending';
                            $badge = 'bg-gray-100 text-gray-800';

                            if ($status === 'Pending') {
                                $badge = 'bg-yellow-100 text-yellow-800';
                            } elseif ($status === 'Under Final Review') {
                                $badge = 'bg-blue-100 text-blue-800';
                            } elseif ($status === 'Finalized') {
                                $badge = 'bg-green-100 text-green-800';
                            } elseif (in_array($status, ['Rejected', 'Needs Revision'])) {
                                $badge = 'bg-red-100 text-red-800';
                            }

                            $submitted = $row['submitted_at'] ?? null;
                            try {
                                $date = $submitted ? \Illuminate\Support\Carbon::parse($submitted) : null;
                            } catch (\Throwable $e) {
                                $date = null;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-900">
                                {{ $row['applicant'] ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $row['type'] }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $row['subtype'] }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $date ? $date->format('M d, Y · h:i A') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                No active or pending applications at the moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
