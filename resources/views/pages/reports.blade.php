@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<style>
    /* #transactionTable thead {
        position: sticky;
        top: 37px;
    } */
    /* .sticky__ {
        position: sticky;
        top: 0;
        background-color: white;
        height: 40px;
    }
    .scroll___ {
        height: calc(100vh - 370px);
    } */
    #transactionTable thead th {
        background-color: white;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .dataTables_scrollHeadInner, .table{
        width:100%!important;
    }
</style>
<div class="main-padding" style="margin-top: -10px;">
    <div class="table-card">
        <div class="mb-2 d-flex align-items-center justify-content-between">
            <h1 class="heading mb-3">Reports</h1>
            <div class="customer_status customer_status_amount" style="display:none">
                <div class="d-flex">
                    <h4>Status:</h4>
                    <h3 class="customer_blnc"></h3>
                </div>
            </div>
            <div class="customer_status customer_status_amount system_status" style="display:">
                <div class="d-flex">
                    <h4>Status:</h4>
                    <h3>@php $profit = ($monthly_transactions->sum('received_amount')+ $monthly_transactions->sum('paid_amount')) - (App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$monthly_transactions->pluck('transactoion_with'))->sum('paid_amount') + App\Models\Transaction::where(['cancel_transaction'=>0])->whereIn('id' ,$monthly_transactions->pluck('transactoion_with'))->sum('received_amount')); if($profit != 0){ echo ($profit >= 0 ) ? number_format($profit - $monthly_subtract_profit) : number_format(-($profit + $monthly_subtract_profit)); }else{echo 0;} @endphp</h3>
                </div>
            </div>
            <button class="theme-btn me-2 generate_report">Generate Report</button>
        </div>
        <div class="row mb-1">
            <div class="col-xl-3 col-md-6 total_paid customer_status" style="display:none">
                <div class="d-flex">
                    <h4>Total Paid:</h4>
                    <h3 class="total_paid_amount"></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 total_received customer_status" style="display:none">
                <div class="d-flex">
                    <h4>Total Received:</h4>
                    <h3 class="total_received_amount"></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 total_received customer_status" style="display:none">
                <div class="d-flex">
                    <h4>Total Remaining Cash:</h4>
                    <h3 class="total_remaining_cash"></h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 total_received customer_status" style="display:none">
                <div class="d-flex">
                    <h4>Status:</h4>
                    <h3 class="customer_status_summary"></h3>
                </div>
            </div>
        </div>
        <div class="total_transactions customer_status mb-2" style="display:none">
            <div class="row">
                <div class="col-xl-3 col-md-6 d-flex">
                    <h4>Total Transactions:</h4>
                    <h3 class="total_transaction"></h3>
                </div>
                <div class="col-xl-3 col-md-6 d-flex">
                    <h4>Total Paid Transactions:</h4>
                    <h3 class="total_paid_transaction"></h3>
                </div>
                <div class="col-xl-3 col-md-6 d-flex">
                    <h4>Total Received Transactions:</h4>
                    <h3 class="total_received_transaction"></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-6">
                <label for="" class="theme-label">Type</label>
                <select name="" id="currency" class="theme-select">
                    <option value="">All</option>
                    <option value="local">Local</option>
                    <option value="foreign">Foreign</option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label for="" class="theme-label">Select Customer</label>
                <select name="" id="select-customer" class="theme-select customers">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label for="" class="theme-label">Select Date</label>
                <input type="text" id="daterange" class="theme-input" placeholder="Select Date" />
            </div>
            <div class="col-md-3 col-6">
                <label class="theme-checkbox reports-daily-checkbox"><span class="d-inline-block">Daily Transaction</span>
                    <input type="checkbox" id="daily_transaction">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="table-responsive">
            <table id="transactionTable" class="theme-table" style="width:100%;">
                <thead id="transaction_table_header">
                    <tr>
                        <th>Customer</th>
                        <th style="max-width:150px;">Description</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Paid</th>
                        <th>Received</th>
                        <th class="white-space-nowrap">Daily Trans</th>
                        <th class="amount_head" style="display:none">Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="add-transaction-modal" tabindex="-1" aria-labelledby="add-transaction-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close selection-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="selection-div mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Transaction Type</h2>
                            <div class="flex-center">
                                <div class="selections-card type flex-center">
                                    <span>Local</span>
                                    <input type="radio" name="type" class="transaction_type" value="local" id="local-check">
                                    <div class="checkmark"></div>
                                </div>
                                <div class="selections-card type flex-center">
                                    <span>Foreign</span>
                                    <input type="radio" name="type"  class="transaction_type" value="foreign" id="foreign-check">
                                    <div class="checkmark"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2>Transaction Category</h2>
                            <div class="flex-center">
                                <div class="selections-card category flex-center">
                                    <span>Paid</span>
                                    <input type="radio" class="transaction_category" name="category" value="paid" id="paid-check">
                                    <div class="checkmark"></div>
                                </div>
                                <div class="selections-card category flex-center">
                                    <span>Received</span>
                                    <input type="radio" name="category" class="transaction_category" value="received" id="received-check">
                                    <div class="checkmark"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-center selection-footer">
                        <button class="theme-btn cancel selection-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button class="theme-btn ms-2 selection-next" style="width: 90px;">Next</button>
                    </div>
                </div>

                <form action="" class="local-paid transaction-forms" style="display: none;">
                @csrf
                    <h1 class="heading theme-clr">Add Local - Paid Transaction</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="theme-label">Select customer</label>
                            <select name="customer" id="" class="transactionModal-select2 customer">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Link Transaction With</label>
                            <select name="transactoion_with" id="" class="transactionModal-select2 transactoion_with">
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Description</label>
                            <input type="text" name="description" class="theme-input description" placeholder="Description" />
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Paid Amount</label>
                            <div class="input-group theme-input-group">
                                <span class="input-group-text">AED</span>
                                <input type="text" name="paid_amount" class="locale-string-input form-control paid_amount" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Date</label>
                            <div class="input-group theme-input-group">
                                <input type="date" name="date" class="locale-string-input form-control date" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col mt-2">
                            <label class="theme-label mb-2">Daily Transaction</label><br>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Yes
                                    <input type="radio" value="1" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="daily-transaction-input-label theme-radio">No
                                    <input type="radio" value="0" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Cancel Transaction
                                    <input type="radio" value="1" name="cancel_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text"></strong>
                            </span>
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text_2"></strong>
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="flex-center selection-footer">
                                <button class="theme-btn cancel selection-cancel" data-bs-dismiss="modal" type="button">Cancel</button>
                                <button type="button" class="theme-btn ms-2 selection-next save_btn" style="width: 90px;">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="" class="local-received transaction-forms" style="display: none;">
                @csrf
                    <h1 class="heading theme-clr">Add Local - Received Transaction</h1>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 18px;">
                            <label for="" class="theme-label">Select customer</label>
                            <select name="customer" id="" class="transactionModal-select2 customer">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Link Transaction With</label>
                            <select name="transactoion_with" id="" class="transactionModal-select2 transactoion_with">
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""  class="theme-label">Description</label>
                            <input type="text" name="description" class="theme-input description" placeholder="Description" />
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Received Amount in AED</label>
                            <div class="input-group theme-input-group">
                                <span class="input-group-text">AED</span>
                                <input type="number" min="0" name="received_amount" class="form-control received_amount" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Date</label>
                            <div class="input-group theme-input-group">
                                <input type="date" name="date" class="locale-string-input form-control date" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col mt-2">
                            {{-- <label class="theme-checkbox"><span class="d-inline-block">Daily Transaction</span>
                                <input value="1" name="daily_transaction" class="daily_transaction" type="checkbox">
                                <span class="checkmark"></span>
                            </label> --}}
                            <label class="theme-label mb-2">Daily Transaction</label><br>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Yes
                                    <input type="radio" value="1" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="daily-transaction-input-label theme-radio">No
                                    <input type="radio" value="0" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Cancel Transaction
                                    <input type="radio" value="1" name="cancel_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text"></strong>
                            </span>
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text_2"></strong>
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="flex-center selection-footer">
                                <button class="theme-btn cancel selection-cancel" data-bs-dismiss="modal" type="button">Cancel</button>
                                <button class="theme-btn ms-2 selection-next save_btn" type="button" style="width: 90px;">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="" class="foreign-paid transaction-forms" style="display: none;">
                @csrf
                    <h1 class="heading theme-clr">Add Foreign - Paid Transaction</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="theme-label">Select customer</label>
                            <select name="customer" id="" class="transactionModal-select2 customer">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Link Transaction With</label>
                            <select name="transactoion_with" id="" class="transactionModal-select2 transaction_with">
                                <option value="">Select Ttransaction</option>
                                @foreach($transactions as $transaction)
                                    <option value="{{$transaction->id}}">{{$transaction->name.'-'.$transaction->id}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Description</label>
                            <input type="text" name="description" class="theme-input description" placeholder="Description" />
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Select currency</label>
                            <select name="currency" id="" class="theme-select select-currency">
                                <option value="">Select Currenct</option>
                                @foreach($currencies as $currency)
                                    <option data-val="{{$currency->currency}}" value="{{$currency->id}}">{{$currency->currency}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Amount</label>
                            <div class="input-group theme-input-group">
                                <span class="input-group-text currency_label"></span>
                                <input type="text" name="amount" id="paid_ampunt_val" value="0" class="locale-string-input form-control calculate amount" placeholder="Amount" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Exchange Rate</label>
                            <input type="number" min="0" name="exchange_rate" id="exchange_rate" value="0" class="theme-input calculate exchange_rate" placeholder="Amount" />
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Paid amount</label>
                            <div class="input-group theme-input-group">
                                <span class="input-group-text">AED</span>
                                <input type="text" min="0" name="paid_amount" id="foreign_paid_amount" class="locale-string-input form-control paid_amount" value="0" readonly />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Date</label>
                            <div class="input-group theme-input-group">
                                <input type="date" name="date" class="locale-string-input form-control date" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col mt-2">
                            {{-- <label class="theme-checkbox"><span class="d-inline-block">Daily Transaction</span>
                                <input name="daily_transaction" value="1" class="daily_transaction" type="checkbox">
                                <span class="checkmark"></span>
                            </label> --}}
                            <label class="theme-label mb-2">Daily Transaction</label><br>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Yes
                                    <input type="radio" value="1" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="daily-transaction-input-label theme-radio">No
                                    <input type="radio" value="0" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Cancel Transaction
                                    <input type="radio" value="1" name="cancel_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text"></strong>
                            </span>
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text_2"></strong>
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="flex-center selection-footer">
                                <button class="theme-btn cancel selection-cancel" data-bs-dismiss="modal" type="button">Cancel</button>
                                <button type="button" class="theme-btn ms-2 selection-next save_btn" style="width: 90px;">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="" class="foreign-received transaction-forms" style="display: none;">
                @csrf
                    <h1 class="heading theme-clr">Add Foreign - Received Transaction</h1>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 18px;">
                            <label for="" class="theme-label">Select customer</label>
                            <select name="customer" id="" class="transactionModal-select2 customer">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Link Transaction With</label>
                            <select name="transactoion_with" id="" class="transactionModal-select2 transaction_with">
                                <option value="">Select Ttransaction</option>
                                @foreach($transactions as $transaction)
                                    <option value="{{$transaction->id}}">{{$transaction->name.'-'.$transaction->id}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Description</label>
                            <input type="text" name="description" class="theme-input description" placeholder="Description" />
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Select currency</label>
                            <select name="currency" id="" class="theme-select select-currency">
                                <option value="">Select Currenct</option>
                                @foreach($currencies as $currency)
                                    <option data-val="{{$currency->currency}}" value="{{$currency->id}}">{{$currency->currency}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <div class="col-md-6"></div> -->
                        <div class="col-md-6">
                            <label for="" class="theme-label">Amount</label>
                            <div class="input-group theme-input-group">
                                <span class="input-group-text currency_label"></span>
                                <input name="amount" type="text" id="paid_ampunt_val_received" class="locale-string-input form-control calculate amount" placeholder="Amount" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Exchange Rate</label>
                            <input name="exchange_rate" id="exchange_rate_received" type="number" min="0" class="theme-input calculate exchange_rate" placeholder="Amount" />
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Received amount</label>
                            <div class="input-group theme-input-group">
                                <span class="input-group-text">AED</span>
                                <input name="received_amount" id="foreign_received_amount" type="number" min="0" class="form-control received_amount" value="0" readonly />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="theme-label">Date</label>
                            <div class="input-group theme-input-group">
                                <input type="date" name="date" class="locale-string-input form-control date" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col">
                            {{-- <label class="theme-checkbox"><span class="d-inline-block">Daily Transaction</span>
                                <input name="daily_transaction" value="1" class="daily_transaction" type="checkbox">
                                <span class="checkmark"></span>
                            </label> --}}
                            <label class="theme-label mb-2">Daily Transaction</label><br>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Yes
                                    <input type="radio" value="1" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="daily-transaction-input-label theme-radio">No
                                    <input type="radio" value="0" name="daily_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="ps-1 d-flex">
                                <label class="daily-transaction-input-label theme-radio me-3">Cancel Transaction
                                    <input type="radio" value="1" name="cancel_transaction" class="daily-transaction-input">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text"></strong>
                            </span>
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong class="error_text_2"></strong>
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="flex-center selection-footer">
                                <button class="theme-btn cancel selection-cancel" data-bs-dismiss="modal" type="button">Cancel</button>
                                <button type="button" class="theme-btn ms-2 selection-next save_btn" style="width: 90px;">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id='DivIdToPrint' style="display:none">
   @include('pages.invoice')
</div>
<div id='DivIdToPrint_receipt' style="display:none">
   @include('pages.receipt')
</div>
<script>
    $(document).ready(function () {
        $('#select-customer').select2();
        $('#daterange').daterangepicker();
    });
</script>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/transaction.js') }}"></script>
<script type="text/javascript">
    var table_bit = false;
    var page_load = false;
    function data_table() {
        $(".amount_head").css('display','none');
        if($('#select-customer').val() != "")
        {
            $(".amount_head").css('display','');
            var columns = [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'trans_type',
                    name: 'trans_type'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'rate',
                    name: 'rate'
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'received_amount',
                    name: 'received_amount'
                },
                {
                    data: 'daily_transaction',
                    name: 'daily_transaction'
                },
                {
                    data: 'amount_status',
                    name: 'amount_status'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ];
        }else{
            var columns = [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'trans_type',
                    name: 'trans_type'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'rate',
                    name: 'rate'
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'received_amount',
                    name: 'received_amount'
                },
                {
                    data: 'daily_transaction',
                    name: 'daily_transaction'
                },
                {
                    data: 'free',
                    name: 'free'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ];
        }
        
        var length = [];
        var bInfo = true;
        var scrollYVal = 'calc(100vh - 385px)';
        var lengthChange = true;
        if($('#select-customer').val() != "" || $('#daily_transaction').is(':checked')){
            var length = [
                [2500, 5000, 10000, 20000, -1],
                [2500, 5000, 10000, 20000, "All"]
            ];
            bInfo = false;
            lengthChange = false;
            
        }
        var table = $('#transactionTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: lengthChange,
            bInfo : bInfo,
            aLengthMenu: length,
            scrollY: scrollYVal,
            // columnDefs: [
            //     { width: 50, targets: 1 }
            // ],
            ajax: {
                url: "{{ route('reports.index') }}",
                data: function(d) {
                    // alert($('#daily_transaction').is(':checked'));
                    d.currency = $('#currency').val(),
                    d.customer = $('#select-customer').val(),
                    d.daily_transaction = $('#daily_transaction').is(':checked')?1:0
                    d.cancel = 0;
                    if(table_bit == true){
                        d.date = $("#daterange").val();
                    }else{
                        d.date = "";
                    }
                }
            },
            columns: columns,
            columnDefs: [{
                targets: 8,
                className: 'white-space-nowrap'
            }],
            initComplete: function( settings ) {
                $(".free").each(function(){
                    $(this).parent().remove();
                });
                $(".dataTables_scrollBody").animate({ scrollTop: $(".dataTables_scrollBody")[0].scrollHeight}, 1);
                // if($('#select-customer').val() != "" || $('#daily_transaction').is(':checked')){
                append_data();
                // }
                cancel_transactions();
            }
        });
        // $('.aside-toggle').click( () => {
        //     table.columns.adjust().draw();
        // });
        // table.columns.adjust().draw();
        //   $('#approved').change(function(){
        //       table.draw();
        //   });

    }

    $(document).on('click','.paginate_button ',function(){
        setTimeout(function() {
            append_data();
        }, 1000);
    });

    function append_data()
    {
        daily_transaction = $('#daily_transaction').is(':checked')?1:0
        $.ajax({
            method : "POST",
            url: "{{url('get-customer-paid-received')}}",
            data: {customer: $('#select-customer').val(),daily_transaction:daily_transaction, _token: "{{csrf_token()}}"},
            success:function(response)
            {
                $(".append_tr").remove();
                $('#transactionTable').append(`<tr class="even append_tr">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Paid<br>${response.customer_total_paid}</td>
                <td style="color:red">Received<br>${response.customer_total_received}</td>`);
                if($('#select-customer').val() != ""){
                $('.append_tr').append(`
                <td>Status</td>
                <td>${response.customer_total_status}</td><td></td><td></td>`);
                }else{
                    $('.append_tr').append(`
                    <td></td>
                    <td>Total Remaining Cash:</td>
                    <td>${response.total_remaining}</td>`);
                }
                $('#transactionTable').append(`</tr>`);
                $(".dataTables_scrollBody").animate({ scrollTop: $(".dataTables_scrollBody")[0].scrollHeight}, 1);
            }
        });
    }

    $('#currency').change(function(){
            table_bit = true;
            $('#transactionTable').dataTable().fnDestroy();
           data_table();
        });
        $('#select-customer').change(function(){
            table_bit = true;
            $('#transactionTable').dataTable().fnDestroy();
           data_table();
            var data = $("#select2-select-customer-container").text();
            if (data === 'All Customers') {
                $('.dataTables_paginate').show();
            } else {
                $('.dataTables_paginate').hide();
            }
        });
        $('#daterange').change(function(){
            if(page_load){
                table_bit = true;
            }
            $('#transactionTable').dataTable().fnDestroy();
            data_table();
            summary();
            page_load = true;
        });
        $('#daily_transaction').change(function(){
            table_bit = true;
            $('#transactionTable').dataTable().fnDestroy();
            data_table();
            // if checked
            if($(this).is(':checked')){
                $('.dataTables_paginate').hide();
            }
        });
</script>
<script>
    $('#transactionTable tbody').on('click', '.delete', function(e) {
            e.preventDefault();
            let that = jQuery(this);
            jQuery.confirm({
                icon: 'fas fa-wind-warning',
                closeIcon: true,
                title: 'Are you sure!',
                content: 'You can not undo this action.!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    confirm: function() {
                        that.parent('form').submit();
                        //$.alert('Confirmed!');
                    },
                    cancel: function() {
                        //$.alert('Canceled!');
                    }
                }
            });

    });

    function cancel_transactions() {
        $(".cancel_transaction").each(function(){
            $(this).parent().parent().parent().addClass('td-red');
        });
    }

    $(".customers").on('change', function(){
        $(".customer_status").css('display','none');
        $(".customer_blnc").empty();
        $(".system_status").css('display','none');
        if($(this).find(":selected").text() == 'Owner (Profit Oder)')
        {
            $(".system_status").css('display','');
            return false;
        }
        var customer_id = $(this).find(":selected").val();
        if(customer_id == ""){
            return false;
        }
        $.ajax({
            method: 'GET',
            url: '{{url("get-balance")}}/'+customer_id,
            success:function(response){
                $(".customer_blnc").append(response);
                $(".customer_status_amount").css('display','');
                $(".system_status").css('display','none');
            }
        });
    });

    $("#daily_transaction").on('change',function(){
        summary();
    });

    function summary(){
        var date = $("#daterange").val();
        var customer = $('#select-customer').val();
        $(".customer_status").css('display','none');
        if($("#daily_transaction").is(":checked")){
            $.ajax({
                method: 'GET',
                url: "{{url('get-paid-received')}}",
                data: {date: date, customer: customer},
                success:function(response)
                {
                    $(".customer_status_summary").empty()
                    $(".total_paid_amount").text(response.paid_amount);
                    $(".total_received_amount").text(response.received_amount);
                    $(".total_transaction").text(response.total);
                    $(".total_paid_transaction").text(response.total_paid);
                    $(".total_received_transaction").text(response.total_received);
                    $(".total_remaining_cash").text(response.total_remaining);
                    $(".customer_status_summary").append(response.status);
                    $(".total_paid").css('display','');
                    $(".total_received").css('display','');
                    $(".total_transactions").css('display','');
                    if(customer == ""){
                        $(".total_remaining_cash").parent().css('display','');
                        $(".customer_status_summary").parent().css('display','none');
                    }else{
                        $(".total_remaining_cash").parent().css('display','none');
                        $(".customer_status_summary").parent().css('display','');
                    }
                }
            });
        }
    }

    $(".generate_report").on('click',function(){
        $(".amount_head").css('display','none');
        if($('#select-customer').val() != "")
        {
            $(".amount_head").css('display','');
            var columns = [
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'trans_type',
                            name: 'trans_type'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'rate',
                            name: 'rate'
                        },
                        {
                            data: 'paid_amount',
                            name: 'paid_amount'
                        },
                        {
                            data: 'received_amount',
                            name: 'received_amount'
                        },
                        {
                            data: 'amount_status',
                            name: 'amount_status'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                    ]
        }else{
            var columns = [
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'trans_type',
                            name: 'trans_type'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'rate',
                            name: 'rate'
                        },
                        {
                            data: 'paid_amount',
                            name: 'paid_amount'
                        },
                        {
                            data: 'received_amount',
                            name: 'received_amount'
                        },
                        {
                            data: 'free',
                            name: 'free'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                    ];
        }
        var date = $("#daterange").val();
        var customer = $('#select-customer').val();
        var daily_transaction = $('#daily_transaction').is(':checked')?1:0;
        $.ajax({
            method: 'post',
            url: "{{url('invoice_data')}}",
            data: {date: date, customer: customer, daily_transaction:daily_transaction, _token: "{{csrf_token()}}"},
            success:function(response)
            {
                $(".customer_status_summary_invoice").empty()
                    $(".total_paid_amount_invoice").text(response.paid_amount);
                    $(".total_received_amount_invoice").text(response.received_amount);
                    $(".total_transaction_invoice").text(response.total);
                    $(".total_paid_transaction_invoice").text(response.total_paid);
                    $(".total_received_transaction_invoice").text(response.total_received);
                    $(".total_remaining_cash_invoice").text(response.total_remaining);
                    $(".customer_status_summary_invoice").append(response.status);
                    if(customer == ""){
                        $(".total_remaining_cash_invoice").parent().css('display','');
                        $(".customer_status_summary_invoice").parent().css('display','none');
                    }else{
                        $(".total_remaining_cash_invoice").parent().css('display','none');
                        $(".customer_status_summary_invoice").parent().css('display','');
                    }
                $('#invoice_table').dataTable().fnDestroy();
                var table = $('#invoice_table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    lengthChange: false,
                    bInfo : false,
                    aLengthMenu: [
                        [2500, 5000, 10000, 20000, -1],
                        [2500, 5000, 10000, 20000, "All"]
                    ],
                    ajax: {
                        url: "{{ route('reports.index') }}",
                        data: function(d) {
                        // alert($('#daily_transaction').is(':checked'));

                        d.currency = $('#currency').val(),
                        d.customer = $('#select-customer').val(),
                        d.daily_transaction = $('#daily_transaction').is(':checked')?1:0
                        d.date = $("#daterange").val();
                        d.cancel = 1
                        }
                    },
                    columns: columns,
                    columnDefs: [{
                        targets: 6,
                        className: 'white-space-nowrap',
                    }],
                    initComplete: function( settings ) {
                        $("#invoice_table_paginate").css('display','none');
                        // if( $('#select-customer').val() != ''){
                        $.ajax({
                        method : "POST",
                        url: "{{url('get-customer-paid-received')}}",
                        data: {customer: $('#select-customer').val(), _token: "{{csrf_token()}}"},
                        success:function(response)
                        {
                            $(".append_tr").remove();
                            $('#invoice_table').append(`<tr class="even append_tr">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Paid<br>${response.customer_total_paid}</td>
                            <td style="color:red">Received<br>${response.customer_total_received}</td>
                            `)
                            if( $('#select-customer').val() != ''){
                                $('.append_tr').append(`<td>Status<br>${response.customer_total_status}</td>`)
                            }else{
                                $('.append_tr').append(`<td></td>`)
                            }
                            $('#invoice_table').append(`</tr>`);
                            print('DivIdToPrint');
                        }
                    });
                    // }
                    }
                });
            }
        });
    });

    $(document).on('click','.print_receipt', function(){
        var transaction_id = $(this).attr('data-id');
        print_receipt(transaction_id);
    });
</script>
@endpush