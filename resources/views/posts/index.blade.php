<x-layouts.app>
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Content Library</h1>
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Manage all articles and publications created inside the platform.</p>
        </div>
        @can('create post')
            <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg dark:bg-blue-500 dark:hover:bg-blue-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Post
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800/80">
                <tr>
                    <th scope="col" class="px-6 py-3 text-gray-500 dark:text-gray-400 text-left">ID</th>
                    <th scope="col" class="px-6 py-3 text-gray-500 dark:text-gray-400 text-left">Title</th>
                    <th scope="col" class="px-6 py-3 text-gray-500 dark:text-gray-400 text-left">Author</th>
                    <th scope="col" class="px-6 py-3 text-gray-500 dark:text-gray-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($posts as $post)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $post->id }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $post->title }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white">{{ $post->author->first_name . " " . $post->author->last_name ?? 'System Guest' }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-900 dark:text-white text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-3">
                                @can('update post')
                                    <a href="{{ route('posts.edit', $post->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline inline-flex items-center">
                                        Edit
                                    </a>
                                @endcan
                                
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Confirm permanent deletion of this content?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No content posts published yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</div>
</x-layouts.app>
