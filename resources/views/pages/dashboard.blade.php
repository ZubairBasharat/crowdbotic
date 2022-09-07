@extends('layouts.app')
@section('content')

<div class="main-padding">
    <div class="row mb-1">
        <div class="col-xxl-3 col-md-6">
            <a class="top-card text-decoration-none">
                <h2>Today's Cash Received</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/cash.svg') }}" alt="">
                    <h1>{{number_format($transaction->where(['transaction_category'=> 'received', 'transaction_type'=> 'local'])->where(['cancel_transaction'=>0])->whereDate('created_at',  \Carbon\Carbon::today())->sum('received_amount'))}}</h1>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-md-6">
            <a class="top-card text-decoration-none">
                <h2>Today's Cash Paid</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/balance.svg') }}" alt="">
                    <h1>{{number_format($transaction->where(['transaction_category'=> 'paid' , 'transaction_type'=> 'local'])->where(['cancel_transaction'=>0])->whereDate('created_at',  \Carbon\Carbon::today())->sum('paid_amount'))}}</h1>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-md-6">
            <a class="top-card text-decoration-none">
                <h2>Opening Cash Received</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/cash.svg') }}" alt="">
                    <h1>{{number_format($transaction->where(['transaction_category'=> 'received', 'transaction_type'=> 'local'])->where(['cancel_transaction'=>0])->sum('received_amount'))}}</h1>
                </div>
            </a>
        </div>
        <div class="col-xxl-3 col-md-6">
            <a class="top-card text-decoration-none">
                <h2>Opening Cash Paid</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/balance.svg') }}" alt="">
                    <h1>{{number_format($transaction->where(['transaction_category'=> 'paid', 'transaction_type'=> 'local'])->where(['cancel_transaction'=>0])->sum('paid_amount'))}}</h1>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{url('pending-amount-details')}}" class="top-card text-decoration-none">
                <h2>Total Pending Amount</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/cash.svg') }}" alt="">
                    <h1>@php $total_pending = total_pending_balanced_amount()['pending_amount']; echo number_format($total_pending); @endphp</h1>
                </div>
            </a>
        </div>
         <div class="col-md-6">
            <a href="{{url('balanced-amount-details')}}" class="top-card text-decoration-none">
                <h2>Total Balanced Amount</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/cash.svg') }}" alt="">
                    <h1>@php $total_balance = total_pending_balanced_amount()['balanced_amount']; echo number_format(- $total_balance); @endphp</h1>
                </div>
            </a>
        </div>
         <div class="col-md-6">
            <a href="#" class="top-card text-decoration-none">
                <h2>Total Cash</h2>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/svg/cash.svg') }}" alt="">
                    <h1>{{number_format($total_pending - $total_balance)}}</h1>
                </div>
            </a>
        </div>
    </div>
    <ul class="nav nav-pills theme-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#daily-paid" type="button" role="tab" aria-controls="daily-paid" aria-selected="true">Daily Local Paid</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#daily-received" type="button" role="tab" aria-controls="daily-received" aria-selected="false">Daily Local Received</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#daily-foreign-paid" type="button" role="tab" aria-controls="daily-foreign-paid" aria-selected="true">Daily Foreign Paid</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#daily-foreign-received" type="button" role="tab" aria-controls="daily-foreign-received" aria-selected="false">Daily Foreign Received</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="daily-paid" role="tabpanel" aria-labelledby="daily-paid-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <a  class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'local', 'transaction_category'=> 'paid','daily_transaction'=> 1])->sum('paid_amount'))}}</h1>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="daily-received" role="tabpanel" aria-labelledby="daily-received-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <a  class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'local', 'transaction_category'=> 'received','daily_transaction'=> 1])->sum('received_amount'))}}</h1>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show " id="daily-foreign-paid" role="tabpanel" aria-labelledby="daily-paid-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <a  class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid','daily_transaction'=> 1])->sum('paid_amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>USD</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/USD.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>2 ,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>RMB</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>3 ,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>PKR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/PKR.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>1,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>POUND</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/pound.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>4,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>EURO</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/euro.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>5,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a class="top-card text-decoration-none">
                        <h2>YEN</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>6,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>SAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>7,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>QAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/qar.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'paid', 'currency_id'=>8,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>Omani Rial</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>0</h1>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="daily-foreign-received" role="tabpanel" aria-labelledby="daily-received-tab" tabindex="0">
            <div class="row">
            <div class="col-lg-4">
                    <a  class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received','daily_transaction'=> 1])->sum('received_amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>USD</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/USD.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>2,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>RMB</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>3 ,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a href="{{ url('/detail') }}" class="top-card text-decoration-none">
                        <h2>PKR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/PKR.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>1,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a href="{{ url('/detail') }}" class="top-card text-decoration-none">
                        <h2>POUND</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/pound.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>4,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>EURO</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/euro.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>5,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a class="top-card text-decoration-none">
                        <h2>YEN</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>6,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>SAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>7,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>QAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/qar.svg') }}" alt="">
                            <h1>{{number_format($transaction->where(['transaction_type'=>'foreign', 'transaction_category'=> 'received', 'currency_id'=>8,'daily_transaction'=> 1])->sum('amount'))}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>Omani Rial</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>0</h1>
                        </div>
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection