<div>
    <!-- Header with Create Button -->
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-xl font-semibold text-gray-800">Manage Accounts</h3>
        <button type="button" wire:click="toggleCreateForm" 
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ $showCreateForm ? 'Cancel' : 'Create New Account' }}
        </button>
    </div>

    <!-- Success/Error Messages -->
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

    <!-- Create Account Form -->
    @if($showCreateForm)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Create New Account</h4>
            
            <form wire:submit.prevent="createAccount">
                <!-- Account Type Selector -->
                <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Account Type</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors
                            {{ $identifier == 3 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="identifier" value="3" class="sr-only">
                            <div class="flex-1 text-center">
                                <div class="text-2xl mb-2">👤</div>
                                <div class="text-sm font-medium text-gray-900">User</div>
                                <div class="text-xs text-gray-500 mt-1">Regular user account</div>
                            </div>
                        </label>
                        
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors
                            {{ $identifier == 2 ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="identifier" value="2" class="sr-only">
                            <div class="flex-1 text-center">
                                <div class="text-2xl mb-2">👔</div>
                                <div class="text-sm font-medium text-gray-900">Staff</div>
                                <div class="text-xs text-gray-500 mt-1">Staff member access</div>
                            </div>
                        </label>
                        
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors
                            {{ $identifier == 1 ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="identifier" value="1" class="sr-only">
                            <div class="flex-1 text-center">
                                <div class="text-2xl mb-2">⚙️</div>
                                <div class="text-sm font-medium text-gray-900">Admin</div>
                                <div class="text-xs text-gray-500 mt-1">Full system access</div>
                            </div>
                        </label>
                    </div>
                    @error('identifier') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Account Credentials -->
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
                        <input type="password" wire:model.defer="password" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Minimum 6 characters">
                        @error('password') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" wire:model.defer="password_confirmation" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Re-enter password">
                        @error('password_confirmation') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h5 class="text-sm font-semibold text-gray-900 mb-3">Personal Information</h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" wire:model.defer="first_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('first_name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" wire:model.defer="middle_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Optional">
                            @error('middle_name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" wire:model.defer="last_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="toggleCreateForm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Accounts List -->
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
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                {{ $account->identifier == 1 ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $account->identifier == 2 ? 'bg-green-100 text-green-700' : '' }}
                                {{ $account->identifier == 3 ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($account->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $account->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>