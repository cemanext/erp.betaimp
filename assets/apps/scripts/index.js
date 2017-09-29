/**
jektypro custom javascript
**/
var JektyPro = function () {

    // private functions & variables

    var _initComponents = function() {

        // init datepicker
        $('.todo-taskbody-due').datepicker({
            rtl: App.isRTL(),
            language: "it",
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: true
        });

        // init datepicker
        $('.timepicker-data-messaggio').datepicker({
            rtl: App.isRTL(),
            language: "it",
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: true
        });

        $('.timepicker-data-costo').datepicker({
            rtl: App.isRTL(),
            language: "it",
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: true
        });

        $('.timepicker-data-scadenza-costo').datepicker({
            rtl: App.isRTL(),
            language: "it",
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: true
        });

        // init select2 sottoStato
        $("#sottoStato").select2({
            placeholder: 'Seleziona Stato',
            minimumResultsForSearch: Infinity
        });
        $("#prioritaProcesso").select2({
            placeholder: 'Seleziona Priorita',
            minimumResultsForSearch: Infinity
        });
    }



    // public functions
    return {

        //main function
        init: function () {
            _initComponents();
            //_handleProjectListMenu();

            //App.addResizeHandler(function(){
            //    _handleProjectListMenu();
            //});
        }

    };

}();

var UIGeneral = function () {

    var handlePulsate = function () {
        if (!jQuery().pulsate) {
            return;
        }

        if (App.isIE8() == true) {
            return; // pulsate plugin does not support IE8 and below
        }

        if (jQuery().pulsate) {
            jQuery('#pulsate-regular').pulsate({
                color: "#bf1c56"
            });

            jQuery('#pulsate-once').click(function () {
                $('#pulsate-once-target').pulsate({
                    color: "#399bc3",
                    repeat: false
                });
            });

            jQuery('#pulsate-crazy').click(function () {
                $('#pulsate-crazy-target').pulsate({
                    color: "#fdbe41",
                    reach: 50,
                    repeat: 10,
                    speed: 100,
                    glow: true
                });
            });
        }
    }


    return {
        //main function to initiate the module
        init: function () {
            handlePulsate();

        }

    };

}();

var TableDatatablesResponsive = function () {

  //CONFIGURAZIONE
  var initTable1 = function () {
      var table = $('#tabella_base');

      var oTable = table.dataTable({
          // Internationalisation. For more info refer to http://datatables.net/manual/i18n
          /*"language": {
              "aria": {
                  "sortAscending": ": activate to sort column ascending",
                  "sortDescending": ": activate to sort column descending"
              },
              "emptyTable": "No data available in table",
              "info": "Showing _START_ to _END_ of _TOTAL_ entries",
              "infoEmpty": "No entries found",
              "infoFiltered": "(filtered1 from _MAX_ total entries)",
              "lengthMenu": "_MENU_ entries",
              "search": "Search:",
              "zeroRecords": "No matching records found"
          },*/

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
              [10, 25, 30, 50, -1],
              [10, 25, 30, 50, "Tutti"] // change per page values here
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
  
  var initTableBase2 = function () {
      var table = $('#tabella_base2');

      var oTable = table.dataTable({
          // Internationalisation. For more info refer to http://datatables.net/manual/i18n
          /*"language": {
              "aria": {
                  "sortAscending": ": activate to sort column ascending",
                  "sortDescending": ": activate to sort column descending"
              },
              "emptyTable": "No data available in table",
              "info": "Showing _START_ to _END_ of _TOTAL_ entries",
              "infoEmpty": "No entries found",
              "infoFiltered": "(filtered1 from _MAX_ total entries)",
              "lengthMenu": "_MENU_ entries",
              "search": "Search:",
              "zeroRecords": "No matching records found"
          },*/

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
              [10, 25, 30, 50, -1],
              [10, 25, 30, 50, "Tutti"] // change per page values here
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

    var initTable2 = function () {
        var table = $('#sample_2');

        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
                { extend: 'print', className: 'btn dark btn-outline' },
                { extend: 'pdf', className: 'btn green btn-outline' },
                { extend: 'csv', className: 'btn purple btn-outline ' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columnDefs: [ {
                className: 'control',
                orderable: false,
                targets:   0
            } ],

            order: [ 1, 'asc' ],

            // pagination control
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "pagingType": 'bootstrap_extended', // pagination type

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
            // So when dropdowns used the scrollable div should be removed.
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    var initTable3 = function () {
        var table = $('#sample_3');

        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
                { extend: 'print', className: 'btn default' },
                { extend: 'pdf', className: 'btn default' },
                { extend: 'csv', className: 'btn default' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                details: {

                }
            },

            "order": [
                [0, 'asc']
            ],

            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
            // So when dropdowns used the scrollable div should be removed.
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    var initTable4 = function () {
        var table = $('#sample_4');

        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
                { extend: 'print', className: 'btn default' },
                { extend: 'pdf', className: 'btn default' },
                { extend: 'csv', className: 'btn default' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                details: {

                }
            },

            "order": [
                [0, 'asc']
            ],

            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,

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

            initTable1();
            initTableBase2();
            initTable2();
            initTable3();
            initTable4();
        }

    };

}();

var ComponentsEditors = function () {

    var handleWysihtml5 = function () {
        if (!jQuery().wysihtml5) {
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5({
                "stylesheets": ["/assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            });
        }
    }
    
    var handleWysihtml5Destroy = function () {
        //$('#summernote_1').summernote({height: 300});
        //API:
        //var sHTML = $('#summernote_1').code(); // get code
        $('.wysihtml5').remove();
        //$('#summernote_1').destroy(); // destroy
    }


    var handleSummernote = function () {
        $('#summernote_1').summernote({height: 300});
        //API:
        //var sHTML = $('#summernote_1').code(); // get code
        //$('#summernote_1').destroy(); // destroy
    }
    
    var handleSummernoteDestroy = function () {
        //$('#summernote_1').summernote({height: 300});
        //API:
        //var sHTML = $('#summernote_1').code(); // get code
        $('#summernote_1').summernote('destroy');
        //$('#summernote_1').destroy(); // destroy
    }

    return {
        //main function to initiate the module
        init: function () {
            handleWysihtml5();
            //handleSummernote();
        },
        
        destroy: function () {
            handleWysihtml5Destroy();
            //handleSummernoteDestroy();
        }
    };

}();


if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function() {
        JektyPro.init();
        UIGeneral.init();
        TableDatatablesResponsive.init();
    });
}
