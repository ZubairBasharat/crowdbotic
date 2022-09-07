<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!auth()->user()->can("delete-transactions")){
            $classDelete = 'd-none';
        }else{
            $classDelete = '';
        }
        if ($request->ajax()) {
        $_order = request('order');
        $_columns = request('columns');
        $order_by = $_columns[$_order[0]['column']]['name'];
        $order_dir = $_order[0]['dir'];
        $search = request('search');
        $skip = request('start');
        $take = request('length');
        $search = request('search');
        $query = User::query()->role('Customer');
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
            $d->action = '<div class="d-flex align-align-items-center"><a href="'.route('customers.edit',$d->id).'"><button class="btn-none me-1"><img src="'.asset("assets/images/svg/edit.svg").'" alt="Edit" width="16px"></button></a>
            <form id="delete_customer" method="POST" action="' . route('customers.destory',$d->id) . '" accept-charset="UTF-8" class="d-inline-block dform">
            <input name="_method" type="hidden" value="DELETE">
            <input name="_token" type="hidden" value="' . csrf_token() . '">
        <button type="submit" class="btn-none delete p-0'.$classDelete.'" data-toggle="tooltip" data-placement="top" title="Delete" href="javascript:void()">
        <img src="'.asset("assets/images/svg/delete.svg").'" alt="Edit" width="16px">
    </button> </form></div>';
        }
        return [
            "draw" => request('draw'),
            "recordsTotal" => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            "data" => $data,
        ];
    }
        return view('customers.index');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id','!=','3')->pluck('name','name')->all();
        return view('customers.create',compact('roles'));
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
            'phone_number' => 'required',
        ]);
        activity('customers')
        ->causedBy(Auth::user())
        ->withProperties($request->all())
        ->log('created');
        $input = $request->all();
        $user = User::create($input);
        $user->assignRole('Customer');
        return redirect()->route('customers.index')
                        ->with('success','Customer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        activity('customers')
        ->causedBy(Auth::user())
        ->withProperties($id)
        ->log('show');
        $user = User::find($id);
        return view('customers.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        activity('customers')
        ->causedBy(Auth::user())
        ->withProperties($id)
        ->log('edit');
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->first();
        return view('customers.edit',compact('user','roles','userRole'));
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
        activity('customers')
        ->causedBy(Auth::user())
        ->withProperties($id)
        ->log('updated');
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'required',
        ]);
        activity('customers')
        ->causedBy(Auth::user())
        ->withProperties($request->all())
        ->log('updated');
        $input = $request->all();
        $user = User::find($id);
        $user->update($input);

        return redirect()->route('customers.index')
                        ->with('success','Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        activity('customers')
        ->causedBy(Auth::user())
        ->withProperties($id)
        ->log('deleted');
        User::find($id)->delete();
        return redirect()->route('customers.index')
                        ->with('success','Customer deleted successfully');
    }
}