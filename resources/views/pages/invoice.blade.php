<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<style>
    .h4_ {
        font-size: 0.8rem;
        font-weight: 500;
        line-height: 1.2;
    }
    .h3_ {
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.2;
    }
    td {
        font-size: 13px;
        padding: 6px 5px !important;
    }
    .white-space-nowrap {
        white-space: nowrap;
    }
</style>
<div style="max-width: 200mm; margin: 50px auto 0 auto;">
    <div class="row mb-3">
        <div class="col-6">
            <address>
                <strong>Receipt</strong><br>
            </address>
        </div>
        <div class="col-6 text-end">
            <p class="mb-2">
                <em>Date: {{date('m/d/Y')}}</em>
            </p>
            <p id="transaction_id_receipt">
            </p>
        </div>
    </div>
    <div class="row total_transactions" style="display:">
        <div class="col-4">
            <h4 class="h4_">Total Transactions:</h4>
            <h3 class="h3_ total_transaction_invoice">0</h3>
        </div>
        <div class="col-4">
            <h4 class="h4_">Total Paid Transactions:</h4>
            <h3 class="h3_ total_paid_transaction_invoice">0</h3>
        </div>
        <div class="col-4">
            <h4 class="h4_">Total Received Transactions:</h4>
            <h3 class="h3_ total_received_transaction_invoice">0</h3>
        </div>
        <div class="col-4">
            <h4 class="h4_">Total Paid Amount:</h4>
            <h3 class="h3_ total_paid_amount_invoice">0</h3>
        </div>
        <div class="col-4">
            <h4 class="h4_">Total Received Amount:</h4>
            <h3 class="h3_ total_received_amount_invoice">0</h3>
        </div>
        <div class="col-4">
            <h4 class="h4_">Total Remaining Cash:</h4>
            <h3 class="h3_ total_remaining_cash_invoice">0</h3>
        </div>
        <div class="col-4">
            <h4 class="h4_">Status:</h4>
            <h3 class="h3_ customer_status_summary_invoice">0</h3>
        </div>
    </div>
    <div class="row">
        <table class="table table-hover" id="invoice_table" style="width: 100%">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Description</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Rate</th>
                    <th>Paid</th>
                    <th>Received</th>
                    <th class="amount_head" style="display:none">Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </div>
</div>