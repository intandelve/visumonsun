<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wind Speed Data Management') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <h3 class="text-2xl font-semibold mb-4">Wind Speed Data Management</h3>
            
            <p class="mb-6">
                Ini adalah data yang saat ini ditampilkan di Halaman Statistik Anda.
            </p>

            <div class="mb-4">
                <a href="{{ route('admin.wind_data.create') }}" class="inline-block rounded bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    Add Data
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">ID</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Month</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Speed (m/s)</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Action</th>
                        </tr>
                    </thead>
    
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($wind_speed_data as $data)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $data->id }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $data->month_name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $data->speed_ms }}</td>
                                <td class="whitespace-nowrap px-4 py-2 flex items-center space-x-2">
                                    <a href="{{ route('admin.wind_data.edit', $data->id) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                        Edit
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.wind_data.destroy', $data->id) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>