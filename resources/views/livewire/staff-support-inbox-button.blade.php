<div 
    wire:poll.5s="refreshCount" 
    class="inline-block"
>
    <a href="{{ route('staff.support.chat') }}" 
       class="group flex items-center gap-2 px-3 py-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-blue-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
       title="Support Inbox">

        <div class="relative">
            {{-- Original icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>

            {{-- Red dot indicator (shows if there are open/pending threads) --}}
            @if($openCount > 0)
                <span class="absolute -top-0.5 -right-0.5 flex">
                    <!-- Ping effect -->
                    <span class="animate-ping absolute inline-flex h-2.5 w-2.5 rounded-full bg-red-400 opacity-75"></span>
                    <!-- Solid dot -->
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
            @endif
        </div>

        <span class="hidden sm:block text-sm font-medium">
            Support Inbox
        </span>
    </a>
</div>
