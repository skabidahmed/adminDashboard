<x-layouts.app>
<div class="mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create Authorization Group Profile</h1>
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Declare a fresh configuration role profile and map structural capabilities.</p>
    </div>

    <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role Identification Label</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Content Moderator or Support Agent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <label class="text-sm font-semibold text-gray-900 dark:text-white">Map Privilege Boundaries Checklist</label>
                <button type="button" onclick="toggleAllPermissions(true)" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">Select All System Privileges</button>
            </div>
            
            <!-- Matrix Component Grid Split -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700/50">
                @foreach($permissions as $permission)
                    <div class="flex items-start p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="permissions[]" id="perm_{{ $permission->id }}" value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }} class="perm-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="perm_{{ $permission->id }}" class="font-medium text-gray-700 dark:text-gray-300 select-none block cursor-pointer text-xs font-mono">{{ $permission->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('roles.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</a>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Save Group Settings</button>
        </div>
    </form>
</div>

<script>
    function toggleAllPermissions(checked) {
        document.querySelectorAll('.perm-checkbox').forEach(box => {
            box.checked = checked;
        });
    }
</script>
</x-layouts.app>
