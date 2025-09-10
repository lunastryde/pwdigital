<div>
@if ($open)
    <!-- Backdrop -->
    <div class="fixed inset-0 z-40 bg-black/40" wire:click.self="close"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="close">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Application Details</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700" wire:click="close">✕</button>
            </div>

            <div class="p-5 space-y-6 max-h-[75vh] overflow-y-auto">
                @if ($application)
                    <!-- Personal Info -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">Name</div>
                                <div class="text-gray-800">{{ trim(($application->fname ?? '') . ' ' . ($application->mname ?? '') . ' ' . ($application->lname ?? '')) }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Birthdate</div>
                                <div class="text-gray-800">{{ $application->birthdate ? \Illuminate\Support\Carbon::parse($application->birthdate)->toFormattedDateString() : '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Sex</div>
                                <div class="text-gray-800">{{ $application->sex ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Civil Status</div>
                                <div class="text-gray-800">{{ $application->civil_status ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Blood Type</div>
                                <div class="text-gray-800">{{ $application->bloodtype ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">PWD Number</div>
                                <div class="text-gray-800">{{ $application->pwd_number ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Address -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Contact & Address</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">Mobile</div>
                                <div class="text-gray-800">{{ $application->contact_no ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Email</div>
                                <div class="text-gray-800">{{ $application->email ?? '—' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-gray-500">Address</div>
                                <div class="text-gray-800">
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
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Disability</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">Type</div>
                                <div class="text-gray-800">{{ $application->disability_type ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Cause</div>
                                <div class="text-gray-800">{{ $application->disability_cause ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment & Organization -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Employment & Organization</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">Occupation</div>
                                <div class="text-gray-800">{{ optional($application->occupation)->occupation ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Employment</div>
                                <div class="text-gray-800">
                                    {{ optional($application->occupation)->employment_status ?? '—' }}
                                    {{ optional($application->occupation)->employment_category ? ' • ' . optional($application->occupation)->employment_category : '' }}
                                    {{ optional($application->occupation)->employment_type ? ' • ' . optional($application->occupation)->employment_type : '' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-500">Organization</div>
                                <div class="text-gray-800">{{ optional($application->oi)->oi_affiliated ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Org Contact</div>
                                <div class="text-gray-800">{{ optional($application->oi)->oi_contactperson ?? '—' }} {{ optional($application->oi)->oi_telno ? ' • ' . optional($application->oi)->oi_telno : '' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Numbers -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Reference Numbers</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">SSS</div>
                                <div class="text-gray-800">{{ optional($application->refnos)->refno_sss ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">GSIS</div>
                                <div class="text-gray-800">{{ optional($application->refnos)->refno_gsis ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">PhilHealth</div>
                                <div class="text-gray-800">{{ optional($application->refnos)->refno_philhealth ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">PAGIBIG</div>
                                <div class="text-gray-800">{{ optional($application->refnos)->refno_pagibig ?? '—' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-gray-500">Others</div>
                                <div class="text-gray-800">{{ optional($application->refnos)->refno_others ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Family / Guardian -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Family / Guardian</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500">Mother</div>
                                <div class="text-gray-800">{{ optional($application->guardian)->mother_lname ? (optional($application->guardian)->mother_lname . ', ') : '' }}{{ optional($application->guardian)->mother_fname }} {{ optional($application->guardian)->mother_mname }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Father</div>
                                <div class="text-gray-800">{{ optional($application->guardian)->father_lname ? (optional($application->guardian)->father_lname . ', ') : '' }}{{ optional($application->guardian)->father_fname }} {{ optional($application->guardian)->father_mname }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Spouse / Guardian</div>
                                <div class="text-gray-800">{{ optional($application->guardian)->spouse_guardian_lname ? (optional($application->guardian)->spouse_guardian_lname . ', ') : '' }}{{ optional($application->guardian)->spouse_guardian_fname }} {{ optional($application->guardian)->spouse_guardian_mname }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Spouse / Guardian Contact</div>
                                <div class="text-gray-800">{{ optional($application->guardian)->spouse_guardian_contact ?? '—' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-gray-500">Physician Name</div>
                                <div class="text-gray-800">{{ optional($application->guardian)->physician_name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-gray-600">No data loaded.</div>
                @endif
</div>
            </div>

        </div>
    </div>
@endif
