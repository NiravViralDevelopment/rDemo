<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $permissions = Permission::query()->orderBy('name')->paginate(20);

        return view('permissions.index', compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    public function create(): View
    {
        return view('permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module_name' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:150'],
        ]);

        $created = [];
        $moduleName = $this->normalizeModuleName((string) ($validated['module_name'] ?? ''));
        $singleName = trim((string) ($validated['name'] ?? ''));

        if ($moduleName === '' && $singleName === '') {
            return back()->withErrors([
                'module_name' => 'Enter a module name or a permission name.',
            ])->withInput();
        }

        if ($moduleName !== '') {
            foreach (['list', 'create', 'edit', 'delete'] as $action) {
                $permissionName = $moduleName . '-' . $action;
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
                $created[] = $permissionName;
            }
        }

        if ($singleName !== '') {
            Permission::firstOrCreate([
                'name' => $singleName,
                'guard_name' => 'web',
            ]);
            $created[] = $singleName;
        }

        return redirect()->route('permissions.index')
            ->with('message', 'Permission created successfully: ' . implode(', ', array_unique($created)));
    }

    public function edit(int $id): View
    {
        $permission = Permission::query()->findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $permission = Permission::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:permissions,name,' . $permission->id],
        ]);

        $permission->update([
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('permissions.index')->with('message', 'Permission updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $permission = Permission::query()->findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')->with('message', 'Permission deleted successfully.');
    }

    private function normalizeModuleName(string $moduleName): string
    {
        $moduleName = strtolower(trim($moduleName));
        $moduleName = preg_replace('/[^a-z0-9]+/', '-', $moduleName) ?? '';

        return trim($moduleName, '-');
    }
}
