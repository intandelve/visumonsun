<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Wind Speed Data') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <h3 class="text-2xl font-semibold mb-6">
                Editing data for: <span class="text-blue-600">{{ $wind_speed->month_name }}</span>
            </h3>

            <form method="POST" action="{{ route('admin.wind_data.update', $wind_speed->id) }}">
                @csrf
                @method('PATCH')

                <div>
                    <label for="month_name" class="block font-medium text-sm text-gray-700">Month</label>
                    <input id="month_name" name="month_name" type="text" value="{{ $wind_speed->month_name }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly disabled>
                </div>

                <div class="mt-4">
                    <label for="speed_ms" class="block font-medium text-sm text-gray-700">Speed (m/s)</label>
                    <input id="speed_ms" name="speed_ms" type="number" step="0.01" value="{{ $wind_speed->speed_ms }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.wind_data.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        Cancel
                    </a>
                    
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>