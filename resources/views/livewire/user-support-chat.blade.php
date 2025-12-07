<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 relative flex flex-col h-[calc(100vh-140px)] min-h-[500px]">
   
    @if($showDisclaimer)
        <div class="absolute inset-0 z-30 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 p-6 text-sm">
                <h2 class="text-base font-semibold text-gray-900 mb-3">
                    Chat Data Notice
                </h2>

                <p class="text-xs text-gray-700 mb-2">
                    By using this chat feature, you understand and agree that:
                </p>

                <p class="text-xs text-gray-600 mb-2">
                    Your messages and other information you submit through the chat will be collected and stored in this system’s database for system operation, improvement, and academic research purposes related to this thesis.
                </p>

                <p class="text-xs text-gray-600 mb-2">
                    Chat records may be deleted by users or system administrators, and deleted messages may no longer be visible in the user interface. However, system backups or logs may retain copies for a limited period as part of normal system maintenance, unless and until they are permanently deleted.
                </p>

                <p class="text-xs text-gray-600 mb-2">
                    Please do not share sensitive personal information (such as government ID numbers, passwords, financial information, or highly confidential data) through this chat. Any personal information you choose to provide will be processed in accordance with applicable data protection laws, including the Philippine Data Privacy Act of 2012, and only for the purposes described above.
                </p>

                <p class="text-xs text-gray-600 mb-4">
                    This system is for research and educational use only and is provided “as is,” without any guarantee of continuous availability, accuracy of responses, or fitness for any particular professional or commercial use.
                </p>

                <div class="flex justify-end">
                    <button
                        type="button"
                        wire:click="acknowledgeDisclaimer"
                        class="px-3 py-1.5 text-xs font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700"
                    >
                        Yes, I understand
                    </button>
                </div>
            </div>
        </div>
    @endif
  
    {{-- 1. Header Section (Fixed Height) --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-white flex items-center justify-between flex-shrink-0 z-10">
        <div class="flex items-center gap-3">
            <div class="bg-blue-50 p-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                    <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.678 3.348-3.97z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 leading-tight">Support Chat</h2>
                <p class="text-xs text-gray-500">We usually reply within a few minutes</p>
            </div>
        </div>

        <a href="{{ route('home') }}" class="group flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
            <span class="hidden sm:inline">Exit</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>

    {{-- 2. Resolved Notice --}}
    @if($isResolved)
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex items-start gap-3 flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 mt-0.5">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm text-gray-600">
                This conversation is marked as <span class="font-semibold text-gray-900">resolved</span>. It is read-only.
            </p>
        </div>
    @endif

    {{-- 3. Chat Area (Flexible Height) --}}
    <div 
        id="user-chat-box"
        class="bg-gray-50/50 p-4 sm:p-6 flex-1 overflow-y-auto scroll-smooth space-y-6 custom-scrollbar min-h-0"
        wire:poll.5s="refreshMessages"
    >
        @forelse($chatMessages as $message)
            @php
                $isMe = $message->sender_id === auth()->id();
            @endphp

            <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[85%] sm:max-w-[75%]">
                    
                    {{-- Message Bubble --}}
                    <div class="relative px-4 py-3 shadow-sm text-sm leading-relaxed
                        {{ $isMe 
                            ? 'bg-blue-600 text-white rounded-2xl rounded-br-none' 
                            : 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-bl-none' 
                        }}">
                        
                        {{-- Text Body --}}
                        @if($message->body)
                            <p class="whitespace-pre-line">{{ $message->body }}</p>
                        @endif

                        {{-- Attachment --}}
                        @if($message->attachment_path)
                            <div class="mt-3">
                                @php
                                    $isImage = $message->attachment_mime && \Illuminate\Support\Str::startsWith($message->attachment_mime, 'image/');
                                @endphp

                                @if($isImage)
                                    <a href="{{ asset('storage/'.$message->attachment_path) }}" target="_blank" class="block overflow-hidden rounded-lg border {{ $isMe ? 'border-blue-400' : 'border-gray-200' }}">
                                        <img 
                                            src="{{ asset('storage/'.$message->attachment_path) }}" 
                                            alt="Attachment" 
                                            class="max-h-48 w-auto object-cover hover:opacity-90 transition-opacity"
                                        >
                                    </a>
                                @else
                                    <a href="{{ asset('storage/'.$message->attachment_path) }}" target="_blank" class="flex items-center gap-2 p-2 rounded bg-opacity-20 {{ $isMe ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="truncate max-w-[150px] underline decoration-1 underline-offset-2">
                                            {{ $message->attachment_original_name ?? 'Download File' }}
                                        </span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Timestamp --}}
                    <span class="mt-1 text-[11px] text-gray-400 px-1">
                        {{ $message->created_at ? $message->created_at->format('M d, g:i A') : '' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-3">
                <div class="bg-gray-100 p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                </div>
                <p class="text-sm">No messages yet. Start the conversation!</p>
            </div>
        @endforelse
    </div>

    {{-- 4. Input Area (Fixed Height) --}}
    @unless($isResolved)
        <div class="bg-white p-4 border-t border-gray-100 flex-shrink-0 z-10">
            
            {{-- Error Display --}}
            @if($errors->any())
                <div class="mb-3 px-3 py-2 bg-red-50 border border-red-100 rounded-lg text-xs text-red-600">
                    {{ $errors->first('body') ?: $errors->first('attachment') }}
                </div>
            @endif

            <form wire:submit.prevent="sendMessage" class="relative">
                <div class="flex items-end gap-2 bg-gray-50 p-2 rounded-xl border border-gray-200 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all">
                    
                    {{-- Attachment Button --}}
                    <div class="relative flex-shrink-0">
                        <input type="file" wire:model="attachment" id="file-upload" class="hidden">
                        <label for="file-upload" class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 cursor-pointer transition-colors block" title="Attach file">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                        </label>
                    </div>

                    {{-- Text Area --}}
                    <textarea 
                        wire:model.defer="body"
                        wire:keydown.enter.prevent="sendMessage"
                        rows="1" 
                        class="w-full bg-transparent border-0 focus:ring-0 p-2 text-sm text-gray-800 placeholder-gray-400 resize-none max-h-32"
                        placeholder="Type your message..."
                        style="min-height: 44px;"
                    ></textarea>

                    {{-- Send Button --}}
                    <button type="submit" class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm mb-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                        </svg>
                    </button>
                </div>

                {{-- Attachment Preview --}}
                @if ($attachment)
                    <div class="mt-2 flex items-center gap-2 text-xs text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium truncate max-w-[200px]">{{ $attachment->getClientOriginalName() }}</span>
                        <button type="button" wire:click="$set('attachment', null)" class="hover:text-blue-800 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    @endunless

    {{-- Styling for Slim Scrollbar --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
    </style>

    {{-- JS for Auto-Scroll --}}
    <script>
        (function () {
            function scrollUserChatBottom() {
                const box = document.getElementById('user-chat-box');
                if (!box) return;
                box.scrollTop = box.scrollHeight;
            }

            // Scroll on full page load / refresh
            window.addEventListener('load', () => {
                // Slight delay to let layout settle
                setTimeout(scrollUserChatBottom, 80);
            });

            // Scroll when Livewire tells us (new messages / thread changes)
            document.addEventListener('livewire:init', () => {
                if (window.__userChatScrollBound) return;
                window.__userChatScrollBound = true;

                Livewire.on('scroll-user-chat', () => {
                    // After DOM patch
                    requestAnimationFrame(scrollUserChatBottom);
                });
            });
        })();
    </script>
</div>