<div class="space-y-8 p-4 md:p-6">

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm" role="alert">
            <span class="font-medium">Success!</span> {{ session('message') }}
        </div>
    @endif

    {{-- Form for creating/editing announcements --}}
    <form wire:submit.prevent="save" class="p-6 bg-white rounded-lg shadow-md space-y-4 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-4">{{ $editing ? 'Edit Announcement' : 'Create New Announcement' }}</h2>
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" wire:model.defer="title" id="title" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea wire:model.defer="content" id="content" rows="5" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="expires_at" class="block text-sm font-medium text-gray-700">Expires At (Optional)</label>
            <input type="datetime-local" wire:model.defer="expires_at" id="expires_at" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <p class="text-xs text-gray-500 mt-1 pl-2">* Leave blank for the announcement to stay permanently.</p>
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model.defer="is_published" id="is_published" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <label for="is_published" class="ml-2 block text-sm text-gray-900">Publish Immediately</label>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t">
            @if($editing)
            <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-semibold">Cancel</button>
            @endif
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                <span wire:loading.remove wire:target="save">
                    Save Announcement
                </span>
                <span wire:loading wire:target="save">
                    Saving...
                </span>
            </button>
        </div>
    </form>

    {{-- List of existing announcements in a table --}}
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-xl font-semibold text-gray-700">Existing Announcements</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $announcement->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $isExpired = $announcement->expires_at && $announcement->expires_at->isPast();
                                    $statusClass = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
                                    if ($isExpired) {
                                        $statusText = 'Expired';
                                        $statusClass .= ' bg-red-100 text-red-800';
                                    } elseif ($announcement->is_published) {
                                        $statusText = 'Published';
                                        $statusClass .= ' bg-green-100 text-green-800';
                                    } else {
                                        $statusText = 'Draft';
                                        $statusClass .= ' bg-gray-100 text-gray-800';
                                    }
                                @endphp
                                <span class="{{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>Posted by: <span class="font-medium text-gray-700">{{ $announcement->poster->username ?? 'N/A' }}</span></div>
                                <div class="text-xs">
                                    @if($announcement->expires_at)
                                        Expires: {{ $announcement->expires_at->format('M d, Y, g:i A') }}
                                    @else
                                        Permanent
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                <button wire:click="edit({{ $announcement->announcement_id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="delete({{ $announcement->announcement_id }})" wire:confirm="Are you sure you want to delete this announcement?" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                No announcements have been created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>