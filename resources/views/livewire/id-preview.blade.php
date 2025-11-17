<div>
    @if($showModal && $form)
        <div class="fixed inset-0 bg-black/50 z-40"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl overflow-auto">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">ID Preview — {{ $form->fname }} {{ $form->lname }}</h3>
                    <div class="flex items-center gap-2">
                        <button wire:click="close" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Close</button>
                        <button wire:click="release" class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">Release</button>
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
                        <strong>Note:</strong> Press <span class="font-semibold">Release</span> to finalize the ID issuance. This will set the application status to <em>Finalized</em> and set <code>date_issued</code> to the current time.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>