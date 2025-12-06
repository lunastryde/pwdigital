<div>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-2">
            <h3 class="text-xl font-semibold text-gray-800">Manage User Accounts</h3>
            {{-- Search bar --}}
            <div class="relative max-w-sm">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search by email or username..."
                >
                <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-gray-400">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.25 5.25a7.5 7.5 0 0 0 11.4 11.4Z" />
                    </svg>
                </span>
            </div>
        </div>

        <button
            type="button"
            wire:click="toggleCreateForm" 
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ $showCreateForm ? 'Cancel' : 'Create New User' }}
        </button>
    </div>


    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-red-800">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($showCreateForm)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Create New User Account</h4>
            
            <form wire:submit.prevent="createAccount">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" wire:model.defer="email" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="user@example.com">
                        @error('email') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" wire:model.defer="username" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="username">
                        @error('username') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative mt-1">
                            <input
                                id="admin_password"
                                type="password"
                                wire:model.defer="password"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <button type="button" onclick="togglePassword('admin_password', 'eye_1', 'slash_1')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                                <svg id="eye_1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="slash_1" class="hidden w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            At least 8 characters, with 1 uppercase, 1 lowercase, and 1 number.
                        </p>
                        @error('password') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative mt-1">
                            <input
                                id="admin_confirm_password"
                                type="password"
                                wire:model.defer="password_confirmation"
                                class="block w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <button type="button" onclick="togglePassword('admin_confirm_password', 'eye_2', 'slash_2')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                                <svg id="eye_2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="slash_2" class="hidden w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h5 class="text-sm font-semibold text-gray-900 mb-3">Personal Information</h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" wire:model.defer="first_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase">
                            @error('first_name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" wire:model.defer="middle_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase"
                                placeholder="Optional">
                            @error('middle_name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" wire:model.defer="last_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase">
                            @error('last_name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <input type="text" wire:model.defer="contact_no" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="09XXXXXXXXX">
                            @error('contact_no') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                            <select wire:model.defer="sex" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('sex') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" wire:model.live="birthdate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('birthdate') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age (Auto)</label>
                            <input type="number" wire:model="age" disabled
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="toggleCreateForm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Create User Account
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Username</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Role</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Created</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($accounts as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">
                            {{ trim((optional($account->profile)->fname ?? '') . ' ' . (optional($account->profile)->mname ?? '') . ' ' . (optional($account->profile)->lname ?? '')) }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->username }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                {{ ucfirst($account->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($account->is_active)
                                <!-- Active → Show Deactivate button -->
                                <button wire:click="confirmDeactivate({{ $account->id }})"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Deactivate
                                </button>
                            @else
                                <!-- Inactive → Show Reactivate button -->
                                <button wire:click="confirmReactivate({{ $account->id }})"
                                        class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Reactivate
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No user accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- CONFIRMATION MODAL --}}
        @if ($showDeactivateConfirm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Confirm Deactivation</h2>
                <p class="mb-6">
                    Are you sure you want to deactivate this account?
                    The user will no longer be able to log in.
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('showDeactivateConfirm', false)"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <button
                        wire:click="deactivateUser"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                        Deactivate
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if ($showReactivateConfirm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Reactivate Account</h2>
                <p class="mb-6">
                    Are you sure you want to reactivate this account?
                    The user will be able to log in again.
                </p>

                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showReactivateConfirm', false)"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <button wire:click="reactivateUser"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Reactivate
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function togglePassword(inputId, eyeId, slashId) {
        var input = document.getElementById(inputId);
        var eye = document.getElementById(eyeId);
        var slash = document.getElementById(slashId);

        if (input.type === "password") {
            input.type = "text";
            eye.classList.add("hidden");
            slash.classList.remove("hidden");
        } else {
            input.type = "password";
            eye.classList.remove("hidden");
            slash.classList.add("hidden");
        }
    }
</script>