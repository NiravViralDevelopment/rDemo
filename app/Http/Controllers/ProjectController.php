<?php

namespace App\Http\Controllers;

use App\Models\Project;
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
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        Project::query()->create($validated);

        return redirect()->route('projects.index')->with('message', 'Project created successfully.');
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
