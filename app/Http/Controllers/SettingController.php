<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use anlutro\LaravelSettings\Facade as Setting;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;

class SettingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:update-settings');
        // $this->middleware('permission:view-activity-log', ['only' => ['activity']]);
    }

    public function activity(Request $request){
        if ($request->ajax()) {
            $_order = request('order');
            $_columns = request('columns');
            $order_by = $_columns[$_order[0]['column']]['name'];
            $order_dir = $_order[0]['dir'];
            $search = request('search');
            $skip = request('start');
            $take = request('length');
            $search = request('search');
            $query = Activity::query();
            $query->orderBy('id', 'DESC')->get();
            $recordsTotal = $query->count();
            if (isset($search['value'])) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("log_name LIKE '%" . $search['value'] . "%' ");
                });
            }
            $recordsFiltered = $query->count();
            $data = $query->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
            foreach ($data as $d) {
                $d->causer->name = $d->causer->name;
                $d->created_at = $d->created_at->diffForHumans();
            }
            return [
                "draw" => request('draw'),
                "recordsTotal" => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                "data" => $data,
            ];
        }
        return view('settings.activity');
    }
}
