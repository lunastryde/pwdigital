<div class="w-full">
    <!-- Go back to Home -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex space-x-6">
                    <a href="{{ route('home', ['tab' => 'applications']) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6l-6 6 6 6" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16" />
                        </svg>
                        <span>Back to Home</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Form Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Step indicator -->
        <div class="flex items-center justify-between mb-4">
            <div class="text-sm text-gray-600">Step {{ $step }} of 3</div>
        </div>

        <!-- STEP 1: Personal & Address Information -->
        @if ($step === 1)
            <div class="bg-white shadow rounded-lg overflow-hidden" wire:key="step-1">
                <div class="bg-teal-600 text-white text-center py-2 font-medium">ID Application Form</div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left column -->
                        <div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" wire:model.defer="first_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" wire:model.defer="middle_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" wire:model.defer="last_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Suffix</label>
                                    <input type="text" wire:model.defer="suffix" placeholder="Leave blank if none" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                    <input type="date" wire:model.defer="date_of_birth" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Age</label>
                                    <input type="number" min="0" wire:model.defer="age" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Type of Blood</label>
                                    <select wire:model.defer="blood_type" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select</option>
                                        <option>O+</option>
                                        <option>O-</option>
                                        <option>A+</option>
                                        <option>A-</option>
                                        <option>B+</option>
                                        <option>B-</option>
                                        <option>AB+</option>
                                        <option>AB-</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Civil Status -->
                            <div class="mt-6">
                                <span class="block text-sm font-medium text-gray-700 mb-2">Civil Status</span>
                                <div class="flex flex-wrap gap-6 text-sm text-gray-700">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="civil_status" class="accent-blue-600" value="Single" wire:model.defer="civil_status"> Single
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="civil_status" class="accent-blue-600" value="Married" wire:model.defer="civil_status"> Married
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="civil_status" class="accent-blue-600" value="Separated" wire:model.defer="civil_status"> Separated
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="civil_status" class="accent-blue-600" value="Divorced" wire:model.defer="civil_status"> Divorced
                                    </label>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="mt-6">
                                <span class="block text-sm font-medium text-gray-700 mb-2">Gender</span>
                                <div class="flex flex-wrap gap-6 text-sm text-gray-700">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="gender" class="accent-blue-600" value="Male" wire:model.defer="gender"> Male
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="gender" class="accent-blue-600" value="Female" wire:model.defer="gender"> Female
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="Others" wire:model.defer="gender"> Others
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="md:border-l md:pl-8">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">House No.</label>
                                        <input type="text" wire:model.defer="house_no" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Street</label>
                                        <input type="text" wire:model.defer="street" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barangay</label>
                                    <input type="text" wire:model.defer="barangay" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Municipality</label>
                                    <input type="text" wire:model.defer="municipality" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <input type="text" wire:model.defer="province" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>

                                <!-- Contact Details -->
                                <div class="mt-6">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="text-sm font-medium text-gray-700">Contact Details</span>
                                        <div class="flex-1 border-t border-gray-200"></div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Mobile No.</label>
                                            <input type="text" wire:model.defer="mobile_no" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                            <input type="email" wire:model.defer="email" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Landline No. <span class="text-gray-400">(optional)</span></label>
                                            <input type="text" wire:model.defer="landline_no" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Disability sections moved to Step 1 -->
                    <div class="mt-8 grid grid-cols-1 gap-8">
                        <!-- Type of Disability -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Type of Disability</h3>
                            <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm text-gray-800">
                                @php
                                    $types = [
                                        'Deaf / Hard hearing', 'Intellectual Disability', 'Learning Disability', 'Mental Disability', 'Physical Disability',
                                        'Psychosocial Disability', 'Speech & Language Impairment', 'Visual Disability', 'Cancer', 'Rare Disease'
                                    ];
                                @endphp
                                @foreach ($types as $opt)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="disability_types"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Cause of Disability -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Cause of Disability</h3>
                            <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm text-gray-800">
                                @php
                                    $causes = [
                                        'Congenital or Inborn', 'Autism', 'ADHD', 'Cerebral Palsy', 'Acquired',
                                        'Chronic Illness', 'Injury', 'Others, Specify'
                                    ];
                                @endphp
                                @foreach ($causes as $opt)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="disability_causes"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- STEP 2: Education, Employment, Organization, ID Reference -->
        @if ($step === 2)
            <div class="bg-white shadow rounded-lg overflow-hidden" wire:key="step-2">
                <div class="bg-teal-600 text-white text-center py-2 font-medium">Application Form</div>
                <div class="p-6 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Educational Attainment -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Educational Attainment</h3>
                            <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 sm:grid-cols-2 gap-y-3 text-sm text-gray-800">
                                @php
                                    $educs = ['None','Kindergarten','Elementary','Junior Highschool','Senior Highschool','College','Vocational / ALS','Post Graduate Program'];
                                @endphp
                                @foreach ($educs as $opt)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="educational_attainment"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Type of Employment -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Type of Employment</h3>
                            <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                                @foreach (['Permanent / Regular','Seasonal','Casual','Emergency'] as $opt)
                                    <label class="flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="employment_type"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Employment -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Status Employment</h3>
                            <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                                @foreach (['Employed','Unemployed','Self-employed'] as $opt)
                                    <label class="flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="employment_status"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Category of Employment -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Category of Employment</h3>
                            <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                                @foreach (['Government','Private'] as $opt)
                                    <label class="flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="employment_category"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Occupation -->
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Occupation</h3>
                            <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 md:grid-cols-2 gap-y-3 text-sm text-gray-800">
                                @php
                                    $occups = [
                                        'Manager','Professionals','Technician & Associate Professionals','Clerical Support','Service & Sales Workers',
                                        'Skilled Agricultural, Forestry and Fishery Workers','Armed Forces Occupation','Elementary Occupation','Crafts & Related Trade Workers','Others, Specify'
                                    ];
                                @endphp
                                @foreach ($occups as $opt)
                                    <label class="flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="occupation"> {{ $opt }}
                                    </label>
                                @endforeach
                                <div class="md:col-span-2">
                                    <input type="text" placeholder="If Others, specify" wire:model.defer="occupation_other" class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                        </div>

                        <!-- 4P's Member -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">4P's Member</h3>
                            <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                                @foreach (['YES','NO'] as $opt)
                                    <label class="flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="four_ps_member"> {{ $opt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Second card: Organization Information & ID Reference -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Organization Information</h3>
                            <div class="rounded-md border border-gray-200 p-4 space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-700">Organization Affiliated</label>
                                    <input type="text" wire:model.defer="org_affiliated" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">Contact Person</label>
                                    <input type="text" wire:model.defer="org_contact_person" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <!-- Office Address removed as per request -->
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm text-gray-700">Address Details</span>
                                        <div class="flex-1 border-t border-gray-200"></div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-700">House No.</label>
                                            <input type="text" wire:model.defer="org_house_no" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" />
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">Street</label>
                                            <input type="text" wire:model.defer="org_street" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" />
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">Barangay</label>
                                            <input type="text" wire:model.defer="org_brgy" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" />
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">Municipality</label>
                                            <input type="text" wire:model.defer="org_municipality" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm text-gray-700">Province</label>
                                            <input type="text" wire:model.defer="org_province" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" />
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">Contact No.</label>
                                    <input type="text" wire:model.defer="org_contact_no" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">ID Reference</h3>
                            <div class="rounded-md border border-gray-200 p-4 space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-700">SSS No.</label>
                                    <input type="text" wire:model.defer="id_sss" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">GSIS No.</label>
                                    <input type="text" wire:model.defer="id_gsis" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">PhilHealth No.</label>
                                    <input type="text" wire:model.defer="id_philhealth" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">PAGIBIG No.</label>
                                    <input type="text" wire:model.defer="id_pagibig" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">Others</label>
                                    <input type="text" wire:model.defer="id_others" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- STEP 3: Family Background (final submit) -->
        @if ($step === 3)
            <div class="bg-white shadow rounded-lg overflow-hidden" wire:key="step-3">
                <div class="bg-teal-600 text-white text-center py-2 font-medium">Application Form</div>
                <form wire:submit.prevent="submit" class="p-6 space-y-8">
                    <!-- Family Background -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Family Background</h3>
                        <div class="rounded-md border border-gray-200 p-4 bg-gray-50">
                            <div class="grid grid-cols-5 gap-3 text-sm text-gray-700 font-medium mb-3">
                                <div></div>
                                <div>Last Name</div>
                                <div>First Name</div>
                                <div>Middle Name</div>
                                <div>Contact No.</div>
                            </div>
                            <!-- Father row -->
                            <div class="grid grid-cols-5 gap-3 items-center mb-3">
                                <div class="text-sm text-gray-800">Father's</div>
                                <input type="text" wire:model.defer="father_last" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="father_first" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="father_middle" class="rounded-md border border-gray-300 px-3 py-2" />
                                <div class="text-center text-gray-400">—</div>
                            </div>
                            <!-- Mother row -->
                            <div class="grid grid-cols-5 gap-3 items-center mb-3">
                                <div class="text-sm text-gray-800">Mother's</div>
                                <input type="text" wire:model.defer="mother_last" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="mother_first" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="mother_middle" class="rounded-md border border-gray-300 px-3 py-2" />
                                <div class="text-center text-gray-400">—</div>
                            </div>
                            <!-- Spouse / Guardian row (with contact field) -->
                            <div class="grid grid-cols-5 gap-3 items-center">
                                <div class="text-sm text-gray-800">Spouse / Guardian</div>
                                <input type="text" wire:model.defer="spouse_last" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="spouse_first" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="spouse_middle" class="rounded-md border border-gray-300 px-3 py-2" />
                                <input type="text" wire:model.defer="spouse_contact" class="rounded-md border border-gray-300 px-3 py-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Physician Name -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Physician Name</label>
                        <input type="text" wire:model.defer="physician_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <!-- File Upload Section -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Documents</h3>
                        <p class="text-sm text-gray-600 mb-6">Please upload all required documents. Maximum file size: 10MB each.</p>
                        
                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                                <span>Upload Progress</span>
                                <span>{{ $this->getUploadedCount() }} of 5 files uploaded</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ ($this->getUploadedCount() / 5) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- 1x1 ID Picture -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">1x1 ID Picture</h4>
                                    <span class="text-xs text-gray-500">JPG, JPEG, PNG only</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer {{ $files['id_picture'] ? 'border-blue-400 bg-blue-50' : '' }}"
                                        onclick="document.getElementById('id_picture_input').click()">
                                        @if(!$files['id_picture'])
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload</p>
                                        @else
                                            <div class="text-green-600">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm">Uploaded successfully</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($files['id_picture'])
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $files['id_picture']->getClientOriginalName() }}</p>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="removeFile('id_picture')" class="text-red-400 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="id_picture_input" wire:model="files.id_picture" accept="image/*" class="hidden">
                            </div>

                            <!-- PSA Birth Certificate -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">PSA Birth Certificate</h4>
                                    <span class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors cursor-pointer {{ $files['psa'] ? 'border-green-400 bg-green-50' : '' }}"
                                        onclick="document.getElementById('psa_input').click()">
                                        @if(!$files['psa'])
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload</p>
                                        @else
                                            <div class="text-green-600">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm">Uploaded successfully</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($files['psa'])
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $files['psa']->getClientOriginalName() }}</p>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="removeFile('psa')" class="text-red-400 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="psa_input" wire:model="files.psa" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                            </div>

                            <!-- Certificate of Disability -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">Certificate of Disability</h4>
                                    <span class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-purple-400 transition-colors cursor-pointer {{ $files['cert_of_disability'] ? 'border-purple-400 bg-purple-50' : '' }}"
                                        onclick="document.getElementById('cert_of_disability_input').click()">
                                        @if(!$files['cert_of_disability'])
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload</p>
                                        @else
                                            <div class="text-green-600">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm">Uploaded successfully</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($files['cert_of_disability'])
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $files['cert_of_disability']->getClientOriginalName() }}</p>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="removeFile('cert_of_disability')" class="text-red-400 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="cert_of_disability_input" wire:model="files.cert_of_disability" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                            </div>

                            <!-- Medical Certificate -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">Medical Certificate</h4>
                                    <span class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-red-400 transition-colors cursor-pointer {{ $files['med_cert'] ? 'border-red-400 bg-red-50' : '' }}"
                                        onclick="document.getElementById('med_cert_input').click()">
                                        @if(!$files['med_cert'])
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload</p>
                                        @else
                                            <div class="text-green-600">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm">Uploaded successfully</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($files['med_cert'])
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $files['med_cert']->getClientOriginalName() }}</p>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="removeFile('med_cert')" class="text-red-400 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="med_cert_input" wire:model="files.med_cert" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                            </div>

                            <!-- Endorsement Letter -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">Endorsement Letter</h4>
                                    <span class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-yellow-400 transition-colors cursor-pointer {{ $files['endorsement_letter'] ? 'border-yellow-400 bg-yellow-50' : '' }}"
                                        onclick="document.getElementById('endorsement_letter_input').click()">
                                        @if(!$files['endorsement_letter'])
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload</p>
                                        @else
                                            <div class="text-green-600">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm">Uploaded successfully</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($files['endorsement_letter'])
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $files['endorsement_letter']->getClientOriginalName() }}</p>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="removeFile('endorsement_letter')" class="text-red-400 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="endorsement_letter_input" wire:model="files.endorsement_letter" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                            </div>
                        </div>

                        <!-- Upload Summary -->
                        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="text-center">
                                <div class="mb-2">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->allFilesUploaded() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        @if($this->allFilesUploaded())
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                All documents uploaded
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $this->getMissingFilesCount() }} documents remaining</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Please ensure all documents are clear and readable
                                </p>
                            </div>
                        </div>
                    </div>
                                        


                    <!-- Submit Button-->
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2.5 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Bottom Navigation -->
         <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('scroll-to-top', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>

        <div class="mt-4 flex items-center justify-between">
            @if ($step > 1)
                <button type="button" wire:click="prevStep" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">
                    Back
                </button>
            @else
                <span></span>
            @endif

            @if ($step < 3)
                <button type="button" wire:click="nextStep" class="inline-flex items-center rounded-md bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">
                    Next
                </button>
            @endif
        </div>
    </div>
</div>
