<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
require_once(BASE_ROOT.'config/confPermessi.php');
include_once(BASE_ROOT . 'moduli/ticket/funzioni.php');

if(isset($_GET['idMenu'])){
    $idMenu = $_GET['idMenu'];
}else{
    $idMenu = "";
}

/* 	fine post ricerca sinistra	 */
if (isset($_GET['id']) && $_GET['id'] != "") {
    $id = $_GET['id'];
} else {
    $id = "";
}

if (isset($_GET['idProf']) && $_GET['idProf'] != "") {
    $idProfessionista_presente = $_GET['idProf'];
} else {
    $idProfessionista_presente = 0;
}

if (isset($_GET['telefono']) && $_GET['telefono'] != "") {
    $telefono_presente = $_GET['telefono'];
} else {
    $telefono_presente = "";
}

if (isset($_GET['tbl']) && $_GET['tbl'] != "") {
    $tabella = $_GET['tbl'];
} else {
    $tabella = "";
}


switch ($tabella) {
    case 'calendario';
        $sql_00004 = "SELECT * FROM calendario WHERE id=" . $id;
        $row_00004 = $dblink->get_row($sql_00004, true);
        $idCalendario_daPassare = $row_00004['id'];
        $id_professionista_presente = $row_00004['id_professionista'];
        $id_azienda_presente = $row_00004['id_azienda'];

        if($row_00004['id_professionista']>0){
            $sql_00001 = "SELECT * FROM lista_professionisti WHERE id=" . $row_00004['id_professionista'];
            $row_00001 = $dblink->get_row($sql_00001, true);
        }

        if($row_00004['id_azienda']>0){
            $sql_00002 = "SELECT lista_aziende.* FROM lista_aziende
                        INNER JOIN matrice_aziende_professionisti
                        ON lista_aziende.id = matrice_aziende_professionisti.id_azienda
                        WHERE matrice_aziende_professionisti.id_azienda=" . $row_00004['id_azienda']. "
                        AND (matrice_aziende_professionisti.stato='Attivo' OR matrice_aziende_professionisti.stato='')";
        $row_00002 = $dblink->get_row($sql_00002, true);
        }

        $sql_00003 = "SELECT * FROM calendario WHERE id=" . $id . " ORDER BY id DESC";
        $row_00003 = $dblink->get_row($sql_00003, true);
        //print_r($row_00003);
        break;
}

if($idProfessionista_presente>0){
    $sql_00001 = "SELECT * FROM lista_professionisti WHERE id=" . $idProfessionista_presente;
    $row_00001 = $dblink->get_row($sql_00001, true);
}

