<?php
/*
Template Name: Dasboard
Version: 1
Author: CEMA NEXT
Website: http://www.cemanext.it/
Contact: support@cemanext.it
Link:
Note: TEMPLATE ESEMPIO STAMPA WIDGET
*/
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');
include_once(BASE_ROOT.'config/confDebug.php');
include_once(BASE_ROOT.'libreria/libreria.php');
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
        <title><?php echo $site_name; ?> | </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?=BASE_URL?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?=BASE_URL?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?=BASE_URL?>/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?=BASE_URL?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />
      </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
        <!-- BEGIN HEADER -->
        <?php include(BASE_ROOT.'/assets/header_risultatiRicerca.php'); ?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <?php include(BASE_ROOT.'/assets/sidebar.php'); ?>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL TODO DA CANCELLARE -->

                    <!-- END THEME PANEL -->
                    <!-- BEGIN PAGE BAR
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="home.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Dashboard</span>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <div id="dashboard-report-range" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
                                <i class="icon-calendar"></i>&nbsp;
                                <span class="thin uppercase hidden-xs"></span>&nbsp;
                                <i class="fa fa-angle-down"></i>
                            </div>
                        </div>
                    </div>-->
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <?php //include(BASE_ROOT.'/assets/search_form.php'); ?>
                    <?php
                    if(isset($_GET['idMenu'])){
                      $sql_1= "SELECT * FROM lista_menu WHERE id='".$_GET['idMenu']."' AND stato='Attivo' $where_lista_menu ORDER BY ordine ASC";
                      $rs_1 = mysql_query($sql_1);
                      if($sql_1){
                        while($row_1 = mysql_fetch_array($rs_1,MYSQL_BOTH)){
                            echo '<h1 class="page-title">'.$row_1['nome'].' <small>'.$row_1['descrizione_breve'].' - '.$row_1['descrizione'].'</small></h1>';
                            //echo '<div>'.$row_1['descrizione_breve'].'</div>';
                            //echo '<div>'.$row_1['descrizione'].'</div>';
                        }
                      }else{

                      }
                    }else{
                      echo '<h1 class="page-title">STAMPA WIDGET<small></small></h1>';
                    }
                    ?>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- START ROW 1 - 6 COLUMN-->
                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                          <?php
                          $sql_007 = "SELECT * FROM lista_professionisti WHERE 1";
                          $titolo = 'Professionisti';
                          $icona = 'fa fa-users';
                          $colore = 'blue';
                          $link = '/moduli/anagrafiche/index.php?tbl=lista_professionisti&idMenu=3';
                          stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                          ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 6</h1></div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 6</h1></div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 6</h1></div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 6</h1></div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 6</h1></div>
                        </div>

                    </div>
                    <!-- END ROW 1 - 6 COLUMN-->
                    <!-- START ROW 2 - 4 COLUMN-->
                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <?php
                          $sql_007 = "SELECT * FROM lista_professionisti WHERE 1";
                          $titolo = 'Professionisti';
                          $icona = 'fa fa-users';
                          $colore = 'blue';
                          $link = '/moduli/anagrafiche/index.php?tbl=lista_professionisti&idMenu=3';
                          stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                          ?>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <div class="well well-lg"><h1>1 of 4</h1></div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 4</h1></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 4</h1></div>
                        </div>

                    </div>
                    <!-- END ROW 2 - 4 COLUMN-->
                    <!-- START ROW 3 - 3 COLUMN-->
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="well well-lg"><h1>1 of 3</h1></div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                        </div>

                    </div>
                    <!-- END ROW 3 - 3 COLUMN-->
                    <div class="clearfix"></div>

                    <!-- START ROW 4 - 2 COLUMN-->
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                          <?php
                          $sql_007 = "SELECT dataagg AS 'data', CONCAT('<i class=\"fa fa-building\" aria-hidden=\"true\"></i> ',ragione_sociale) AS 'campo_1', CONCAT('/moduli/anagrafiche/dettaglio.php?tbl=lista_aziende&id=',id,'') AS 'link', 'Vedi' AS 'nome_link' FROM lista_aziende  ORDER BY dataagg DESC LIMIT 10";
                          $titolo = 'Ultime Aziende';
                          $stile = '';
                          $colore = 'blue';
                          stampa_widget_tasks_1($sql_007, $titolo, $stile, $colore);
                          ?>
                        </div>
                        <div class="col-md-6 col-sm-6">
                        <?php
                        $sql_0002 = "SELECT dataagg AS 'data', CONCAT('<i class=\"fa fa-building\" aria-hidden=\"true\"></i> ',ragione_sociale) AS 'campo_1', CONCAT('/moduli/anagrafiche/dettaglio.php?tbl=lista_aziende&id=',id,'') AS 'link', 'Vedi' AS 'nome_link' FROM lista_aziende  ORDER BY dataagg DESC LIMIT 5";
                        stampa_table_static_basic($sql_0002,'','Lista Aziende', 'yellow');
                          ?>
                        </div>
                    </div>
                    <!-- END ROW 4 - 2 COLUMN-->
                    <div class="clearfix"></div>

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
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <?php include(BASE_ROOT.'/assets/quick_sidebar.php'); ?>
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> Copyright &copy; 2016 powered by CEMA NEXT - Ultimo aggiornamento <?php echo date("Y-m-d H:i:s");?></div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="<?=BASE_URL?>/assets/global/plugins/respond.min.js"></script>
<script src="<?=BASE_URL?>/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?=BASE_URL?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
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
        <script src="<?=BASE_URL?>/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/fullcalendar/locale/it.js" type="text/javascript"></script>
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
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!--<script src="<?=BASE_URL?>/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>-->
        <?php
                          $sql_007 = "SELECT ragione_sociale AS 'oggetto', DATE(data_creazione) AS 'data_inizio', '10:00:00' AS 'ora_inizio', DATE(dataagg) AS 'data_fine', '12:00:00' AS 'ora_fine', CONCAT('/moduli/anagrafiche/dettaglio.php?tbl=lista_aziende&id=',id,'') AS 'link', 'red' AS 'colore_sfondo'
                          FROM lista_aziende LIMIT 100";

$sql_007 = "SELECT oggetto AS 'oggetto', DATE(datainsert) AS 'data_inizio', orainsert AS 'ora_inizio', DATE(datainsert) AS 'data_fine', (orainsert+1) AS 'ora_fine', CONCAT('/moduli/base/dettaglio.php?tbl=calendario&id=',id,'') AS 'link', 'green' AS 'colore_sfondo'
                          FROM calendario LIMIT 100";
                          $defaultView = 'month';
                          $stile = '';
                          $colore = 'green';
                          stampa_calendario_1($sql_007, $defaultView, $stile, $colore);
?>

        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
<?php
echo '<div style="text-align:right; padding:30px; background-color:#FFF; color: red;">';
//echo '<h1>LIVELLO -> '.$_SESSION['livello_utente'].'</h1>';
//echo $sql_2;
//echo '$variabili_data_1 = '.$variabili_data_1;
echo '</div>';

?>
</html>
