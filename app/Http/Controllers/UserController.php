<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-view');
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
        if(!auth()->user()->can("user-delete")){
            $classDelete = 'd-none';
        }else{
            $classDelete = '';
        }
        if(!auth()->user()->can("user-edit")){
            $classEdit = 'd-none';
        }else{
            $classEdit = '';
        }
        $_order = request('order');
        $_columns = request('columns');
        $order_by = $_columns[$_order[0]['column']]['name'];
        $order_dir = $_order[0]['dir'];
        $search = request('search');
        $skip = request('start');
        $take = request('length');
        $search = request('search');
        $query = User::query()->whereHas(
            'roles', function($q){
                $q->where('name', 'Admin');
                $q->orWhere('name', 'Cashier');
            }
        );
        $query->orderBy('id', 'DESC')->get();
        $recordsTotal = $query->count();
        if (isset($search['value'])) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("name LIKE '%" . $search['value'] . "%' ");
            });
        }
        $recordsFiltered = $query->count();
        $data = $query->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
        foreach ($data as $d) {
            $d->role = $d->roles->pluck('name')->implode(', ');
            $d->action = '<a href="'.route('users.edit',$d->id).'"><button class="btn-none '.$classEdit.'"><img src="'.asset("assets/images/svg/edit.svg").'" alt="Edit" width="16px"></button></a>';
        }
        return [
            "draw" => request('draw'),
            "recordsTotal" => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            "data" => $data,
        ];
    }
        return view('users.index');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id','!=','3')->pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone_number' => 'required',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('id','!=','3')->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->first();
        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes',
            'phone_number' => 'required',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}