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
                                        <input type="radio" class="accent-blue-600" value="Single" wire:model.defer="civil_status"> Single
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="Married" wire:model.defer="civil_status"> Married
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="Separated" wire:model.defer="civil_status"> Separated
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="Divorced" wire:model.defer="civil_status"> Divorced
                                    </label>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="mt-6">
                                <span class="block text-sm font-medium text-gray-700 mb-2">Gender</span>
                                <div class="flex flex-wrap gap-6 text-sm text-gray-700">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="Male" wire:model.defer="gender"> Male
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="accent-blue-600" value="Female" wire:model.defer="gender"> Female
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

                    <!-- Submit inside the form (final step) -->
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2.5 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Bottom Navigation (outside of any form) -->
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
