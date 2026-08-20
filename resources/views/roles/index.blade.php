<x-layouts.app>
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Roles & Privilege Configurations</h1>
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Establish logical grouping profiles and assign fine-grained capabilities.</p>
        </div>
        @can('create roles')
            <a href="{{ route('roles.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg dark:bg-blue-500 dark:hover:bg-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New System Role
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800/80 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left">ID</th>
                    <th scope="col" class="px-6 py-3 text-left">Role Key Target</th>
                    <th scope="col" class="px-6 py-3 text-left">Assigned Capability Matrix Breakdown</th>
                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($roles as $role)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $role->id }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white uppercase tracking-wider text-xs">
                            <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-900">{{ str_replace('_', ' ', $role->name) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xl">
                                @if($role->name === 'super_admin')
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 global-bypass-flag">
                                        * Global System Bypass Privilege Set Active *
                                    </span>
                                @else
                                    @forelse($role->permissions as $perm)
                                        <span class="px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            {{ $perm->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">No privileges linked to this group tier.</span>
                                    @endforelse
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-medium">
                            @if($role->name !== 'super_admin')
                                <div class="inline-flex items-center gap-3">
                                    @can('update roles')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-600 dark:text-blue-500 hover:underline">Edit Matrix</a>
                                    @endcan
                                    @can('delete roles')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Purge this role? All associated profiles will lose these configuration metrics.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic font-normal">Immutable Tier</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No organizational roles created inside the platform.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $roles->links() }}</div>
</div>
</x-layouts.app>
