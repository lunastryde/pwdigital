<div class="bg-white rounded-2xl shadow p-6">

    <div class="mt-6 overflow-x-auto">
        <div class="min-w-[600px] rounded-lg ring-1 ring-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-blue-400 text-white">
                    <tr>
                        <th class="px-8 py-2 text-left rounded-tl-lg">Application Type</th>
                        <th class="px-8 py-2 text-left">Date Applied</th>
                        <th class="px-8 py-2 text-left">Status</th>
                        <th class="px-8 py-2 text-left rounded-tr-lg">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-8 py-3 text-gray-800">
                                {{ $app->type }}
                            </td>
                            <td class="px-9 py-3 text-gray-600">
                                @php
                                    try {
                                        $dt = \Illuminate\Support\Carbon::parse($app->date);
                                    } catch (\Throwable $e) {
                                        $dt = null;
                                    }
                                @endphp
                                {{ $dt ? $dt->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($app->status === 'approved') bg-green-100 text-green-700
                                    @elseif($app->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button type="button" class="px-3 py-1.5 text-xs font-medium rounded-md bg-blue-500 text-white hover:bg-blue-600">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                                You have no applications yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
