<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Forecast') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-2xl font-semibold mb-6">New forecast entry</h3>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                            <ul class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.forecasts.store') }}">
                        @csrf

                        <div>
                            <label for="data_type" class="block font-medium text-sm text-gray-700">Type</label>
                            <select id="data_type" name="data_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="seasonal_outlook" {{ old('data_type') == 'seasonal_outlook' ? 'selected' : '' }}>Seasonal Outlook</option>
                                <option value="monsoon_onset" {{ old('data_type') == 'monsoon_onset' ? 'selected' : '' }}>Monsoon Onset</option>
                                <option value="other" {{ old('data_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-4">
                            <label for="content" class="block font-medium text-sm text-gray-700">Content (Markdown allowed)</label>
                            <textarea id="content" name="content" rows="6"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('content') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.forecasts.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>

                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                                Create Forecast
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
