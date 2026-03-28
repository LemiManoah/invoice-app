<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final readonly class RoleController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class); // Reusing user viewing permission

        $roles = Role::query()
            ->withCount('permissions')
            ->orderBy('name')
            ->paginate(15);

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        $permissions = Permission::all()->groupBy(fn ($permission) => explode('.', $permission->name)[0]);

        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->validated('name'), 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->validated('permissions'));
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $this->authorize('update', User::class);

        $permissions = Permission::all()->groupBy(fn ($permission) => explode('.', $permission->name)[0]);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')->with('error', 'The Admin role cannot be modified.');
        }

        $role->update(['name' => $request->validated('name')]);

        $role->syncPermissions($request->input('permissions', []));

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', User::class);

        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')->with('error', 'The Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
