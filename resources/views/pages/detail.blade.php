@extends('layouts.app')
@section('content')
<style>
    a {
        text-decoration: none !important;
    }
</style>
<div class="main-padding">
    <div class="table-card">
        <div class="alert alert-success mt-3 succsess_msg" style="display: none;">

        </div>
        <div class="mb-4">
            <h1 class="heading mb-3">Transactions History</h1>
            <div class="d-flex">
                <button class="theme-btn me-2" onclick="openModal()">Add New Transaction</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="transactionTable" class="theme-table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <!-- <th>Transaction ID</th> -->
                        <th>Paid</th>
                        <th>Received</th>
                        <th>Amount - Status</th>
                        <th>View</th>
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
                            <div style="margin-bottom: 18px;">
                                <label for="" class="theme-label">Select customer</label>
                                <select name="customer" id="" class="transactionModal-select2 customer">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="margin-bottom: 18px;">
                                <label for="" class="theme-label">Link Transaction With</label>
                                <select name="transactoion_with" id="" class="transactionModal-select2 transactoion_with">
                                </select>
                            </div>
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
                            <div style="margin-bottom:18px">
                                <label for="" class="theme-label">Link Transaction With</label>
                                <select name="transactoion_with" id="" class="transactionModal-select2 transactoion_with">
                                </select>
                            </div>
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
                            <label for="" class="theme-label">Linke Transaction With</label>
                            <select name="transactoion_with" id="" class="transactionModal-select2 transactoion_with">
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
<!-- Modal -->
<div class="modal" id="print-modal" tabindex="-1" aria-labelledby="print-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close selection-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center selection-div mt-4">
                    <h2 class="mb-3">Print Receipt</h2>
                    <p class="mb-4">Do you want to print your receipt?</p>
                    <div class="flex-center selection-footer">
                        <button class="theme-btn btn-none" data-bs-dismiss="modal" type="button">No</button>
                        <button type="button" class="theme-btn ms-2" id="print__receipt" data-bs-dismiss="modal" style="width: 90px;">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id='DivIdToPrint_receipt' style="display:none">
   @include('pages.receipt')
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/transaction.js') }}"></script>
<script type="text/javascript">
    $(function() {
        data_table();
    });
    function data_table()
    {
        var table = $('#transactionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('transaction.index') }}",
                data: function(d) {
                    //   d.approved = $('#approved').val(),
                    //  d.search = $('input[type="search"]').val()
                }
            },
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'paid',
                        name: 'paid'
                    },
                    {
                        data: 'received',
                        name: 'received'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'detial',
                        name: 'detial'
                    }
                ]
            });
            //   $('#approved').change(function(){
            //       table.draw();
            //   });
        }
    </script>
@endpush