if(strlen($telefono_presente)>5){
    $row_00001['telefono'] = $telefono_presente;
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
        <title><?php echo $site_name; ?> | NUOVO TICKET</title>
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
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/typeahead/typeahead.css" rel="stylesheet" type="text/css">

        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css">
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
                    get_pagina_titolo($_GET['idMenu'], $where_lista_menu);
                    ?>
                    <!-- END PAGE TITLE -->
                    <!-- END PAGE HEADER-->
                    <!-- INIZIO ROW TABELLA-->
                    <form id="formNuovoTicket" name="formNuovoTicket" class="form-horizontal form-bordered" enctype="multipart/form-data" role="form" action="<?= BASE_URL ?>/moduli/ticket/salva.php?fn=nuovoTicket" method="POST">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet box yellow-crusta">
                                        <div class="portlet-title">
                                            <div class="caption font-light">
                                                <i class="fa fa-ticket"></i>
                                                <span class="caption-subject bold uppercase">Nuovo Ticket</span>
                                                <span class="caption-helper"> </span>
                                            </div>
                                        </div>
                                        <div class="portlet-body" style="padding:25px;">
                                            <!-- START TIPO CHIUSURA-->
                                            <div class="row" style="">
                                                <div class="col-md-6">
                                                    <div class="form-group " style="padding-right:5px;">
                                                        <label class="control-label font-dark bold uppercase">STATO</label>
                                                        <?php print_input("lista_ticket_txt_stato", "In Attesa", "", true, true); ?>
                                                        <?php //print_bs_select("SELECT nome as valore, nome, colore_sfondo as colore FROM lista_ticket_stati WHERE stato='Attivo' AND nome LIKE 'Ticket Nuovo' $where_lista_richieste_stati", "lista_ticket_txt_stato", "Ticket Nuovo", "", false); ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group" style="padding-right:5px;">
                                                        <label class="control-label">Data</label>
                                                        <div class="input-icon">
                                                            <i class="fa fa-calendar"></i>
                                                            <?php print_input_date("lista_ticket_txt_datainsert", date("d-m-Y"), "Data...", true, true, "dd-mm-yyyy"); ?>
                                                            <!--<input type="text" class="form-control date-picker contatti-data-chiusura tooltips" placeholder="Data..."  id="calendario_txt_data" name="calendario_txt_data" data-container="body" data-placement="top" data-original-title="DATA " data-date-format="dd-mm-yyyy"  value="<?=date("d-m-Y")?>">-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                                <div class="col-md-3">
                                                    <div class="form-group" style="padding-left:5px;">
                                                        <label class="control-label">Ora</label>
                                                        <div class="input-group">
                                                            <?php print_input_ora("lista_ticket_txt_orainsert", date("H:i"), "Ora...", true, true); ?>
                                                            <!--<input type="text" class="form-control timepicker timepicker-24-ora-inizio tooltips"  id="calendario_txt_ora" name="calendario_txt_ora" data-container="body" data-placement="top" data-original-title="ORA" value="<?=date("H:i")?>">-->
                                                            <span class="input-group-btn">
                                                                <button class="btn default" type="button">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- END TIPO CHIUSURA-->
                                            <!-- START DATA - ORA-->
                                            <div class="row" style="margin-bottom:15px;">
                                                <div class="col-md-8">
                                                    <div class="form-group" style="padding-right:5px;">
                                                        <label class="control-label">Oggetto</label>
                                                        <div class="input-icon right">
                                                        <i class="fa"></i>
                                                        <?=print_input("lista_ticket_txt_oggetto", '', "Oggetto", false) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group" style="padding-right:5px;">
                                                        <label class="control-label">Allegato</label>
                                                        <div class="input-icon right">
                                                            <i class="fa"></i>
                                                            <!--<input type="file" class="input-lg" name="lista_ticket_txt_allegato" id="lista_ticket_txt_allegato">-->
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <span class="btn default btn-file btn-sm">
                                                                    <span class="fileinput-new"> Seleziona File </span>
                                                                    <span class="fileinput-exists"> Cambia </span>
                                                                    <input type="file" class="input-lg" name="lista_ticket_txt_allegato" id="lista_ticket_txt_allegato">
                                                                    </span>
                                                                <span class="fileinput-filename"> </span> &nbsp;
                                                                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END DATA - ORA-->
                                            <hr>

                                            <!-- START DESCRIZIONE CHIUSURA-->
                                            <div class="row" style="margin-bottom:15px;">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label">Messaggio</label>
                                                        <textarea <?php echo $stile_text_area; ?> class="form-control" rows="7"  id="lista_ticket_txt_messaggio" name="lista_ticket_txt_messaggio"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END DESCRIZIONE CHIUSURA -->

                                            <?php print_hidden("lista_ticket_txt_id", 0); ?>
                                            <?php print_hidden("lista_ticket_txt_etichetta", "Nuovo Ticket"); ?>
                                            <?php print_hidden("lista_ticket_txt_id_mittente", $_SESSION['id_utente']); ?>
                                            <?php print_hidden("lista_ticket_txt_mittente", $_SESSION['cognome_nome_utente']); ?>
                                            <?php print_hidden("lista_ticket_txt_campo_5", $_SESSION['email_utente']); ?>
                                            <?php print_hidden("lista_ticket_txt_id_destinatario", 1); ?>
                                            <?php print_hidden("lista_ticket_txt_destinatario", "CEMA NEXT Crocco"); ?>
                                            <?php print_hidden("lista_ticket_txt_url", $_SERVER['HTTP_REFERER']); ?>
                                            <?php print_hidden("lista_ticket_txt_priorita", 'Bassa'); ?>


                                            <div style="text-align: center;">
                                                <button name="nuovoTicket" id="nuovoTicket" type="submit" class="btn btn-circle btn-lg green-jungle" alt="INVIA TICKET" title="INVIA TICKET"> Invia Ticket <i class="fa fa-paper-plane"></i></button>
                                            </div>
                                        </div>  <!--end portlet body -->
                                    </div>
                                    <!--FINE PORTLET CHIUSURE -->
                                </div>
                            </form>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>

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
        <script src="<?= BASE_URL ?>/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!--<script src="<?= BASE_URL ?>/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>-->

        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>

        <!--<script src="<?= BASE_URL ?>/assets/pages/scripts/form-input-mask.min.js" type="text/javascript"></script>-->
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->

        <script src="<?= BASE_URL ?>/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/apps/scripts/dettaglio_tab.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/pages/scripts/ui-toastr.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/apps/scripts/php.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/apps/scripts/utility.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/pages/scripts/ui-bootbox.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/moduli/ticket/scripts/funzioni.js" type="text/javascript"></script>

    </body>
<?php
/*
  echo '<div style="text-align:right; padding:30px; background-color:#FFF; color: red;">';
  echo '$variabili_data_1 = '.$variabili_data_1;
  echo '</div>';
 */
?>
</html>
