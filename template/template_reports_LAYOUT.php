<?php
/*
Template Name: Reports
Version: 1
Author: CEMA NEXT
Website: http://www.cemanext.it/
Contact: support@cemanext.it
Link:
Note: TEMPLATE ESEMPIO LAYOUT
*/
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');
include_once(BASE_ROOT.'config/confDebug.php');
include_once(BASE_ROOT.'libreria/libreria.php');

//RECUPERO LA VARIABILE POST DAL FORM defaultrange
if(isset($_POST['intervallo_data'])){
    $intervallo_data = $_POST['intervallo_data'];
    $data_in = before('|', $intervallo_data);
    $data_out = after('|', $intervallo_data);
    $where_intervallo = " AND lista_presenze.data BETWEEN  '".$data_in."' AND  '".$data_out."'";
    $titolo_intervallo = " dal  ".$data_in." al  ".$data_out."";
    //echo '<h1>$intervallo_data = '.$intervallo_data.'</h1>';
}else{
    $where_intervallo = " AND YEAR(lista_presenze.data)=YEAR(CURDATE()) AND MONTH(lista_presenze.data)=MONTH(CURDATE())";
    $titolo_intervallo = " del mese in corso";
}

$sql_2 = "SELECT SEC_TO_TIME(SUM(numerico_1)) as tempo_lavorazione_esteso, id_contatto, MONTH(data), YEAR(data)
FROM lista_presenze
WHERE id_contatto = '".$_SESSION['id_contatto_utente']."'
$where_intervallo";
$rs_2 = mysql_query($sql_2);
if($rs_2){
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){
          $tempo_lavorazione_esteso = $row_2['tempo_lavorazione_esteso'];
        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
}

$sql_2 = "SELECT ROUND(SUM( ((lista_presenze.numerico_1 /3600) * costo_prodotto )),2) AS COSTO, lista_presenze . * , lista_commesse_dettaglio . *
FROM lista_presenze
INNER JOIN lista_commesse_dettaglio ON lista_presenze.id_commessa = lista_commesse_dettaglio.id_commessa
WHERE 1 $where_intervallo
AND  lista_presenze.id_commessa_dettaglio = lista_commesse_dettaglio.id
AND lista_presenze.id_contatto ='".$_SESSION['id_contatto_utente']."'
AND lista_commesse_dettaglio.tipo_prodotto =  'Componente'";
$rs_2 = mysql_query($sql_2);
if($rs_2){
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){
            $totale_euro_produzione_mese = $row_2['COSTO'];
        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
}

$sql_2 = "SELECT * FROM lista_commesse_dettaglio WHERE campo_1 = 'In Attesa Account'";
$rs_2 = mysql_query($sql_2);
if($rs_2){
    $conteggio_commesse_in_attesa_account = mysql_num_rows($rs_2);
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){

        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
}

$sql_2 = "SELECT * FROM lista_commesse_dettaglio WHERE campo_1 = 'In Attesa Cliente'";
$rs_2 = mysql_query($sql_2);
if($rs_2){
    $conteggio_commesse_in_attesa_cliente = mysql_num_rows($rs_2);
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){

        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
}

$sql_2 = "SELECT * FROM lista_commesse_dettaglio WHERE campo_1 = 'In Attesa Fornitore'";
$rs_2 = mysql_query($sql_2);
if($rs_2){
    $conteggio_commesse_in_attesa_fornitore = mysql_num_rows($rs_2);
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){

        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
}


