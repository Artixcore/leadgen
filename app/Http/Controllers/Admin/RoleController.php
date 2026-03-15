<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate(['permissions' => ['sometimes', 'array'], 'permissions.*' => ['string', 'exists:permissions,name']]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('status', __('Role permissions updated.'));
    }
}
