<div class="space-y-6">

    {{-- Success/Error flash --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if ($statusMessage)
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ $statusMessage }}
        </div>
    @endif

    <!-- Header + Create Account button -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                Encode PWD ID Application
            </h3>
            <p class="text-sm text-gray-600">
                Select an existing account or create a new one for walk-in clients.
            </p>
        </div>

        <button type="button"
                onclick="openStaffRegister()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Create Account for User
        </button>
    </div>

    {{-- 1. Account selection --}}
    <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Account to Encode</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email or Username <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    wire:model.defer="accountLookup"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter user's email or username">
                @if($accountError)
                    <p class="text-sm text-red-600 mt-1">{{ $accountError }}</p>
                @endif
            </div>
            <div class="flex md:justify-end">
                <button type="button"
                    wire:click="loadAccount"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 4h13M8 9h13M8 14h13M8 19h13M3 4h.01M3 9h.01M3 14h.01M3 19h.01" />
                    </svg>
                    Load Account
                </button>
            </div>
        </div>

        {{-- Summary of loaded account --}}
        @if($selectedAccountId)
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="border rounded-lg p-3 bg-gray-50">
                    <p><span class="font-semibold text-gray-700">Name:</span>
                        <span class="text-gray-900">{{ $summary_name ?? '—' }}</span></p>
                    <p><span class="font-semibold text-gray-700">Email:</span>
                        <span class="text-gray-900">{{ $summary_email ?? '—' }}</span></p>
                    <p><span class="font-semibold text-gray-700">Contact:</span>
                        <span class="text-gray-900">{{ $summary_contact ?? '—' }}</span></p>
                </div>
                <div class="border rounded-lg p-3 bg-gray-50">
                    <p><span class="font-semibold text-gray-700">Address:</span>
                        <span class="text-gray-900">{{ $summary_address ?? '—' }}</span></p>
                    @if($summary_pwd_number)
                        <p class="mt-2">
                            <span class="font-semibold text-gray-700">Existing PWD ID:</span>
                            <span class="text-gray-900">{{ $summary_pwd_number }}</span>
                        </p>
                    @endif
                </div>
            </div>

            @if($encodingLocked && $encodingLockMessage)
                <div class="mt-4 rounded-md border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                    {{ $encodingLockMessage }}
                </div>
            @endif
        @endif
    </div>

    {{-- If no account or locked, don't show the big form --}}
    @if($selectedAccountId && !$encodingLocked)
        {{-- Step indicator --}}
        <div class="bg-white rounded-2xl shadow border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                @foreach ([1 => 'Personal Info', 2 => 'Education & IDs', 3 => 'Family Background'] as $s => $label)
                    <div class="flex-1 flex items-center">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold
                                {{ $step === $s ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                {{ $s }}
                            </div>
                            <span class="ml-2 text-sm font-medium {{ $step === $s ? 'text-blue-700' : 'text-gray-600' }}">
                                {{ $label }}
                            </span>
                        </div>
                        @if($s < 3)
                            <div class="flex-1 h-0.5 mx-3 {{ $step > $s ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">

            {{-- STEP 1 --}}
            @if($step === 1)
                <div class="bg-white rounded-2xl shadow border border-gray-200 p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Personal Information & Disability</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="first_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                            @error('first_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" wire:model.defer="middle_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="last_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                            @error('last_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                            <input type="text" wire:model.defer="suffix"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="date_of_birth"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            @error('date_of_birth') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                            <input type="text" wire:model="age" disabled
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type <span class="text-red-500">*</span></label>
                            <select wire:model.defer="blood_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
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
                            @error('blood_type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sex <span class="text-red-500">*</span></label>
                            <select wire:model.defer="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Select</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            @error('gender') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status <span class="text-red-500">*</span></label>
                            <select wire:model.defer="civil_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Select</option>
                                <option value="Single">Single</option>
                                <option value="Separated">Separated</option>
                                <option value="Cohabitation">Cohabitation (Live-in)</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widow/er</option>
                            </select>
                            @error('civil_status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" wire:model.defer="mobile_no"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model.defer="email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Landline No.</label>
                            <input type="text" wire:model.defer="landline_no"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">Address</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">House No. <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="house_no"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                @error('house_no') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Street <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="street"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                @error('street') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barangay <span class="text-red-500">*</span></label>
                                <select wire:model.defer="barangay"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">Select Barangay</option>
                                    <option value="ADRIALUNA">Adrialuna</option>
                                    <option value="BALITE">Balite</option>
                                    <option value="BARUYAN">Baruyan</option>
                                    <option value="BATINO">Batino</option>
                                    <option value="BAYANAN I">Bayanan I</option>
                                    <option value="BAYANAN II">Bayanan II</option>
                                    <option value="BIGA">Biga</option>
                                    <option value="BONDOC">Bondoc</option>
                                    <option value="BUCAYAO">Bucayao</option>
                                    <option value="BUHUAN">Buhuan</option>
                                    <option value="BULUSAN">Bulusan</option>
                                    <option value="CALERO">Calero</option>
                                    <option value="CAMANSIHAN">Camansihan</option>
                                    <option value="CAMILMIL">Camilmil</option>
                                    <option value="CANUBING I">Canubing I</option>
                                    <option value="CANUBING II">Canubing II</option>
                                    <option value="COMUNAL">Comunal</option>
                                    <option value="GUINOBATAN">Guinobatan</option>
                                    <option value="GULOD">Gulod</option>
                                    <option value="GUTAD">Gutad</option>
                                    <option value="IBABA EAST">Ibaba East</option>
                                    <option value="IBABA WEST">Ibaba West</option>
                                    <option value="ILAYA">Ilaya</option>
                                    <option value="LALUD">Lalud</option>
                                    <option value="LAZARETO">Lazareto</option>
                                    <option value="LIBIS">Libis</option>
                                    <option value="LUMANG BAYAN">Lumang Bayan</option>
                                    <option value="MAHAL NA PANGALAN">Mahal na Pangalan</option>
                                    <option value="MAIDLANG">Maidlang</option>
                                    <option value="MALAD">Malad</option>
                                    <option value="MALAMIG">Malamig</option>
                                    <option value="MANAGPI">Managpi</option>
                                    <option value="MASIPIT">Masipit</option>
                                    <option value="NAG-IBA I">Nag-iba I</option>
                                    <option value="NAG-IBA II">Nag-iba II</option>
                                    <option value="NAVOTAS">Navotas</option>
                                    <option value="PACHOCA">Pachoca</option>
                                    <option value="PALHI">Palhi</option>
                                    <option value="PANGGALAAN">Panggalaan</option>
                                    <option value="PARANG">Parang</option>
                                    <option value="PATAS">Patas</option>
                                    <option value="PERSONAS">Personas</option>
                                    <option value="POBLACION I">Poblacion I</option>
                                    <option value="POBLACION II">Poblacion II</option>
                                    <option value="POBLACION III">Poblacion III</option>
                                    <option value="POBLACION IV">Poblacion IV</option>
                                    <option value="PUTINGTUBIG">Putingtubig</option>
                                    <option value="SALONG (SAN RAPHAEL)">Salong (San Raphael)</option>
                                    <option value="SAN ANTONIO">San Antonio</option>
                                    <option value="SAN VICENTE CENTRAL">San Vicente Central</option>
                                    <option value="SAN VICENTE EAST">San Vicente East</option>
                                    <option value="SAN VICENTE NORTH">San Vicente North</option>
                                    <option value="SAN VICENTE SOUTH">San Vicente South</option>
                                    <option value="SAN VICENTE WEST">San Vicente West</option>
                                    <option value="SANTA CRUZ">Santa Cruz</option>
                                    <option value="SANTA ISABEL">Santa Isabel</option>
                                    <option value="SANTA MARIA VILLAGE">Santa Maria Village</option>
                                    <option value="SANTA RITA">Santa Rita</option>
                                    <option value="SANTO NIÑO">Santo Niño</option>
                                    <option value="SAPUL">Sapul</option>
                                    <option value="SILONAY">Silonay</option>
                                    <option value="SUQUI">Suqui</option>
                                    <option value="TAWAGAN">Tawagan</option>
                                    <option value="TAWIRAN">Tawiran</option>
                                    <option value="TIBAG">Tibag</option>
                                    <option value="WAWA">Wawa</option>
                                </select>
                                @error('barangay') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                                <input type="text"
                                       wire:model.defer="municipality"
                                       readonly
                                       value="Calapan City"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300">
                                @error('municipality') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                            <input type="text"
                                   wire:model.defer="province"
                                   readonly
                                   value="Oriental Mindoro"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300">
                            @error('province') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">Disability Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type of Disability <span class="text-red-500">*</span></label>
                                <select wire:model.defer="disability_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">Select Type</option>
                                    <option value="DEAF / HARD HEARING">Deaf / Hard hearing</option>
                                    <option value="INTELLECTUAL DISABILITY">Intellectual Disability</option>
                                    <option value="LEARNING DISABILITY">Learning Disability</option>
                                    <option value="MENTAL DISABILITY">Mental Disability</option>
                                    <option value="PHYSICAL DISABILITY">Physical Disability</option>
                                    <option value="PSYCHOSOCIAL DISABILITY">Psychosocial Disability</option>
                                    <option value="SPEECH & LANGUAGE IMPAIRMENT">Speech & Language Impairment</option>
                                    <option value="VISUAL DISABILITY">Visual Disability</option>
                                    <option value="CANCER">Cancer</option>
                                    <option value="RARE DISEASE">Rare Disease</option>
                                </select>
                                @error('disability_type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cause of Disability <span class="text-red-500">*</span></label>
                                <select wire:model.live="disability_cause"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">Select Cause</option>
                                    <option value="CONGENITAL OR INBORN">Congenital or Inborn</option>
                                    <option value="AUTISM">Autism</option>
                                    <option value="ADHD">ADHD</option>
                                    <option value="CEREBRAL PALSY">Cerebral Palsy</option>
                                    <option value="ACQUIRED">Acquired</option>
                                    <option value="CHRONIC ILLNESS">Chronic Illness</option>
                                    <option value="INJURY">Injury</option>
                                    <option value="Others, Specify">Others, Specify</option>
                                </select>
                                @error('disability_cause') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        @if ($disability_cause === 'Others, Specify')
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">If "Others, Specify"</label>
                                <input type="text" wire:model.defer="disability_cause_other"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                @error('disability_cause_other') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- STEP 2 --}}
            @if($step === 2)
                <div class="bg-white rounded-2xl shadow border border-gray-200 p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Education, Employment & ID References</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Educational Attainment <span class="text-red-500">*</span></label>
                            <select wire:model.defer="educational_attainment"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Select</option>
                                <option value="None">None</option>
                                <option value="Kindergarten">Kindergarten</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Junior Highschool">Junior Highschool</option>
                                <option value="Senior Highschool">Senior Highschool</option>
                                <option value="College">College</option>
                                <option value="Vocational / ALS">Vocational / ALS</option>
                                <option value="Post Graduate Program">Post Graduate Program</option>
                            </select>
                            @error('educational_attainment') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">4P's Member <span class="text-red-500">*</span></label>
                            <select wire:model.defer="four_ps_member"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Select</option>
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                            </select>
                            @error('four_ps_member') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Employment Status <span class="text-red-500">*</span></label>
                            <select wire:model.live="employment_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Select</option>
                                <option value="Employed">Employed</option>
                                <option value="Unemployed">Unemployed</option>
                                <option value="Self-employed">Self-employed</option>
                            </select>
                            @error('employment_status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                            <select wire:model.defer="employment_type"
                                    @if($employment_status === 'Unemployed') disabled @endif
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md
                                        @if($employment_status === 'Unemployed') bg-gray-100 cursor-not-allowed @endif">
                                <option value="">Select</option>
                                <option value="Permanent / Regular">Permanent / Regular</option>
                                <option value="Seasonal">Seasonal</option>
                                <option value="Casual">Casual</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                            @error('employment_type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Employment Category</label>
                            <select wire:model.defer="employment_category"
                                    @if($employment_status === 'Unemployed') disabled @endif
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md
                                        @if($employment_status === 'Unemployed') bg-gray-100 cursor-not-allowed @endif">
                                <option value="">Select</option>
                                <option value="Government">Government</option>
                                <option value="Private">Private</option>
                            </select>
                            @error('employment_category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Occupation --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Occupation <span class="text-red-500">*</span></label>
                        <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 md:grid-cols-2 gap-y-3 text-sm text-gray-800">
                            @php
                                $occups = [
                                    'Manager','Professionals','Technician & Associate Professionals','Clerical Support','Service & Sales Workers',
                                    'Skilled Agricultural, Forestry and Fishery Workers','Armed Forces Occupation','Elementary Occupation','Crafts & Related Trade Workers','Others, Specify'
                                ];
                            @endphp

                            @foreach ($occups as $opt)
                                <label class="flex items-center gap-2 {{ $employment_status === 'Unemployed' ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <input type="radio"
                                           class="accent-blue-600"
                                           value="{{ $opt }}"
                                           wire:model.live="occupation"
                                           @if($employment_status === 'Unemployed') disabled @endif>
                                    {{ $opt }}
                                </label>
                            @endforeach

                            <input type="text"
                                   placeholder="If Others, specify"
                                   wire:model.defer="occupation_other"
                                   @if($occupation !== 'Others, Specify' || $employment_status === 'Unemployed') disabled @endif
                                   class="block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                        @if($occupation !== 'Others, Specify' || $employment_status === 'Unemployed') bg-gray-100 cursor-not-allowed @endif">
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">Organization Information</h4>

                        <div class="rounded-md border border-gray-200 p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                                    <input type="text" wire:model.defer="org_affiliated"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                    <input type="text" wire:model.defer="org_contact_person"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                    <input type="text" wire:model.defer="org_contact_no"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">House No.</label>
                                    <input type="text" wire:model.defer="org_house_no"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                                    <input type="text" wire:model.defer="org_street"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                    <input type="text" wire:model.defer="org_brgy"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                                    <input type="text" wire:model.defer="org_municipality"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                    <input type="text" wire:model.defer="org_province"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">ID Reference Numbers <span class="text-red-500">*</span></h4>
                        <p class="text-xs text-gray-500 mb-2">At least one valid ID number is required.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SSS No. (10 digits)</label>
                                <input type="text"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       wire:model.defer="id_sss"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       placeholder="00-0000000-0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('id_sss') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GSIS No. (10–12 digits)</label>
                                <input type="text"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       wire:model.defer="id_gsis"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       placeholder="0000-0000000-0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('id_gsis') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PhilHealth No. (12 digits)</label>
                                <input type="text"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       wire:model.defer="id_philhealth"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       placeholder="00-000000000-0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('id_philhealth') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PAGIBIG No. (12 digits)</label>
                                <input type="text"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       wire:model.defer="id_pagibig"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       placeholder="0000-0000-0000"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('id_pagibig') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Other Valid ID</label>
                                <select wire:model.live="id_others_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">Select ID Type</option>
                                    <option value="PhilSys ID (National ID)">PhilSys ID (National ID)</option>
                                    <option value="Unified Multi-Purpose ID (UMID)">Unified Multi-Purpose ID (UMID)</option>
                                    <option value="Driver's License">Driver's License</option>
                                    <option value="PRC ID">PRC ID</option>
                                    <option value="Philippine Passport">Philippine Passport</option>
                                    <option value="TIN ID">TIN ID</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Other ID Number</label>
                                <input type="text"
                                       wire:model.defer="id_others_number"
                                       @if(empty($id_others_type)) disabled @endif
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase
                                            @if(empty($id_others_type)) bg-gray-100 cursor-not-allowed @endif">
                                @error('id_others_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @error('id_reference')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            {{-- STEP 3 --}}
            @if($step === 3)
                <div class="bg-white rounded-2xl shadow border border-gray-200 p-4 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Family Background &amp; Physician</h3>

                    {{-- Header row --}}
                    <div class="grid grid-cols-5 gap-3 text-xs font-semibold text-gray-600">
                        <div></div>
                        <div>Last Name</div>
                        <div>First Name</div>
                        <div>Middle Name</div>
                        <div>Contact No.</div>
                    </div>

                    {{-- Father --}}
                    <div class="grid grid-cols-5 gap-3 items-center">
                        <div class="text-sm font-medium text-gray-800">Father</div>
                        <input type="text" wire:model.defer="father_last"   placeholder="Last Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="father_first"  placeholder="First Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="father_middle" placeholder="Middle Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="father_contact" placeholder="Contact Number"
                            class="px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    {{-- Mother --}}
                    <div class="grid grid-cols-5 gap-3 items-center">
                        <div class="text-sm font-medium text-gray-800">Mother</div>
                        <input type="text" wire:model.defer="mother_last"   placeholder="Last Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="mother_first"  placeholder="First Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="mother_middle" placeholder="Middle Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="mother_contact" placeholder="Contact Number"
                            class="px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    {{-- Spouse / Guardian (required) --}}
                    <div class="grid grid-cols-5 gap-3 items-center">
                        <div class="text-sm font-medium text-gray-800">
                            Spouse / Guardian <span class="text-red-500">*</span>
                        </div>
                        <input type="text" wire:model.defer="spouse_last"   placeholder="Last Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="spouse_first"  placeholder="First Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="spouse_middle" placeholder="Middle Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="spouse_contact" placeholder="Contact Number"
                            class="px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    {{-- Physician (required) --}}
                    <div class="grid grid-cols-5 gap-3 items-center">
                        <div class="text-sm font-medium text-gray-800">
                            Physician <span class="text-red-500">*</span>
                        </div>
                        <input type="text" wire:model.defer="physician_last"   placeholder="Last Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="physician_first"  placeholder="First Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="physician_middle" placeholder="Middle Name"
                            class="px-3 py-2 border border-gray-300 rounded-md uppercase">
                        <input type="text" wire:model.defer="physician_contact" placeholder="Contact Number"
                            class="px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

            @endif

            {{-- Navigation buttons --}}
            <div class="flex items-center justify-between pt-2">
                <div>
                    <!-- <button type="button"
                            onclick="window.history.back()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </button> -->
                </div>
                <div class="flex gap-3">
                    @if($step > 1)
                        <button type="button"
                                wire:click="prevStep"
                                class="px-4 py-2 text-sm bg-gray-100 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200">
                            Previous
                        </button>
                    @endif

                    @if($step < 3)
                        <button type="button"
                                wire:click="nextStep"
                                class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Next
                        </button>
                    @else
                        <button type="submit"
                                class="px-6 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700">
                            Submit Encoded Application
                        </button>
                    @endif
                </div>
            </div>
        </form>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

</div>
