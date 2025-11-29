<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Forecast Data') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <h3 class="text-2xl font-semibold mb-6">
                Editing: <span class="text-blue-600">{{ $forecast->title }}</span>
            </h3>

            <form method="POST" action="{{ route('admin.forecasts.update', $forecast->id) }}">
                @csrf
                @method('PATCH')

                <div>
                    <label for="data_type" class="block font-medium text-sm text-gray-700">Data Type</label>
                    <input id="data_type" name="data_type" type="text" value="{{ $forecast->data_type }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required placeholder="e.g. Rainfall, Wind, etc.">
                </div>

                <div class="mt-4">
                    <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                    <input id="title" name="title" type="text" value="{{ $forecast->title }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="mt-4">
                    <label for="content" class="block font-medium text-sm text-gray-700">Content</label>
                    <textarea id="content" name="content" rows="6" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ $forecast->content }}</textarea>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.forecasts.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
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