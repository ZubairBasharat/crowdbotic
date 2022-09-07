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
            <a href="{{ url('dashboard') }}">
                <button class="theme-btn">Back</button>
            </a>
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
                    </tr>
                </thead>
                <tbody id="history">
                    @php
                        $total_paid_amount = 0;
                        $total_received_amount = 0;
                    @endphp
                    @foreach($details as $detail)
                        @php
                            $total_paid_amount += intval(preg_replace('/[^\d.]/', '',  $detail->paid));
                            $total_received_amount +=intval(preg_replace('/[^\d.]/', '',  strip_tags($detail->received)));
                        @endphp
                        <tr>
                            <td>{{$detail->name}}</td>
                            <td>{{$detail->paid}}</td>
                            <td style="color:#ff6873">{{strip_tags($detail->received)}}</td>
                            @if(request()->is('pending-amount-details'))
                            <td>{{number_format($detail->profit)}} (Pending)</td>
                            @else
                            <td>{{number_format($detail->profit)}} (Balanced)</td>
                            @endif
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
        <td>Total Paid <br>{{number_format($total_paid_amount)}}</td>
        <td style="color:#ff6873">Total Received <br> {{number_format($total_received_amount)}}</td>
        <td></td>
        <td></td>
    </tr>`);

    $(".dataTables_scrollBody").animate({ scrollTop: $(".dataTables_scrollBody")[0].scrollHeight}, 1);
</script>
@endpush