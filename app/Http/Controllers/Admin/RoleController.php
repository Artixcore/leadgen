<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('admin.roles.index', ['roles' => $roles]);
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.edit', ['role' => $role, 'permissions' => $permissions]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->syncPermissions($request->validated('permissions', []));

        app(ActivityLogService::class)->log($request->user(), 'role.updated', $role, [
            'permissions' => $role->permissions->pluck('name')->all(),
        ]);

        return redirect()->route('admin.roles.index')->with('status', __('Role permissions updated.'));
    }
}
