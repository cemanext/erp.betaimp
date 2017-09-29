<?php
/*
* ELENCO RICERCA TEMPLATE
*/
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');
include_once(BASE_ROOT.'config/confDebug.php');
include_once(BASE_ROOT.'libreria/libreria.php');

if(isset($_GET['tbl'])){
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
        <title><?php echo $site_name; ?> | Elenco Ricerca Template</title>
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
        <link href="<?=BASE_URL?>/assets/pages/css/search.min.css" rel="stylesheet" type="text/css" />
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

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white page-sidebar-fixed">
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
                    <!-- BEGIN PAGE BAR -->

                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <!-- FORM CERCA-->
                    <?php //include(BASE_ROOT.'/assets/search_form.php'); ?>
                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                                    echo '<h1 class="page-title"><small></small></h1>';
                            }
                            ?>
                        </div>

                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                          <div class="search-page search-content-4">
                              <form action="" method="POST">
                                  <div class="search-bar ">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div class="input-group">
                                                  <input type="text" class="form-control" placeholder="Digita qui il criterio di ricerca..." id="findme" name="findme">
                                                  <span class="input-group-btn">
                                                      <button type="submit" class="btn blue uppercase bold" type="button">Cerca</button>
                                                  </span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                            </form>
                          </div>
                        </div>
                    </div>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <div class="clearfix"></div>
                    <div style="margin-bottom:25px"></div>

                    <!-- START ROW 3-->
                    <div class="row">
                        <div class="col-md-12">
                          <?php
                          $conteggio = 0;
																								if(isset($_POST['findme']) and strlen($_POST['findme'])>2){
																								$findme = $_POST['findme'];
																								echo 'Hai cercato <b>'.$findme.'</b>';

																								//path to directory to scan
																								$directory = $_SERVER['DOCUMENT_ROOT'].'/template/';
                                                //$directory = BASE_URL.'/assets/';
																								//get all text files with a .txt extension.
																								$texts = glob($directory . "*.php");

																								//print each file name
																								echo '<ol class="list-group">';

																								foreach($texts as $text){
																								$pos = stripos($text, $findme);
																								$nome_de_file = str_replace('/home/betaformazione/erp.betaformazione.com/template/','',$text);
																								if ($pos !== false){
																								$conteggio ++;
																								$thereisafile = true;//Tell the script something was found.
																								//Display the search results as links.
																								//echo'<a href="' . $dirname . $file . '">' . $file . '</a><br>';
                                                    if ($nome_de_file == "index.php") {
                                                      $conteggio --;
                                                      # code...
                                                    }else {
                                                      echo '<li class="list-group-item"><a href="'.$nome_de_file.'" target="_blank"><i class="fa fa-file-o" aria-hidden="true"></i> '.$nome_de_file.' </a></li>';
                                                    }
																								//echo '<li class="list-group-item"><a href="'.$nome_de_file.'" target="_blank"><i class="fa fa-file-o" aria-hidden="true"></i> '.$nome_de_file.' </a></li>';
																								}else{
																								//Leave this blank
																								//echo '<li><img src="http://agrifacile.it/AzMarangoni/images/pdf20.png" style="vertical-align:middle;"><a href="http://agrifacile.it/AzMarangoni/lista_appezzamenti/'.$text.'" target="_blank">'.$text.'</a></li>';
																								//echo '<li><img src="http://agrifacile.it/AzMarangoni/images/pdf20.png" style="vertical-align:middle;"><a href="http://www.ultrasci.it//'.$text.'" target="_blank"> '.$nome_de_file.' </a></li>';
																								}
																								}
																								echo '</ol>';

																								}else{

																								//path to directory to scan
                                                $directory = $_SERVER['DOCUMENT_ROOT'].'/template/';
                                                //$directory = BASE_URL.'/assets/';

																								//get all text files with a .txt extension.
																								$texts = glob($directory . "*.php");

																								//print each file name
																								echo '<ol class="list-group">';

																								foreach($texts as $text){
																								$pos = stripos($text, $findme);
																								$nome_de_file = str_replace('/home/betaformazione/erp.betaformazione.com/template/','',$text);
																								if ($pos !== false){
																								$conteggio ++;
																								$thereisafile = true;//Tell the script something was found.
																								//Display the search results as links.
																								//echo'<a href="' . $dirname . $file . '">' . $file . '</a><br>';
                                                    if ($nome_de_file == "index.php") {
                                                      $conteggio --;
                                                      # code...
                                                    }else {
                                                      echo '<li class="list-group-item"><a href="'.$nome_de_file.'" target="_blank"><i class="fa fa-file-o" aria-hidden="true"></i> '.$nome_de_file.' </a></li>';
                                                    }
                                                    //echo '<li class="list-group-item"><a href="'.$nome_de_file.'" target="_blank"><i class="fa fa-file-o" aria-hidden="true"></i> '.$nome_de_file.' </a></li>';
																								}else{
																								$conteggio ++;
																								//Leave this blank

                                                    if ($nome_de_file == "index.php") {
                                                      $conteggio --;
                                                      # code...
                                                    }else {

                                                      echo '<li class="list-group-item"><a href="'.$nome_de_file.'" target="_blank"><i class="fa fa-file-o" aria-hidden="true"></i> '.$nome_de_file.' </a></li>';
                                                    }
																								//echo '<li class="list-group-item"><a href="'.$nome_de_file.'" target="_blank"><i class="fa fa-file-o" aria-hidden="true"></i>  '.$nome_de_file.' </a></li>';
																								}
																								}
																								echo '</ol>';
																								}


																								echo 'Sono presenti <b>'.$conteggio.'</b> template';
                                                ?>
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
        <script src="<?=BASE_URL?>/assets/pages/scripts/search.min.js" type="text/javascript"></script>
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
