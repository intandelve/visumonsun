<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Rainfall Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-2xl font-semibold mb-6">Add New Rainfall Data</h3>

                    <form method="POST" action="{{ route('rainfall.store') }}">
                        @csrf

                        <div>
                            <label for="month_name" class="block font-medium text-sm text-gray-700">Month</label>
                            <select id="month_name" name="month_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="" disabled selected>Select a month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="rainfall_mm" class="block font-medium text-sm text-gray-700">Rainfall (mm)</label>
                            <input id="rainfall_mm" name="rainfall_mm" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                Cancel
                            </a>
                            
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                                Add Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>