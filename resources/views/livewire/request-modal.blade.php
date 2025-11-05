<div>
@if ($open && $request)
    <!-- Backdrop -->
    <div class="fixed inset-0 z-40 bg-black/40" wire:click.self="close"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4" wire:click.self="close">
        <div class="w-full h-full max-w-5xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Request Details</h3>
                    <p class="text-sm text-gray-600 mt-1">Request ID: {{ $selectedId }}</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 p-2" wire:click="close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">

                <!-- Applicant Info -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Applicant</h4>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500">Name</div>
                            <div class="text-base text-gray-900">
                                {{ $request->applicant->fname }} {{ $request->applicant->mname }} {{ $request->applicant->lname }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">PWD Number</div>
                            <div class="text-base text-gray-900">{{ $request->applicant->pwd_number }}</div>
                        </div>
                    </div>
                </div>

                <!-- Request Info -->
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Request Information</h4>

                    @if ($request->request_type === 'booklet' && $request->bookletRequest)
                        <div class="space-y-2">
                            <div><span class="text-sm text-gray-500">Booklet Type:</span>
                                <span class="text-base text-gray-900">{{ ucfirst($request->bookletRequest->booklet_type) }}</span>
                            </div>
                            <div><span class="text-sm text-gray-500">Reason:</span>
                                <span class="text-base text-gray-900">{{ $request->bookletRequest->reason_for_request }}</span>
                            </div>
                        </div>

                    @elseif ($request->request_type === 'device' && $request->deviceRequest)
                        <div class="space-y-2">
                            <div><span class="text-sm text-gray-500">Device Requested:</span>
                                <span class="text-base text-gray-900">{{ $request->deviceRequest->device_requested }}</span>
                            </div>
                            <div><span class="text-sm text-gray-500">Reason:</span>
                                <span class="text-base text-gray-900">{{ $request->deviceRequest->reason_for_request }}</span>
                            </div>
                            <div><span class="text-sm text-gray-500">Local Social Pension:</span>
                                <span class="text-base text-gray-900">
                                    {{ $request->deviceRequest->local_social_pension === 'Y' ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                    @elseif ($request->request_type === 'financial' && $request->financialRequest)
                        <div class="space-y-2">
                            <div><span class="text-sm text-gray-500">Reason:</span>
                                <span class="text-base text-gray-900">{{ $request->financialRequest->reason_for_request }}</span>
                            </div>
                        </div>

                    @elseif ($request->request_type === 'renewal' && $request->renewal)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h5 class="font-semibold text-gray-900 text-sm mb-3">Medical Certificate</h5>

                            @if ($request->renewal->file_medcert)
                                @php
                                    $metadata = $request->renewal->file_metadata['file_medcert'] ?? null;
                                    $originalName = $metadata['original_name'] ?? basename($request->renewal->file_medcert);
                                    $size = $metadata['size'] ?? null;
                                @endphp

                                <div class="flex items-center text-sm text-green-600 mb-3">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Uploaded
                                </div>

                                <div class="text-xs text-gray-600 space-y-1 mb-4">
                                    <div class="truncate font-medium">{{ $originalName }}</div>
                                    @if($size)
                                        <div class="text-gray-500">{{ number_format($size / 1024, 1) }} KB</div>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2">
                                    <a href="{{ Storage::url($request->renewal->file_medcert) }}"
                                    target="_blank"
                                    class="flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">
                                        View File
                                    </a>

                                    <a href="{{ Storage::url($request->renewal->file_medcert) }}"
                                    download="{{ $originalName }}"
                                    class="flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition">
                                        Download
                                    </a>
                                </div>
                            @else
                                <div class="flex items-center text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    No file uploaded
                                </div>
                            @endif
                        </div>

                    @elseif ($request->request_type === 'loss' && $request->loss)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <h5 class="font-semibold text-gray-900 text-sm mb-3">Affidavit of Loss</h5>

                            @if ($request->loss->file_affidavit)
                                @php
                                    $metadata = $request->loss->file_metadata['file_affidavit'] ?? null;
                                    $originalName = $metadata['original_name'] ?? basename($request->loss->file_affidavit);
                                    $size = $metadata['size'] ?? null;
                                @endphp

                                <div class="flex items-center text-sm text-green-600 mb-3">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Uploaded
                                </div>

                                <div class="text-xs text-gray-600 space-y-1 mb-4">
                                    <div class="truncate font-medium">{{ $originalName }}</div>
                                    @if($size)
                                        <div class="text-gray-500">{{ number_format($size / 1024, 1) }} KB</div>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2">
                                    <a href="{{ Storage::url($request->loss->file_affidavit) }}"
                                    target="_blank"
                                    class="flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">
                                        View File
                                    </a>

                                    <a href="{{ Storage::url($request->loss->file_affidavit) }}"
                                    download="{{ $originalName }}"
                                    class="flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition">
                                        Download
                                    </a>
                                </div>
                            @else
                                <div class="flex items-center text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    No file uploaded
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-gray-500">No request details found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
</div>
