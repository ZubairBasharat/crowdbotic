<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Currency;
use Carbon\Carbon;
use ExcelReport;
use Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if(!auth()->user()->can("delete-transactions")){
                $classDelete = 'd-none';
            }else{
                $classDelete = '';
            }
            $currency = request('currency');
            $customer = request('customer');
            $cancel = request('cancel');
            $date = request('date')!=''? explode(' - ', request('date')):'';
            $daily_transaction = request('daily_transaction');
            $_order = request('order');
            $_columns = request('columns');
            $order_by = $_columns[$_order[0]['column']]['name'];
            $order_dir = $_order[0]['dir'];
            $search = request('search');
            $skip = request('start');
            $take = request('length');
            $search = request('search');
            $query = Transaction::select('users.name','users.phone_number','transactions.*')->join('users','users.id','=','transactions.customer_id');
            $query->when($customer > 0 || $daily_transaction > 0,function ($q) use ($customer) {
                $q->orderBy('id', 'ASC');
            });
            $query->when($customer == '' || $daily_transaction == 0,function ($q) use ($customer) {
                $q->orderBy('id', 'DESC');
            });
            $recordsTotal = $query->count();
            $query->when($daily_transaction,function ($q) use ($daily_transaction) {
                $q->where('daily_transaction', $daily_transaction);
            });
            $query->when($search['value'] !='',function ($q) use ($search) {
                    $q->whereRaw("name LIKE '%" . $search['value'] . "%' ");
             });
            $query->when($currency !='',function ($q) use ($currency) {
                $q->whereRaw("transaction_type = '" . $currency . "' ");
            });
            $query->when($cancel == 1,function ($q) use ($cancel) {
                $q->whereRaw("cancel_transaction = '" . 0 . "' ");
            });
            $query->when($customer !='',function ($q) use ($customer) {
                $q->whereRaw("customer_id = '" . $customer . "' ");
            });

            if($customer =='' || $daily_transaction==1){
                $query->when(!empty($date),function ($q) use ($date) {
                    $date_from = date('Y-m-d', strtotime($date[0]));
                    $date_to = date('Y-m-d', strtotime($date[1]));
                    if($date_from == $date_to){
                        $q->whereDate('transactions.created_at' , $date_from);
                    }else{
                        $q->whereBetween('transactions.created_at' , [$date_from, $date_to]);
                    }
                });
            }
            $recordsFiltered = $query->count();
            $data = $query->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
            foreach ($data as $d) {
                $d->rate = $d->exchange_rate;
                $d->amount = number_format($d->amount);
                $d->paid_amount = number_format($d->paid_amount);
                $d->received_amount = '<span style="color:red">'.number_format($d->received_amount).'</span>';
                $d->date = date('d-m-Y', strtotime($d->created_at));
                $d->daily_transaction =  $d->daily_transaction == 1 ? 'Yes' : 'No';
                $d->amount_status =  number_format($d->amount_status);
                $d->free =  '<span class="free"></spane>';
                $d->trans_type = $d->transaction_type == 'local' ? $d->transaction_type.'<br>AED' : $d->transaction_type.'<br>'.$d->currency;
                $cancel_class = $d->cancel_transaction == 1 ? 'cancel_transaction' : '';
                $d->action = '<div class="d-flex"><a class='.$cancel_class.'><button class="btn-none edit-btn me-1" data-id="'.$d->id.'"><img src="'.asset("assets/images/svg/edit.svg").'" alt="Edit" width="16px"></button></a>
                <form method="POST" action="' . route('report.destory',$d->id) . '" accept-charset="UTF-8" class="d-inline-block dform">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="' . csrf_token() . '">
            <button type="submit" class="btn-none delete me-1'.$classDelete.'" data-toggle="tooltip" data-placement="top" title="Delete" href="javascript:void()">
            <img src="'.asset("assets/images/svg/delete.svg").'" alt="Delete" width="16px">
        </button> </form><button class="btn-none print_receipt" data-id="'.$d->id.'"><img src="'.asset("assets/images/svg/print.svg").'" alt="Print" width="16px"></button></div>
                ';
            }
            return [
                "draw" => request('draw'),
                "recordsTotal" => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                "data" => $data,
            ];
        }
        $monthly_subtract_profit = 0;
        $system_owner = User::where(['name'=> 'Owner (Profit Oder)'])->first();
        if(!empty($system_owner)){
            $monthly_subtract_profit =  Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->sum('paid_amount') + Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->sum('received_amount'); 
        }
        $monthly_transactions = Transaction::where(['transaction_type' => 'foreign', 'cancel_transaction'=>0])->where('transactoion_with','>',0);
        $currencies = Currency::get();
        $transactions = Transaction::where(['cancel_transaction'=>0])->orderBy('id','DESC')->get();
        $customers = User::whereHas('roles', function($role) {
                        $role->where('name', '=', 'Customer');
                    })->get();
        return view('pages.reports',compact('customers','currencies','transactions','monthly_transactions','monthly_subtract_profit'));
    }

    public function deleteTranaction(Request $request)
    {
        if ($request->ajax()) {
            $currency = request('currency');
            $customer = request('customer');
            $date = request('date')!=''? explode(' - ', request('date')):'';
            $daily_transaction = request('daily_transaction');
            $_order = request('order');
            $_columns = request('columns');
            $order_by = $_columns[$_order[0]['column']]['name'];
            $order_dir = $_order[0]['dir'];
            $search = request('search');
            $skip = request('start');
            $take = request('length');
            $search = request('search');
            $query = Transaction::select('users.name','users.phone_number','transactions.*')->join('users','users.id','=','transactions.customer_id');
            $query->orderBy('id', 'DESC')->onlyTrashed()->get();
            $recordsTotal = $query->count();
            $query->when($search['value'] !='',function ($q) use ($search) {
                    $q->whereRaw("name LIKE '%" . $search['value'] . "%' ");
             });
            // $query->when($currency !='',function ($q) use ($currency) {
            //     $q->whereRaw("transaction_type = '" . $currency . "' ");
            // });
            // $query->when($customer !='',function ($q) use ($customer) {
            //     $q->whereRaw("customer_id = '" . $customer . "' ");
            // });
            // $query->when(!empty($date),function ($q) use ($date) {
            //     $q->whereRaw("transactions.created_at BETWEEN '" . date('Y-m-d',strtotime($date[0])). "' AND '" . date('Y-m-d',strtotime($date[1])) . "' ");
            // });
            // $query->when($daily_transaction,function ($q) use ($daily_transaction) {
            //     $q->whereRaw("daily_transaction = '" . $daily_transaction . "' ");
            // });
            $recordsFiltered = $query->count();
            $data = $query->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
            foreach ($data as $d) {
                $d->paid_amount =  $d->paid_amount;
                $d->received_amount =  $d->received_amount;
                $d->daily_transaction =  $d->daily_transaction == 1 ? 'Yes' : 'No';
            }
            return [
                "draw" => request('draw'),
                "recordsTotal" => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                "data" => $data,
            ];
        }
        $currencies = Currency::get();
        $transactions = Transaction::orderBy('id','DESC')->get();
        $customers = User::whereHas('roles', function($role) {
                        $role->where('name', '=', 'Customer');
                    })->get();
        return view('pages.delete_transaction',compact('customers','currencies','transactions'));
    }

    public function save_transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer'         => 'required',
            'description'      => 'required',
            'type'             => 'required',
            'category'         => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return ['status'=>202, 'message'=>$error];
        }

        // if type is local
        if($request->type == "local"){
            if($request->category == 'paid'){
                $validator = Validator::make($request->all(), [
                    'paid_amount'      => 'required',
                ]);
            }
            if($request->category == 'received'){
                $validator = Validator::make($request->all(), [
                    'received_amount'      => 'required',
                ]);
            }

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ['status'=>202, 'message'=>$error];
            }
        }

        //  if type is foreign
        if($request->type == "foreign"){

            $validator = Validator::make($request->all(), [
                'amount'      => 'required',
                'exchange_rate'      => 'required',
                'currency'      => 'required',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ['status'=>202, 'message'=>$error];
            }

            if($request->category == 'paid'){
                $validator = Validator::make($request->all(), [
                    'paid_amount'      => 'required',
                ]);
            }
            if($request->category == 'received'){
                $validator = Validator::make($request->all(), [
                    'received_amount'      => 'required',
                ]);
            }

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ['status'=>202, 'message'=>$error];
            }
        }

        $save_transaction = [
            'customer_id' => $request->customer,
            'description' => $request->description,
            'paid_amount' => $request->paid_amount,
            'transaction_type'=> $request->type,
            'transaction_category'=> $request->category,
            'daily_transaction' => $request->daily_transaction,
            'received_amount' => $request->received_amount,
            'amount' =>  $request->amount,
            'exchange_rate' => $request->exchange_rate,
            'currency_id' => $request->currency,
        ];
        activity('Transaction')
        ->causedBy(Auth::user())
        ->withProperties($request->all())
        ->log('Created');
        Transaction::create($save_transaction);
        return ['status'=>200, 'message'=>'Transaction saved successfully'];
    }

     /**
     * Remove the soft delete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::find($id)->delete();
        activity('Transaction')
        ->causedBy(Auth::user())
        ->withProperties($id)
        ->log('deleted');
        flash('Transaction deleted successfully!')->info();
        return back();
    }

    public function get_balance($customer_id)
    {
        $transactions = Transaction::where(['customer_id'=> $customer_id, 'cancel_transaction'=>0])->first();
        echo $status =  $transactions->profit == 0 ? "<h6 style='color:#04f489'>OK</h6>" : ($transactions->profit > 0 ? "<h6 style='color:#f9a825'><span class='ms-2' style='color:#444;font-size: 13px;'>Pending</span>".  number_format($transactions->profit). '</h6>'  : "<h6 style='color:#ff6873'><span class='ms-2' style='color:#444;font-size: 13px;'>Balanced</span>".number_format($transactions->profit).'</h6>');
    }

    public function get_paid_received(Request $request)
    {
        // echo \Carbon\Carbon::today();die;
        $date_arr = explode("-", $request->date);
        $date_from = date('Y-m-d', strtotime($date_arr[0]));
        $date_to = date('Y-m-d', strtotime($date_arr[1]));
        $transaction =  Transaction::where(['cancel_transaction'=>0])->where('daily_transaction', 1);
        if(!empty($request->customer)){
            $transaction = $transaction->where(['customer_id'=> $request->customer]);
        }
        if($date_from == $date_to)
        {
            $transaction = $transaction->whereDate('created_at' , $date_from);
        }else{
            $transaction = $transaction->whereBetween('created_at' , [$date_from, $date_to]);
        }
        
        $data['paid_amount'] = number_format($transaction->sum('paid_amount'));
        $data['received_amount'] = number_format($transaction->sum('received_amount'));
        $data['total_remaining'] = number_format($transaction->sum('received_amount') - $transaction->sum('paid_amount'));
        $data['total'] = $transaction->count();
        $paid_transaction = Transaction::where(['cancel_transaction'=>0])->where('daily_transaction', 1)->where('transaction_category', 'paid');
        if(!empty($request->customer)){
            $paid_transaction = $paid_transaction->where(['customer_id'=> $request->customer]);
        }
        if($date_from == $date_to)
        {
            $paid_transaction = $paid_transaction->whereDate('created_at' , $date_from);
        }else{
            $paid_transaction = $paid_transaction->whereBetween('created_at' , [$date_from, $date_to]);
        }

        $data['total_paid'] = $paid_transaction->count();
        $data['total_received'] = $transaction->where('transaction_category', 'received')->count();

        $data['status'] = 0;
        if(!empty($request->customer)){
            $transactions = Transaction::where(['customer_id'=> $request->customer, 'cancel_transaction'=>0])->first();
            $data['status'] =  $transactions->profit == 0 ? "<h6 style='color:#04f489'>OK</h6>" : ($transactions->profit > 0 ? "<h6 style='color:#f9a825'><span class='ms-2' style='color:#444;font-size: 13px;'>Pending</span>".  number_format($transactions->profit). '</h6>'  : "<h6 style='color:#ff6873'><span class='ms-2' style='color:#444;font-size: 13px;'>Balanced</span>".number_format($transactions->profit).'</h6>');
        }
        return $data;
    }

    public function receipt_data(Request $request)
    {
        $transaction = Transaction::where(['id'=>$request->transaction_id])->first();
        $transaction->paid_amount = $transaction->paid_amount > 0 ? number_format($transaction->paid_amount) : 0;
        $transaction->received_amount = $transaction->received_amount > 0 ? number_format($transaction->received_amount) : 0;
        return $transaction;
    }

    public function invoice_data(Request $request)
    {
        $date_arr = explode("-", $request->date);
        $date_from = date('Y-m-d', strtotime($date_arr[0]));
        $date_to = date('Y-m-d', strtotime($date_arr[1]));
        if($request->daily_transaction == 1)
        $transaction =  Transaction::where(['cancel_transaction'=>0])->where('daily_transaction', $request->daily_transaction);
        else
        $transaction = new Transaction;

        if(!empty($request->customer)){
            $transaction = $transaction->where(['customer_id'=> $request->customer, 'cancel_transaction'=>0]);
        }

        if($date_from == $date_to)
        {
            // $transaction = $transaction->whereDate('created_at' , $date_from);
        }else{
            $transaction = $transaction->whereBetween('created_at' , [$date_from, $date_to]);
        }
        $data['total'] = $transaction->count();
        $data['paid_amount'] = number_format($transaction->sum('paid_amount'));
        $data['received_amount'] = number_format($transaction->sum('received_amount'));
        $data['total_remaining'] = number_format($transaction->sum('received_amount') - $transaction->sum('paid_amount'));

        //total paid transactions
        $paid_transaction = Transaction::where(['cancel_transaction'=>0])->where('daily_transaction', $request->daily_transaction)->where('transaction_category', 'paid');
        if(!empty($request->customer)){
            $paid_transaction = $paid_transaction->where(['customer_id'=> $request->customer]);
        }
        if($date_from == $date_to)
        {
            // $paid_transaction = $paid_transaction->whereDate('created_at' , $date_from);
        }else{
            $paid_transaction = $paid_transaction->whereBetween('created_at' , [$date_from, $date_to]);
        }

        $data['total_paid'] = $paid_transaction->count();

        //total received transactions
        $paid_transaction = Transaction::where(['cancel_transaction'=>0])->where('daily_transaction', $request->daily_transaction)->where('transaction_category', 'received');
        if(!empty($request->customer)){
            $paid_transaction = $paid_transaction->where(['customer_id'=> $request->customer]);
        }
        if($date_from == $date_to)
        {
            // $paid_transaction = $paid_transaction->whereDate('created_at' , $date_from);
        }else{
            $paid_transaction = $paid_transaction->whereBetween('created_at' , [$date_from, $date_to]);
        }

        $data['total_received'] = $paid_transaction->count();

        $data['status'] = 0;
        if(!empty($request->customer)){
            $transactions = Transaction::where(['customer_id'=> $request->customer, 'cancel_transaction'=>0])->first();
            $data['status'] =  $transactions->profit == 0 ?  "0 OK" : ($transactions->profit > 0 ? 'Pending' . number_format($transactions->profit) : ' Balanced'.number_format($transactions->profit));
        }

        return $data;
    }

    public function get_customer_paid_received(Request $request)
    {
        if(!empty($request->customer)){
            $cusotmer['customer_total_paid'] = number_format(Transaction::where(['customer_id'=> $request->customer])->sum('paid_amount'));
            $cusotmer['customer_total_received'] = number_format(Transaction::where(['customer_id'=> $request->customer])->sum('received_amount'));
            $transactions = Transaction::where(['customer_id'=> $request->customer])->first();
            $cusotmer['customer_total_status'] =  $transactions->profit == 0 ? "<h6 style='color:#04f489'>OK</h6>" : ($transactions->profit > 0 ? "<h6 style='color:#f9a825'><span class='ms-2' style='color:#444;font-size: 13px;'>Pending</span><br>".  number_format($transactions->profit). '</h6>'  : "<h6 style='color:#ff6873'><span class='ms-2' style='color:#444;font-size: 13px;'>Balanced</span><br>".number_format($transactions->profit).'</h6>');
        }else{
            $transaction = new Transaction;
            if($request->daily_transaction == 1){
                $transaction = $transaction->where(['daily_transaction'=>1]);
            }
            $transaction = $transaction->where(['cancel_transaction'=>0]);
            $cusotmer['customer_total_paid'] = number_format($transaction->sum('paid_amount'));
            $cusotmer['customer_total_received'] = number_format($transaction->sum('received_amount'));
            $cusotmer['customer_total_status'] = 0;
            $cusotmer['total_remaining'] = number_format($transaction->sum('received_amount') - $transaction->sum('paid_amount'));
        }
        return $cusotmer;
    }
}
