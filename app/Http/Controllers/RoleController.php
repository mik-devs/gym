<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a paginated list of roles.
     */
    public function index(): Response
    {
        return Inertia::render('roles/Index', [
            'roles' => Role::withCount(['permissions', 'users'])->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a role.
     */
    public function create(): Response
    {
        return Inertia::render('roles/Create', [
            'permissions' => Permission::pluck('name'),
        ]);
    }

    /**
     * Store a newly created role and its permissions.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role): Response
    {
        return Inertia::render('roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ],
            'permissions' => Permission::pluck('name'),
        ]);
    }

    /**
     * Update a role and its permissions.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,'.$role->id],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        if ($role->name === 'Admin' && $validated['name'] !== 'Admin') {
            return back()->with('error', 'The Admin role cannot be renamed.');
        }

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Delete an unused role unless it is the protected Admin role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'Admin') {
            return back()->with('error', 'The Admin role cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'A role assigned to users cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
