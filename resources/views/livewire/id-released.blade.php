<div">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Released IDs</h3>
            <p class="mt-1 text-gray-700 text-sm">List of finalized / released ID applications.</p>
        </div>

        <div>
            <select wire:model.live="selectedYear" 
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md cursor-pointer">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

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
                                <a href="{{ route('admin.form_personal.print', ['id' => $it->applicant_id]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-800 text-white text-xs hover:bg-gray-700 transition">View / Print ID Card</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                @if($selectedYear)
                                    No released IDs found for {{ $selectedYear }}.
                                @else
                                    No released IDs yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>