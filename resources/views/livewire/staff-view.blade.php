<div>
    <!-- Container -->
    <div id="staff-view-root" class="relative">
        @php
            $section = $section ?? 'dashboard';
            $sidebarOpen = $sidebarOpen ?? true; // default open; user can toggle via burger
            $labels = [
                'dashboard' => 'Dashboard',
                'applications' => 'Applications',
                'announcements' => 'Announcements',
                'encode' => 'Encode Form',
                'admin' => 'Admin Panel',
                'released' => 'Released ID',
                'survey' => 'Survey',
                'reports' => 'Report & Analytics',
            ];
        @endphp
        <!-- Fixed Header -->
        <header class="fixed top-0 z-30 left-0 right-0 bg-white/90 backdrop-blur border-b border-gray-200 {{ $sidebarOpen ? 'lg:ml-64 lg:w-[calc(100%-16rem)]' : '' }}">
            <div class="h-14 flex items-center px-3">
                <button wire:click="$toggle('sidebarOpen')" class="p-2 rounded-md hover:bg-gray-100 text-gray-700">
                    <!-- Burger Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M3.75 5.25a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 6a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 6a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                    </svg>
                </button>
                <h2 class="ml-3 text-gray-800 font-semibold">{{ $labels[$section] ?? 'Dashboard' }}</h2>

                <div class="ml-auto flex items-center gap-3">

                    {{-- SUPPORT INBOX BUTTON --}}
                    @if (auth()->check() && auth()->user()->identifier != 1)
                        <a href="{{ route('staff.support.chat') }}" 
                        class="group flex items-center gap-2 px-3 py-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-blue-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        title="Support Inbox">
                            
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>

                            <span class="hidden sm:block text-sm font-medium">Support Inbox</span>
                        </a>
                    @endif

                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    {{-- EXISTING PROFILE DROPDOWN --}}
                    <div class="relative">
                        <details class="relative">
                            <summary class="list-none p-1 rounded-full hover:bg-gray-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Profile menu">
                                <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-200 ring-2 ring-gray-300 flex items-center justify-center">
                                    @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                        <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M12 2a5 5 0 00-3.5 8.5A9 9 0 003 19h18a9 9 0 00-5.5-8.5A5 5 0 0012 2z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                            </summary>

                            <div class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                                        <circle cx="12" cy="8" r="3" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 4-6 8-6s8 2 8 6" />
                                    </svg>
                                    <span class="ml-3">Profile Settings</span>
                                </a>

                                <div class="border-t border-gray-200 my-1"></div>

                                <form action="/staff/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7v-2a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 12h9m0 0-3-3m3 3-3 3" />
                                        </svg>
                                        <span class="ml-3">Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </details>
                        </div>

                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="fixed top-0 bottom-0 left-0 z-40 w-64 bg-gray-900 text-gray-100 border-r border-white/10 overflow-y-auto {{ $sidebarOpen ? 'block' : 'hidden' }}">
            <div class="h-16 hidden lg:flex items-center px-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/pdao_logo.png') }}" alt="Logo" class="w-10 h-10 rounded-full border-2 border-white/30">
                    <span class="font-semibold tracking-wide">PDAO</span>
                </div>
            </div>
            <nav class="px-2 py-4 space-y-1 text-sm">
                <a wire:click="$set('section','dashboard')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='dashboard' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Dashboard</span>
                </a>
                <a wire:click="$set('section','applications')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='applications' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Applications</span>
                </a>
                <a wire:click="$set('section','announcements')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='announcements' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Announcements</span>
                </a>
                <a wire:click="$set('section','encode')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='encode' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Encode Form</span>
                </a>

                @if (auth()->user()->identifier == 1)
                    <a wire:click="$set('section','admin')" 
                    class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='admin' ? 'bg-white/10' : 'hover:bg-white/10' }}" 
                    href="#" 
                    onclick="return false;">
                        <span>Admin Panel</span>
                    </a>
                @endif

                <a wire:click="$set('section','released')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='released' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Released ID</span>
                </a>
                <a wire:click="$set('section','survey')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='survey' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Survey</span>
                </a>
                <a wire:click="$set('section','reports')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='reports' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Report & Analytics</span>
                </a>
            </nav>
        </aside>

        <!-- Backdrop (mobile) -->
        <div class="fixed inset-0 z-30 bg-black/40 {{ $sidebarOpen ? 'block' : 'hidden' }} lg:hidden" wire:click="$set('sidebarOpen', false)"></div>

        <!-- Content (adds padding-top for fixed header) -->
        <div class="pt-14 transition-all duration-200 {{ $sidebarOpen ? 'lg:ml-64' : '' }}">
            <div class="px-3 py-6">
                @if ($section === 'dashboard')
                    {{-- Dashboard Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <livewire:staff-dashboard />
                    </div>

                @elseif ($section === 'applications')
                    {{-- Application Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        {{-- 1. Header & Navigation Area --}}
                        <div class="px-6 py-5 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Application List</h3>
                                    <p class="text-sm text-gray-500 mt-1">Manage incoming PWD applications and requests.</p>
                                </div>

                                {{-- Tabs: Segmented "Pill" Style --}}
                                <div class="flex flex-wrap gap-2">
                                    @php 
                                        $tabs = [
                                            'id' => 'ID Apps',
                                            'renewal' => 'Renewal',
                                            'loss' => 'Loss ID',
                                            'booklet' => 'Booklet',
                                            'device' => 'Device',
                                            'financial' => 'Financial'
                                        ];
                                    @endphp

                                    @foreach($tabs as $key => $label)
                                        <button 
                                            type="button" 
                                            wire:click="$set('appTab','{{ $key }}')"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-md border transition-all
                                            {{ $appTab === $key 
                                                ? 'bg-gray-900 text-white border-gray-900' 
                                                : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400 hover:bg-gray-50' 
                                            }}"
                                        >
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- 2. Data Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 border-b border-gray-200 uppercase text-xs font-semibold text-gray-500">
                                    <tr>
                                        <th class="px-6 py-3">Applicant Name</th>
                                        <th class="px-6 py-3">Type & Method</th>
                                        <th class="px-6 py-3">Date</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3 text-center">Requirements</th>
                                        <th class="px-6 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($applications as $app)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            
                                            {{-- Name --}}
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                @if ($isRequestType && $app->applicant)
                                                    {{ trim(($app->applicant->fname ?? '') . ' ' . ($app->applicant->lname ?? '')) }}
                                                @else
                                                    {{ trim(($app->fname ?? '') . ' ' . ($app->lname ?? '')) }}
                                                @endif
                                            </td>

                                            {{-- Type/Method --}}
                                            <td class="px-6 py-4">
                                                @if ($isRequestType)
                                                    <div class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-medium border border-gray-200">
                                                        {{ ucfirst($app->request_type ?? 'request') }} Request
                                                    </div>
                                                @else
                                                    @php 
                                                        $isEncoded = $app->applicant_type === 'Encoded Application';
                                                    @endphp
                                                    <div class="inline-flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full {{ $isEncoded ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                                                        <span>{{ $isEncoded ? 'Encoded by Staff' : 'Online App' }}</span>
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Date --}}
                                            <td class="px-6 py-4">
                                                @php
                                                    $dateString = $app->submitted_at;
                                                    try { $dt = \Illuminate\Support\Carbon::parse($dateString); } catch (\Throwable $e) { $dt = null; }
                                                @endphp
                                                {{ $dt ? $dt->format('M d, Y') : '—' }}
                                            </td>

                                            {{-- Status --}}
                                            <td class="px-6 py-4">
                                                @php
                                                    $status = $app->status ?? 'Pending';
                                                    $statusClass = match($status) {
                                                        'Pending' => 'text-yellow-600 bg-yellow-50 border-yellow-100',
                                                        'Under Final Review' => 'text-blue-600 bg-blue-50 border-blue-100',
                                                        'Finalized' => 'text-green-600 bg-green-50 border-green-100',
                                                        'Rejected', 'Needs Revision' => 'text-red-600 bg-red-50 border-red-100',
                                                        default => 'text-gray-600 bg-gray-50 border-gray-100',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-1 rounded text-xs font-semibold border {{ $statusClass }}">
                                                    {{ $status }}
                                                </span>
                                            </td>

                                            {{-- Details Button --}}
                                            <td class="px-6 py-4 text-center">
                                                @if ($isRequestType)
                                                    <button type="button" wire:click="openRequestDetails({{ $app->request_id }})" 
                                                        class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                        View Details
                                                    </button>
                                                @else
                                                    <button type="button" wire:click="openRequirements({{ $app->applicant_id }})" 
                                                        class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                        View Requirements
                                                    </button>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if ($isRequestType)
                                                        {{-- REQUEST BUTTONS --}}
                                                        @if (auth()->user()->identifier == 1) {{-- Admin --}}
                                                            @if ($app->status === 'Finalized')
                                                                <button type="button" wire:click="$dispatch('open-id-preview', {{ $app->applicant_id }})" 
                                                                    class="px-3 py-1.5 text-xs font-medium rounded border border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-100">
                                                                    Preview ID
                                                                </button>
                                                            @else
                                                                <button type="button" wire:click="openConfirmFinalizeRequest({{ $app->request_id }})" 
                                                                    class="px-3 py-1.5 text-xs font-medium rounded border border-green-200 bg-green-50 text-green-700 hover:bg-green-100">
                                                                    Finalize
                                                                </button>
                                                            @endif
                                                        @elseif (auth()->user()->identifier == 2) {{-- Staff --}}
                                                            <button type="button" wire:click="openConfirmAcceptRequest({{ $app->request_id }})" 
                                                                class="px-3 py-1.5 text-xs font-medium rounded border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100">
                                                                Accept
                                                            </button>
                                                        @endif
                                                        
                                                        <button type="button" wire:click="$dispatch('open-rejection-modal', { id: {{ $app->request_id }}, type: 'request' })" 
                                                            class="px-3 py-1.5 text-xs font-medium rounded border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                                                            Reject
                                                        </button>

                                                    @else
                                                        {{-- PERSONAL APP BUTTONS --}}
                                                        @if (auth()->user()->identifier == 1) {{-- Admin --}}
                                                            @if ($app->status === 'Finalized')
                                                                <button type="button" wire:click="$dispatch('open-id-preview', {{ $app->applicant_id }})" 
                                                                    class="px-3 py-1.5 text-xs font-medium rounded border border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-100">
                                                                    Preview ID
                                                                </button>
                                                            @else
                                                                <button type="button" wire:click="finalizeApplication({{ $app->applicant_id }})" 
                                                                    class="px-3 py-1.5 text-xs font-medium rounded border border-green-200 bg-green-50 text-green-700 hover:bg-green-100">
                                                                    Finalize
                                                                </button>
                                                            @endif
                                                        @elseif (auth()->user()->identifier == 2) {{-- Staff --}}
                                                            <button type="button" wire:click="openConfirmAcceptPersonal({{ $app->applicant_id }})" 
                                                                class="px-3 py-1.5 text-xs font-medium rounded border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100">
                                                                Accept
                                                            </button>
                                                        @endif

                                                        <button type="button" wire:click="$dispatch('open-rejection-modal', { id: {{ $app->applicant_id }}, type: 'application' })" 
                                                            class="px-3 py-1.5 text-xs font-medium rounded border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                                                            Reject
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-10 h-10 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span class="text-sm">No applications found in this category.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                @elseif ($section === 'announcements')
                    {{-- Announcements Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Announcements</h3>
                        <livewire:announcement-manager />
                    </div>

                @elseif ($section === 'encode')
                    {{-- Encode Form Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        @if (session('status'))
                            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                                {{ session('status') }}
                            </div>
                        @endif

                        <livewire:staff-encode-form />
                    </div>

                @elseif ($section === 'admin')
                    {{-- Admin Panel Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <livewire:admin-accounts />
                    </div>
                
                @elseif ($section === 'released')
                    {{-- Released Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <livewire:id-released />
                    </div>

                @elseif ($section === 'survey')
                    {{-- Survey Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Survey</h3>
                        <p class="mt-2 text-gray-700">Survey placeholder.</p>
                    </div>

                @elseif ($section === 'reports')
                    {{-- Report Analytics Tab --}}
                    <div class="w-full max-w-none bg-white rounded-2xl shadow p-6">
                        @livewire('reports-charts')
                    </div>

                @else
                    {{-- Default Dashboard--}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $labels[$section] ?? 'Dashboard' }}</h3>
                        <p class="mt-2 text-gray-700">This is {{ $labels[$section] ?? 'Dashboard' }}</p>
                    </div>
                @endif
            </div>
        </div>
        @livewire('request-modal')
        @livewire('requirement-modal')
        @livewire('rejection-modal')
        @livewire('id-preview')
    </div>

    {{-- Confirmation Modal for Accepting Personal Application --}}
    @if($showConfirmAcceptPersonal)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-3">Confirm Accept Application</h2>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to accept this ID application?</p>

                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showConfirmAcceptPersonal', false)"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button wire:click="confirmAcceptPersonal"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Confirmation Modal for Accepting a Request --}}
    @if($showConfirmAcceptRequest)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-3">Confirm Accept Request</h2>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to accept this request?</p>

                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showConfirmAcceptRequest', false)"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button wire:click="confirmAcceptRequest"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Confirmation Modal for Finalizing a Request --}}
    @if($showConfirmFinalizeRequest)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-3">Finalize Request</h2>
                <p class="text-sm text-gray-700 mb-4">
                    Finalizing this request will update the applicant’s ID status.  
                    Are you sure you want to proceed?
                </p>

                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showConfirmFinalizeRequest', false)"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button wire:click="confirmFinalizeRequest"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Yes, Finalize
                    </button>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            let staffRegisterWin = null;

            function openStaffRegister() {
                const url = "{{ route('register') }}?from=staff";

                // If the window is already open, just focus it
                if (staffRegisterWin && !staffRegisterWin.closed) {
                    staffRegisterWin.focus();
                    return;
                }

                // Open a child window/tab that JS is allowed to close
                staffRegisterWin = window.open(
                    url,
                    "staffRegisterWindow",
                    "width=900,height=700"
                );
            }

            // Listen for the “account verified” message from the OTP window
            window.addEventListener('message', function (event) {
                if (event.data === 'staff-encode:account-verified') {
                    // trigger Livewire event so StaffEncodeForm can show the toast/banner
                    if (window.Livewire && Livewire.dispatch) {
                        Livewire.dispatch('encode-account-verified');
                    }
                }
            });
        </script>
    @endpush

</div>
