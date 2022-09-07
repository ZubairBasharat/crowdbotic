<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['transaction_type','amount_status', 'transaction_category', 'customer_id', 'description', 'amount', 'exchange_rate', 'paid_amount', 'currency_id', 'daily_transaction', 'status', 'received_amount','transactoion_with'];

    protected $appends = ['paid','received','profit','name','detailp', 'detailr', 'currency'];

    public function getPaidAttribute()
    {
        return number_format(Transaction::where(['customer_id'=> $this->customer_id, 'cancel_transaction'=>0])->sum('paid_amount'));
    }
    public function getReceivedAttribute()
    {
        return  '<span style="color:#ff6873">'.number_format(Transaction::where(['customer_id'=> $this->customer_id, 'cancel_transaction'=>0])->sum('received_amount')).'</span>';
    }

    public function getProfitAttribute()
    {
        return intval(Transaction::where(['customer_id'=> $this->customer_id, 'cancel_transaction'=>0])->sum('received_amount') - Transaction::where(['customer_id'=> $this->customer_id, 'cancel_transaction'=>0])->sum('paid_amount'));
        // return intval(preg_replace('/[^\d.]/', '',$this->received)) - intval(preg_replace('/[^\d.]/', '',$this->paid));
    }

    public function getNameAttribute()
    {
        $name = "Customer Deleted";
        $customer = User::where(['id'=> $this->customer_id])->first();
        if(!empty($customer)){
            $name = $customer->name;
        }
        return $name;
    }

    public function getDetailpAttribute()
    {
       return Transaction::where(['customer_id'=> $this->customer_id,'transactoion_with'=>$this->id, 'cancel_transaction'=>0])->sum('paid_amount');
    }
    public function getDetailrAttribute()
    {
       return Transaction::where(['customer_id'=> $this->customer_id,'transactoion_with'=>$this->id, 'cancel_transaction'=>0])->sum('received_amount');
    }
    public function getCurrencyAttribute()
    {
       $currency = "";
       $selected_currency = Currency::where(['id'=> $this->currency_id])->first();
       if(!empty($selected_currency)){
        $currency = $selected_currency->currency;
       }

       return $currency;
    }
}
