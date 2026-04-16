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
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
    
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
        ]);
       
        $input = $request->all();
        $input['show_password'] = $request->password;
        $input['country_id'] = null;
        $input['project_id'] = $this->resolveProjectIdFromNameAndRole($request);

        $input['password'] = FacadesHash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
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

        return view('users.edit', compact('user', 'roles', 'userRole'));
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
}