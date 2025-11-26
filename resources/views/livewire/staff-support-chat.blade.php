@php use Illuminate\Support\Str; @endphp

{{-- 
    FIXES APPLIED: 
    1. Added 'min-h-0' to grid children to prevent content from forcing height.
    2. Added 'flex-shrink-0' to Header and Input areas so they never get pushed out.
    3. Adjusted spacing to space-y-4 to reduce huge gaps.
--}}
<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 flex flex-col h-[calc(100vh-140px)] min-h-[500px] relative" wire:poll.3s="pollData">
    
    {{-- Header (Fixed Height) --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-white flex items-center justify-between flex-shrink-0 z-20">
        <h2 class="text-lg font-bold text-gray-900">Support Inbox</h2>
        <div class="flex items-center gap-2">
             <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
             <span class="text-xs text-gray-500 font-medium">Live</span>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="flex-1 grid grid-cols-1 md:grid-cols-12 min-h-0">

        {{-- LEFT PANEL: Thread List (3/12 width) --}}
        <div class="md:col-span-4 lg:col-span-3 border-r border-gray-100 flex flex-col h-full bg-white min-h-0">
            
            {{-- Search Bar Placeholder (Optional) --}}
            {{-- <div class="p-3 border-b border-gray-50 flex-shrink-0">
                <input type="text" class="w-full text-xs bg-gray-50 border-none rounded-md" placeholder="Search threads...">
            </div> --}}

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @forelse($threads as $thread)
                    @php
                        $isSelected = $selectedThreadId === $thread->id;
                        $statusColor = match($thread->status) {
                            'open' => 'bg-green-100 text-green-700 border-green-200',
                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'resolved' => 'bg-blue-100 text-blue-700 border-blue-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                        $activeClass = $isSelected ? 'bg-blue-50/50 border-l-4 border-blue-600' : 'bg-white hover:bg-gray-50 border-l-4 border-transparent';
                    @endphp
                    
                    <button
                        type="button"
                        wire:click="selectThread({{ $thread->id }})"
                        class="w-full text-left p-4 border-b border-gray-50 transition-all duration-200 group {{ $activeClass }}"
                    >
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-semibold text-gray-900 truncate pr-2">
                                {{ $thread->user?->username ?? 'User #'.$thread->user_id }}
                            </span>
                            <span class="text-[10px] font-medium px-2 py-0.5 rounded-full border {{ $statusColor }}">
                                {{ ucfirst($thread->status) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-end">
                            <span class="text-xs text-gray-500 truncate max-w-[120px]">
                                {{ $thread->user?->email ?? 'No Email' }}
                            </span>
                            <span class="text-[10px] text-gray-400 group-hover:text-gray-500">
                                @if($thread->last_message_at)
                                    {{ \Carbon\Carbon::parse($thread->last_message_at)->format('M d, H:i') }}
                                @else
                                    New
                                @endif
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                        <p class="text-xs">No active threads</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT PANEL: Chat Area (9/12 width) --}}
        <div class="md:col-span-8 lg:col-span-9 flex flex-col h-full bg-gray-50/30 min-h-0 relative">
            
            @if(!$selectedThreadId)
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-300">
                            <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium">Select a conversation</p>
                </div>
            @else
                
                {{-- Messages Container (Flex-1 allows it to grow/shrink) --}}
                <div id="staff-chat-box" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 custom-scrollbar min-h-0">
                    @forelse($chatMessages as $message)
                        @php
                            $isStaff = $message->sender_is_staff;
                        @endphp

                        <div class="flex w-full {{ $isStaff ? 'justify-end' : 'justify-start' }}">
                            <div class="flex flex-col {{ $isStaff ? 'items-end' : 'items-start' }} max-w-[85%] sm:max-w-[70%]">
                                
                                {{-- Sender Label --}}
                                <span class="text-[10px] text-gray-400 mb-1 px-1">
                                    {{ $isStaff ? 'You' : 'User' }}
                                </span>

                                {{-- Bubble --}}
                                <div class="relative px-4 py-3 shadow-sm text-sm leading-relaxed
                                    {{ $isStaff 
                                        ? 'bg-blue-600 text-white rounded-2xl rounded-br-none' 
                                        : 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-bl-none' 
                                    }}">
                                    
                                    @if($message->body)
                                        <p class="whitespace-pre-line">{{ $message->body }}</p>
                                    @endif

                                    @if($message->attachment_path)
                                        <div class="mt-3">
                                            @php
                                                $isImage = $message->attachment_mime && Str::startsWith($message->attachment_mime, 'image/');
                                            @endphp

                                            @if($isImage)
                                                <a href="{{ asset('storage/'.$message->attachment_path) }}" target="_blank" class="block overflow-hidden rounded-lg border {{ $isStaff ? 'border-blue-400' : 'border-gray-200' }}">
                                                    <img src="{{ asset('storage/'.$message->attachment_path) }}" alt="Attachment" class="max-h-40 w-auto object-cover hover:opacity-90">
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/'.$message->attachment_path) }}" target="_blank" class="flex items-center gap-2 p-2 rounded bg-opacity-20 {{ $isStaff ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="truncate max-w-[150px] underline decoration-1 underline-offset-2">
                                                        {{ $message->attachment_original_name ?? 'Download' }}
                                                    </span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Timestamp --}}
                                <span class="mt-1 text-[10px] text-gray-400 px-1">
                                    {{ $message->created_at ? $message->created_at->format('M d, g:i A') : '' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <p class="text-sm">Start of conversation.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Input Area (Fixed Height, Shrink-0) --}}
                <div class="bg-white border-t border-gray-100 p-4 flex-shrink-0 z-10">
                    
                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="mb-2 px-3 py-1.5 bg-red-50 border border-red-100 rounded text-xs text-red-600">
                            {{ $errors->first('body') ?: $errors->first('attachment') }}
                        </div>
                    @endif

                    @php
                        $currentThread = $threads->firstWhere('id', $selectedThreadId);
                        $canDelete = $currentThread && $currentThread->status === 'resolved';
                    @endphp

                    <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3">
                        <div class="flex items-end gap-2 bg-gray-50 p-2 rounded-xl border border-gray-200 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all">
                            
                            {{-- Attachment Icon --}}
                            <div class="relative flex-shrink-0">
                                <input type="file" wire:model="attachment" id="staff-file" class="hidden">
                                <label for="staff-file" class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 cursor-pointer block" title="Attach file">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                        <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                                    </svg>
                                </label>
                            </div>

                            <textarea
                                wire:model.defer="body"
                                wire:keydown.enter.prevent="sendMessage"
                                rows="1"
                                class="w-full bg-transparent border-0 focus:ring-0 p-2 text-sm text-gray-800 placeholder-gray-400 resize-none max-h-32"
                                placeholder="Reply to user..."
                                style="min-height: 44px;"
                            ></textarea>

                            <button type="submit" class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm mb-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                                </svg>
                            </button>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex items-center justify-between">
                            
                            {{-- Attachment Preview --}}
                            <div class="min-h-[20px]">
                                @if ($attachment)
                                    <div class="flex items-center gap-2 text-xs text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">
                                        <span class="truncate max-w-[150px] font-medium">{{ $attachment->getClientOriginalName() }}</span>
                                        <button type="button" wire:click="$set('attachment', null)" class="hover:text-blue-800 ml-1">x</button>
                                    </div>
                                @endif
                            </div>

                            {{-- Management Buttons --}}
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    wire:click="markResolved"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 text-xs font-medium rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Mark Resolved
                                </button>
                                
                                <button
                                    type="button"
                                    wire:click="confirmDelete"
                                    @disabled(!$canDelete)
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 border text-xs font-medium rounded-lg transition-colors
                                        {{ $canDelete 
                                            ? 'border-red-100 text-red-600 hover:bg-red-50 hover:border-red-200' 
                                            : 'border-gray-100 text-gray-300 cursor-not-allowed' }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Confirmation Modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 transform transition-all scale-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-100 p-2 rounded-full text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Delete Thread?</h3>
                </div>
                
                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    This action is permanent and cannot be undone. All messages within this thread will be lost forever.
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click="cancelDelete"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="deleteThread"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-md transition-colors"
                    >
                        Yes, Delete It
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Styling for Slim Scrollbar --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #cbd5e1;
        }
    </style>

    <script>
        (function () {
            function scrollStaffChatBottom() {
                const box = document.getElementById('staff-chat-box');
                if (!box) return;
                box.scrollTop = box.scrollHeight;
            }

            // Scroll on full page load / refresh
            window.addEventListener('load', () => {
                setTimeout(scrollStaffChatBottom, 80);
            });

            // Scroll when Livewire triggers event
            document.addEventListener('livewire:init', () => {
                if (window.__staffChatScrollBound) return;
                window.__staffChatScrollBound = true;

                Livewire.on('scroll-staff-chat', () => {
                    requestAnimationFrame(scrollStaffChatBottom);
                });
            });
        })();
    </script>
</div>