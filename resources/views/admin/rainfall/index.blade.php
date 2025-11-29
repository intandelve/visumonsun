<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <h3 class="text-2xl font-semibold mb-4">Rainfall Data Management</h3>
            
            <p class="mb-6">
                Ini adalah data yang saat ini ditampilkan di Halaman Statistik Anda.
            </p>

            <div class="mb-4">
                <a href="{{ route('rainfall.create') }}" class="inline-block rounded bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    Add Data
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">ID</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Month</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Rainfall (mm)</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Action</th>
                        </tr>
                    </thead>
    
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($rainfall_data as $data)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $data->id }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $data->month_name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $data->rainfall_mm }}</td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('rainfall.edit', $data->id) }}"
                                           class="inline-flex items-center gap-2 rounded px-3 py-1 text-sm font-medium"
                                           title="Edit"
                                           style="background:#4f46e5;color:#ffffff;padding:6px 10px;border-radius:6px;display:inline-flex;align-items:center;z-index:10;min-width:56px;text-align:center;">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('rainfall.destroy', $data->id) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded bg-red-600 px-3 py-1 text-sm font-medium text-white hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>