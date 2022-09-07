<?php
use App\Models\Transaction;
use App\Models\User;


function calcualte_profit($currency, $profit_type)
{
    $profit = 0;
    $currency_profit = 0;
    $transactions = Transaction::where(['transaction_type' => 'foreign'])->where('transactoion_with','>',0);
    if($profit_type == 'today'){
        $transactions = $transactions->whereDate('created_at',  \Carbon\Carbon::today());
    }elseif($profit_type == 'week'){
        $transactions = $transactions->whereBetween('created_at' , [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
    }elseif($profit_type == 'month'){
        $transactions = $transactions->whereMonth('created_at', \Carbon\Carbon::now()->month);
    }
    $transactions = $transactions->where(['currency_id'=> $currency])->get();
    foreach($transactions as $transaction)
    {
        $profit = 0;
        $exchange_rate = $transaction->exchange_rate;
        $profit += $transaction->paid_amount;
        if($profit == 0){
            $profit += $transaction->received_amount;
        }

        $linked_transaction = Transaction::where(['id'=> $transaction->transactoion_with])->first();
        if(!empty($linked_transaction)){
            if($linked_transaction->received_amount > 0)
            $profit -= $linked_transaction->received_amount;
            else
            $profit -= $linked_transaction->paid_amount;
            
            if($profit < 0){
                $profit = -($profit);
            }
            // return $profit;
            if($linked_transaction->exchange_rate > $exchange_rate){
                $exchange_rate = $linked_transaction->exchange_rate;
            } 

            if($currency == 1 || $currency == 3)
            $currency_profit += $profit * $exchange_rate;
            else
            $currency_profit += $profit / $exchange_rate;
        }
        

    }

    return number_format(round($currency_profit));
}

function total_pending_balanced_amount()
{
    $states['pending_amount'] = 0;
    $states['balanced_amount'] = 0;
    $transactions = Transaction::groupBy('customer_id')->get();
    foreach($transactions as $transaction){
        if($transaction->profit > 0){
            $states['pending_amount'] += $transaction->profit;
        }elseif($transaction->profit < 0){
            $states['balanced_amount'] -= $transaction->profit;
        }
    }
    return $states;
}


?>