$sql_2 = "SELECT MONTH(data) AS MESE, YEAR(data) AS ANNO, ROUND(SUM( ((lista_presenze.numerico_1 /3600) * costo_prodotto )),2) AS COSTO
FROM lista_presenze
INNER JOIN lista_commesse_dettaglio ON lista_presenze.id_commessa = lista_commesse_dettaglio.id_commessa
WHERE 1 $where_intervallo
AND  lista_presenze.id_commessa_dettaglio = lista_commesse_dettaglio.id
AND lista_presenze.id_contatto ='".$_SESSION['id_contatto_utente']."'
AND lista_commesse_dettaglio.tipo_prodotto =  'Componente'
GROUP BY YEAR(lista_presenze.data), MONTH(lista_presenze.data)
ORDER BY MONTH(lista_presenze.data), YEAR(lista_presenze.data)";
$rs_2 = mysql_query($sql_2);
if($rs_2){
        $numero_record = mysql_num_rows($rs_2);
        $a_record = 1;
        $variabili_data_1 = '';
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){
        $costo_in_corso = $row_2['COSTO'];
        if($costo_in_corso>=0){
            $costo_in_corso = str_replace('.',',',$costo_in_corso);
        }else{

        }
            $variabili_data_1 .= "['".$row_2['MESE']."/".$row_2['ANNO']."', ".($costo_in_corso)."]";
                    if($a_record>=$numero_record){

					}else{
						$variabili_data_1 .= ', ';
					}
				$a_record++;
        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
}


