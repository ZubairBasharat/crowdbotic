@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<div class="main-padding">
    <div class="table-card">
        <div class="table-responsive">
            <table id="transactionTable" class="theme-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Description</th>
                        <th>Currency</th>
                        <th>Rate</th>
                        <th>Paid</th>
                        <th>Received</th>
                        <th>Is Daily Transation</th>
                        {{-- <th>Total AED</th> --}}
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
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
    $(function() {
            data_table();
        });
        function data_table()
        {
            var table = $('#transactionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('delete.transaction') }}",
                data: function(d) {
                   // alert($('#daily_transaction').is(':checked'));

                  d.currency = $('#currency').val(),
                  d.customer = $('#select-customer').val(),
                  d.daily_transaction = $('#daily_transaction').is(':checked')?1:0
                }
            },
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'transaction_type',
                    name: 'transaction_type'
                },
                {
                    data: 'exchange_rate',
                    name: 'exchange_rate'
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
                // {
                //     data: 'total_amount',
                //     name: 'total_amount'
                // },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ]
        });

            //   $('#approved').change(function(){
            //       table.draw();
            //   });
          $('#currency').change(function(){
            $('#transactionTable').dataTable().fnDestroy();
                data_table();
          });
          $('#select-customer').change(function(){
            $('#transactionTable').dataTable().fnDestroy();
                data_table();
          });
          $('#daterange').change(function(){
            $('#transactionTable').dataTable().fnDestroy();
                data_table();
          });
          $('#daily_transaction').change(function(){
            $('#transactionTable').dataTable().fnDestroy();
                data_table();
          });
        }
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
</script>
@endpush