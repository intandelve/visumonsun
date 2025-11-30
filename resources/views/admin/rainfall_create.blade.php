@ -1,64 +0,0 @@
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Rainfall Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-2xl font-semibold mb-6">New rainfall observation</h3>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                            <ul class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('rainfall.store') }}">
                        @csrf

                        <div>
                            <label for="station_name" class="block font-medium text-sm text-gray-700">Station</label>
                            <input id="station_name" name="station_name" type="text" value="{{ old('station_name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-4">
                            <label for="observed_at" class="block font-medium text-sm text-gray-700">Date & time</label>
                            <input id="observed_at" name="observed_at" type="datetime-local" value="{{ old('observed_at') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-4">
                            <label for="rainfall_mm" class="block font-medium text-sm text-gray-700">Rainfall (mm)</label>
                            <input id="rainfall_mm" name="rainfall_mm" type="number" step="0.1" value="{{ old('rainfall_mm') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block font-medium text-sm text-gray-700">Notes (optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.rainfall.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>

                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                                Create
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>