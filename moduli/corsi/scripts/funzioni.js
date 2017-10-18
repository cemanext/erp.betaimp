/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var BASE_URL_HOST = "http://"+window.location.hostname+"";

$( document ).ready(function() {
    
    BASE_URL_HOST = "http://"+window.location.hostname+"";
    
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": 3000,
        "extendedTimeOut": 0,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    }

    var urlReferer = get_referer();
    
    if($.urlParam('res')=="1"){
        toastr.success("Dati aggiornati!","Salvato");
        //toastr.success("Dati aggiornati!");
        pulisciRefereStorico();
    }
    
    if($.urlParam('res')=="0"){
        toastr.error("Si è verificato un errore nell'aggironamento dei dati.");
        pulisciRefereStorico();
    }
    
    
    TableDatatablesAjaxCorsi.init();
    TabelleCorsi.init();
});

var TableDatatablesAjaxCorsi = function () {

    var handleTableAjaxCorsi = function () {
        var table = $('#datatable_ajax_search');

        var oTable = table.dataTable({

            "processing": true,
            "serverSide": true,

            // Or you can use remote translation file
            "language": {
               url: '//cdn.datatables.net/plug-ins/1.10.12/i18n/Italian.json'
            },

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
              { extend: 'print', className: 'btn white btn-outline' },
              { extend: 'copy', className: 'btn white btn-outline' },
              { extend: 'pdf', className: 'btn white btn-outline' },
              { extend: 'excel', className: 'btn white btn-outline ' },
              { extend: 'csv', className: 'btn white btn-outline ' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: false,

            "ajax": {
                    "url": BASE_URL_HOST+"/moduli/corsi/scripts/server_processing.php?tbl="+$.urlParam('tbl'), // ajax source
            },

            //"ordering": true,
            "orderMulti": true,

            "order": [
                [3, 'asc']
            ],

            "lengthMenu": [
                [10, 25, 30, 50],
                [10, 25, 30, 50] // change per page values here
            ],
            // set the initial value
            "pageLength": 30,

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            "columnDefs": [
                {"className": "dt-center", "targets": "_all"},
                {"orderable": true, "targets": [ 3, 4, 5, 6, 7 ],},
                {"orderable": false, "targets": [ 0, 1, 2],}
            ],
            
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
            // So when dropdowns used the scrollable div should be removed.
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    var initTableAjax1 = function () {
        var table = $('#datatable_ajax');

        var oTable = table.dataTable({

            "processing": true,
            "serverSide": true,

            // Or you can use remote translation file
            "language": {
               url: '//cdn.datatables.net/plug-ins/1.10.12/i18n/Italian.json'
            },

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
              { extend: 'print', className: 'btn white btn-outline' },
              { extend: 'copy', className: 'btn white btn-outline' },
              { extend: 'pdf', className: 'btn white btn-outline' },
              { extend: 'excel', className: 'btn white btn-outline ' },
              { extend: 'csv', className: 'btn white btn-outline ' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: false,

            "ajax": {
                    "url": BASE_URL_HOST+"/moduli/corsi/scripts/server_processing.php?tbl="+$.urlParam('tbl'), // ajax source
            },

            //"ordering": true,
            "orderMulti": true,

            "order": [
                [3, 'asc']
            ],

            "lengthMenu": [
                [10, 25, 30, 50],
                [10, 25, 30, 50] // change per page values here
            ],
            // set the initial value
            "pageLength": 30,

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            "columnDefs": [
                {"className": "dt-center", "targets": "_all"},
                {"orderable": false, "targets": [ 0, 1, 2],}
            ],
            
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
            // So when dropdowns used the scrollable div should be removed.
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            //initPickers();
            handleTableAjaxCorsi();
            initTableAjax1();
        }

    };

}();

var TabelleCorsi = function () {

  var initTableBase1 = function () {
      var table = $('#tabella_base1, #tabella_base2, #tabella_base3, #tabella_base4, #tabella_base5, #tabella_base6, #tabella_base7, #tabella_base8, #tabella_base9, #tabella_base10');

      var oTable = table.dataTable({
          // Internationalisation. For more info refer to http://datatables.net/manual/i18n

          // Or you can use remote translation file
          "language": {
             url: '//cdn.datatables.net/plug-ins/1.10.12/i18n/Italian.json'
          },

          // setup buttons extentension: http://datatables.net/extensions/buttons/
          buttons: [
            { extend: 'print', className: 'btn white btn-outline' },
            { extend: 'copy', className: 'btn white btn-outline' },
            { extend: 'pdf', className: 'btn white btn-outline' },
            { extend: 'excel', className: 'btn white btn-outline ' },
            { extend: 'csv', className: 'btn white btn-outline ' }
          ],

          // setup responsive extension: http://datatables.net/extensions/responsive/
          responsive: false,

          "order": [
              [2, 'asc']
          ],

          "lengthMenu": [
              [10, 25, 30, 50],
              [10, 25, 30, 50] // change per page values here
          ],
          // set the initial value
          "pageLength": 30,

          "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

          // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
          // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
          // So when dropdowns used the scrollable div should be removed.
          //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
      });
  }
  
  


    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            initTableBase1();
        }

    };

}();