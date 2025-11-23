<div>
    <!-- Container -->
    <div id="staff-view-root" class="relative">
        @php
            $section = $section ?? 'dashboard';
            $sidebarOpen = $sidebarOpen ?? true; // default open; user can toggle via burger
            $labels = [
                'dashboard' => 'Dashboard',
                'applications' => 'Applications',
                'chat' => 'Chat',
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

                <div class="ml-auto relative">
                    <details class="relative">
                        <summary class="list-none p-1 rounded-full hover:bg-gray-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Profile menu">
                            <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-200 ring-2 ring-gray-300 flex items-center justify-center">
                                @if (Auth::user()->profile && Auth::user()->profile->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2a5 5 0 00-3.5 8.5A9 9 0 003 19h18a9 9 0 00-5.5-8.5A5 5 0 0012 2z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                        </summary>

                        <div class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50">
                            <!-- Profile Settings -->
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                                    <circle cx="12" cy="8" r="3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 4-6 8-6s8 2 8 6" />
                                </svg>
                                <span class="ml-3">Profile Settings</span>
                            </a>

                            <div class="border-t border-gray-200 my-1"></div>

                            <!-- Log Out -->
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

                    <!-- close dropdown when clicking outside -->
                    <script>
                        document.addEventListener('click', function(event) {
                            document.querySelectorAll('details[open]').forEach(function(dropdown) {
                                if (!dropdown.contains(event.target)) {
                                    dropdown.removeAttribute('open');
                                }
                            });
                        });
                    </script>
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
                <a wire:click="$set('section','chat')" class="flex items-center gap-3 px-3 py-2 rounded-md transition {{ $section==='chat' ? 'bg-white/10' : 'hover:bg-white/10' }}" href="#" onclick="return false;">
                    <span>Chat</span>
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
                        <h3 class="text-xl font-semibold text-gray-800">Dashboard</h3>
                        @livewire('reports-summary')
                    </div>

                @elseif ($section === 'applications')
                    {{-- Application Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Application List</h3>

                        <!-- Sub Tabs -->
                        <div class="mt-4 flex justify-center">
                            <div class="inline-flex rounded-full bg-gray-100 p-1 shadow-sm">
                                @php $tabClasses = 'px-4 py-1.5 text-sm rounded-full transition'; @endphp
                                <button type="button" wire:click="$set('appTab','id')"
                                    class="{{ $tabClasses }} {{ $appTab === 'id' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-800' }}">
                                    ID Applications
                                </button>
                                <button type="button" wire:click="$set('appTab','renewal')"
                                    class="{{ $tabClasses }} {{ $appTab === 'renewal' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-800' }}">
                                    ID Renewal
                                </button>
                                <button type="button" wire:click="$set('appTab','loss')"
                                    class="{{ $tabClasses }} {{ $appTab === 'loss' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-800' }}">
                                    Loss ID
                                </button>
                                <button type="button" wire:click="$set('appTab','booklet')"
                                    class="{{ $tabClasses }} {{ $appTab === 'booklet' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-800' }}">
                                    Booklet Request
                                </button>
                                <button type="button" wire:click="$set('appTab','device')"
                                    class="{{ $tabClasses }} {{ $appTab === 'device' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-800' }}">
                                    Request Device
                                </button>
                                <button type="button" wire:click="$set('appTab','financial')"
                                    class="{{ $tabClasses }} {{ $appTab === 'financial' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-800' }}">
                                    Financial Request
                                </button>
                            </div>
                        </div>

                        <!-- Table-like List -->
                        <div class="mt-6 overflow-x-auto">
                            <div class="min-w-[720px] rounded-lg ring-1 ring-gray-200">
                                <table class="w-full text-sm">
                                    <thead class="text-left">
                                        <tr class="bg-blue-400 text-white">
                                            <th class="w-12 rounded-l-lg px-3 py-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-90">
                                                    <path d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 .8 1.6l-6.4 8.53V19a1 1 0 0 1-1.45.9l-3-1.5A1 1 0 0 1 9 17.5v-4.37L3.2 5.6A1 1 0 0 1 3 5V4Z" />
                                                </svg>
                                            </th>
                                            <th class="px-4 py-2">Applicant Name</th>
                                            <th class="px-4 py-2">Method</th>
                                            <th class="px-4 py-2">Application Date</th>
                                            <th class="px-4 py-2">Status</th>
                                            <th class="px-4 py-2">Details</th>
                                            <th class="px-4 py-2 rounded-r-lg">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($applications as $app)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 py-3 align-middle">
                                                    <input type="checkbox" class="rounded border-gray-300">
                                                </td>
                                                <td class="px-4 py-3 align-middle text-gray-800">
                                                    @if ($isRequestType && $app->applicant)
                                                        {{-- For FormRequest, get name from the related applicant model --}}
                                                        {{ trim(($app->applicant->fname ?? '') . ' ' . ($app->applicant->mname ?? '') . ' ' . ($app->applicant->lname ?? '')) }}
                                                    @else
                                                        {{-- For FormPersonal, get name directly from the model --}}
                                                        {{ trim(($app->fname ?? '') . ' ' . ($app->mname ?? '') . ' ' . ($app->lname ?? '')) }}
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 align-middle text-gray-600">—</td>
                                                <td class="px-4 py-3 align-middle text-gray-600">
                                                    @php
                                                        // This now correctly uses 'submitted_at' for all types.
                                                        $dateString = $app->submitted_at;
                                                        try { $dt = \Illuminate\Support\Carbon::parse($dateString); } catch (\Throwable $e) { $dt = null; }
                                                    @endphp
                                                    {{ $dt ? $dt->format('m-d-y') : '—' }}
                                                </td>
                                                <td class="px-4 py-3 align-middle">
                                                    {{-- DYNAMIC STATUS BADGE --}}
                                                    @php
                                                        $status = $app->status ?? 'Pending';
                                                        $statusClass = 'inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full';
                                                        if ($status === 'Pending') {
                                                            $statusClass .= ' bg-yellow-100 text-yellow-800';
                                                        } elseif ($status === 'Under Final Review') {
                                                            $statusClass .= ' bg-blue-100 text-blue-800';
                                                        } elseif ($status === 'Finalized') {
                                                            $statusClass .= ' bg-green-100 text-green-800';
                                                        } elseif ($status === 'Rejected' || $status === 'Needs Revision') {
                                                            $statusClass .= ' bg-red-100 text-red-800';
                                                        }
                                                    @endphp
                                                    <span class="{{ $statusClass }}">{{ $status }}</span>
                                                </td>
                                                <td class="px-4 py-3 align-middle">
                                                    @if ($isRequestType)
                                                        <button type="button" class="text-indigo-600 hover:underline"
                                                            wire:click="openRequestDetails({{ $app->request_id }})">
                                                            View Details
                                                        </button>
                                                    @else
                                                        <button type="button" class="text-indigo-600 hover:underline"
                                                            wire:click="openRequirements({{ $app->applicant_id }})">
                                                            View Requirements
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 align-middle">
                                                    {{-- DYNAMIC ACTION BUTTONS --}}
                                                    <div class="flex items-center gap-2">
                                                        @if ($isRequestType)
                                                            {{-- Buttons for Requests --}}
                                                            @if (auth()->user()->identifier == 1) {{-- Admin --}}
                                                                @if ($app->status === 'Finalized')
                                                                    <button type="button" wire:click="$dispatch('open-id-preview', {{ $app->applicant_id }})" class="px-3 py-1.5 text-xs font-medium rounded-md bg-purple-500 text-white hover:bg-purple-600">Preview/Release ID</button>
                                                                @else
                                                                    <button type="button" wire:click="openConfirmFinalizeRequest({{ $app->request_id }})"
                                                                        class="px-3 py-1.5 text-xs font-medium rounded-md bg-green-500 text-white hover:bg-green-600">
                                                                        Finalize
                                                                    </button>
                                                                @endif
                                                            @elseif (auth()->user()->identifier == 2) {{-- Staff --}}
                                                                <button type="button" wire:click="openConfirmAcceptRequest({{ $app->request_id }})"
                                                                    class="px-3 py-1.5 text-xs font-medium rounded-md bg-blue-500 text-white hover:bg-blue-600">
                                                                    Accept
                                                                </button>
                                                            @endif
                                                            <button type="button"
                                                                wire:click="$dispatch('open-rejection-modal', { id: {{ $app->request_id }}, type: 'request' })"
                                                                class="px-3 py-1.5 text-xs font-medium rounded-md bg-red-500 text-white hover:bg-red-600">
                                                                Reject
                                                            </button>
                                                        @else
                                                            {{-- Buttons for Personal Applications --}}
                                                            @if (auth()->user()->identifier == 1) {{-- Admin --}}
                                                                @if ($app->status === 'Finalized')
                                                                    <button type="button" wire:click="$dispatch('open-id-preview', {{ $app->applicant_id }})" class="px-3 py-1.5 text-xs font-medium rounded-md bg-purple-500 text-white hover:bg-purple-600">Preview/Release ID</button>
                                                                @else
                                                                    <button type="button" wire:click="finalizeApplication({{ $app->applicant_id }})" class="px-3 py-1.5 text-xs font-medium rounded-md bg-green-500 text-white hover:bg-green-600">Finalize</button>
                                                                @endif
                                                            @elseif (auth()->user()->identifier == 2) {{-- Staff --}}
                                                                <button type="button" wire:click="openConfirmAcceptPersonal({{ $app->applicant_id }})"
                                                                    class="px-3 py-1.5 text-xs font-medium rounded-md bg-blue-500 text-white hover:bg-blue-600">
                                                                    Accept
                                                                </button>
                                                            @endif
                                                            <button type="button"
                                                                wire:click="$dispatch('open-rejection-modal', { id: {{ $app->applicant_id }}, type: 'application' })"
                                                                class="px-3 py-1.5 text-xs font-medium rounded-md bg-red-500 text-white hover:bg-red-600">
                                                                Reject
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                                                    No applications found for this category.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                @elseif ($section === 'chat')
                    {{-- Chat Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Chat</h3>
                        <p class="mt-2 text-gray-700">Messaging system placeholder.</p>
                    </div>

                @elseif ($section === 'announcements')
                    {{-- Announcements Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Announcements</h3>
                        <livewire:announcement-manager />
                    </div>

                @elseif ($section === 'encode-form')
                    {{-- Encode Form Tab --}}
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Encode Form</h3>
                        <p class="mt-2 text-gray-700">Encode form content placeholder.</p>
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
                    <div class="bg-white rounded-2xl shadow p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Report Analytics</h3>
                        @livewire('reports-summary')
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


</div>
