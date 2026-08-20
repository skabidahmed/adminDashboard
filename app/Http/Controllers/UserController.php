<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a paginated collection list of users.
     */
    public function index(): View
    {
        // Paginate by 10 users as implemented natively in the video
        $users = User::with('roles')->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the creation entry form interface.
     */
    public function create(): View
    {
        // Laravel 13 allows querying roles while stripping out the Super Admin role choice safely
        $roles = Role::where('name', '!=', 'super_admin')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly initialized user object.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address'      => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password'     => ['required', 'string', 'min:8'],
            'roles'        => ['nullable', 'array'],
        ]);

        $user = User::create([
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'email'        => $validated['email'],
            'address'      => $validated['address'],
            'phone_number' => $validated['phone_number'],
            'password'     => bcrypt($validated['password']), // Secure encryption hashing layer
        ]);

        if (! empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User successfully populated!');
    }

    /**
     * Display the modification user entry view.
     */
    public function edit(User $user): View
    {
        $roles     = Role::where('name', '!=', 'super_admin')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Process changes to an existing database entity record.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address'      => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password'     => ['nullable', 'string', 'min:8'],
            'roles'        => ['nullable', 'array'],
        ]);

        $user->update([
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'email'        => $validated['email'],
            'address'      => $validated['address'],
            'phone_number' => $validated['phone_number'],
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        $user->syncRoles($validated['roles'] ?? []);

        return redirect()->route('users.index')->with('success', 'User fields safely modified!');
    }

    /**
     * Delete the specified instance safely.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
