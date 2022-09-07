var submitted_form = "";
var type = "";
var category = "";
var transaction_id = 0;
var selectedCurrency = "";
var saved_transaction_id;
$(document).ready( function () {
    $('.locale-string-input').on('keyup', function(e) {
        var x = $(this).val();
        // accept only number and format it with commas
        $(this).val(x.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        // $(this).val(x.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    });

    $('.transactionModal-select2').select2({
        dropdownParent: $('#add-transaction-modal'),
        width: '100%',
    });
    $('.selections-card').click(function(){
        $(this).siblings().find('.checkmark').removeClass('required');
        $(this).find('.checkmark').removeClass('required');
    });
    const myModalEl = document.getElementById('add-transaction-modal')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $('.transaction-forms').hide();
        $('.selection-div').show();
        $('.daily-transaction-input').prop('checked', false);
        $('.error_text_2').text('');
    });
    $('.selection-next').click(function(){
        $(".error_text").text('');
        if ($('.selections-card.type input').is(':checked') && $('.selections-card.category input').is(':checked')) {
            type = $(".transaction_type:checked").val();
            category = $(".transaction_category:checked").val();
            $('.selection-div').hide();
            if ($('#local-check').is(':checked') && $('#paid-check').is(':checked')) {
                $('.local-paid').show();
                submitted_form = 'local-paid';
            } else if ($('#local-check').is(':checked') && $('#received-check').is(':checked')) {
                $('.local-received').show();
                submitted_form = 'local-received';
            } else if ($('#foreign-check').is(':checked') && $('#paid-check').is(':checked')) {
                $('.foreign-paid').show();
                submitted_form = 'foreign-paid';
            } else if ($('#foreign-check').is(':checked') && $('#received-check').is(':checked')) {
                $('.foreign-received').show();
                submitted_form = 'foreign-received';
            }
        } else {
            if (!$('.selections-card.type input').is(':checked')) {
                $('.selections-card.type .checkmark').addClass('required');
            } else {
                $('.selections-card.type .checkmark').removeClass('required');
            }
            if (!$('.selections-card.category input').is(':checked')) {
                $('.selections-card.category .checkmark').addClass('required');
            } else {
                $('.selections-card.category .checkmark').removeClass('required');
            }
        }
    });
    $('.select-currency').change(function(){
        selectedCurrency = $(this).find(":selected").attr('data-val');
        $(".currency_label").text(selectedCurrency);
        // $(this).parent().next().next().find('.input-group-text').text(selectedCurrency);
        calculate_exchange_rate();
        calculate_exchange_rate_received();
    });
    $('.daily-transaction-input-label').click(function(){
        $('.error_text_2').text('');
    });
});
$(".save_btn").on('click',function(){
    $(".save_btn").prop('disabled', true);
    // if (!$('.daily-transaction-input').is(':checked')) {
        // $('.error_text_2').text('Please select transaction type');
    // } else {
        var post_data = $("."+submitted_form+"").serialize();
        $.ajax({
            method: 'POST',
            url: 'save-transaction',
            data: post_data + '&type=' + type + '&category=' + category + '&transaction_id=' + transaction_id,
            success:function(response)
            {
                if(response.status != 200)
                {
                    $(".error_text").text(response.message);
                    $(".save_btn").prop('disabled', false);
                }else{
                    saved_transaction_id = response.transaction_id;
                    $("#add-transaction-modal").modal('hide');
                    $(".succsess_msg").text(response.message);
                    $(".succsess_msg").css('display','');
                    $('#transactionTable').dataTable().fnDestroy();
                    data_table();
                    if(transaction_id == 0){
                        alert_print_receipt();
                    }
                    $(".save_btn").prop('disabled', false);
                }
            }
        });
    // }
});

function openModal()
{
    get_transaction();
    selectedCurrency = "";
    $(".daily_transaction").attr('checked',false);
    $(".received_amount").val('')
    $(".paid_amount").val('')
    $(".customer").val('').change();
    $(".select-currency").val('').change();
    $("input[name=type]").prop('checked', false);
    $("input[name=category]").prop('checked', false);
    $(".description").val('');
    $(".exchange_rate").val('');
    $(".amount").val('');
    $(".currency_label").text("");
    $(".date").val('');
    transaction_id = 0;
    $("#add-transaction-modal").modal('show');
}


$(".calculate").on('keyup',function(){
    calculate_exchange_rate();
    calculate_exchange_rate_received();
});
function calculate_exchange_rate()
{
    if(selectedCurrency == "" || selectedCurrency == undefined){
        $("#foreign_paid_amount").val(0);
        $(".error_text").text('Please select currency first');
        return false;
    }else{
        var exchange_amount = 0;
        var paid_amount_val = $("#paid_ampunt_val").val().replace(/,/g, '');
        var exchange_rate = $("#exchange_rate").val().replace(/,/g, '');
        if(paid_amount_val > 0 && exchange_rate > 0)
        {
            if(selectedCurrency == 'PKR' || selectedCurrency == 'RMB'){
                exchange_amount = paid_amount_val / exchange_rate;
            }else{
                exchange_amount = paid_amount_val * exchange_rate;
            }
        }
        $("#foreign_paid_amount").val(Math.round(exchange_amount));
        var x = $("#foreign_paid_amount").val();
        $("#foreign_paid_amount").val(x.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    }
}

function calculate_exchange_rate_received()
{
    if(selectedCurrency == "" || selectedCurrency == undefined){
        $("#foreign_received_amount").val(0);
        $(".error_text").text('Please select currency first');
        return false;
    }else{
        var exchange_amount = 0;
        var paid_amount_val = $("#paid_ampunt_val_received").val().replace(/,/g, '');;
        var exchange_rate = $("#exchange_rate_received").val().replace(/,/g, '');;
        if(paid_amount_val > 0 && exchange_rate > 0)
        {
            if(selectedCurrency == 'PKR' || selectedCurrency == 'RMB'){
                exchange_amount = paid_amount_val / exchange_rate;
            }else{
                exchange_amount = paid_amount_val * exchange_rate;
            }
        }
        $("#foreign_received_amount").val(Math.round(exchange_amount));
        // var f = $("#foreign_received_amount").val();
    }   
}

//edit functionality
$(document).on('click','.edit-btn', function(){
    transaction_id = $(this).data('id');
    $.ajax({
        method: 'GET',
        url: "edit-transaction/"+transaction_id,
        success:function(transaction)
        {
            if(transaction != "")
            {
                 $(".received_amount").val(parseFloat(transaction.received_amount))
                $("input[name=daily_transaction][value=" + transaction.daily_transaction + "]").prop('checked', true);
                $("input[name=cancel_transaction][value=" + transaction.cancel_transaction + "]").prop('checked', true);
                $(".paid_amount").val(parseFloat(transaction.paid_amount))
                $("input[name=type][value=" + transaction.transaction_type + "]").prop('checked', true);
                $("input[name=category][value=" + transaction.transaction_category + "]").prop('checked', true);
                $(".description").val(transaction.description);
                $(".exchange_rate").val(parseFloat(transaction.exchange_rate));
                $(".amount").val(parseFloat(transaction.amount));
                $(".date").val(transaction.date);
                $("#add-transaction-modal").modal('show');
                 $(".customer").val(transaction.customer_id).change();
                $(".select-currency").val(transaction.currency_id).change();
                $(".transaction_with").val(transaction.transactoion_with).change();
            }
        }
    });
});

function get_transaction()
{
    $.ajax({
        method: 'GET',
        url: 'get-transaction',
        success:function(transactions)
        {
            $(".transactoion_with").empty();
            var linked_transaction = "<option value=''>Select Ttransaction</option>";
            $(transactions).each(function(index, transaction){
                linked_transaction += "<option value="+transaction.id+">"+transaction.name+"-"+transaction.id+"</option>"
            });

            $(".transactoion_with").append(linked_transaction);
        }
    });
}

function alert_print_receipt() {
    $("#print-modal").modal('show');
}
$('#print__receipt').click(function(){
    print_receipt(saved_transaction_id);
});

function print_receipt(transaction_id)
{
    $.ajax({
        method: 'GET',
        url: 'receipt_data',
        data: {transaction_id: transaction_id},
        success:function(response)
        {
            $(".transaction_id_receipt").empty();
            if(response.transaction_type == 'local'){
                var currency = 'AED';
            }else{
                var currency = response.currency;
            }
            $(".transaction_id_receipt").append(`<em>Transaction ID:`+response.name+'-'+response.id+'</em>');
            $(".transactio_currency").empty();
            $(".transactio_amount").empty();
            $(".customer_name").text(response.name);
            $(".receipt_description").text(response.description);
            $(".transactio_currency").append(response.transaction_type+'<br>'+currency);
            $(".transactio_amount").append(response.amount);
            $(".transactio_paid").text(response.paid_amount);
            $(".transactio_received").text(response.received_amount);
            print('DivIdToPrint_receipt');
        }
    });
}

function print(id)
{
    var divToPrint=document.getElementById(''+id+'');

    var newWin=window.open('Print-Window');

    newWin.document.open();

    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

    newWin.document.close();

    // setTimeout(function(){newWin.close();},10);      
}
