<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a paginated list of posts with authors eager-loaded.
     */
    public function index(): View
    {
        $posts = Post::with('author')->paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the creation form.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post securely linked to the authenticated user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        Post::create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(), // Maps to the author parameter setup
        ]);

        return redirect()->route('posts.index')->with('success', 'Post published successfully!');
    }

    /**
     * Show the edit form for a specific post.
     */
    public function edit(Post $post): View
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the post payload.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Post fields updated safely.');
    }

    /**
     * Remove the post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post); // Ensure the user has permission to delete
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted permanently.');
    }
}
