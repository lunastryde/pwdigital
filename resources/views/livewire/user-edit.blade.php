<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
    
    {{-- HEADER WITH BACK BUTTON --}}
    <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Profile</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your personal information and security settings.</p>
        </div>

        {{-- Back Button --}}
        <a href="
            @if (Auth::user()->role === 'admin')
                {{ route('staff.home') }}
            @elseif (Auth::user()->role === 'staff')
                {{ route('staff.home') }}
            @else
                {{ route('home') }} {{-- user home --}}
            @endif" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Home
        </a>

    </div>

    <div class="p-8 space-y-8">
        {{-- SUCCESS ALERT --}}
        @if (session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg animate-fade-in-down">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- PROFILE PICTURE SECTION --}}
        <div class="flex flex-col sm:flex-row items-center gap-8 pb-8 border-b border-gray-100">
            
            {{-- Preview Container --}}
            <div class="relative group">
                <div class="w-28 h-28 rounded-full overflow-hidden bg-gray-100 ring-4 ring-white shadow-lg flex items-center justify-center">
                    @if ($profile_picture)
                        <img src="{{ $profile_picture->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif (Auth::user()->profile->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile->profile_picture) }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 2a5 5 0 00-3.5 8.5A9 9 0 003 19h18a9 9 0 00-5.5-8.5A5 5 0 0012 2z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>
            </div>

            {{-- Upload Controls --}}
            <div class="flex-1 w-full text-center sm:text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                
                <div class="flex flex-col gap-3">
                    <input type="file" wire:model="profile_picture"
                        class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 cursor-pointer transition">

                    @error('profile_picture')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div class="flex flex-wrap gap-3 mt-1 justify-center sm:justify-start">
                        <button wire:click="uploadProfilePicture"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Upload New
                        </button>

                        @if (Auth::user()->profile->profile_picture || $profile_picture)
                            <button wire:click="confirmRemovePicture"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-red-600 uppercase tracking-widest shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Remove
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- SECURITY / PASSWORD SECTION --}}
        <div class="pt-6 border-t border-gray-100">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Security</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- NEW PASSWORD FIELD --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" 
                            wire:model.blur="password" 
                            placeholder="••••••••" 
                            class="w-full rounded-lg shadow-sm transition duration-150 ease-in-out px-4 py-3 
                            @error('password') 
                                border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 
                            @else 
                                border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 
                            @enderror">
                        
                        {{-- Error Message Display --}}
                        @error('password') 
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- CONFIRM PASSWORD FIELD --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        {{-- 
                            Note: The 'confirmed' rule puts the error on the main 'password' field, 
                            so we don't usually need @error here, but we can add a simple check 
                            if you want this box to turn red too when there is a mismatch. 
                        --}}
                        <input type="password" 
                            wire:model.blur="password_confirmation" 
                            placeholder="••••••••" 
                            class="w-full rounded-lg shadow-sm transition duration-150 ease-in-out px-4 py-3
                            @error('password') 
                                border-gray-300 focus:border-blue-500 focus:ring-blue-500
                            @else 
                                border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 
                            @enderror">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="updatePassword"
                        class="px-5 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 transition shadow-sm">
                        Update Password
                    </button>
                </div>
            </div>
        </div>

        {{-- PERSONAL INFORMATION --}}
        <div class="space-y-6">
            <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" wire:model.defer="fname" 
                        class="w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                    <input type="text" wire:model.defer="mname" 
                        class="w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" wire:model.defer="lname" 
                        class="w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">#</span>
                        </div>
                        <input type="text" wire:model.defer="contact_no" 
                            class="pl-8 w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out">
                    </div>
                </div>

                @if ($role === 'user')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Civil Status</label>
                        <select wire:model.defer="civil_status" 
                            class="w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out">
                            <option value="">Select status</option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Separated</option>
                            <option>Widowed</option>
                        </select>
                    </div>
                @endif
            </div>
        </div>

        

        {{-- MAIN SAVE ACTION --}}
        <div class="flex justify-end pt-4 border-t border-gray-100">
            <button wire:click="save"
                class="flex items-center gap-2 px-8 py-3 bg-green-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    {{-- MODAL (CONFIRM REMOVE PICTURE) --}}
    @if ($showRemovePictureConfirm)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" 
                     aria-hidden="true" 
                     wire:click="$set('showRemovePictureConfirm', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Remove Profile Picture</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to remove your profile picture? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="removeProfilePicture" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Remove
                        </button>
                        <button wire:click="$set('showRemovePictureConfirm', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>