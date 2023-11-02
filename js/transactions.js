CRM.$(function ($) {

    var transactions_ajax_url = CRM.vars.source_url['transactions_ajax_url'];

    
    $(document).ready(function () {

        var transactions_tab = $('.selector-transactions');
        var transactions_table = transactions_tab.DataTable();
        var transactions_dtsettings = transactions_table.settings().init();
        transactions_dtsettings.bFilter = true;
        transactions_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of fields
        transactions_dtsettings.sAjaxSource = transactions_ajax_url;
        transactions_dtsettings.fnInitComplete = function (oSettings, json) {
        };

        transactions_dtsettings.fnDrawCallback = function (oSettings) {
        };

        transactions_dtsettings.fnServerData = function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "method",
                "value": $('#method').val()
            });
            aoData.push({
                "name": "sent_response",
                "value": $('#sent_response').val()
            });
            aoData.push({
                "name": "transaction_range_start_date",
                "value": $('#transaction_range_start_date').val()
            });
            aoData.push({
                "name": "transaction_range_end_date",
                "value": $('#transaction_range_end_date').val()
            });
            aoData.push({
                "name": "sent_range_start_date",
                "value": $('#sent_range_start_date').val()
            });
            aoData.push({
                "name": "sent_range_end_date",
                "value": $('#sent_range_end_date').val()
            });

            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };

        transactions_table.destroy();
        var new_transactions_table = transactions_tab.DataTable(transactions_dtsettings);
        // new_transactions_table.draw();
        //End Reset Table
        $('.transactions-filter :input').change(function () {

            // alert($('#transaction_range_start_date').val());
            new_transactions_table.destroy();
            new_transactions_table = transactions_tab.DataTable(transactions_dtsettings);
            new_transactions_table.draw();
        });

    });
});

