<div>
    @if($showModal && $form)
        <div class="fixed inset-0 bg-black/50 z-40"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl overflow-auto">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">
                        ID Preview — {{ $form->fname }} {{ $form->lname }}
                    </h3>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->identifier == 1)
                            <button type="button"
                                    wire:click="$set('showMayorEditor', true)"
                                    class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm">
                                Edit Mayor
                            </button>
                        @endif

                        <button wire:click="close"
                                class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
                            Close
                        </button>
                        <button wire:click="release"
                                class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                            Release
                        </button>
                    </div>
                </div>

                <div class="p-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border rounded p-4">
                            <h4 class="font-medium mb-4">Front</h4>
                            <div class="flex justify-center">
                                @include('components.id-card-side', [
                                    'form' => $form,
                                    'side' => 'front',
                                    'preview' => true
                                ])
                            </div>
                        </div>

                        <div class="border rounded p-4">
                            <h4 class="font-medium mb-4">Back</h4>
                            <div class="flex justify-center">
                                @include('components.id-card-side', [
                                    'form' => $form,
                                    'side' => 'back',
                                    'preview' => true
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <strong>Note:</strong> Press <span class="font-semibold">Release</span>
                        to finalize the ID issuance. This will set the application status to
                        <em>Finalized</em> and set <code>date_issued</code> to the current time.
                    </div>
                </div>
            </div>
        </div>

        {{-- Mayor editor modal --}}
        @if($showMayorEditor)
            <div class="fixed inset-0 z-60 flex items-center justify-center bg-black/40">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Edit Mayor Details
                    </h3>

                    <form wire:submit.prevent="saveMayorSettings" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Mayor Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model.defer="mayorName"
                                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 uppercase
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('mayorName')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Mayor Title
                            </label>
                            <input type="text"
                                   wire:model.defer="mayorTitle"
                                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('mayorTitle')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Signature (PNG/JPG, optional)
                            </label>
                            <input type="file"
                                   wire:model="newSignature"
                                   accept="image/png,image/jpeg"
                                   class="mt-1 block w-full text-sm text-gray-700">
                            @error('newSignature')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-1">Current signature:</p>
                                <img src="{{ $this->mayorSignatureUrl }}"
                                     alt="Current signature"
                                     class="h-10 border border-gray-200 rounded">
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end gap-2">
                            <button type="button"
                                    wire:click="$set('showMayorEditor', false)"
                                    class="px-3 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-1.5 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
