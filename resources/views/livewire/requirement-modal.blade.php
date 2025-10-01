<div>
@if ($open)
    <!-- Backdrop -->
    <div class="fixed inset-0 z-40 bg-black/40" wire:click.self="close"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" wire:click.self="close">
        <div class="w-full h-full max-w-7xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col">
            <!-- Header - Fixed -->
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Application Details</h3>
                    <p class="text-sm text-gray-600 mt-1">Application ID: {{ $selectedId }}</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors p-2" wire:click="close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content Area - Scrollable -->
            <div class="flex flex-1 min-h-0">
                <!-- Left Panel - Application Details -->
                <div class="flex-1 p-6 overflow-y-auto">
                    @if ($application)
                        <div class="space-y-6 pb-6">
                            <!-- Personal Info -->
                            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Personal Information
                                </h4>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Name</div>
                                        <div class="text-base text-gray-900">{{ trim(($application->fname ?? '') . ' ' . ($application->mname ?? '') . ' ' . ($application->lname ?? '')) }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Birthdate</div>
                                        <div class="text-base text-gray-900">{{ $application->birthdate ? \Illuminate\Support\Carbon::parse($application->birthdate)->toFormattedDateString() : '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Sex</div>
                                        <div class="text-base text-gray-900">{{ $application->sex ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Civil Status</div>
                                        <div class="text-base text-gray-900">{{ $application->civil_status ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Blood Type</div>
                                        <div class="text-base text-gray-900">{{ $application->bloodtype ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">PWD Number</div>
                                        <div class="text-base text-gray-900">{{ $application->pwd_number ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Address -->
                            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Contact & Address
                                </h4>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Mobile</div>
                                        <div class="text-base text-gray-900">{{ $application->contact_no ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Email</div>
                                        <div class="text-base text-gray-900">{{ $application->email ?? '—' }}</div>
                                    </div>
                                    <div class="lg:col-span-2 space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Address</div>
                                        <div class="text-base text-gray-900">
                                            {{ $application->house_no ? $application->house_no . ', ' : '' }}
                                            {{ $application->street ? $application->street . ', ' : '' }}
                                            {{ $application->barangay ?? '' }}
                                            {{ $application->municipality ? ', ' . $application->municipality : '' }}
                                            {{ $application->province ? ', ' . $application->province : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disability -->
                            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    Disability Information
                                </h4>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Type</div>
                                        <div class="text-base text-gray-900">{{ $application->disability_type ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Cause</div>
                                        <div class="text-base text-gray-900">{{ $application->disability_cause ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employment & Organization -->
                            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 002 2h2a1 1 0 001-1V4a1 1 0 00-1-1h-2a2 2 0 00-2 2z" />
                                    </svg>
                                    Employment & Organization
                                </h4>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Occupation</div>
                                        <div class="text-base text-gray-900">{{ optional($application->occupation)->occupation ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Employment</div>
                                        <div class="text-base text-gray-900">
                                            {{ optional($application->occupation)->employment_status ?? '—' }}
                                            {{ optional($application->occupation)->employment_category ? ' • ' . optional($application->occupation)->employment_category : '' }}
                                            {{ optional($application->occupation)->employment_type ? ' • ' . optional($application->occupation)->employment_type : '' }}
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Organization</div>
                                        <div class="text-base text-gray-900">{{ optional($application->oi)->oi_affiliated ?? '—' }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Org Contact</div>
                                        <div class="text-base text-gray-900">{{ optional($application->oi)->oi_contactperson ?? '—' }} {{ optional($application->oi)->oi_telno ? ' • ' . optional($application->oi)->oi_telno : '' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family / Guardian -->
                            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Family / Guardian
                                </h4>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Mother</div>
                                        <div class="text-base text-gray-900">{{ optional($application->guardian)->mother_lname ? (optional($application->guardian)->mother_lname . ', ') : '' }}{{ optional($application->guardian)->mother_fname }} {{ optional($application->guardian)->mother_mname }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Father</div>
                                        <div class="text-base text-gray-900">{{ optional($application->guardian)->father_lname ? (optional($application->guardian)->father_lname . ', ') : '' }}{{ optional($application->guardian)->father_fname }} {{ optional($application->guardian)->father_mname }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Spouse / Guardian</div>
                                        <div class="text-base text-gray-900">{{ optional($application->guardian)->spouse_guardian_lname ? (optional($application->guardian)->spouse_guardian_lname . ', ') : '' }}{{ optional($application->guardian)->spouse_guardian_fname }} {{ optional($application->guardian)->spouse_guardian_mname }}</div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Contact</div>
                                        <div class="text-base text-gray-900">{{ optional($application->guardian)->spouse_guardian_contact ?? '—' }}</div>
                                    </div>
                                    <div class="lg:col-span-2 space-y-1">
                                        <div class="text-sm text-gray-500 font-medium">Physician Name</div>
                                        <div class="text-base text-gray-900">{{ optional($application->guardian)->physician_name ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500">No application data found.</div>
                        </div>
                    @endif
                </div>

                <!-- Right Panel - Files -->
                <div class="w-80 xl:w-96 bg-gray-50 border-l border-gray-200 flex-shrink-0">
                    <div class="p-6 h-full overflow-y-auto">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center sticky top-0 bg-gray-50 py-2 -mt-2">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Supporting Documents
                        </h4>

                        @if($files)
                            <!-- File Status -->
                            <div class="mb-6 p-4 rounded-lg {{ $files->isComplete() ? 'bg-green-100 border border-green-200' : 'bg-yellow-100 border border-yellow-200' }}">
                                <div class="flex items-center">
                                    @if($files->isComplete())
                                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-green-800 font-medium">All documents uploaded</span>
                                    @else
                                        <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-yellow-800 font-medium">{{ $files->getCompletionPercentage() }}% complete</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Individual Files -->
                            <div class="space-y-4 pb-6">
                                @foreach(\App\Models\FormFile::$fileTypes as $key => $label)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="mb-3">
                                            <h5 class="font-semibold text-gray-900 text-sm">{{ $label }}</h5>
                                        </div>
                                        
                                        @if($files->$key)
                                            <!-- File exists -->
                                            <div class="space-y-3">
                                                <div class="flex items-center text-sm text-green-600">
                                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Uploaded
                                                </div>
                                                
                                                @php
                                                    $metadata = $files->file_metadata[$key] ?? null;
                                                    $originalName = $metadata['original_name'] ?? basename($files->$key);
                                                    $size = $metadata['size'] ?? null;
                                                @endphp
                                                
                                                <div class="text-xs text-gray-600 space-y-1">
                                                    <div class="truncate font-medium">{{ $originalName }}</div>
                                                    @if($size)
                                                        <div class="text-gray-500">{{ number_format($size / 1024, 1) }} KB</div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex flex-col gap-2">
                                                    <a href="{{ Storage::url($files->$key) }}" 
                                                       target="_blank"
                                                       class="flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View File
                                                    </a>
                                                    <a href="{{ Storage::url($files->$key) }}" 
                                                       download="{{ $originalName }}"
                                                       class="flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <!-- File missing -->
                                            <div class="flex items-center text-sm text-red-600">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Not uploaded
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($files->submitted_at)
                                <div class="p-4 bg-gray-100 rounded-lg">
                                    <div class="text-xs text-gray-600">
                                        <div class="font-medium mb-1">Submitted:</div>
                                        <div>{{ $files->submitted_at->format('M j, Y g:i A') }}</div>
                                    </div>
                                </div>
                            @endif

                        @else
                            <div class="bg-gray-100 border border-gray-200 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="text-sm text-gray-600">No documents uploaded yet</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
</div>