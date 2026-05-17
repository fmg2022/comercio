<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.rolePermission.index', [
            'roles' => Role::whereNotIn('name', ['Super Admin'])->orderBy('name', 'asc')->get(['id', 'name']),
            'permissions' => Permission::orderBy('name', 'asc')->get(['id', 'name']),
            'users' => User::withoutRole('Super Admin')->orderBy('name', 'asc')->get(['id', 'name', 'surname']),
        ]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $permissions = Permission::whereIn('id', $validated['permissions_ids'])->get();
        $role = Role::create(['name' => $validated['name']]);
        $role->givePermissionTo($permissions);

        return redirect()->route('roles.index');
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();
        $permissions = Permission::whereIn('id', $validated['permissions_ids'])->get();
        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->route('roles.index');
    }

    public function fetch(String $id): JsonResponse
    {
        $role = Role::with('permissions:id')->findOrFail($id, ['id', 'name']);

        $role->permissions_ids = $role->permissions->pluck('id');
        $role->unsetRelation('permissions');


        return response()->json($role);
    }
}
