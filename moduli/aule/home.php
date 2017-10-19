<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');
require_once(BASE_ROOT.'config/confPermessi.php');
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
        <link href="<?= BASE_URL ?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?= BASE_URL ?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
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
        <?php include(BASE_ROOT . '/assets/header_risultatiRicerca.php'); ?>
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
                    get_pagina_titolo($_GET['idMenu'], $where_lista_menu);
                    ?>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- START ROW 2 - 4 COLUMN-->
                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT COUNT(id) AS conteggio FROM lista_aule WHERE MONTH(data_inizio)=MONTH(CURDATE()) AND YEAR(data_inizio)=YEAR(CURDATE())";
                            $titolo = 'lista_aule del Mese';
                            $icona = 'fa fa-graduation-cap';
                            $colore = 'green-meadow';
                            $link = '/moduli/aule/index.php?tbl=lista_aule&idMenu=59';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT COUNT(*) AS conteggio FROM lista_aule WHERE YEAR(data_inizio_iscrizione)=YEAR(CURDATE()) AND (stato LIKE 'In Attesa' OR stato LIKE 'In Corso')";
                            $titolo = 'Iscritti Anno ';
                            $icona = 'fa fa-graduation-cap';
                            $colore = 'green-seagreen';
                            $link = '/moduli/aule/index.php?tbl=lista_aule&idMenu=59';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT COUNT(id) AS conteggio FROM lista_aule_dettaglio WHERE MONTH(data_completamento)=MONTH(CURDATE()) AND YEAR(data_completamento)=YEAR(CURDATE())";
                            $titolo = 'Completati del Mese';
                            $icona = 'fa fa-graduation-cap';
                            $colore = 'green-haze';
                            $link = '/moduli/aule/index.php?tbl=lista_aule&idMenu=59';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT COUNT(id) AS conteggio FROM lista_aule_dettaglio WHERE YEAR(data_completamento)=YEAR(CURDATE())";
                            $titolo = 'Completati Annuali';
                            $icona = 'fa fa-graduation-cap';
                            $colore = 'green-jungle';
                            $link = '/moduli/aule/index.php?tbl=lista_aule&idMenu=59';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>

                    </div>
                    <!-- END ROW 2 - 4 COLUMN-->
                    <!-- START ROW 2 - 4 COLUMN-->
                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT COUNT(stato) as conteggio FROM `lista_aule` WHERE 1 AND stato='In Attesa' GROUP BY stato";
                            $titolo = 'Totale Corsi - In Attesa';
                            $icona = 'fa fa-users';
                            $colore = 'yellow-saffron';
                            $link = '/moduli/aule/index.php?tbl=table_listaIscrizioniInAttesa&idMenu=105';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
                            ?>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <?php
                            $sql_007 = "SELECT COUNT(stato) as conteggio FROM `lista_aule` WHERE 1 AND stato='In Corso' GROUP BY stato";
                            $titolo = 'Totale Corsi - In Corso';
                            $icona = 'fa fa-users';
                            $colore = 'yellow-gold';
                            $link = '/moduli/iscrizioni/index.php?tbl=table_listaIscrizioniInCorso&idMenu=104';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
                        ?>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <?php
                            $sql_007 = "SELECT COUNT(stato) as conteggio FROM `lista_aule` WHERE 1 AND stato='Completato' GROUP BY stato";
                            $titolo = 'Totale Corsi - Completati';
                            $icona = 'fa fa-users';
                            $colore = 'green-jungle';
                            $link = '/moduli/aule/index.php?tbl=table_listaIscrizioniCompletati&idMenu=106';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
                        ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <?php
                            $sql_007 = "SELECT COUNT(*) as conteggio FROM `lista_aule` WHERE 1 AND stato='Configurazione'";
                            $titolo = 'Abbonamenti Attivi';
                            $icona = 'fa fa-users';
                            $colore = 'green-jungle';
                            $link = '';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
                        ?>
                        </div>
                            <div class="col-md-6 col-sm-6">
                            <?php
                            $sql_007 = "SELECT COUNT(*) as conteggio FROM `lista_aule` WHERE 1 AND stato LIKE 'Configurazione Scadut%'";
                            $titolo = 'Abbonamenti Scaduti';
                            $icona = 'fa fa-users';
                            $colore = 'red-thunderbird';
                            $link = '';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
                            ?>
                            </div>
                            <div class="col-md-6 col-sm-6">
                            <?php
                            $sql_007 = "SELECT COUNT(*) as conteggio FROM `lista_aule` WHERE abbonamento='0' AND stato LIKE 'Scaduto e Disattivato'";
                            $titolo = 'Corsi Singoli Scaduti';
                            $icona = 'fa fa-users';
                            $colore = 'red-flamingo';
                            $link = '';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
                            ?>
                            </div>
                    </div>
                    <!-- END ROW 2 - 4 COLUMN-->
                    <div class="clearfix"></div>

                    <!-- END ROW 4 - 2 COLUMN-->
                    <div class="clearfix"></div><script src="//www.google.com/jsapi" type="text/javascript"></script>


                    <!-- END ROW 3-->
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <?php include(BASE_ROOT . '/assets/quick_sidebar.php'); ?>
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <?=pageFooterCopy()?>
        <!--[if lt IE 9]>
        <script src="<?= BASE_URL ?>/assets/global/plugins/respond.min.js"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?= BASE_URL ?>/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="//www.google.com/jsapi" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- STAMPA GOOGLE CHART BAR -->
        <?php
        $sql_0006 = "SELECT LEFT(dataagg,7) as anno_mese, SUM(IF(stato LIKE 'Mai Contattato',1,0)) AS chiuso, SUM(IF(stato LIKE 'In Attesa',1,0)) AS in_attesa, SUM(IF(stato LIKE 'Negativo',1,0)) AS negativo FROM calendario GROUP BY LEFT(dataagg,7)";
        $title = 'Iscritti: Chiusi / In Attesa / Negativi';
        $vAxis = 'Conteggio';
        $hAxis = 'Anno-Mese';
        $stile = '';
        $colore = 'blue';
        stampa_gchart_col_1($sql_0006, $title, $vAxis, $hAxis, $stile, $colore);
        ?>
        <!-- STAMPA GOOGLE CHART BAR -->
        <?php
        $sql_0007 = "SELECT COUNT(stato) as conto, stato FROM calendario WHERE destinatario='" . $_SESSION['cognome_nome_utente'] . "' $where_calendario GROUP BY destinatario,stato ORDER BY stato";
        $title = '';
        $stile = '';
        $colore = 'blue';
        stampa_gchart_pie_1($sql_0007, $title, $stile, $colore);
        ?>
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
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
