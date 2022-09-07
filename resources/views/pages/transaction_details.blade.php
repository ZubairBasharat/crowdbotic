@extends('layouts.app')
@section('content')
<style>
    a {
        text-decoration: none !important;
    }
    
    #transactionTable thead th {
        background-color: white;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .dataTables_scrollHeadInner, .table{
        width:100%!important; 
    }
</style>
<div class="main-padding">
    <div class="table-card">
        <div class="alert alert-success mt-3 succsess_msg" style="display: none;">

        </div>
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="heading">Transactions History</h1>
            <a href="{{ url('transactions') }}">
                <button class="theme-btn">Back</button>
            </a>
        </div>
        <div class="table-responsive">
            <table id="transactionTable" class="theme-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer Name</th>
                        <th>Description</th>
                        <th>Foreigh Currency</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Local Currency</th>
                        <th>Paid</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody id="history">
                    @php
                        $total_paid_amount = 0;
                        $total_received_amount = 0;
                    @endphp
                    @foreach($transactions as $transaction)
                        @php
                            $total_paid_amount += $transaction->paid_amount;
                            $total_received_amount += $transaction->received_amount;
                        @endphp
                        <tr>
                            <td>{{$transaction->name.'-'.$transaction->id}}</td>
                            <td>{{$transaction->name}}</td>
                            <td>{{$transaction->description}}</td>
                            <td>{{$transaction->transaction_type}}<br>{{$transaction->currency}}</td>
                            <td>{{number_format(round($transaction->amount))}}</td>
                            <td>{{$transaction->exchange_rate}}</td>
                            <td>AED</td>
                            <td>{{empty($transaction->paid_amount) ? 0 : $transaction->paid_amount}}</td>
                            <td style="color:red">{{empty($transaction->received_amount) ? 0 : $transaction->received_amount}}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var table = $('#transactionTable').DataTable({
        searching: true,
        lengthChange: false,
        scrollY: 'calc(100vh - 360px)',
        bInfo : false,
        aLengthMenu:  [
                [2500, 5000, 10000, 20000, -1],
                [2500, 5000, 10000, 20000, "All"]
            ],
    });
    $("#history").append(` <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total:</td>
                            <td>{{number_format($total_paid_amount)}}</td>
                            <td style="color:red">{{number_format($total_received_amount)}}</td>
                        </tr>`);
    $(".dataTables_scrollBody").animate({ scrollTop: $(".dataTables_scrollBody")[0].scrollHeight}, 1);
</script>
@endpush