<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-4">Welcome to your dashboard</h3>
                <p class="text-gray-700">This is the user dashboard. From here you can access public pages and user-specific content.</p>

                <div class="mt-6">
                    <a href="{{ url('/statistics') }}" class="inline-block rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">View Statistics</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
