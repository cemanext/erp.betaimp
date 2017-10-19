<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');
require_once(BASE_ROOT.'config/confPermessi.php');

global $dblink, $table_listaFatture, $table_listaFattureMultiple, $table_listaFattureInvioMultiplo;

if(isset($_GET['idMenu'])){
    $idMenu = $_GET['idMenu'];
}else{
    $idMenu = "";
}

//RECUPERO LA VARIABILE POST DAL FORM defaultrange
if (!empty($_POST['intervallo_data']) AND !empty($_POST['sezionale'])) {
    $intervallo_data = $_POST['intervallo_data'];
    $sezionale_selezionato = $_POST['sezionale'];
    
    $data_in = before(' al ', $intervallo_data);
    $data_out = after(' al ', $intervallo_data);
    
    $setDataCalIn = $data_in;
    $setDataCalOut = $data_out;
    
    //$where_intervallo = " AND lista_fatture.dataagg BETWEEN  '" . $data_in . "' AND  '" . $data_out . "'";
    $where_intervallo = " AND (lista_fatture.data_preventivo BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "') AND sezionale='".$sezionale_selezionato."'";
    
    if("01-".date("m-Y")." al ".date("t-m-Y") == $intervallo_data){
        $titolo_intervallo = " del mese in corso". " / Sezionale ".$sezionale_selezionato;
    }else if(date("d-m-Y", strtotime("-29 days"))." al ".date('d-m-Y') == $intervallo_data) {
        $titolo_intervallo = " utlimi 30 gioni". " / Sezionale ".$sezionale_selezionato;
    }else if(date("d-m-Y", strtotime("-6 days"))." al ".date('d-m-Y') == $intervallo_data) {
        $titolo_intervallo = " utlimi 7 gioni". " / Sezionale ".$sezionale_selezionato;
    }else if(date("d-m-Y", strtotime("-1 days"))." al ".date('d-m-Y', strtotime("-1 days")) == $intervallo_data) {
        $titolo_intervallo = " ieri". " / Sezionale ".$sezionale_selezionato;
    }elseif(date("d-m-Y")." al ".date('d-m-Y') == $intervallo_data) {
        $titolo_intervallo = " oggi". " / Sezionale ".$sezionale_selezionato;
    }else{
        $titolo_intervallo = " dal  " . ($data_in) . " al  " . ($data_out) . " / Sezionale ".$sezionale_selezionato;
    }
    
    
    //echo '<h1>$intervallo_data = '.$intervallo_data.'</h1>';
} else {
    //$where_intervallo = " AND YEAR(lista_fatture.dataagg)=YEAR(CURDATE()) AND MONTH(lista_fatture.dataagg)=MONTH(CURDATE())";
    $where_intervallo = " AND YEAR(lista_fatture.data_preventivo)=YEAR(CURDATE()) AND MONTH(lista_fatture.data_preventivo)=MONTH(CURDATE())";
    $titolo_intervallo = " del mese in corso";
    $_POST['intervallo_data'] = date("d-m-Y")." al ".date("d-m-Y");
    $setDataCalIn = date("d-m-Y");
    $setDataCalOut = date("d-m-Y");
    $_POST['sezionale'] = "";
    $sezionale_selezionato = "";
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
        <link href="<?= BASE_URL ?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css">
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

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
        <link rel="shortcut icon" href="favicon.ico" /> </head>
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
                    <!-- BEGIN PAGE BAR -->
                    <div class="row">

                        <form action="?idMenu=<?=$_GET['idMenu']?>" class="form-horizontal form-bordered" method="POST" id="formIntervallo" name="formIntervallo">
                            <div class="col-md-6">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Intervallo </label>
                                        <div class="col-md-9">
                                            <div class="input-group" id="dataRangeHome" name="dataRangeHome">
                                                <input type="text" class="form-control" id="intervallo_data" name="intervallo_data" value="<?=$_POST['intervallo_data']?>">
                                                <span class="input-group-btn">
                                                    <button class="btn default date-range-toggle" type="submit">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-9" style="vertical-align: middle;"><center><small>Risultati <?= $titolo_intervallo; ?></small></center></div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <?=print_select2("SELECT nome AS valore, nome FROM lista_fatture_sezionali WHERE 1 ORDER BY nome ASC", "sezionale", $_POST['sezionale'], "", false, 'tooltips select_sezionale-allow-clear', 'data-container="body" data-placement="top" data-original-title="SELEZIONA SEZIONALE"') ?>
                            </div>
                        </form>

                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->

                    <!--<h3 class="page-title"> Dashboard
                        <small>& Statistiche</small>
                    </h3>-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT SUM(imponibile) AS conteggio FROM lista_fatture WHERE stato LIKE 'In Attesa di Emissione' " . $where_intervallo;
                            $titolo = 'Totale Fatture In Attesa di Emissione<br>' . $titolo_intervallo;
                            $icona = 'fa fa-line-chart';
                            $colore = 'yellow-lemon';
                            //$link = '/moduli/anagrafiche/index.php?tbl=lista_professionisti&idMenu=3';
                            $link = '/moduli/fatture/index.php?tbl=lista_fatture&whr_state=0c5d1191eb5033b241de0c655ceac356&idMenu=78';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $sql_007 = "SELECT COUNT(imponibile) AS conteggio FROM lista_fatture WHERE stato LIKE 'In Attesa di Emissione' " . $where_intervallo;
                            $titolo = 'Conteggio Fatture In Attesa di Emissione<br>' . $titolo_intervallo;
                            $icona = 'fa fa-area-chart';
                            $colore = 'yellow-lemon';
                            //$link = '/moduli/anagrafiche/index.php?tbl=lista_professionisti&idMenu=3';
                            $link = '/moduli/fatture/index.php?tbl=lista_fatture&whr_state=0c5d1191eb5033b241de0c655ceac356&idMenu=78';
                            stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                <div class="row">
                    
            <?php
            
            $sql_data_creazione_max = "SELECT MAX(data_creazione) AS 'data_max' FROM `lista_fatture` WHERE 1 AND sezionale='".$sezionale_selezionato."' ";
            $data_fattura_massima = $dblink->get_field($sql_data_creazione_max);

            $tabella = "lista_fatture";
            //echo 'Ultima Data di Fatturazione: '.GiraDataOra($data_fattura_massima).'';
            echo '<a class="btn btn-lg btn-icon btn-outline red" href="#">Ultima Data di Fatturazione: '.GiraDataOra($data_fattura_massima).'</a>';
                
            ECHO '<FORM action="salva.php?fn=emettiFattureMultiple" method="POST">';
            echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
            echo '<div class="form-group" style="padding-right:10px; border:1px solid #EEE;">
                        <label class="control-label">Data Creazione Emissione</label>
                                <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" class="form-control date-picker data-creazione-emissione" id="dataCreazioneEmissione" name="dataCreazioneEmissione" value="'.GiraDataOra($data_fattura_massima).'">
                    </div>
                </div>';

            //ECHO '<label>dataCreazioneEmissione:</label><INPUT TYPE="TEXT" class="form-control" NAME="dataCreazioneEmissione" VALUE="'.date("Y-m-d").'">';
            echo '</div>';
            echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
            echo '<div class="form-group" style="padding-right:5px;">
                        <label class="control-label">Data Scadenza Emissione</label>
                                <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" class="form-control date-picker data-scadenza-emissione" id="dataScadenzaEmissione" name="dataScadenzaEmissione" value="'.GiraDataOra($data_fattura_massima).'">
                    </div>
                </div>';
            //ECHO 'dataScadenzaEmissione:<INPUT TYPE="INPUT" NAME="dataScadenzaEmissione" VALUE="'.date("Y-m-d").'">';
            echo '</div>';
            echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
            ECHO '<div class="form-group" style="padding-right:5px;"><label class="control-label">Tipo Banca</label><SELECT NAME="tipoBanca" class="form-control select2">';
            $sql_seleziona_tipo_banca = "SELECT * FROM lista_fatture_banche WHERE stato LIKE 'Attivo' ORDER BY nome ASC";
            $rs_seleziona_tipo_banca = $dblink->get_results($sql_seleziona_tipo_banca); 
            if(!empty($rs_seleziona_tipo_banca)) {
                foreach ($rs_seleziona_tipo_banca as $row_seleziona_tipo_banca) {
                    echo '<option name="tipoBanca" value="'.$row_seleziona_tipo_banca['id'].'">'.$row_seleziona_tipo_banca['nome'].'</option>';
                }
            }
            
            ECHO '</SELECT>';
            echo '</div></div>';
            echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
            ECHO '<div class="form-group" style="padding-right:5px;"><label class="control-label">Tipo Pagamento</label><SELECT NAME="tipoPagamento" class="form-control select2">';
            $sql_seleziona_tipo_pagamento = "SELECT * FROM `lista_tipologie_pagamento` WHERE stato LIKE 'Attivo' ORDER BY nome ASC";
            $rs_seleziona_tipo_pagamento = $dblink->get_results($sql_seleziona_tipo_pagamento); 
            if(!empty($rs_seleziona_tipo_pagamento)) {
                foreach ($rs_seleziona_tipo_pagamento as $row_seleziona_tipo_pagamento) {
                    echo '<option name="tipoBanca" value="'.$row_seleziona_tipo_pagamento['nome'].'">'.$row_seleziona_tipo_pagamento['nome'].'</option>';
                }
            }else{

            }
            ECHO '</SELECT>';
            echo '</div></div>';
            ?>
            </div>
            <div class="clearfix"></div>
            <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php
                $campi_visualizzati = $table_listaFattureEmettiMultiplo['index']['campi'];
            //$where = $table_listaFattureInvioMultiplo['index']['where'];
            $where = " stato LIKE 'In Attesa di Emissione'";
            $ordine = $table_listaFattureEmettiMultiplo['index']['order'];
            $titolo = 'Emetti Fatture Multiplo';
            $sql_0001 = "SELECT ".$campi_visualizzati.", IF(id_azienda>0 && imponibile>0, id, 0) AS selezione FROM lista_fatture WHERE $where $where_intervallo $ordine";
            stampa_table_static_basic($sql_0001, '', 'Elenco Fatture', '', 'fa fa-handshake-o');
            //stampa_table_datatables_ajax($sql_0001, '#datatable_ajax', $titolo, '');
            //stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
            echo '<center><button type="submit" class="btn blue-steel btn-lg"><i class="fa fa-hourglass-end"> Emetti Selezionate</i></button></center>';
            ECHO '</FORM>';
                    ?>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-- END DASHBOARD STATS 1-->
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
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
     <script src="<?= BASE_URL ?>/assets/pages/scripts/ui-toastr.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataRangeHome').daterangepicker({
                opens: (App.isRTL() ? 'left' : 'right'),
                format: 'DD-MM-YYYY',
                separator: ' al ',
                startDate: '<?=$setDataCalIn?>',
                endDate: '<?=$setDataCalOut?>',
                ranges: {
                    'Oggi': [moment(), moment()],
                    'Ieri': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimi 7 giorni': [moment().subtract(6, 'days'), moment()],
                    'Ultimi 30 giorni': [moment().subtract(29, 'days'), moment()],
                    'Questo mese': [moment().startOf('month'), moment().endOf('month')],
                    'Scorso Mese': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'DD-MM-YYYY',
                    separator: ' al ',
                    applyLabel: 'Filtra',
                    cancelLabel: 'Resetta',
                    fromLabel: 'Dal',
                    toLabel: 'Al',
                    customRangeLabel: 'Date Personalizzate',
                    daysOfWeek: ['Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa'],
                    monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                    firstDay: 1
                },
                minDate: '07/01/2017',
            },
                function (startDate, endDate) {
                    $('#intervallo_data').val(startDate.format('DD-MM-YYYY') + ' al ' + endDate.format('DD-MM-YYYY'));
                    //$('#defaultrange input').val(startDate.format('YYYY-MM-DD') + '|' + endDate.format('YYYY-MM-DD'));
                    //$('#defaultrange input').html(startDate.format('DD-MM-YYYY') + ' a ' + endDate.format('DD-MM-YYYY'));
                }
            );
            $('#dataRangeHome').on('apply.daterangepicker', function(ev, picker) {
                //console.log(picker.startDate.format('YYYY-MM-DD'));
                //console.log(picker.endDate.format('YYYY-MM-DD'));
                document.formIntervallo.submit();
            }); 
            
            $('#sezionale').on('change', function(ev, picker) {
                document.formIntervallo.submit();
            });
            
            $('#intervallo_data').on('change', function(ev, picker) {
                document.formIntervallo.submit();
            });
            
        });
    </script>
    <!--<script src="<?= BASE_URL ?>/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>-->
    <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <script src="<?= BASE_URL ?>/assets/pages/scripts/table-datatables-responsive.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/apps/scripts/php.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/apps/scripts/utility.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/moduli/fatture/scripts/funzioni.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>

    <!-- END THEME LAYOUT SCRIPTS -->
</body>
</html>
