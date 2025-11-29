<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Management') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <h3 class="text-2xl font-semibold mb-4">Users Management</h3>
            
            <p class="mb-6">
                Ini adalah daftar semua akun yang memiliki akses ke Admin Side.
            </p>

            <div class="mb-4">
                <a href="{{ route('admin.users.create') }}" class="inline-block rounded bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    Add User
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">ID</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Name</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Email</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-medium text-gray-900">Action</th>
                        </tr>
                    </thead>
    
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $user->id }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $user->email }}</td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700
                                                            @if($user->id == Auth::id()) opacity-50 cursor-not-allowed @endif"
                                                    @if($user->id == Auth::id()) disabled @endif>
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