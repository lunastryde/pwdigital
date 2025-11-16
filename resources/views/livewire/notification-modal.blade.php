<div>
    @if ($open && $notification)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4"
             wire:keydown.escape.window="close">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" wire:click="close"></div>

            <!-- Panel -->
            <div class="relative w-full max-w-lg bg-white rounded-xl shadow-xl ring-1 ring-black/5 overflow-hidden transform transition-all"
                 wire:click.stop>
                <div class="p-6">
                    {{-- CLOSE BUTTON --}}
                    <button wire:click="close"
                            class="absolute top-3 right-3 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd" d="M6.225 4.811a.75.75 0 011.06 0L12 9.525l4.715-4.714a.75.75 0 111.06 1.06L13.06 10.586l4.715 4.714a.75.75 0 11-1.06 1.06L12 11.646l-4.715 4.714a.75.75 0 11-1.06-1.06l4.714-4.715-4.714-4.715a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="flex items-start gap-3">
                        @php
                            $type = $notification->type;
                            $color = match($type) {
                                'id_finalized','request_finalized' => 'text-green-600 bg-green-50',
                                'id_rejected','request_rejected' => 'text-red-600 bg-red-50',
                                default => 'text-blue-600 bg-blue-50',
                            };
                        @endphp
                        <div class="shrink-0 rounded-lg p-2 {{ $color }}">
                            @if(in_array($type, ['id_finalized','request_finalized']))
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                                    <path fill-rule="evenodd" d="M10.28 15.22a.75.75 0 01-1.06 0L6.97 13.97a.75.75 0 111.06-1.06l1.19 1.19 2.75-2.75a.75.75 0 111.06 1.06l-3.75 3.75z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM3.75 12a8.25 8.25 0 1116.5 0 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif(in_array($type, ['id_rejected','request_rejected']))
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM9.53 8.47a.75.75 0 10-1.06 1.06L10.94 12l-2.47 2.47a.75.75 0 101.06 1.06L12 13.06l2.47 2.47a.75.75 0 101.06-1.06L13.06 12l2.47-2.47a.75.75 0 10-1.06-1.06L12 10.94 9.53 8.47z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12 7.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0112 7.5zm0 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1">
                            {{-- TITLE --}}
                            <h2 class="text-xl font-semibold text-gray-900">
                                {{ $notification->title }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->created_at?->format('M d, Y • h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- MESSAGE --}}
                    <div class="mt-4">
                        <p class="text-gray-700 leading-relaxed">
                            {{ $notification->message }}
                        </p>
                    </div>

                    {{-- EXTRA DETAILS BASED ON TYPE --}}
                    @if ($notification->type === 'id_finalized')
                        <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg p-3">
                            Your ID has been finalized. You may now claim it at the PDAO office.
                        </div>

                    @elseif ($notification->type === 'id_rejected')
                        <div class="mt-3 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3">
                            Your ID application was rejected.
                        </div>

                        @if ($reference && !empty($reference->remarks))
                            <div class="mt-2 text-sm text-gray-700 bg-gray-50 border border-gray-100 rounded-lg p-3">
                                <span class="font-semibold">Reason:</span>
                                {{ $reference->remarks }}
                            </div>
                        @endif

                    @elseif ($notification->type === 'request_finalized')
                        <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg p-3">
                            Your request has been finalized. You may now claim it at the PDAO office.
                        </div>

                    @elseif ($notification->type === 'request_rejected')
                        <div class="mt-3 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3">
                            Your request was rejected.
                        </div>

                        @if ($reference && !empty($reference->remarks))
                            <div class="mt-2 text-sm text-gray-700 bg-gray-50 border border-gray-100 rounded-lg p-3">
                                <span class="font-semibold">Reason:</span>
                                {{ $reference->remarks }}
                            </div>
                        @endif
                    @endif

                    {{-- FOOTER BUTTON --}}
                    <div class="mt-6 flex justify-end gap-3">
                        <button wire:click="close" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
