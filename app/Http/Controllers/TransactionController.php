<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Currency;
use DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // return Transaction::get();
        if ($request->ajax()) {
            $_order = request('order');
            $_columns = request('columns');
            $order_by = $_columns[$_order[0]['column']]['name'];
            $order_dir = $_order[0]['dir'];
            $search = request('search');
            $skip = request('start');
            $take = request('length');
            $search = request('search');
            $query = Transaction::whereIn('transactions.id', function($query) {
                $query->from('transactions')->groupBy('customer_id')->selectRaw('MAX(id)');
             })->select('users.name','users.phone_number','transactions.*')->join('users','users.id','=','transactions.customer_id');;
            $query->orderBy('transactions.id', 'DESC')->get();
            $recordsTotal = $query->count();
            if (isset($search['value'])) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("name LIKE '%" . $search['value'] . "%' ");
                });
            }
            $recordsFiltered = $query->count();
            $data = $query->skip($skip)->take($take)->get();
            foreach ($data as $d) {
                $d->customer_transaction = $d->name.'-'.$d->id;
                $d->paid_amount =  $d->paid_amount;
                $d->received_amount =  $d->received_amount;
                $d->daily_transaction =  $d->daily_transaction == 1 ? 'Yes' : 'No';
                $d->status =  $d->profit == 0 ? "<span style='color:#04f489'>OK</span>" : ($d->profit > 0 ? "<span style='color:#f9a825'>".  number_format($d->profit). '- Pending </span>'  : "<span style='color:#ff6873'>".number_format($d->profit).'- Balanced </span>');
                $d->detial = '<a href='.url('transaction-detail',$d->customer_id).'><button class="btn-none" data-id="'.$d->customer_id.'"><img src="'.asset("assets/images/svg/view.svg").'" alt="Edit" width="16px"></button></a>';
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
        return view('pages.detail',compact('customers','currencies', 'transactions'));
    }

    public function save_transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer'         => 'required',
            'description'      => 'required',
            'type'             => 'required',
            'category'         => 'required',
            'daily_transaction' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return ['status'=>202, 'message'=>$error];
        }

        // if($request->category == 'received'){
        //     $validator = Validator::make($request->all(), [
        //         'transactoion_with'         => 'required',
        //     ]);

        //     if ($validator->fails()) {
        //         $error = $validator->errors()->first();
        //         return ['status'=>202, 'message'=>$error];
        //     }
        // }

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
            'paid_amount' => $request->paid_amount > 0 ? round(intval(preg_replace('/[^\d.]/', '',$request->paid_amount))) : NULL,
            'transaction_type'=> $request->type,
            'transaction_category'=> $request->category,
            'daily_transaction' => $request->daily_transaction,
            'received_amount' => $request->received_amount > 0 ? round((preg_replace('/[^\d.]/', '',$request->received_amount))) : NULL,
            'amount' =>  intval(preg_replace('/[^\d.]/', '',  $request->amount)),
            'exchange_rate' => preg_replace('/[^\d.]/', '',  $request->exchange_rate),
            'currency_id' => $request->currency,
            'transactoion_with' => $request->transactoion_with,
            'cancel_transaction' => isset($request->cancel_transaction) ? $request->cancel_transaction : 0,
        ];

        $transaction_id = $request->transaction_id; 
        if($request->transaction_id == 0){
            activity('Transaction')
            ->causedBy(Auth::user())
            ->withProperties($request->all())
            ->log('Created');
            $saved_transaction = Transaction::create($save_transaction);
            $transaction_id = $saved_transaction->id;
            $amount_status = Transaction::where(['customer_id'=> $request->customer,'cancel_transaction'=>0])->sum('received_amount') - Transaction::where(['customer_id'=> $request->customer,'cancel_transaction'=>0])->sum('paid_amount');
            Transaction::where(['id'=> $transaction_id])->update(['amount_status'=>$amount_status]);
            $message = "Transaction saved successfully";
        }
        else{
            activity('Transaction')
            ->causedBy(Auth::user())
            ->withProperties($request->all())
            ->log('Updated');
            Transaction::where(['id'=> $request->transaction_id])->update($save_transaction);
            $this->update_amount_status($request->transaction_id, $request->customer);
            $message = "Transaction updated successfully";
        }
        if(!empty($request->date)){
            Transaction::where(['id'=> $transaction_id])->update(['created_at'=> date('Y-m-d H:i:s', strtotime($request->date))]);
        }
        return ['status'=>200, 'message'=>$message, 'transaction_id'=> $transaction_id];
    }

    public function edit_transaction($transaction_id)
    {
        $transaction = Transaction::where(['id'=> $transaction_id])->first();
        $transaction->date = date('Y-m-d',strtotime($transaction->created_at));
        return $transaction;
    }

    public function Get_transaction()
    {
        $transactions = Transaction::where(['cancel_transaction'=>0])->orderBy('id','DESC')->get();
        return $transactions;
    }

    public function transaction_detail($customer_id)
    {
        $transactions = Transaction::where(['customer_id'=> $customer_id, 'cancel_transaction'=>0])->get();
        return view('pages.transaction_details', compact('transactions'));
    }

    public function details()
    {
        $pending_amount = array();
        $balance_amount = array();
        $details = array();
        $pending_amount_index = 0;
        $balance_amount_index = 0;
        $transactions = Transaction::groupBy('customer_id')->get();
        foreach($transactions as $transaction){
            if($transaction->profit > 0){
                $pending_amount[$pending_amount_index] = $transaction;
                $pending_amount_index++;
            }elseif($transaction->profit < 0){
                $balance_amount[$balance_amount_index] = $transaction;
                $balance_amount_index++;
            }
        }

        if(request()->is('pending-amount-details')){
            $details = $pending_amount;
        }else{
            $details = $balance_amount;
        }

        return view('pages.amount_detail', compact('details'));
    }

    public function update_amount_status($transaction_id, $customer_id)
    {
        $amount_status = Transaction::where(['customer_id'=>$customer_id, 'cancel_transaction'=> 0])->where('id','<=',$transaction_id)->sum('received_amount') - Transaction::where(['customer_id'=>$customer_id, 'cancel_transaction'=> 0])->where('id','<=',$transaction_id)->sum('paid_amount');
        Transaction::where('id',$transaction_id)->update(['amount_status'=>$amount_status]);
        $all_transactions = Transaction::where(['customer_id'=>$customer_id, 'cancel_transaction'=> 0])->where('id','>',$transaction_id)->get();
        foreach($all_transactions as $transaction)
        {
            $amount_status = Transaction::where(['customer_id'=>$customer_id, 'cancel_transaction'=> 0])->where('id','<=',$transaction->id)->sum('received_amount') - Transaction::where(['customer_id'=>$customer_id, 'cancel_transaction'=> 0])->where('id','<=',$transaction->id)->sum('paid_amount');
            Transaction::where('id',$transaction->id)->update(['amount_status'=>$amount_status]);
        }
    }
}
