<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    {{-- Back Link (Breadcrumb Style) --}}
    <div class="mb-4">
        <a href="{{ route('home', ['tab' => 'applications']) }}" 
        class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
            {{-- Arrow Left Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow border border-gray-200 p-4 mb-6">
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

    @if ($step === 1)
        <div class="bg-white shadow rounded-lg overflow-hidden" wire:key="step-1">
            {{-- Updated to Blue to match indicator --}}
            <div class="bg-blue-600 text-white text-center py-2 font-medium">ID Application Form</div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name<span class="text-red-500"> *</span></label>
                                <input type="text" wire:model.defer="first_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                <input type="text" wire:model.defer="middle_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name<span class="text-red-500"> *</span></label>
                                <input type="text" wire:model.defer="last_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Suffix</label>
                                <input type="text" wire:model.defer="suffix" placeholder="Leave blank if none" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                    wire:model.live="date_of_birth"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Age</label>
                                <input type="number"
                                    min="0"
                                    wire:model="age"
                                    disabled
                                    class="mt-1 block w-full bg-gray-100 rounded-md border border-gray-300 px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type of Blood<span class="text-red-500"> *</span></label>
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Sex <span class="text-red-500"> *</span>
                                </label>
                                <select wire:model.defer="gender"
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Sex</option>
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <span class="block text-sm font-medium text-gray-700 mb-2">Civil Status <span class="text-red-500"> *</span></span>
                            <div class="flex flex-col gap-3 text-sm text-gray-700">
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" name="civil_status" class="accent-blue-600" value="Single" wire:model.defer="civil_status"> Single
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" name="civil_status" class="accent-blue-600" value="Separated" wire:model.defer="civil_status"> Separated
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" name="civil_status" class="accent-blue-600" value="Cohabitation" wire:model.defer="civil_status"> Cohabitation (Live-in)
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" name="civil_status" class="accent-blue-600" value="Married" wire:model.defer="civil_status"> Married
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" name="civil_status" class="accent-blue-600" value="Widowed" wire:model.defer="civil_status"> Widow/er
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="md:border-l md:pl-8">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">House No.<span class="text-red-500"> *</span></label>
                                    <input type="text" wire:model.defer="house_no" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Street<span class="text-red-500"> *</span></label>
                                    <input type="text" wire:model.defer="street" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Barangay <span class="text-red-500"> *</span>
                                </label>
                                <select wire:model.defer="barangay"
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Municipality
                                </label>
                                <input type="text"
                                    wire:model.defer="municipality"
                                    readonly
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2
                                            focus:ring-0 focus:border-gray-300 cursor-not-allowed"
                                    value="Calapan City" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Province
                                </label>
                                <input type="text"
                                    wire:model.defer="province"
                                    readonly
                                    class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2
                                            focus:ring-0 focus:border-gray-300 cursor-not-allowed"
                                    value="Oriental Mindoro" />
                            </div>


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
                                        <input type="email"
                                            wire:model="email"
                                            class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed px-3 py-2"
                                            readonly />
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

                <div class="mt-8 grid grid-cols-1 gap-8">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">
                            Type of Disability <span class="text-red-500">*</span>
                        </h3>
                        <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm text-gray-800">
                            @php
                                $types = [
                                    'Deaf / Hard hearing',
                                    'Intellectual Disability',
                                    'Learning Disability',
                                    'Mental Disability',
                                    'Physical Disability',
                                    'Psychosocial Disability',
                                    'Speech & Language Impairment',
                                    'Visual Disability',
                                    'Cancer',
                                    'Rare Disease'
                                ];
                            @endphp
                            @foreach ($types as $opt)
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio"
                                        class="accent-blue-600"
                                        name="disability_type"
                                        value="{{ strtoupper($opt) }}"
                                        wire:model.defer="disability_type">
                                    <span>{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">
                            Cause of Disability <span class="text-red-500">*</span>
                        </h3>
                        <div class="rounded-md border border-gray-200 p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm text-gray-800">
                            @php
                                $causes = [
                                    'Congenital or Inborn',
                                    'Autism',
                                    'ADHD',
                                    'Cerebral Palsy',
                                    'Acquired',
                                    'Chronic Illness',
                                    'Injury',
                                    'Others, Specify'
                                ];
                            @endphp

                            @foreach ($causes as $opt)
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio"
                                        class="accent-blue-600"
                                        value="{{ $opt === 'Others, Specify' ? 'Others, Specify' : strtoupper($opt) }}"
                                        wire:model.live="disability_cause">
                                    <span>{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>

                        @if ($disability_cause === 'Others, Specify')
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700">
                                    Please specify cause of disability <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    wire:model.defer="disability_cause_other"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($step === 2)
        <div class="bg-white shadow rounded-lg overflow-hidden" wire:key="step-2">
            {{-- Updated to Blue to match indicator --}}
            <div class="bg-blue-600 text-white text-center py-2 font-medium">Application Form</div>
            <div class="p-6 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Educational Attainment <span class="text-red-500"> *</span></h3>
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

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Status Employment <span class="text-red-500"> *</span></h3>
                        <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                            @foreach (['Employed','Unemployed','Self-employed'] as $opt)
                                <label class="flex items-center gap-2">
                                    <input type="radio"
                                        class="accent-blue-600"
                                        value="{{ $opt }}"
                                        wire:model.live="employment_status">
                                    {{ $opt }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Type of Employment <span class="text-red-500"> *</span></h3>
                        <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                            @foreach (['Permanent / Regular','Seasonal','Casual','Emergency'] as $opt)
                                <label class="flex items-center gap-2 {{ $employment_status === 'Unemployed' ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <input type="radio"
                                        class="accent-blue-600"
                                        value="{{ $opt }}"
                                        wire:model.defer="employment_type"
                                        @if($employment_status === 'Unemployed') disabled @endif>
                                    {{ $opt }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Category of Employment <span class="text-red-500"> *</span></h3>
                        <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                            @foreach (['Government','Private'] as $opt)
                                <label class="flex items-center gap-2 {{ $employment_status === 'Unemployed' ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <input type="radio"
                                        class="accent-blue-600"
                                        value="{{ $opt }}"
                                        wire:model.defer="employment_category"
                                        @if($employment_status === 'Unemployed') disabled @endif>
                                    {{ $opt }}
                                </label>
                            @endforeach
                        </div>
                    </div>


                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Occupation <span class="text-red-500"> *</span></h3>
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
                                @if($occupation !== 'Others, Specify') disabled @endif
                                class="block w-full rounded-md border border-gray-300 px-3 py-2
                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase
                                    @if($occupation !== 'Others, Specify') bg-gray-100 cursor-not-allowed @endif"
                            />
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">4P's Member <span class="text-red-500"> *</span></h3>
                        <div class="rounded-md border border-gray-200 p-4 space-y-3 text-sm text-gray-800">
                            @foreach (['YES','NO'] as $opt)
                                <label class="flex items-center gap-2">
                                    <input type="radio" class="accent-blue-600" value="{{ $opt }}" wire:model.defer="four_ps_member"> {{ $opt }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Organization Information</h3>

                        <div class="rounded-md border border-gray-200 p-4 space-y-4">
                            <div>
                                <label class="block text-sm text-gray-700">Organization Affiliated</label>
                                <input type="text"
                                    wire:model.defer="org_affiliated"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700">Contact Person</label>
                                <input type="text"
                                    wire:model.defer="org_contact_person"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>

                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-sm text-gray-700">Address Details</span>
                                    <div class="flex-1 border-t border-gray-200"></div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">House No.</label>
                                        <input type="text"
                                            wire:model.defer="org_house_no"
                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Street</label>
                                        <input type="text"
                                            wire:model.defer="org_street"
                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Barangay</label>
                                        <input type="text"
                                            wire:model.defer="org_brgy"
                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Municipality</label>
                                        <input type="text"
                                            wire:model.defer="org_municipality"
                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label class="block text-sm text-gray-700">Province</label>
                                        <input type="text"
                                            wire:model.defer="org_province"
                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Contact No.</label>
                                        <input type="text"
                                            wire:model.defer="org_contact_no"
                                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-3">ID Reference</h3>
                            <p class="text-xs text-gray-500 mb-2 pt-1">
                                Provide at least one valid ID number.<span class="text-red-500"> *</span>
                            </p>
                            <div class="rounded-md border border-gray-200 p-4 space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-700">
                                        SSS No.
                                    </label>
                                    <input type="text" inputmode="numeric" 
                                        pattern="[0-9]*"
                                        wire:model.defer="id_sss"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder = "00-0000000-0"
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    <p class="mt-1 text-xs text-gray-500">Minimum of 10 digits required</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">
                                        GSIS No.
                                    </label>
                                    <input type="text" inputmode="numeric" 
                                        pattern="[0-9]*"
                                        wire:model.defer="id_gsis" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder = "0000-0000000-0"
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    <p class="mt-1 text-xs text-gray-500">Minimum of 10–12 digits required</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">
                                        PhilHealth No.
                                    </label>
                                    <input type="text" inputmode="numeric" 
                                        pattern="[0-9]*"
                                        wire:model.defer="id_philhealth" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder = "00-000000000-0"
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    <p class="mt-1 text-xs text-gray-500">Minimum of 12 digits required</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">
                                        PAGIBIG No.
                                    </label>
                                    <input type="text" inputmode="numeric" 
                                        pattern="[0-9]*"
                                        wire:model.defer="id_pagibig" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder= "0000-0000-0000"
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    <p class="mt-1 text-xs text-gray-500">Minimum of 12 digits required</p>
                                </div>

                                {{-- Other IDs --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm text-gray-700">
                                            Other Valid ID
                                        </label>
                                        <select wire:model.live="id_others_type"
                                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                    <label class="block text-sm text-gray-700">
                                        Other ID Number
                                    </label>
                                    <input type="text"
                                        wire:model.defer="id_others_number"
                                        @if(empty($id_others_type)) disabled @endif
                                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase
                                                @if(empty($id_others_type)) bg-gray-100 cursor-not-allowed @endif" />
                                </div>
                            </div>

                            @error('id_reference')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($step === 3)
        <div class="bg-white shadow rounded-lg overflow-hidden" wire:key="step-3">
            {{-- Updated to Blue to match indicator --}}
            <div class="bg-blue-600 text-white text-center py-2 font-medium">Application Form</div>
            <form wire:submit.prevent="submit" class="p-6 space-y-8">
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Family Background</h3>
                    <div class="rounded-md border border-gray-200 p-4 bg-gray-50">

                        <div class="hidden sm:grid grid-cols-5 gap-3 text-sm text-gray-700 font-medium mb-3">
                            <div></div>
                            <div>Last Name</div>
                            <div>First Name</div>
                            <div>Middle Name</div>
                            <div>Contact No.</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-center mb-3">
                            <div class="text-sm text-gray-800">
                                <span class="sm:inline block font-medium">Father's Name</span>
                            </div>
                            <input type="text" wire:model.defer="father_last" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="Last Name " />
                            <input type="text" wire:model.defer="father_first" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="First Name" />
                            <input type="text" wire:model.defer="father_middle" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="Middle Name" />
                            <input type="text" wire:model.defer="father_contact" class="rounded-md border border-gray-300 px-3 py-2" placeholder="09XXXXXXXXX" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-center mb-3">
                            <div class="text-sm text-gray-800">
                                <span class="sm:inline block font-medium">Mother's Name</span>
                            </div>
                            <input type="text" wire:model.defer="mother_last" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="Last Name" />
                            <input type="text" wire:model.defer="mother_first" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="First Name" />
                            <input type="text" wire:model.defer="mother_middle" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="Middle Name" />
                            <input type="text" wire:model.defer="mother_contact" class="rounded-md border border-gray-300 px-3 py-2" placeholder="09XXXXXXXXX" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-center">
                            <div class="text-sm text-gray-800">
                                <span class="sm:inline block font-medium">Spouse / Guardian <span class="text-red-500"> *</span></span>
                            </div>
                            <input type="text" wire:model.live="spouse_last" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="Last Name" />
                            <input type="text" wire:model.live="spouse_first" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="First Name" />
                            <input type="text" wire:model.live="spouse_middle" class="rounded-md border border-gray-300 px-3 py-2 uppercase" placeholder="Middle Name" />
                            <input type="text" wire:model.live="spouse_contact" class="rounded-md border border-gray-300 px-3 py-2" placeholder="09XXXXXXXXX" />
                        </div>
                    </div>
                </div>


                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">Physician Name <span class="text-red-500"> *</span></label>
                    <input type="text" wire:model.live="physician_name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" />
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Documents</h3>
                    <p class="text-sm text-gray-600 mb-6">Please upload all required documents. Maximum file size: 10MB each.</p>
                    
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
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">1x1 ID Picture <span class="text-red-500"> *</span></h4>
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

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">PSA Birth Certificate <span class="text-red-500"> *</span></h4>
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

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">Certificate of Disability <span class="text-red-500"> *</span></h4>
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

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">Medical Certificate <span class="text-red-500"> *</span></h4>
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

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">Endorsement Letter <span class="text-red-500"> *</span></h4>
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
                                    
                @php
                    $canSubmit = $this->isFormComplete();
                @endphp
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            @if(!$canSubmit) disabled @endif
                            class="inline-flex items-center rounded-md px-4 py-2.5 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500
                                {{ $canSubmit ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    @endif

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
            {{-- Updated to Blue to match indicator --}}
            <button type="button" wire:click="nextStep" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Next
            </button>
        @endif
    </div>

    @if ($errors->any())
        {{-- Backdrop with blur --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity">
            
            {{-- Modal Card --}}
            <div class="w-full max-w-md mx-4 bg-white rounded-2xl shadow-2xl transform transition-all scale-100 overflow-hidden"
                role="dialog" 
                aria-modal="true">
                
                {{-- Header Icon Area --}}
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-col items-center text-center">
                        
                        {{-- Warning Icon Circle --}}
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>

                        {{-- Text Content --}}
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                            Action Required
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                We found a few issues with your submission. Please correct the following errors to proceed:
                            </p>
                        </div>

                        {{-- Error List Container --}}
                        <div class="mt-4 w-full text-left bg-red-50 rounded-lg p-3 border border-red-100">
                            <ul class="list-disc list-inside text-xs sm:text-sm text-red-700 space-y-1 max-h-48 overflow-y-auto custom-scrollbar">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Footer / Action --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            wire:click="closeErrorModal"
                            class="inline-flex w-full justify-center rounded-lg bg-gray-900 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:w-auto transition-colors">
                        Okay, I'll fix it
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>