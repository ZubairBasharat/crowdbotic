@extends('layouts.app')
@section('content')

<div class="main-padding">
    <ul class="nav nav-pills theme-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#today-tab" type="button" role="tab" aria-controls="today-tab" aria-selected="true">Today</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#week-tab" type="button" role="tab" aria-controls="week-tab" aria-selected="false">Current Week</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#month-tab" type="button" role="tab" aria-controls="month-tab" aria-selected="false">Life Time</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="today-tab" role="tabpanel" aria-labelledby="today-tab" tabindex="0">

            <div class="row">
                <div class="col-lg-4">
                    <a class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>@php $profit = ($today_transactions->sum('received_amount') + $today_transactions->sum('paid_amount')) -( App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$today_transactions->whereDate('created_at',  \Carbon\Carbon::today())->pluck('transactoion_with'))->sum('paid_amount') +  App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$today_transactions->whereDate('created_at',  \Carbon\Carbon::today())->pluck('transactoion_with'))->sum('received_amount')); if($profit != 0){ echo ($profit >= 0 ) ? number_format($profit - $today_subtract_profit) : number_format(-($profit + $today_subtract_profit)) ;}else{echo 0;} @endphp</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>USD</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/USD.svg') }}" alt="">
                            <h1>{{calcualte_profit(2, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>RMB</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{calcualte_profit(3, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>PKR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/PKR.svg') }}" alt="">
                            <h1>{{calcualte_profit(1, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>POUND</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/pound.svg') }}" alt="">
                            <h1>{{calcualte_profit(4, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>EURO</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/euro.svg') }}" alt="">
                            <h1>{{calcualte_profit(5, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>YEN</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{calcualte_profit(6, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>SAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>{{calcualte_profit(7, 'today')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>QAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/qar.svg') }}" alt="">
                            <h1>{{calcualte_profit(8, 'today')}}</h1>
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
        <div class="tab-pane fade" id="week-tab" role="tabpanel" aria-labelledby="week-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <a  class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>@php $profit = ($week_transactions->sum('received_amount') + $week_transactions->sum('paid_amount')) - (App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$week_transactions->whereBetween('created_at' , [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->pluck('transactoion_with'))->sum('paid_amount') + App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$week_transactions->whereBetween('created_at' , [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->pluck('transactoion_with'))->sum('received_amount')); if($profit != 0){ echo ($profit >= 0 ) ? number_format($profit - $week_subtract_profit) : number_format(-($profit + $week_subtract_profit)) ;}else{ echo 0 ;} @endphp</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>USD</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/USD.svg') }}" alt="">
                            <h1>{{calcualte_profit(2, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>RMB</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{calcualte_profit(3, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>PKR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/PKR.svg') }}" alt="">
                            <h1>{{calcualte_profit(1, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>POUND</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/pound.svg') }}" alt="">
                            <h1>{{calcualte_profit(4, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>EURO</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/euro.svg') }}" alt="">
                            <h1>{{calcualte_profit(5, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>YEN</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{calcualte_profit(6, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>SAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>{{calcualte_profit(7, 'week')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>QAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/qar.svg') }}" alt="">
                            <h1>{{calcualte_profit(8, 'week')}}</h1>
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
        <div class="tab-pane fade" id="month-tab" role="tabpanel" aria-labelledby="month-tab" tabindex="0">
            <div class="row">
                <div class="col-lg-4">
                    <a  class="top-card text-decoration-none">
                        <h2>Total (AED)</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/AED.svg') }}" alt="">
                            <h1>@php $profit = ($monthly_transactions->sum('received_amount')+ $monthly_transactions->sum('paid_amount')) - (App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$monthly_transactions->pluck('transactoion_with'))->sum('paid_amount') + App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$monthly_transactions->pluck('transactoion_with'))->sum('received_amount')); if($profit != 0){ echo ($profit >= 0 ) ? number_format($profit - $monthly_subtract_profit) : number_format(-($profit + $monthly_subtract_profit)); }else{echo 0;} @endphp</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>USD</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/USD.svg') }}" alt="">
                            <h1>{{calcualte_profit(2, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>RMB</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{calcualte_profit(3, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>PKR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/PKR.svg') }}" alt="">
                            <h1>{{calcualte_profit(1, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>POUND</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/pound.svg') }}" alt="">
                            <h1>{{calcualte_profit(4, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>EURO</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/euro.svg') }}" alt="">
                            <h1>{{calcualte_profit(5, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>YEN</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/RMB.svg') }}" alt="">
                            <h1>{{calcualte_profit(6, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>SAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/sar.svg') }}" alt="">
                            <h1>{{calcualte_profit(7, 'month')}}</h1>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a  class="top-card text-decoration-none">
                        <h2>QAR</h2>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/svg/qar.svg') }}" alt="">
                            <h1>{{calcualte_profit(8, 'month')}}</h1>
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

@endsection