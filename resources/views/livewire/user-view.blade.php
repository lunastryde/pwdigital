<div class="w-full">
    {{-- CHECK FOR UNREAD NOTIFICATIONS --}}
    @php
        $hasUnread = \App\Models\Notification::where('account_id', auth()->id())
                                                ->where('is_read', false)
                                                ->exists();
    @endphp
    <!-- Tabs Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Left: Tabs -->
                <div class="flex space-x-6">
                    <button
                        type="button"
                        wire:click="setTab('profile')"
                        class="py-4 px-1 border-b-2 text-sm font-medium transition-colors
                            {{ $tab === 'profile' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Profile
                    </button>
                    <button
                        type="button"
                        wire:click="setTab('applications')"
                        class="py-4 px-1 border-b-2 text-sm font-medium transition-colors
                            {{ $tab === 'applications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        My Applications
                    </button>
                    <button
                        type="button"
                        wire:click="setTab('notifications')"
                        class="py-4 px-1 border-b-2 text-sm font-medium transition-colors {{ $tab === 'notifications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <span class="relative inline-flex items-center">
                            Notification
                            
                            @if ($hasUnread)
                                <span class="absolute top-0 right-0 transform -translate-y-1/2 translate-x-1/2 w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white">
                                </span>
                            @endif
                        </span>
                    </button>
                </div>

                <!-- Right: Settings Dropdown -->
                <div class="relative">

                    <details class="relative">
                        <summary class="list-none p-2 rounded-full hover:bg-gray-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Settings">
                            <!-- Settings (gear) icon: placeholder SVG. -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-6 h-6 text-gray-600">
                                <circle cx="12" cy="12" r="3.5"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2M12 19v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M3 12h2M19 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                            </svg>
                        </summary>

                        <div class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50">

                            <!-- Account Settings -->
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                                    <circle cx="12" cy="8" r="3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 4-6 8-6s8 2 8 6" />
                                </svg>
                                <span class="ml-3">Account Settings</span>
                            </a>

                            <div class="border-t border-gray-200 my-1"></div>

                            <!-- Log Out -->
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         viewBox="0 0 24 24" 
                                         fill="none" 
                                         stroke="currentColor" 
                                         stroke-width="1.5" 
                                         class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7v-2a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 12h9m0 0-3-3m3 3-3 3" />
                                    </svg>
                                    <span class="ml-3">Log Out</span>
                                </button>
                            </form>
                        </div>
                    </details>

                    <!-- Script for closing dropdown when clicking outside -->
                    <script>
                        document.addEventListener('click', function(event) {
                            document.querySelectorAll('details[open]').forEach(dropdown => {
                            if (!dropdown.contains(event.target)) {
                                dropdown.removeAttribute('open');
                            }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </nav>

    <!-- Tab Contents -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if ($tab === 'profile')
            <div class="bg-white shadow rounded-lg p-6 mb-3">
                <h2 class="text-lg font-semibold mb-2">Profile</h2>
                @auth
                    @php
                        $user = auth()->user();
                        $profile = $user->profile ?? null;
                        $fullName = trim(($profile->fname ?? '') . ' ' . ($profile->mname ?? '') . ' ' . ($profile->lname ?? ''));
                        if ($fullName === '') {
                            $fullName = $user->name ?? $user->username ?? '—';
                        }
                    @endphp

                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Avatar -->
                        <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-gray-200 overflow-hidden ring-2 ring-gray-300 flex items-center justify-center">
                            @if ($profile->profile_picture)
                                <img src="{{ asset('storage/' . $profile->profile_picture) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2a5 5 0 00-3.5 8.5A9 9 0 003 19h18a9 9 0 00-5.5-8.5A5 5 0 0012 2z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>


                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">Welcome back, {{ $fullName }}</h3>
                            <p class="text-gray-600">PWD.</p>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">PWD ID</dt>
                                            <dd class="font-medium text-gray-900">{{ $profile->pwd_number ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Full Name</dt>
                                            <dd class="font-medium text-gray-900">{{ $fullName }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Gender</dt>
                                            <dd class="font-medium text-gray-900">{{ $profile->sex ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Date of Birth</dt>
                                            <dd class="font-medium text-gray-900">{{ $profile->birthdate ?? '—' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Civil Status</dt>
                                            <dd class="font-medium text-gray-900">{{ $profile->civil_status ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Type of Disability</dt>
                                            <dd class="font-medium text-gray-900">{{ $profile->disability_type ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Email</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->email ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Address</dt>
                                            <dd class="font-medium text-gray-900">
                                                @php
                                                    $addrParts = array_filter([
                                                        $profile->house_no ?? null,
                                                        $profile->street ?? null,
                                                        $profile->barangay ?? null,
                                                        $profile->municipality ?? null,
                                                        $profile->province ?? null,
                                                    ]);
                                                @endphp
                                                {{ $addrParts ? implode(', ', $addrParts) : '—' }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-700">Welcome, Guest</p>
                @endauth
            </div>

            {{-- Released ID block: shows only when user has finalized ID --}}
            @php
                $releasedForm = $application && $application->status === 'Finalized' && !empty($application->date_issued) ? $application : null;
            @endphp

            @if($releasedForm)
                {{-- Desktop clickable box (visible on md+) --}}
                <div class="hidden md:block mt-6 mb-6">
                    <div class="bg-white rounded-2xl shadow p-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Your Released PWD ID</h3>
                            <p class="text-sm text-gray-600">Your ID has been issued on {{ \Illuminate\Support\Carbon::parse($releasedForm->date_issued)->format('M d, Y') }}.</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button id="open-id-desktop" data-appid="{{ $releasedForm->applicant_id }}" class="px-4 py-2 bg-gray-800 text-white rounded">View ID</button>
                            <a href="{{ route('admin.form_personal.print', ['id' => $releasedForm->applicant_id]) }}" target="_blank" class="px-4 py-2 border rounded text-sm">Print ID</a>
                        </div>
                    </div>
                </div>

                {{-- Mobile placeholder (visible on small screens) --}}
                <div class="block md:hidden mt-6 mb-6">
                    <div class="bg-white rounded-2xl shadow p-4 flex items-center gap-4">
                        {{-- small ID preview box with faded PWD logos --}}
                        <button id="open-id-mobile" data-appid="{{ $releasedForm->applicant_id }}" class="w-full text-left">
                            <div style="width:100%; max-width:420px;" class="mx-auto">
                                <div class="relative"
                                    style="aspect-ratio:3/2; border-radius:10px; background:linear-gradient(180deg,#f3f4f6,#fff);
                                            display:flex; align-items:center; justify-content:center; border:1px dashed #d1d5db; overflow:hidden;">
                                    
                                    {{-- Background logos --}}
                                    <img src="{{ asset('images/calapan_flag.png') }}"
                                        alt="Calapan Logo"
                                        style="position:absolute; left:10%; top:50%; transform:translateY(-50%);
                                                width:40%; opacity:0.15; pointer-events:none; z-index:0;">
                                    
                                    <img src="{{ asset('images/pdao_logo.png') }}"
                                        alt="PDAO Logo"
                                        style="position:absolute; right:10%; top:50%; transform:translateY(-50%);
                                                width:40%; opacity:0.15; pointer-events:none; z-index:0;">
                                    
                                    {{-- Foreground transparent layer so it looks faded beneath --}}
                                    <div class="relative z-10 text-gray-500 font-medium text-sm text-center">
                                        Tap to view your ID
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Desktop modal (hidden by default) --}}
                <div id="id-modal" class="fixed inset-0 z-50 hidden">
                    <div class="fixed inset-0 bg-black/50"></div>
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl overflow-auto">
                            <div class="flex items-center justify-between p-4 border-b">
                                <h3 class="text-lg font-semibold">Your PWD ID — {{ trim(($releasedForm->fname ?? '') . ' ' . ($releasedForm->lname ?? '')) }}</h3>
                                <div class="flex items-center gap-2">
                                    <button id="close-id-modal" class="px-3 py-1 rounded bg-gray-200">Close</button>
                                    <a href="{{ route('admin.form_personal.print', ['id' => $releasedForm->applicant_id]) }}" target="_blank" class="px-3 py-1 rounded bg-gray-800 text-white">Print ID</a>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- FIX: Removed the div style="width:360px" wrapper --}}
                                    <div class="border rounded p-4 flex justify-center">
                                        @include('components.id-card-side', ['form' => $releasedForm, 'side' => 'front', 'preview' => true])
                                    </div>

                                    {{-- FIX: Removed the div style="width:360px" wrapper --}}
                                    <div class="border rounded p-4 flex justify-center">
                                        @include('components.id-card-side', ['form' => $releasedForm, 'side' => 'back', 'preview' => true])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile fullscreen landscape overlay (hidden by default) --}}
                <div id="id-fullscreen" class="fixed inset-0 z-50 hidden">
                    {{-- 
                    FIX: 
                    1. Changed to flex-col and added padding/gap.
                    2. This stacks the button above the card, not on it.
                    --}}
                    <div class="fixed inset-0 flex flex-col bg-black/75 p-4 gap-3">

                        {{-- 
                        FIX: 
                        1. Moved button to be a direct child of the overlay.
                        2. Removed 'absolute' and made it a simple button at the top.
                        --}}
                        <button id="close-id-fullscreen" class="flex-shrink-0 self-start px-3 py-1 rounded bg-white/90 text-black text-sm">
                            Back
                        </button>

                        {{-- This container centers the card in the remaining space --}}
                        <div class="relative w-full flex-1 flex items-center justify-center min-h-0">
                            
                            {{-- 
                            FIX: 
                            1. Removed all 'style' attributes from this wrapper.
                            2. It now just holds the 3D card at its native size.
                            --}}
                            <div id="mobile-id-card-wrapper" class="mx-auto">
                                <div id="mobile-id-card" class="id-3d-card" style="width:100%; perspective:2000px;">
                                    <div class="id-3d-inner" style="position:relative; width:100%; transform-style:preserve-3d; transition:transform 0.7s ease;">
                                        <div class="id-3d-front" style="backface-visibility:hidden; -webkit-backface-visibility:hidden;">
                                            {{-- 
                                            FIX: Set 'preview' => false. 
                                            This renders the card at its base size (85.6mm ≈ 324px),
                                            which fits perfectly on a phone.
                                            --}}
                                            @include('components.id-card-side', ['form' => $releasedForm, 'side' => 'front', 'preview' => false])
                                        </div>

                                        <div class="id-3d-back" style="position:absolute; top:0; left:0; width:100%; transform:rotateY(180deg); backface-visibility:hidden; -webkit-backface-visibility:hidden;">
                                            {{-- 
                                            FIX: Set 'preview' => false. 
                                            --}}
                                            @include('components.id-card-side', ['form' => $releasedForm, 'side' => 'back', 'preview' => false])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- small hint --}}
                        <div class="text-center text-sm text-white flex-shrink-0">
                            Tap the card to flip
                        </div>
                    </div>
                </div>
            @endif


            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-4">Announcements</h3>
                <div class="space-y-6">
                    @forelse ($announcements as $announcement)
                        <div class="bg-white border border-gray-300 rounded-2xl shadow-md hover:shadow-lg transition duration-200 p-6">
                            <!-- Header -->
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <h4 class="text-2xl font-semibold text-gray-900 leading-snug">
                                    {{ $announcement->title }}
                                </h4>
                                <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1.5 rounded-md whitespace-nowrap">
                                    {{ $announcement->published_at->format('M d, Y, g:i A') }}
                                </span>
                            </div>

                            <!-- Content -->
                            <p class="mt-4 text-base text-gray-800 leading-relaxed">
                                {!! nl2br(e($announcement->content)) !!}
                            </p>

                            <!-- Footer -->
                            <div class="mt-5 flex justify-end items-center">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 1.104-.896 2-2 2s-2-.896-2-2 .896-2 2-2 2 .896 2 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197M5 12a7 7 0 1114 0 7 7 0 01-14 0z" />
                                    </svg>
                                    <span class="font-medium text-gray-700">
                                        Posted by: {{ $announcement->poster->name ?? 'System Admin' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400 mx-auto mb-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600 text-base">No active announcements at this time.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        @elseif ($tab === 'applications')
        {{-- Global error/success for applications (ID, requests, support) --}}
        @if (session('error'))
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold">My Applications</h2>
                    <div class="relative">
                        <details class="relative">
                            <summary class="list-none p-2 rounded-full hover:bg-gray-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Create new application">
                                <!-- Plus icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-6 h-6 text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                </svg>
                            </summary>
                            <div class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50">
                                <a href="{{ route('form.id') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">New ID Application</a>
                                <a href="{{ route('form.request') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">New Request Application</a>
                                <a href="{{ route('form.support') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">ID Support/Services</a>
                            </div>
                        </details>
                    </div>
                </div>
                @livewire('user-applications-tab')
            </div>
            
        @elseif ($tab === 'notifications')
            <div class="bg-white shadow rounded-lg p-6">
                <livewire:user-notifications/>
                <div class="relative">
                </div>
            </div>
        @endif
    </div>
    {{-- FLOATING SUPPORT CHAT BUTTON --}}
    <a href="{{ route('support.chat') }}" 
       class="fixed bottom-6 right-6 z-40 flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-blue-300 group">
        
        {{-- Chat Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
        </svg>

        {{-- Text (Hidden on very small screens if you want, but Pill shape usually fits all) --}}
        <span class="text-sm tracking-wide">Support</span>
        
        {{-- Optional: Unread badge indicator (If you have chat notifications later) --}}
        {{-- <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
        </span> --}}
    </a>
</div>

@push('scripts')
    <script>
        function initIdCardEvents() {
            // Desktop modal
            const openDesktopBtn = document.getElementById('open-id-desktop');
            const idModal = document.getElementById('id-modal');
            const closeModalBtn = document.getElementById('close-id-modal');

            if (openDesktopBtn && idModal) {
                openDesktopBtn.addEventListener('click', () => {
                    idModal.classList.remove('hidden');
                });
            }
            if (closeModalBtn && idModal) {
                closeModalBtn.addEventListener('click', () => {
                    idModal.classList.add('hidden');
                });
            }

            // Mobile fullscreen overlay + flip
            const openMobileBtn = document.getElementById('open-id-mobile');
            const idFullscreen = document.getElementById('id-fullscreen');
            const closeFullscreenBtn = document.getElementById('close-id-fullscreen');
            const id3dInner = document.querySelector('.id-3d-inner');

            if (openMobileBtn && idFullscreen) {
                openMobileBtn.addEventListener('click', () => {
                    idFullscreen.classList.remove('hidden');
                    if (id3dInner) id3dInner.style.transform = 'rotateY(0deg)';
                });
            }
            if (closeFullscreenBtn && idFullscreen) {
                closeFullscreenBtn.addEventListener('click', () => {
                    idFullscreen.classList.add('hidden');
                });
            }

            // Flip on tap/click for the mobile card
            const mobileCardWrapper = document.getElementById('mobile-id-card-wrapper');
            if (mobileCardWrapper && id3dInner) {
                mobileCardWrapper.addEventListener('click', function (e) {
                    if (e.target.closest('#close-id-fullscreen')) return;
                    const current = id3dInner.style.transform || 'rotateY(0deg)';
                    id3dInner.style.transform = current === 'rotateY(0deg)' ? 'rotateY(180deg)' : 'rotateY(0deg)';
                });
            }

            // Close overlays on ESC
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') {
                    if (idModal && !idModal.classList.contains('hidden')) idModal.classList.add('hidden');
                    if (idFullscreen && !idFullscreen.classList.contains('hidden')) idFullscreen.classList.add('hidden');
                }
            });
        }

        // Run after DOM is ready
        document.addEventListener('DOMContentLoaded', initIdCardEvents);

        // Re-run every time Livewire updates this component
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                initIdCardEvents();
            });
        });
    </script>
@endpush
