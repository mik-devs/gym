<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display permissions grouped by their name prefix.
     */
    public function index(): Response
    {
        $permissions = Permission::withCount('roles')
            ->orderBy('name')
            ->paginate(20);

        return Inertia::render('permissions/Index', [
            'permissions' => $permissions,
            'grouped' => $permissions->getCollection()->groupBy(
                fn (Permission $permission): string => explode('.', $permission->name)[0],
            ),
        ]);
    }

    /**
     * Delete a permission that is not assigned to any roles.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete permission assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted.');
    }
}
