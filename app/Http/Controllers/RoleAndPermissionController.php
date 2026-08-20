<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

class RoleAndPermissionController extends Controller
{
    /**
     * Display a collection list of system roles.
     */
    public function index(): View
    {
        // Simple pagination for administration groups
        $roles = Role::with('permissions')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the creation entry interface layout.
     */
    public function create(): View
    {
        // Load all available system privileges
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role model instance.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        // Enforce a standardized snake_case format as shown in the video setup
        $roleName = strtolower(str_replace(' ', '_', $validated['name']));

        $role = Role::create([
            'name'       => $roleName,
            'guard_name' => 'web'
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role generated successfully!');
    }

    /**
     * Display the modification user group view.
     */
    public function edit(Role $role): View
    {
        // Prevent editing the core Super Admin framework tier via web panel UI
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')->with('error', 'The core system tier cannot be altered.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Process configuration modifications for an existing record.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')->with('error', 'The core system tier cannot be altered.');
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
        ]);

        $roleName = strtolower(str_replace(' ', '_', $validated['name']));

        $role->update([
            'name' => $roleName,
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);
        
        // Force-clear Spatie cache tables to instantly apply new configuration rules globally
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Role configurations updated safely.');
    }

    /**
     * Delete the specified instance.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')->with('error', 'The core system tier cannot be deleted.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role purged from system databases.');
    }
}
