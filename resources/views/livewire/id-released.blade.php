<div class="bg-white rounded-2xl shadow p-6">
    <h3 class="text-xl font-semibold text-gray-800">Released IDs</h3>
    <p class="mt-2 text-gray-700">List of finalized / released ID applications. Use <strong>View / Print ID Card</strong> to print.</p>

    <div class="mt-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Applicant ID</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Date Issued</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($items as $it)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $it->applicant_id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ trim(($it->fname ?? '') . ' ' . ($it->mname ?? '') . ' ' . ($it->lname ?? '')) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $it->date_issued ? \Illuminate\Support\Carbon::parse($it->date_issued)->format('M d, Y') : '—' }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{-- View / Print: opens print route in new tab --}}
                                <a href="{{ route('admin.form_personal.print', ['id' => $it->applicant_id]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-800 text-white text-xs">View / Print ID Card</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">No released IDs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
