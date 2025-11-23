<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Request Type Indicator -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Submit Request</h2>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Application Type:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $requestType === 'device' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $requestType === 'booklet' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $requestType === 'financial' ? 'bg-purple-100 text-purple-800' : '' }}">
                        @if($requestType === 'device') Device Request
                        @elseif($requestType === 'booklet') Booklet Request
                        @elseif($requestType === 'financial') Financial Assistance
                        @endif
                    </span>
                </div>
            </div>
            
            <!-- Request Type Selector -->
            <div class="grid grid-cols-3 divide-x divide-gray-200">
                <button type="button" wire:click="setRequestType('device')" 
                    class="p-4 text-center transition-colors {{ $requestType === 'device' ? 'bg-blue-50 border-b-4 border-blue-500' : 'hover:bg-gray-50' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 {{ $requestType === 'device' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    <div class="text-sm font-medium {{ $requestType === 'device' ? 'text-blue-900' : 'text-gray-700' }}">Device Request</div>
                </button>
                
                <button type="button" wire:click="setRequestType('booklet')" 
                    class="p-4 text-center transition-colors {{ $requestType === 'booklet' ? 'bg-green-50 border-b-4 border-green-500' : 'hover:bg-gray-50' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 {{ $requestType === 'booklet' ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <div class="text-sm font-medium {{ $requestType === 'booklet' ? 'text-green-900' : 'text-gray-700' }}">Booklet Request</div>
                </button>
                
                <button type="button" wire:click="setRequestType('financial')" 
                    class="p-4 text-center transition-colors {{ $requestType === 'financial' ? 'bg-purple-50 border-b-4 border-purple-500' : 'hover:bg-gray-50' }}">
                    <svg class="w-8 h-8 mx-auto mb-2 {{ $requestType === 'financial' ? 'text-purple-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm font-medium {{ $requestType === 'financial' ? 'text-purple-900' : 'text-gray-700' }}">Financial Assistance</div>
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
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

        <form wire:submit.prevent="submit">
            <!-- Applicant Information (Read-Only) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Applicant Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PWD Number</label>
                        <input type="text" value="{{ $pwd_number }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" value="{{ $full_name }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                        <input type="text" value="{{ $contact_no }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type of Disability</label>
                        <input type="text" value="{{ $disability_type }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" value="{{ $address }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- Request Specific Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 {{ $requestType === 'device' ? 'text-blue-600' : ($requestType === 'booklet' ? 'text-green-600' : 'text-purple-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Request Details
                </h3>

                @if($requestType === 'device')
                    <!-- Device Request Form -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Are you a Local Social Pension recipient? <span class="text-red-500"> *</span></label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model="local_social_pension" value="Y" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model="local_social_pension" value="N" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">No</span>
                                </label>
                            </div>
                            @error('local_social_pension') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Device Requested <span class="text-red-500"> *</span></label>
                            <input type="text" wire:model.defer="device_requested" placeholder="e.g., Wheelchair, Hearing Aid, Crutches" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('device_requested') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Request <span class="text-red-500"> *</span></label>
                            <p class="text-xs text-gray-500 mb-2 pt-1">You can use Tagalog or English.</p>
                            <textarea wire:model.defer="device_reason" rows="4" placeholder="Please explain why you need this device and how it will help you..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('device_reason') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                @elseif($requestType === 'booklet')
                    <!-- Booklet Request Form -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Type of Booklet <span class="text-red-500"> *</span></label>
                            <div class="space-y-3">
                                <label class="flex items-start p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" wire:model="booklet_type" value="grocery" class="mt-1 w-4 h-4 text-green-600 focus:ring-green-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Grocery Discount Booklet</span>
                                        <span class="block text-xs text-gray-500">For use at participating grocery stores and supermarkets</span>
                                    </div>
                                </label>
                                <label class="flex items-start p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" wire:model="booklet_type" value="medicine" class="mt-1 w-4 h-4 text-green-600 focus:ring-green-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Medicine Discount Booklet</span>
                                        <span class="block text-xs text-gray-500">For use at participating pharmacies and drugstores</span>
                                    </div>
                                </label>
                            </div>
                            @error('booklet_type') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Request <span class="text-red-500"> *</span></label>
                            <p class="text-xs text-gray-500 mb-2 pt-1">You can use Tagalog or English.</p>
                            <textarea wire:model.defer="booklet_reason" rows="4" placeholder="Please explain why you need this booklet..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                            @error('booklet_reason') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                @elseif($requestType === 'financial')
                    <!-- Financial Assistance Form -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Financial Assistance Request</label>
                            <p class="text-xs text-gray-500 mb-2 pt-1">You can use Tagalog or English.</p>
                            <textarea wire:model.defer="financial_reason" rows="6" placeholder="Please provide detailed information about why you need financial assistance, including any medical expenses, emergency situations, or other relevant circumstances..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Please be as specific as possible about your situation and needs.</p>
                            @error('financial_reason') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="window.history.back()" 
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 rounded-md font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancel
                </button>
                <button type="submit" 
                    class="px-6 py-2.5 font-medium rounded-md text-white transition-colors
                        {{ $requestType === 'device' ? 'bg-blue-600 hover:bg-blue-700' : '' }}
                        {{ $requestType === 'booklet' ? 'bg-green-600 hover:bg-green-700' : '' }}
                        {{ $requestType === 'financial' ? 'bg-purple-600 hover:bg-purple-700' : '' }}">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>