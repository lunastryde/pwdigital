<div class="bg-white border border-gray-200 rounded-lg shadow-sm">
    
    {{-- Responsive Table Container --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 uppercase text-xs font-semibold text-gray-500">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">Application Type</th>
                    <th class="px-6 py-3 whitespace-nowrap">Date Applied</th>
                    <th class="px-6 py-3 whitespace-nowrap">Status</th>
                    <th class="px-6 py-3 text-right whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($applications as $app)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        {{-- Type --}}
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $app->type }}
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                try {
                                    $dt = \Illuminate\Support\Carbon::parse($app->date);
                                } catch (\Throwable $e) {
                                    $dt = null;
                                }
                            @endphp
                            {{ $dt ? $dt->format('M d, Y') : '—' }}
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $s = strtolower($app->status ?? 'pending');

                                $colorClass = match(true) {
                                    // Green (Success)
                                    in_array($s, ['approved', 'finalized', 'released']) 
                                        => 'bg-green-100 border-green-200',
                                    
                                    // Red (Danger)
                                    in_array($s, ['rejected', 'needs revision', 'cancelled']) 
                                        => 'bg-red-100 border-red-200',
                                    
                                    // Blue (Info/Processing)
                                    in_array($s, ['under final review', 'processing', 'verified']) 
                                        => 'bg-blue-100 border-blue-200',
                                    
                                    // Yellow (Default/Pending)
                                    default 
                                        => 'bg-yellow-100 border-yellow-200',
                                };
                            @endphp

                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold border text-gray-900 {{ $colorClass }}">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            @if($app->source === 'request')
                                <button type="button" 
                                    wire:click="$dispatch('open-request-details', { id: {{ $app->id }} })"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>
                            @else
                                <button type="button"
                                    wire:click="$dispatch('open-requirements', { id: {{ $app->id }} })"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-10 h-10 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm">You haven't submitted any applications yet.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @livewire('requirement-modal')
    @livewire('request-modal')
</div>