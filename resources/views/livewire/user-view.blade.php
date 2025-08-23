<div class="w-full">
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
                        wire:click="setTab('drafts')"
                        class="py-4 px-1 border-b-2 text-sm font-medium transition-colors
                            {{ $tab === 'drafts' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Drafts
                    </button>
                    <button
                        type="button"
                        wire:click="setTab('notifications')"
                        class="py-4 px-1 border-b-2 text-sm font-medium transition-colors
                            {{ $tab === 'notifications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Notification
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
                            <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                                    <circle cx="12" cy="8" r="3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-4 4-6 8-6s8 2 8 6" />
                                </svg>
                                <span class="ml-3">Account Settings</span>
                            </a>

                            <!-- Accessibility -->
                            <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" d="M4 7h10M4 17h16M14 7a2 2 0 1 0 4 0 2 2 0 0 0-4 0ZM8 17a2 2 0 1 0 4 0 2 2 0 0 0-4 0Z" />
                                </svg>
                                <span class="ml-3">Accessibility</span>
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
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-2">Profile</h2>
                @auth
                    @php
                        $user = auth()->user();
                        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? ''));
                        if ($fullName === '') {
                            $fullName = $user->name ?? $user->username ?? '—';
                        }
                    @endphp

                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden ring-2 ring-gray-300">
                                
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">Welcome back, {{ $fullName }}</h3>
                            <p class="text-gray-600">Nyahahaha.</p>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">PWD ID</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->pwd_id ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Full Name</dt>
                                            <dd class="font-medium text-gray-900">{{ $fullName }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Gender</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->gender ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Date of Birth</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->date_of_birth ?? '—' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Civil Status</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->civil_status ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Type of Disability</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->disability_type ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dt class="text-gray-500">Email</dt>
                                            <dd class="font-medium text-gray-900">{{ $user->email ?? '—' }}</dd>
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

        @elseif ($tab === 'applications')
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
                                <a href="{{ route('form.id') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">ID Application</a>
                                <a href="#" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Financial Assistance</a>
                                <a href="#" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Request Assistive Device</a>
                            </div>
                        </details>
                    </div>
                </div>
                <p class="text-gray-600 text-sm">Placeholder for applications list.</p>
            </div>

        @elseif ($tab === 'drafts')
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-2">Drafts</h2>
                <p class="text-gray-600 text-sm">Placeholder for drafts.</p>
            </div>
            
        @elseif ($tab === 'notifications')
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-2">Notification</h2>
                <p class="text-gray-600 text-sm">Placeholder for notifications.</p>
            </div>
        @endif
    </div>
</div>
