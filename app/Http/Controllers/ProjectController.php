<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $this->ensureGlobalUser();
        $projects = Project::query()->latest('id')->paginate(20);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->ensureGlobalUser();
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $this->ensureGlobalUser();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:projects,code'],
            'status' => ['required', 'in:active,inactive'],
            'house_count' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'shop_count' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $houseCount = (int) ($validated['house_count'] ?? 0);
        $shopCount = (int) ($validated['shop_count'] ?? 0);

        unset($validated['house_count'], $validated['shop_count']);

        $project = Project::query()->create($validated);
        $this->createAutoProperties($project, 'house', $houseCount);
        $this->createAutoProperties($project, 'shop', $shopCount);

        return redirect()->route('projects.index')->with('message', 'Project created successfully.');
    }

    private function createAutoProperties(Project $project, string $type, int $count): void
    {
        if ($count <= 0) {
            return;
        }

        $now = now();
        $rows = [];
        $prefix = $type === 'house' ? 'H' : 'S';
        $baseName = $type === 'house' ? 'House' : 'Shop';

        for ($i = 1; $i <= $count; $i++) {
            $code = sprintf('%s-%s-%04d', $project->code, $prefix, $i);

            $rows[] = [
                'project_id' => $project->id,
                'code' => $code,
                'type' => $type,
                'title' => $baseName . ' ' . $i,
                'city' => null,
                'address' => null,
                'bedrooms' => $type === 'house' ? 2 : null,
                'area_sqft' => $type === 'house' ? 900 : 350,
                'price_per_day' => 0,
                'status' => 'available',
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Property::query()->insert($rows);
    }

    private function ensureGlobalUser(): void
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }
        if (! empty($user->project_id)) {
            abort(403, 'Project user cannot manage projects.');
        }
    }
}