$sql_2 = "SELECT MONTH(data) AS MESE, YEAR(data) AS ANNO, ROUND(SUM(lista_presenze.numerico_1 /3600),2) AS ORE
FROM lista_presenze
WHERE 1 $where_intervallo
AND lista_presenze.id_contatto = '".$_SESSION['id_contatto_utente']."'
GROUP BY YEAR(lista_presenze.data), MONTH(lista_presenze.data)
ORDER BY MONTH(lista_presenze.data), YEAR(lista_presenze.data)";
$rs_2 = mysql_query($sql_2);
if($rs_2){
        $numero_record = mysql_num_rows($rs_2);
        $a_record = 1;
        $variabili_data_2 = '';
        while($row_2 = mysql_fetch_array($rs_2, MYSQL_BOTH)){
        $ore_in_corso = $row_2['ORE'];
        if($ore_in_corso>0){
            $ore_in_corso = str_replace('.',',',$ore_in_corso);
        }else{

        }
            $variabili_data_2 .= "['".$row_2['MESE']."/".$row_2['ANNO']."', ".($ore_in_corso)."]";
                    if($a_record>=$numero_record){

					}else{
						$variabili_data_2 .= ', ';
					}
				$a_record++;
        }
}else{
    echo '<li>Errore: '.mysql_error().'</li>';
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
        <title><?php echo $site_name; ?> |</title>
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
        <link href="<?=BASE_URL?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
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
        <link rel="shortcut icon" href="favicon.ico" /> </head>
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
                    <div class="row">

                        <div class="col-md-6">
                            <form action="<?=BASE_URL?>/template/template_reports_LAYOUT.php" class="form-horizontal form-bordered" method="POST" id="formIntervallo" name="formIntervallo">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Intervallo </label>
                                        <div class="col-md-9">
                                            <div class="input-group" id="defaultrange" name="defaultrange">
                                                <input type="text" class="form-control" id="intervallo_data" name="intervallo_data" onChange="submit();">
                                                <span class="input-group-btn">
                                                    <button class="btn default date-range-toggle" type="submit">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                          </div>

                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <?php //include('search_form.php'); ?>

                    <!--<h3 class="page-title"> Dashboard
                        <small>& Statistiche</small>
                    </h3>-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT * FROM lista_professionisti WHERE 1";
                            $titolo = 'Totale Ore';
                            $icona = 'fa fa-line-chart';
                            $colore = 'yellow-lemon';
                            //$link = '/moduli/anagrafiche/index.php?tbl=lista_professionisti&idMenu=3';
                            $link = '#';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT * FROM lista_professionisti WHERE 1";
                            $titolo = 'Totale Euro';
                            $icona = 'fa fa-area-chart';
                            $colore = 'blue';
                            //$link = '/moduli/anagrafiche/index.php?tbl=lista_professionisti&idMenu=3';
                            $link = '#';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                          </div>
                    </div>
                    <div class="clearfix"></div>

                    <!-- END DASHBOARD STATS 1-->
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="portlet">
                            <!--<div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-search"></i>Trovati "X" risultati
                                </div>
                                <div class="actions"> </div>
                            </div>-->
                            <div class="portlet-body">
                              <ul class="nav nav-pills">
                                  <li class="active">
                                      <a href="#tab_per_commesse" data-toggle="tab" aria-expanded="true"> Per Commessa </a>
                                  </li>
                                  <li class="">
                                      <a href="#tab_per_attivita" data-toggle="tab" aria-expanded="false"> Per Attivitá </a>
                                  </li>
                              </ul>
                            </div>
                              <div class="tab-content">
                                  <div class="tab-pane fade active in" id="tab_per_commesse">
                                      <div class="row">
                                          <div class="col-md-12">
                                            <!-- INIZIO TABELLA-->
                                            <div class="portlet box blue">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-list"></i>
                                                        <span class="caption-subject bold uppercase">PER COMMESSA</span>
                                                    </div>
                                                    <div class="tools"> </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tab1_per_commessa">
                                                        <thead>
                                                            <tr>
                                                                <th class="all">Commessa</th>
                                                                <th class="min-phone-l">Totale Ore [<?php echo $tempo_lavorazione_esteso;?>]</th>
                                                                <th class="min-phone-l">Totale &euro; [<?php echo $totale_euro_produzione_mese;?>]</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php

                                                          $sql_001 = "SELECT DISTINCT (SELECT DISTINCT nome FROM lista_commesse WHERE id = lista_presenze.id_commessa) AS nome_commessa,
                                                          SEC_TO_TIME(SUM(lista_presenze.numerico_1)) AS totale_ore,
                                                          ROUND(SUM( ((lista_presenze.numerico_1 /3600) * costo_prodotto )),2) AS totale_euro
                                                          FROM lista_presenze
                                                         INNER JOIN lista_commesse_dettaglio ON lista_presenze.id_commessa = lista_commesse_dettaglio.id_commessa
                                                          WHERE 1 $where_intervallo
                                                          AND  lista_presenze.id_commessa_dettaglio = lista_commesse_dettaglio.id
                                                          AND lista_presenze.id_contatto ='".$_SESSION['id_contatto_utente']."'
                                                          AND lista_commesse_dettaglio.tipo_prodotto =  'Componente'
                                                          GROUP BY lista_presenze.id_commessa";
                                                          $rs_001 = mysql_query($sql_001);
                                                          if($rs_001){
                                                            $conteggio_001 = mysql_num_rows($rs_001);
                                                              while($row_001 = mysql_fetch_array($rs_001, MYSQL_BOTH)){
                                                                echo '<tr>
                                                                    <td>'.$row_001['nome_commessa'].'</td>
                                                                    <td>'.$row_001['totale_ore'].'</td>
                                                                  <td>'.$row_001['totale_euro'].'</td>
                                                                </tr>';
                                                              }
                                                          }else{
                                                            echo '<li>Errore: '.mysql_error().'</li>';
                                                          }

                                                          ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- FINE TABELLA-->
                                          </div>
                                      </div>
                                  </div>
                                  <div class="tab-pane fade" id="tab_per_attivita">
                                    <div class="row">
                                        <div class="col-md-12">
                                          <!-- INIZIO TABELLA-->
                                          <div class="portlet box blue">
                                              <div class="portlet-title">
                                                  <div class="caption">
                                                      <i class="fa fa-list"></i>
                                                      <span class="caption-subject bold uppercase">PER ATTIVITA</span>
                                                  </div>
                                                  <div class="tools"> </div>
                                              </div>
                                              <div class="portlet-body">
                                                  <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tab1_per_attivita">
                                                      <thead>
                                                          <tr>
                                                              <th class="all">Attivitá</th>
                                                              <th class="min-phone-l">Totale Ore</th>
                                                              <th class="none">Totale &euro;</th>
                                                              <!--<th class="all"><i class="icon-wrench"></i></th>-->
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php

                                                        $sql_001 = "SELECT DISTINCT
                                                        (SELECT DISTINCT nome FROM lista_commesse WHERE id = id_commessa) AS nome_commessa,
                                                        (SELECT DISTINCT nome_prodotto FROM lista_commesse_dettaglio WHERE id = id_commessa_dettaglio) AS nome_processo,
                                                        SEC_TO_TIME(SUM(numerico_1)) AS totale_ore
                                                        FROM lista_presenze
                                                        WHERE 1
                                                        $where_intervallo
                                                         AND (lista_presenze.id_commessa>0 AND lista_presenze.id_commessa_dettaglio>0)
                                                        AND id_contatto = '".$_SESSION['id_contatto_utente']."'
                                                        GROUP BY id_commessa, id_commessa_dettaglio";
                                                        $rs_001 = mysql_query($sql_001);
                                                        if($rs_001){
                                                          $conteggio_001 = mysql_num_rows($rs_001);
                                                            while($row_001 = mysql_fetch_array($rs_001, MYSQL_BOTH)){
                                                              echo '<tr>
                                                                  <td>'.$row_001['nome_commessa'].' / '.$row_001['nome_processo'].'</td>
                                                                  <td>'.$row_001['totale_ore'].'</td>
                                                                  <td>'.$row_001['totale_euro'].'</td>
                                                              </tr>';
                                                            }
                                                        }else{
                                                          echo '<li>Errore: '.mysql_error().'</li>';
                                                        }

                                                        ?>

                                                      </tbody>
                                                  </table>
                                              </div>
                                          </div>
                                          <!-- FINE TABELLA-->
                                        </div>
                                    </div>
                                  </div>
                              </div>

                              </div>

                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <!-- ESEMPIO STAMPIA GOOGLE CHART -->
                        <div class="portlet light portlet-fit bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-bar-chart"></i>
                                    <span class="caption-subject bold uppercase">Esempio gchart_col_1</span>
                                </div>
                                <div class="actions">
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="gchart_col_1" style="height:500px;"></div>
                            </div>
                        </div>

                      </div>
                    </div>

                    </div>
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
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="//www.google.com/jsapi" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS
        <script src="<?=BASE_URL?>/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>-->
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
          $sql_0007 = "SELECT COUNT(stato) as conto, stato FROM calendario WHERE destinatario='".$_SESSION['cognome_nome_utente']."' $where_calendario GROUP BY destinatario,stato ORDER BY stato";
          $title = '';
          $stile = '';
          $colore = 'blue';
          stampa_gchart_pie_1($sql_0007, $title, $stile, $colore);
        ?>
        <script>
      $('#defaultrange').daterangepicker({
                opens: (App.isRTL() ? 'left' : 'right'),
                format: 'YYYY-MM-DD',
                separator: ' to ',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                ranges: {
                    'Oggi': [moment(), moment()],
                    'Ieri': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimi 7 giorni': [moment().subtract(6, 'days'), moment()],
                    'Ultimi 30 giorni': [moment().subtract(29, 'days'), moment()],
                    'Questo mese': [moment().startOf('month'), moment().endOf('month')],
                    'Scorso Mese': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            },
            function (startDate, endDate) {
                $('#defaultrange input').val(startDate.format('YYYY-MM-DD') + '|' + endDate.format('YYYY-MM-DD'));
                $('#defaultrange input').html(startDate.format('DD-MM-YYYY') + ' a ' + endDate.format('DD-MM-YYYY'));
            }
        );
        </script>
        <script src="<?=BASE_URL?>/assets/pages/scripts/table-datatables-responsive.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
<?php
echo '<br>'.$variabili_data_1;
echo '<br>'.$variabili_data_2;
echo '<br>'.$numero_record;
?>
</html>
