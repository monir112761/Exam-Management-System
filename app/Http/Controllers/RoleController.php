<?php

/*
|--------------------------------------------------------------------------
| Developed by: Moniruzzaman Monir
| Email: monir112761@gmail.com
| Website: https://rcit-solution.com
|--------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->latest()->get();
        $permissions = Permission::orderBy('group_name')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function apiIndex()
    {
        return response()->json(Role::with('permissions')->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'label' => $validated['label'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        if (! empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles')->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'label' => $validated['label'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles')->with('success', 'Role deleted successfully.');
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'label' => $validated['label'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        if (! empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json($role->load('permissions'), 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'label' => $validated['label'] ?? $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return response()->json($role->load('permissions'));
    }

    public function apiDestroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted']);
    }
}
