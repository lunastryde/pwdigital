<div class="bg-white rounded-2xl shadow p-6">
    <div class="mt-6 overflow-x-auto">
        <div class="min-w-[600px] rounded-lg ring-1 ring-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-blue-400 text-white">
                    <tr>
                        <th class="px-8 py-2 text-left rounded-tl-lg">Application Type</th>
                        <th class="px-8 py-2 text-left">Date Applied</th>
                        <th class="px-8 py-2 text-left">Status</th>
                        <th class="px-13 py-2 text-left rounded-tr-lg">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-8 py-3 text-gray-800">
                                {{ $app->type }}
                            </td>
                            <td class="px-9 py-3 text-gray-600">
                                @php
                                    try {
                                        $dt = \Illuminate\Support\Carbon::parse($app->date);
                                    } catch (\Throwable $e) {
                                        $dt = null;
                                    }
                                @endphp
                                {{ $dt ? $dt->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($app->status === 'approved') bg-green-100 text-green-700
                                    @elseif($app->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($app->source === 'request')
                                    <button type="button" 
                                            wire:click="$dispatch('open-request-details', { id: {{ $app->id }} })"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-1 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        View Document
                                    </button>
                                @else
                                    <button type="button"
                                            wire:click="$dispatch('open-requirements', { id: {{ $app->id }} })"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 mr-1 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        View Document
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                                You have no applications yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @livewire('requirement-modal')
    @livewire('request-modal')
</div>
