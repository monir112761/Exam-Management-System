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
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function apiIndex()
    {
        return response()->json(Permission::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'label' => ['nullable', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Permission::create($validated);

        return redirect()->route('admin.permissions')->with('success', 'Permission created successfully.');
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->id],
            'label' => ['nullable', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions')->with('success', 'Permission deleted successfully.');
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'label' => ['nullable', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->id],
            'label' => ['nullable', 'string', 'max:255'],
            'group_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $permission->update($validated);

        return response()->json($permission);
    }

    public function apiDestroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted']);
    }
}
