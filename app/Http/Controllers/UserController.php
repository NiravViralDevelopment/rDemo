<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Mail\UserCreatedMail;
use App\Models\Project;
use App\Models\Property;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $authUser = Auth::user();
        $data = User::with('project')
            ->when(! empty($authUser->project_id), fn ($q) => $q->where('project_id', $authUser->project_id))
            ->latest()
            ->get();

        $projectIds = $data->pluck('project_id')->filter()->unique()->values();
        $propertyCounts = Property::query()
            ->selectRaw('project_id, type, COUNT(*) as total')
            ->whereIn('project_id', $projectIds)
            ->groupBy('project_id', 'type')
            ->get();

        $countsMap = [];
        foreach ($propertyCounts as $row) {
            $countsMap[$row->project_id][$row->type] = (int) $row->total;
        }

        $data->transform(function ($user) use ($countsMap) {
            $projectId = $user->project_id;
            $user->house_count = $projectId ? (int) ($countsMap[$projectId]['house'] ?? 0) : 0;
            $user->shop_count = $projectId ? (int) ($countsMap[$projectId]['shop'] ?? 0) : 0;
            return $user;
        });

        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create', compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

   
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'total_houses' => 'nullable|integer|min:0',
            'total_shops' => 'nullable|integer|min:0',
        ]);
       
        $input = $request->all();
        $input['show_password'] = $request->password;
        $input['country_id'] = null;
        $input['project_id'] = $this->resolveProjectIdFromNameAndRole($request);

        $input['password'] = FacadesHash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        $this->syncProjectInventoryFromRequest($request, $input['project_id']);
        // Mail::to($user->email)->send(new UserCreatedMail($user));
        return redirect()->route('users.index')
                        ->with('message','User created successfully and email sent');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $authUser = Auth::user();
        $user = User::find($id);
        if (! $user) {
            abort(404);
        }
        if (! empty($authUser->project_id) && (int) $user->project_id !== (int) $authUser->project_id) {
            abort(403, 'Not allowed.');
        }
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $currentTotalHouses = $user->project_id
            ? Property::query()->where('project_id', $user->project_id)->where('type', 'house')->count()
            : 0;
        $currentTotalShops = $user->project_id
            ? Property::query()->where('project_id', $user->project_id)->where('type', 'shop')->count()
            : 0;
    
        return view('users.edit', compact('user', 'roles', 'userRole', 'currentTotalHouses', 'currentTotalShops'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'total_houses' => 'nullable|integer|min:0',
            'total_shops' => 'nullable|integer|min:0',
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
        $authUser = Auth::user();
        $user = User::find($id);
        if (! $user) {
            abort(404);
        }
        if (! empty($authUser->project_id) && (int) $user->project_id !== (int) $authUser->project_id) {
            abort(403, 'Not allowed.');
        }
        $input['country_id'] = null;
        $input['project_id'] = $this->resolveProjectIdFromNameAndRole($request);
    
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
        $this->syncProjectInventoryFromRequest($request, $input['project_id']);
    
        return redirect()->route('users.index')
                        ->with('message','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        $authUser = Auth::user();
        $user = User::find($id);
        if (! $user) {
            abort(404);
        }
        if (! empty($authUser->project_id) && (int) $user->project_id !== (int) $authUser->project_id) {
            abort(403, 'Not allowed.');
        }
        $user->delete();
        return redirect()->route('users.index')
                        ->with('message','User deleted successfully');
    }

    private function resolveProjectIdFromNameAndRole(Request $request): ?int
    {
        $roles = (array) $request->input('roles', []);
        $isProjectUser = in_array('Project User', $roles, true);

        if (! $isProjectUser) {
            return null;
        }

        $projectName = trim((string) $request->input('name'));
        $projectCode = strtoupper(preg_replace('/\s+/', '_', $projectName));

        $project = Project::query()->firstOrCreate(
            ['name' => $projectName],
            ['code' => $projectCode, 'status' => 'active']
        );

        return (int) $project->id;
    }

    private function syncProjectInventoryFromRequest(Request $request, ?int $projectId): void
    {
        $roles = (array) $request->input('roles', []);
        if (! in_array('Project User', $roles, true) || ! $projectId) {
            return;
        }

        $desiredHouses = (int) ($request->input('total_houses') ?? 0);
        $desiredShops = (int) ($request->input('total_shops') ?? 0);

        $this->ensurePropertyCount($projectId, 'house', $desiredHouses);
        $this->ensurePropertyCount($projectId, 'shop', $desiredShops);
    }

    private function ensurePropertyCount(int $projectId, string $type, int $desiredTotal): void
    {
        if ($desiredTotal <= 0) {
            return;
        }

        $current = Property::query()
            ->where('project_id', $projectId)
            ->where('type', $type)
            ->count();

        if ($current >= $desiredTotal) {
            return;
        }

        $projectCode = (string) Project::query()->whereKey($projectId)->value('code');
        if ($projectCode === '') {
            throw ValidationException::withMessages(['name' => 'Project code not found for selected project user.']);
        }

        $prefix = $type === 'house' ? 'H' : 'S';
        $titleBase = $type === 'house' ? 'House' : 'Shop';
        $now = now();
        $rows = [];

        for ($i = $current + 1; $i <= $desiredTotal; $i++) {
            $rows[] = [
                'project_id' => $projectId,
                'code' => sprintf('%s-%s-%04d', $projectCode, $prefix, $i),
                'type' => $type,
                'title' => $titleBase . ' ' . $i,
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
}