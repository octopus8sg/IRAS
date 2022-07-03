CRM.$(function ($) {

    var transactions_ajax_url = CRM.vars.source_url['transactions_ajax_url'];
    var offline_url = '';
    var filename = '';
    (function (open) {
        XMLHttpRequest.prototype.open = function (m, u, a, us, p) {
            this.addEventListener('readystatechange', function (event) {
                if (this.responseURL.includes(offline_url + '&snippet=json')) {
                    const _filename = this.getResponseHeader('Content-Disposition')?.split("filename=")[1]?.split(';')[0].replaceAll('"', '');
                    const contentType = this.getResponseHeader('Content-Type');
                    if (filename !== _filename && contentType === 'application/csv' && this.responseText.length > 0) {
                        var csvElement = document.createElement('a');
                        csvElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(this.responseText);
                        csvElement.target = '_blank';
                        csvElement.download = _filename;
                        filename = _filename;
                        csvElement.click();
                    }
                }
            }, false);
            open.call(this, m, u, a, us, p);
        };
    })(XMLHttpRequest.prototype.open)

    $(document).ready(function () {

        $("a.report_offline").click(function (event) {
            event.preventDefault();
            var href = $(this).attr('href');
            var $el = CRM.loadForm(href, {
                dialog: { width: '50%', height: '50%' }
            }).on('crmFormSubmit', function (event, data) {
                filename = '';
                offline_url = href;
                $el.dialog('close');
            });
        });

        $("a.report_online").click(function (event) {
            event.preventDefault();
            var href = $(this).attr('href');
            var $el = CRM.loadForm(href, {
                dialog: { width: '50%', height: '50%' }
            }).on('crmFormSuccess', function () {
                var hm_tab = $('.transactions');
                var hm_table = hm_tab.DataTable();
                hm_table.draw();
            });
        });

        var transactions_tab = $('.transactions');
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
        //End Reset Table
        $('.transactions-filter :input').change(function () {
            console.log('changed')

            new_transactions_table.draw();
        });

    });
});

