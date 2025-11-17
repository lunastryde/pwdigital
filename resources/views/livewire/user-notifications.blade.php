<div class="space-y-6">
    {{-- HEADER --}}
    <h2 class="text-xl font-bold text-gray-800 border-b pb-3">
        Notifications
    </h2>

    {{-- EMPTY STATE --}}
    @if ($notifications->isEmpty())
        <div class="p-6 bg-white border border-gray-200 text-gray-500 rounded-xl text-center shadow-sm">
            No new notifications. You're all caught up!
        </div>
    @endif

    {{-- NOTIFICATION LIST --}}
    <div class="divide-y divide-gray-200 border-t border-b border-gray-200">
        @foreach ($notifications as $note)
            <div wire:click="openNotification({{ $note->id }})"
                 class="px-4 py-3 sm:px-6 flex items-start space-x-3 cursor-pointer transition 
                        @if (!$note->is_read) 
                            bg-blue-50/50 hover:bg-blue-100/70 
                        @else 
                            bg-white hover:bg-gray-50
                        @endif">

                {{-- UNREAD INDICATOR ICON --}}
                <div class="pt-1.5 flex-shrink-0">
                    @if (!$note->is_read)
                        <svg class="w-2 h-2 text-blue-600" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="4" />
                        </svg>
                    @else
                        <span class="w-2 h-2"></span>
                    @endif
                </div>

                {{-- TEXT CONTENT --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm 
                                @if (!$note->is_read) 
                                    font-semibold text-gray-900 
                                @else 
                                    font-medium text-gray-700 
                                @endif">
                        {{ $note->title }}
                    </p>

                    <p class="text-sm text-gray-600 truncate">
                        {{ $note->message }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $note->created_at->diffForHumans() }} 
                        <span class="text-gray-300">|</span> 
                        {{ $note->created_at->format('M d, h:i A') }}
                    </p>
                </div>

            </div>
        @endforeach
    </div>

    {{-- MODAL (Keep this where it is) --}}
    <livewire:notification-modal />

</div>