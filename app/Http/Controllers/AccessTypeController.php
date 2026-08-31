<?php

/*
|--------------------------------------------------------------------------
| Developed by: Moniruzzaman Monir
| Email: monir112761@gmail.com
| Website: https://rcit-solution.com
|--------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use App\Models\AccessType;
use Illuminate\Http\Request;

class AccessTypeController extends Controller
{
    protected function ensureDefaultAccessTypes(): void
    {
        $plans = [
            ['name' => 'FREE', 'code' => 'FREE', 'description' => 'Default free access type', 'fee' => 0, 'is_active' => true],
            ['name' => 'ST-1', 'code' => 'ST-1', 'description' => 'Starter access plan', 'fee' => 100, 'is_active' => true],
            ['name' => 'SR-2', 'code' => 'SR-2', 'description' => 'Silver recommended plan', 'fee' => 300, 'is_active' => true],
            ['name' => 'ST-3', 'code' => 'ST-3', 'description' => 'Premium access plan', 'fee' => 500, 'is_active' => true],
        ];

        foreach ($plans as $plan) {
            AccessType::updateOrCreate(
                ['code' => $plan['code']],
                [
                    'name' => $plan['name'],
                    'description' => $plan['description'],
                    'fee' => (float) $plan['fee'],
                    'is_active' => $plan['is_active'],
                ]
            );
        }
    }

    public function index(Request $request)
    {
        $this->ensureDefaultAccessTypes();

        $accessTypes = AccessType::latest()->get();
        $editingAccessType = $request->filled('edit') ? AccessType::find($request->edit) : null;

        return view('admin.access-types.index', compact('accessTypes', 'editingAccessType'));
    }

    public function apiIndex()
    {
        return response()->json(AccessType::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:access_types,code'],
            'description' => ['nullable', 'string'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        AccessType::create($validated);

        return redirect()->route('admin.access-types')->with('success', 'Access type created successfully.');
    }

    public function update(Request $request, AccessType $accessType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:access_types,code,'.$accessType->id],
            'description' => ['nullable', 'string'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $accessType->update($validated);

        return redirect()->route('admin.access-types')->with('success', 'Access type updated successfully.');
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:access_types,code'],
            'description' => ['nullable', 'string'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        return response()->json(AccessType::create($validated), 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $accessType = AccessType::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:access_types,code,'.$accessType->id],
            'description' => ['nullable', 'string'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $accessType->update($validated);

        return response()->json($accessType);
    }

    public function destroy(AccessType $accessType)
    {
        $accessType->delete();

        return redirect()->route('admin.access-types')->with('success', 'Access type deleted successfully.');
    }

    public function apiDestroy($id)
    {
        $accessType = AccessType::findOrFail($id);
        $accessType->delete();

        return response()->json(['message' => 'Access type deleted']);
    }
}
