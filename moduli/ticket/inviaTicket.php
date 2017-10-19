<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
require_once(BASE_ROOT.'config/confPermessi.php');
include_once(BASE_ROOT . 'moduli/ticket/funzioni.php');

if (isset($_GET['idMenu'])) {
    $idMenu = $_GET['idMenu'];
} else {
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
        <title><?php echo $site_name; ?> | INDEXRECORD</title>
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
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?= BASE_URL ?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?= BASE_URL ?>/assets/apps/css/todo-2.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />

        <?php if (filter_input(INPUT_GET, 'tbl') != "lista_prodotti") { ?>
            <style type="text/css">
                .dataTables_extended_wrapper {
                    margin-top: 0px !important;
                }
                .dataTables_extended_wrapper .table.dataTable {
                    margin: 0px 0!important;
                }
            </style>
        <?php } ?>
    </head>

    <?php
    // fPer velocizzare la pagina
    flush();
    ?>

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
                    <?php //Stampa_HTML_index_Tickets($tabella, $id); ?>

                    <div class="row">
                        <div class="portlet box yellow-crusta">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-settings font-light"></i>
                                    <span class="caption-subject font-light uppercase">Invia Ticket</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <form action="salva.php?fn=inviaTicket" method="post" enctype="multipart/form-data" class="form">
                                    <div class="form-body">
                                        <!--<h3 class="form-section">Invia Ticket </h3>-->
                                        <div class="row" style="margin-bottom:10px;">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                                                    <input name="mitt" id="mitt" type="text" class="form-control tooltips" placeholder="Mittente" value="<?php echo $mitt; ?>" data-container="body" data-placement="top" data-original-title="MITTENTE"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                                                    <input name="dest" id="dest" type="text" class="form-control tooltips" placeholder="Destinatario" value="<?php echo $dest; ?>" data-container="body" data-placement="top" data-original-title="DESTINATARIO"></div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:10px;">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                                                    <input name="dest_cc" id="dest_cc" type="text" class="form-control tooltips" placeholder="CC" value="<?php echo $dest_cc; ?>" data-container="body" data-placement="top" data-original-title="CC"></div></div>
                                        </div>
                                        <div class="row" style="margin-bottom:10px;">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                                                    <input name="ogg" id="ogg" type="text" class="form-control tooltips" placeholder="Oggetto" value="<?php echo $ogg . str_replace('_', ' ', str_replace('.pdf', '', $filename_oggetto)) . ''; ?>" data-container="body" data-placement="top" data-original-title="OGGETTO"></div></div>
                                        </div>
                                        <div class="row" style="margin-bottom:10px;">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="btn-set pull-left">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <span class="btn btn-primary btn-file btn-sm">
                                                                <span class="fileinput-new"> Seleziona Allegato </span>
                                                                <span class="fileinput-exists"> Cambia </span>
                                                                <input type="file" name="documentoAllegato1">
                                                            </span>
                                                            <span class="fileinput-filename"> </span> &nbsp;
                                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                                                        </div>
                                                    </div>
                                                    <div class="btn-set pull-left">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:10px;">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <textarea id="mess" name="mess" class="wysihtml5 form-control" rows="6"><?php echo $mess; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-actions right">
                                        <!--<button type="button" class="btn dark btn-outline">Annulla</button>-->
                                        <button type="submit" name="invia_ticket" value="Invia Ticket" class="btn btn-circle btn-lg green-jungle">Invia</button>
                                    </div>
                                </form>

                            </div>

                        </div>
                        <!-- END CONTENT BODY -->
                    </div>
                    <!-- END CONTENT -->
                    <!-- BEGIN QUICK SIDEBAR -->

                    <!-- END QUICK SIDEBAR -->
                </div>
            </div>
            <!-- END CONTAINER -->
            <?= pageFooterCopy(); ?>
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
            <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/scripts/datatable.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
            <script src="<?= BASE_URL ?>/assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>

            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL SCRIPTS -->
            <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
            <!-- END THEME GLOBAL SCRIPTS -->
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
            <script src="<?= BASE_URL ?>/moduli/ticket/scripts/funzioni.js" type="text/javascript"></script>
            <!-- END THEME LAYOUT SCRIPTS -->
            <!-- MODAL INVIA TICKET -->

            <!-- FINE MODAL INVIA TICKET -->
    </body>
</html>
