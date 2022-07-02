CRM.$(function ($) {
    $(document).ready(function () {

        $("a.report_offline").click(function( event ) {
            event.preventDefault();
            var href = $(this).attr('href');
            var $el =CRM.loadForm(href, {
                dialog: {width: '50%', height: '50%'}
            }).on('crmFormSuccess', function() {
                var hm_tab = $('.selector-transactions');
                var hm_table = hm_tab.DataTable();
                hm_table.draw();
                
            });
        });

        
        $("a.report_online").click(function( event ) {
            event.preventDefault();
            var href = $(this).attr('href');
            var $el =CRM.loadForm(href, {
                dialog: {width: '50%', height: '50%'}
            }).on('crmFormSuccess', function() {
                var hm_tab = $('.selector-transactions');
                var hm_table = hm_tab.DataTable();
                hm_table.draw();
            });
        });

        // // $("a.devices-subtab").click(function (event) {
        // var devices_sourceUrl = CRM.vars.source_url['device_sourceUrl'];
        // // alert(devices_sourceUrl);
        // //Reset Table, add Filter and Search Possibility
        // //devices datatable
        // var devices_tab = $('.selector-devices');
        // var devices_table = devices_tab.DataTable();
        // var devices_dtsettings = devices_table.settings().init();
        // devices_dtsettings.bFilter = true;
        // //turn on search

        // devices_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        // //turn of search field
        // devices_dtsettings.sAjaxSource = devices_sourceUrl;
        // devices_dtsettings.Buttons = ["csv", "pdf", "copy"];
        // devices_dtsettings.fnInitComplete = function (oSettings, json) {
        // };
        // devices_dtsettings.fnDrawCallback = function (oSettings) {
        //     // $("a.view-device").css('background','red');
        //     $("a.view-device").click(function (event) {
        //         event.preventDefault();
        //         var href = $(this).attr('href');
        //         // alert(href);
        //         var $el = CRM.loadForm(href, {
        //             dialog: { width: '50%', height: '50%' }
        //         }).on('crmFormSuccess', function () {
        //             var hm_tab = $('.selector-devices');
        //             var hm_table = hm_tab.DataTable();
        //             hm_table.draw();
        //         });
        //     });
        //     // $("a.update-device").css('background','blue');
        //     $("a.update-device").off("click").click(function (event) {
        //         event.preventDefault();
        //         var href = $(this).attr('href');
        //         // alert(href);
        //         var $el = CRM.loadForm(href, {
        //             dialog: { width: '50%', height: '50%' }
        //         }).on('crmFormSuccess', function () {
        //             var hm_tab = $('.selector-devices');
        //             var hm_table = hm_tab.DataTable();
        //             hm_table.draw();
        //         });
        //     });
        //     $("a.delete-device").off("click").click(function (event) {
        //         event.preventDefault();
        //         var href = $(this).attr('href');
        //         // alert(href);
        //         var $el = CRM.loadForm(href, {
        //             dialog: { width: '50%', height: '50%' }
        //         }).on('crmFormSuccess', function () {
        //             var hm_tab = $('.selector-devices');
        //             var hm_table = hm_tab.DataTable();
        //             hm_table.draw();
        //         });
        //     });
        // };
        // devices_dtsettings.fnServerData = function (sSource, aoData, fnCallback) {
        //     aoData.push({
        //         "name": "device_type_id",
        //         "value": $('#device_device_type_id').val()
        //     });
        //     aoData.push({
        //         "name": "device_device_id",
        //         "value": $('#device_device_id').val()
        //     });
        //     aoData.push({
        //         "name": "contact_id",
        //         "value": $('#device_contact_id').val()
        //     });
        //     $.ajax({
        //         "dataType": 'json',
        //         "type": "POST",
        //         "url": sSource,
        //         "data": aoData,
        //         "success": fnCallback
        //     });
        // };
        // devices_table.destroy();
        // var new_devices_table = devices_tab.DataTable(devices_dtsettings);
        // //End Reset Table
        // $('.device-filter :input').change(function () {
        //     new_devices_table.draw();
        // });

    });
});