<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{
    public function houses()
    {
        return view('properties.houses_index');
    }

    public function housesData()
    {
        $query = Property::query()
            ->with('project')
            ->where('type', 'house')
            ->latest('id');

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        return DataTables::eloquent($query)
            ->addColumn('project_name', fn (Property $property) => $property->project?->name ?? 'GLOBAL')
            ->addColumn('status_badge', function (Property $property) {
                $statusClass = match ($property->status) {
                    'available' => 'status-available',
                    'booked' => 'status-booked',
                    default => 'status-inactive',
                };
                return '<span class="property-pill ' . $statusClass . '">' . ucfirst($property->status) . '</span>';
            })
            ->addColumn('created_on', fn (Property $property) => optional($property->created_at)->format('Y-m-d'))
            ->addColumn('action', function (Property $property) {
                $editUrl = route('properties.edit', $property->id);
                $deleteUrl = route('properties.destroy', $property->id);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit House">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete House" onclick="return confirm(\'Delete this house?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['status_badge', 'action'])
            ->toJson();
    }

    public function shops()
    {
        return view('properties.shops_index');
    }

    public function shopsData()
    {
        $query = Property::query()
            ->with('project')
            ->where('type', 'shop')
            ->latest('id');

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        return DataTables::eloquent($query)
            ->addColumn('project_name', fn (Property $property) => $property->project?->name ?? 'GLOBAL')
            ->addColumn('status_badge', function (Property $property) {
                $statusClass = match ($property->status) {
                    'available' => 'status-available',
                    'booked' => 'status-booked',
                    default => 'status-inactive',
                };

                return '<span class="property-pill ' . $statusClass . '">' . ucfirst($property->status) . '</span>';
            })
            ->addColumn('created_on', fn (Property $property) => optional($property->created_at)->format('Y-m-d'))
            ->addColumn('action', function (Property $property) {
                $editUrl = route('properties.edit', $property->id);
                $deleteUrl = route('properties.destroy', $property->id);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm property-action-btn action-edit" title="Edit Shop">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm property-action-btn action-delete" title="Delete Shop" onclick="return confirm(\'Delete this shop?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['status_badge', 'action'])
            ->toJson();
    }

    public function create()
    {
        $user = Auth::user();
        $projects = Project::query()
            ->when(! empty($user->project_id), fn ($q) => $q->where('id', $user->project_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($projects->isEmpty()) {
            return redirect()->route('houses.index')->with('error', 'No active project available for property creation.');
        }

        return view('properties.create', compact('projects', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'type' => ['required', 'in:house,shop'],
            'title' => ['required', 'string', 'max:255'],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:20'],
            'area_sqft' => ['nullable', 'integer', 'min:0'],
            'price_per_day' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,booked,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        // Project user can create only in own project
        if (! empty($user->project_id) && (int) $validated['project_id'] !== (int) $user->project_id) {
            abort(403, 'You can only create properties in your assigned project.');
        }

        $validated['code'] = $this->generateNextCode((int) $validated['project_id'], $validated['type']);
        $validated['city'] = null;
        $validated['address'] = null;
        $validated['bedrooms'] = $validated['type'] === 'house' ? ($validated['bedrooms'] ?? null) : null;
        $validated['price_per_day'] = $validated['price_per_day'] ?? 0;

        Property::query()->create($validated);

        $redirectRoute = $validated['type'] === 'shop' ? 'shops.index' : 'houses.index';

        return redirect()->route($redirectRoute)->with('message', 'Property created successfully.');
    }

    public function edit(int $id)
    {
        $property = Property::query()->findOrFail($id);
        $this->authorizePropertyAccess($property);

        $user = Auth::user();
        $projects = Project::query()
            ->when(! empty($user->project_id), fn ($q) => $q->where('id', $user->project_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('properties.edit', compact('property', 'projects', 'user'));
    }

    public function update(Request $request, int $id)
    {
        $property = Property::query()->findOrFail($id);
        $this->authorizePropertyAccess($property);

        $user = Auth::user();
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'type' => ['required', 'in:house,shop'],
            'title' => ['required', 'string', 'max:255'],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:20'],
            'area_sqft' => ['nullable', 'integer', 'min:0'],
            'price_per_day' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,booked,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        if (! empty($user->project_id) && (int) $validated['project_id'] !== (int) $user->project_id) {
            abort(403, 'You can only edit properties in your assigned project.');
        }

        $validated['city'] = null;
        $validated['address'] = null;
        $validated['bedrooms'] = $validated['type'] === 'house' ? ($validated['bedrooms'] ?? null) : null;
        $validated['price_per_day'] = $validated['price_per_day'] ?? 0;

        $property->update($validated);

        $redirectRoute = $validated['type'] === 'shop' ? 'shops.index' : 'houses.index';

        return redirect()->route($redirectRoute)->with('message', 'Property updated successfully.');
    }

    public function destroy(int $id)
    {
        $property = Property::query()->findOrFail($id);
        $this->authorizePropertyAccess($property);

        $propertyType = $property->type;
        $property->delete();

        $redirectRoute = $propertyType === 'shop' ? 'shops.index' : 'houses.index';

        return redirect()->route($redirectRoute)->with('message', 'Property deleted successfully.');
    }

    public function index()
    {
        return redirect()->route('houses.index');
    }

    public function data()
    {
        $query = Property::query()->with('project')->latest('id');

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        return DataTables::eloquent($query)
            ->addColumn('project_name', fn (Property $property) => $property->project?->name ?? 'GLOBAL')
            ->addColumn('type_badge', function (Property $property) {
                $typeClass = $property->type === 'house' ? 'type-house' : 'type-shop';
                return '<span class="property-pill ' . $typeClass . '">' . ucfirst($property->type) . '</span>';
            })
            ->addColumn('status_badge', function (Property $property) {
                $statusClass = match ($property->status) {
                    'available' => 'status-available',
                    'booked' => 'status-booked',
                    default => 'status-inactive',
                };
                return '<span class="property-pill ' . $statusClass . '">' . ucfirst($property->status) . '</span>';
            })
            ->addColumn('created_on', fn (Property $property) => optional($property->created_at)->format('Y-m-d'))
            ->addColumn('action', function (Property $property) {
                $editUrl = route('properties.edit', $property->id);
                $deleteUrl = route('properties.destroy', $property->id);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm property-action-btn action-edit" title="Edit Property">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm property-action-btn action-delete" title="Delete Property" onclick="return confirm(\'Delete this property?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['type_badge', 'status_badge', 'action'])
            ->toJson();
    }

    private function generateNextCode(int $projectId, string $type): string
    {
        $projectCode = (string) Project::query()->whereKey($projectId)->value('code');
        $prefix = $type === 'house' ? 'H' : 'S';
        $next = Property::query()
            ->where('project_id', $projectId)
            ->where('type', $type)
            ->count() + 1;

        do {
            $code = sprintf('%s-%s-%04d', $projectCode, $prefix, $next);
            $exists = Property::query()->where('code', $code)->exists();
            $next++;
        } while ($exists);

        return $code;
    }

    private function authorizePropertyAccess(Property $property): void
    {
        $user = Auth::user();
        if (! empty($user->project_id) && (int) $property->project_id !== (int) $user->project_id) {
            abort(403, 'Not allowed.');
        }
    }

    private function gridListByType(string $type, string $title)
    {
        $query = Property::query()
            ->with('project')
            ->where('type', $type);

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        $items = $query->latest('id')->paginate(18);

        return view('properties.grid', compact('items', 'title', 'type'));
    }
}
