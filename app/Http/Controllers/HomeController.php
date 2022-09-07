<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transaction = new Transaction();
        // $transaction = $transaction->where(['cancel_transaction'=>0]);
        return view('pages.dashboard', compact('transaction'));
    }

    public function profit_calculation()
    {
        $today_subtract_profit = 0;
        $week_subtract_profit = 0;
        $monthly_subtract_profit = 0;
        $system_owner = User::where(['name'=> 'Owner (Profit Oder)'])->first();
        if(!empty($system_owner)){
            $today_subtract_profit =  Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->whereDate('created_at',  \Carbon\Carbon::today())->sum('paid_amount') + Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->whereDate('created_at',  \Carbon\Carbon::today())->sum('received_amount'); 
            $week_subtract_profit =  Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->whereBetween('created_at' , [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->sum('paid_amount') + Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->whereBetween('created_at' , [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->sum('received_amount'); 
            $monthly_subtract_profit =  Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->sum('paid_amount') + Transaction::where(['customer_id'=> $system_owner->id, 'cancel_transaction'=>0])->whereMonth('created_at', \Carbon\Carbon::now()->month)->sum('received_amount'); 
        }
        $today_transactions = Transaction::where(['transaction_type' => 'foreign', 'cancel_transaction'=>0])->whereDate('created_at',  \Carbon\Carbon::today())->where('transactoion_with','>',0);
        $week_transactions = Transaction::where(['transaction_type' => 'foreign', 'cancel_transaction'=>0])->whereBetween('created_at' , [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->where('transactoion_with','>',0);
        $monthly_transactions = Transaction::where(['transaction_type' => 'foreign', 'cancel_transaction'=>0])->where('transactoion_with','>',0);
        return view('pages.profit-calculation', compact('today_transactions', 'week_transactions', 'monthly_transactions','today_subtract_profit','week_subtract_profit','monthly_subtract_profit'));
    }
}
