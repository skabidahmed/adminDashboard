<x-layouts.app>


    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- Header Block Component Section -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">User Management</h1>
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">View and update system privileges assigned throughout your environment.</p>
            </div>
            
            <!-- Render element cleanly through custom structural permissions filters -->
            @can('create users')
                <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 transition duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New User
                </a>
            @endcan
        </div>

        <!-- Feedback Message Handling Blocks -->
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Flowbite Formatted Responsive Data Display Component Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/80">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NAME</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">EMAIL</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ADDRESS</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PHONE</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ROLES</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $user->first_name . " " . $user->last_name}}</td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $user->address }}</td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $user->phone_number }}</td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-2.5 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="px-2.5 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            No Assigned Roles
                                        </span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-3">
                                    @can('update users')
                                        <a href="{{ route('users.edit', $user->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    @endcan

                                    @can('delete users')
                                        <!-- Safe Form Action Wrapper mapping standard fallback requests -->
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Confirm permanent deletion of this record?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No active system users located.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Render Pagination Links -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts.app>
