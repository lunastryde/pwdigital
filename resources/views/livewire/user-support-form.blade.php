<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Manage ID Request</h2>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Request Type:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $tab === 'renewal' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $tab === 'loss' ? 'bg-red-100 text-red-800' : '' }}">
                        @if($tab === 'renewal') Renewal Request
                        @elseif($tab === 'loss') Lost ID Report
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 divide-x divide-gray-200">
                <button type="button" wire:click="switchTab('renewal')" 
                        class="p-4 text-center transition-colors {{ $tab === 'renewal' ? 'bg-blue-50 border-b-4 border-blue-500' : 'hover:bg-gray-50' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 {{ $tab === 'renewal' ? 'text-blue-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <div class="text-sm font-medium {{ $tab === 'renewal' ? 'text-blue-900' : 'text-gray-700' }}">Renewal Request</div>
                </button>
                
                <button type="button" wire:click="switchTab('loss')" 
                        class="p-4 text-center transition-colors {{ $tab === 'loss' ? 'bg-red-50 border-b-4 border-red-500' : 'hover:bg-gray-50' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 {{ $tab === 'loss' ? 'text-red-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <div class="text-sm font-medium {{ $tab === 'loss' ? 'text-red-900' : 'text-gray-700' }}">Lost ID Report</div>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            
            {{-- RENEWAL FORM --}}
            @if($tab === 'renewal')

                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-5 mb-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Your PWD Application</h2>
                        <button 
                            wire:click="$dispatch('open-requirements', { id: {{ $personal->applicant_id }} })"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Details
                        </button>
                    </div>

                    <div class="mt-3 text-sm text-gray-700">
                        <div class="flex justify-between py-1">
                            <span class="font-medium">Application Status:</span>
                            <span class="font-semibold text-gray-900">{{ $personal->status }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-medium">Issued On:</span>
                            <span>{{ $personal->date_issued ?? 'Not issued yet' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-medium">Expiration Date:</span>
                            <span>{{ $personal->expiration_date ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-5 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 text-center">PWD ID Notification</h2>

                    <div class="relative w-full max-w-sm mx-auto border border-gray-300 bg-gray-100 rounded-lg shadow-md h-48 flex items-center justify-center select-none">
                        <div class="text-center text-gray-600">
                            <span class="block font-semibold text-lg">PWD Identification Card</span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="text-5xl font-extrabold text-red-600/60 tracking-widest">
                                EXPIRED
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        This is a visual placeholder only.
                    </p>
                </div>      

                @if($isBlockedRenewal)
                    <div class="text-center py-10 text-gray-600">
                        <p class="font-medium text-lg mb-2">Your ID is still valid.</p>
                        <p class="text-sm">Renewal is only allowed after expiration.</p>
                    </div>
                @else
                    <form wire:submit.prevent="submitRenewal" class="space-y-6">
            
                        {{-- Supporting Document Box --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Document</h3>
                            <p class="text-sm text-gray-600 mb-6">Upload your updated Medical Certificate. Max file size: 10MB</p>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">Medical Certificate</h4>
                                    <span class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <!-- Upload Box -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center 
                                                hover:border-blue-400 transition-colors cursor-pointer
                                                {{ $med_cert_file ? 'border-blue-400 bg-blue-50' : '' }}"
                                        onclick="document.getElementById('renew_med_cert_input').click()">

                                        @if(!$med_cert_file)
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

                                    <!-- Preview with Remove -->
                                    @if($med_cert_file)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ $med_cert_file->getClientOriginalName() }}</p>
                                            <button type="button" wire:click="$set('med_cert_file', null)" class="text-red-400 hover:text-red-600">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                </div>

                                <input type="file" id="renew_med_cert_input" wire:model="med_cert_file" class="hidden" />
                                @error('med_cert_file') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- ✅ Submit Button -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="px-6 py-2.5 font-medium rounded-md text-white transition-colors bg-blue-600 hover:bg-blue-700">
                                Submit Renewal Request
                            </button>
                        </div>

                    </form>
                @endif
            @endif

            {{-- LOSS FORM --}}
            @if($tab === 'loss')

                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-5 mb-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Your PWD Application</h2>
                        <button 
                            wire:click="$dispatch('open-requirements', { id: {{ $personal->applicant_id }} })"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Details
                        </button>
                    </div>

                    <div class="mt-3 text-sm text-gray-700">
                        <div class="flex justify-between py-1">
                            <span class="font-medium">Application Status:</span>
                            <span class="font-semibold text-gray-900">{{ $personal->status }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-medium">Issued On:</span>
                            <span>{{ $personal->date_issued ?? 'Not issued yet' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-medium">Expiration Date:</span>
                            <span>{{ $personal->expiration_date ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            
                @if($isBlockedLoss)
                    <div class="text-center py-10 text-gray-600">
                        <p class="font-medium text-lg mb-2">You must wait until your ID is released first.</p>
                    </div>
                @else
                    <form wire:submit.prevent="submitLoss" class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Document</h3>
                            <p class="text-sm text-gray-600 mb-6">Upload your Affidavit of Loss. Max file size: 10MB</p>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">Affidavit of Loss</h4>
                                    <span class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center 
                                                hover:border-red-400 transition-colors cursor-pointer
                                                {{ $affidavit_file ? 'border-red-400 bg-red-50' : '' }}"
                                        onclick="document.getElementById('loss_affidavit_input').click()">
                                        
                                        @if(!$affidavit_file)
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6" />
                                            </svg>
                                            <p class="text-sm text-gray-600">Click to upload</p>
                                        @else
                                            <div class="text-green-600">
                                                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                                </svg>
                                                <p class="text-sm">Uploaded successfully</p>
                                            </div>
                                        @endif

                                    </div>

                                    @if($affidavit_file)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ $affidavit_file->getClientOriginalName() }}</p>
                                            <button type="button" wire:click="$set('affidavit_file', null)" class="text-red-400 hover:text-red-600">✕</button>
                                        </div>
                                    </div>
                                    @endif

                                </div>

                                <input type="file" id="loss_affidavit_input" wire:model="affidavit_file" class="hidden" />
                                @error('affidavit_file') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- ✅ Submit Button -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="px-6 py-2.5 font-medium rounded-md text-white transition-colors bg-red-600 hover:bg-red-700">
                                Submit Lost ID Report
                            </button>
                        </div>

                    </form>
                @endif
            @endif
        </div>
        @livewire('requirement-modal')
    </div>
</div>