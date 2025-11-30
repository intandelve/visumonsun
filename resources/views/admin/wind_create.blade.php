@ -1,68 +0,0 @@
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Wind Map Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-2xl font-semibold mb-6">New wind dataset</h3>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                            <ul class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.wind.store') }}">
                        @csrf

                        <div>
                            <label for="data_type" class="block font-medium text-sm text-gray-700">Data type</label>
                            <select id="data_type" name="data_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="current_wind" {{ old('data_type') == 'current_wind' ? 'selected' : '' }}>Current Wind</option>
                                <option value="historical_wind" {{ old('data_type') == 'historical_wind' ? 'selected' : '' }}>Historical</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-4">
                            <label for="file_path" class="block font-medium text-sm text-gray-700">File path (relative to `public/`)</label>
                            <input id="file_path" name="file_path" type="text" value="{{ old('file_path', 'assets/data/era5_wind.json') }}" placeholder="assets/data/era5_wind.json" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1">Prefer storing a small pointer (e.g. <code>assets/data/era5_wind.json</code>). Avoid inserting large JSON directly into the DB.</p>
                        </div>

                        <div class="mt-4">
                            <label for="ref_time" class="block font-medium text-sm text-gray-700">Reference time</label>
                            <input id="ref_time" name="ref_time" type="datetime-local" value="{{ old('ref_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block font-medium text-sm text-gray-700">Notes (optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.wind.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>

                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>