<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');
require_once(BASE_ROOT.'config/confPermessi.php');
include_once(BASE_ROOT . 'moduli/corsi/funzioni.php');

if(isset($_GET['idMenu'])){
    $idMenu = $_GET['idMenu'];
}else{
    $idMenu = "";
}

/* 	fine post ricerca sinistra	 */

if (isset($_GET['tbl'])) {
    $tabella = $_GET['tbl'];
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title><?php echo $site_name; ?> | CALENDARIO</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/fullcalendar-3.6.1/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?= BASE_URL ?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->

        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
    </head>

    <!-- END HEAD -->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
        <!-- BEGIN HEADER -->
        <?php include(BASE_ROOT . '/assets/header.php'); ?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <?php include(BASE_ROOT . '/assets/sidebar.php'); ?>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL -->
                    <!-- END THEME PANEL -->
                    <!-- BEGIN PAGE BAR -->
                    <?php include(BASE_ROOT . '/assets/page_bar.php'); ?>
                    <!-- END PAGE BAR -->
                    <!-- START PAGE TITLE -->
                    <?php
                    get_pagina_titolo($idMenu, $where_lista_menu);
                    ?>
                    <!-- END PAGE TITLE -->
                    <!-- END PAGE HEADER-->
                    <?php //Stampa_HTML_index_Corsi($tabella); ?>


<!-- START ROW 5 - 1 COLUMN-->
                    <div class="row">
                        <div class="col-md-12">
                          <div class="portlet light portlet-fit bordered calendar">
                              <div class="portlet-title">
                                  <!--<div class="caption">
                                      <i class=" icon-layers font-green"></i>
                                      <span class="caption-subject font-green sbold uppercase">Calendario</span>
                                  </div>-->
                              </div>
                              <div class="portlet-body">
                                  <div class="row">
                                    <!-- BEGIN DRAGGABLE EVENTS PORTLET
                                      <div class="col-md-3 col-sm-12">

                                          <h3 class="event-form-title margin-bottom-20">Draggable Events</h3>
                                          <div id="external-events">
                                              <form class="inline-form">
                                                  <input type="text" value="" class="form-control" placeholder="Event Title..." id="event_title" />
                                                  <br/>
                                                  <a href="javascript:;" id="event_add" class="btn green"> Add Event </a>
                                              </form>
                                              <hr/>
                                              <div id="event_box" class="margin-bottom-10"></div>
                                              <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline" for="drop-remove"> remove after drop
                                                  <input type="checkbox" class="group-checkable" id="drop-remove" />
                                                  <span></span>
                                              </label>
                                              <hr class="visible-xs" /> </div>
                                      </div>-->
                                      <!-- END DRAGGABLE EVENTS PORTLET-->
                                      <div class="col-md-12 col-sm-12">
                                          <div id="calendar" class="has-toolbar"> </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        </div>
                    </div>
                    <!-- END ROW 3-->

                    <div class="form-actions left">
                        <a href="modifica.php?tbl=<?=$tabella?>&id=0" class="btn btn-circle btn-lg green-jungle"><i class="fa fa-plus"></i> Aggiungi Nuovo</a>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->

            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <?=pageFooterCopy()?>
        <!--[if lt IE 9]>
        <script src="<?= BASE_URL ?>/assets/global/plugins/respond.min.js"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?= BASE_URL ?>/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?=BASE_URL?>/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/fullcalendar-3.6.1/fullcalendar.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/fullcalendar-3.6.1/locale/it.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/horizontal-timeline/horozontal-timeline.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>

        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <?php
                        

$sql_007 = "SELECT oggetto AS 'oggetto', DATE(data) AS 'data_inizio', ora AS 'ora_inizio', CONCAT('dettaglio.php?tbl=calendario_esami&id=',id,'') AS 'link', 'green' AS 'colore_sfondo'
                          FROM calendario WHERE etichetta LIKE 'Calendario Esami' LIMIT 100";
                          $defaultView = 'month';
                          $stile = '';
                          $colore = 'green';
                          stampa_calendario_1($sql_007, $defaultView, $stile, $colore);
?>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!--<script src="<?= BASE_URL ?>/assets/pages/scripts/table-datatables-responsive.js" type="text/javascript"></script>-->
        <!--<script src="<?= BASE_URL ?>/assets/apps/scripts/todo-2.min.js" type="text/javascript"></script>-->
        <script src="<?= BASE_URL ?>/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/apps/scripts/index.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/apps/scripts/php.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/apps/scripts/utility.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/moduli/corsi/scripts/funzioni.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
