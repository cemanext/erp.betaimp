<?php
include_once('../../config/connDB.php');

if(isset($_GET['id']) && !empty($_GET['id'])){
    $idCampagna = $_GET['id'];
}else{
    $_GET['id']=2;
    $idCampagna = 2;
}

if(isset($_GET['url']) && !empty($_GET['url'])){
    $urlReferer = $_GET['url'];
}else{
    $urlReferer = $_SERVE['referer'];
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
        <title>FORM INVIA RICHIESTE</title>
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
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
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
    <body class="page-sidebar-closed-hide-logo page-content-white" style="background-color: transparent;">
        <?php if(isset($_GET['ret']) && $_GET['ret']==="1"){ ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                      <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                          <h2>Grazie !</h2>
                          <div>
                              La tua richiesta è stata inoltrata da un nostro operatore.
                          </div>
                          <div>
                              La preghiamo di attendere, sarà ricontattato al più presto.
                          </div>
                      </div>
                </div>
            </div>
        <?php }elseif(isset($_GET['ret']) && $_GET['ret']==="2"){ ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                      <div class="col-md-12 alert alert-danger" style="padding-left: 0px; padding-right: 0px">
                          <h2>ATTENZIONE !</h2>
                          <div>
                              I campi obbligatori non sono stati complitati.
                          </div>
                          <div>
                              La richiesta non &egrave; stata inviata!
                          </div>
                      </div>
                </div>
            </div>
        <?php } else { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form role="form" id="inviaRichiestaSito" name="inviaRichiestaSito" action="<?=ERP_DOMAIN_NAME?>/automazioni.php" method="POST">
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button> Ci sono dei campi complitati erroneamente. Si prega di verificare. </div>
            <div class="alert alert-success display-hide">
                <button class="close" data-close="alert"></button> La tua richiesta è stata inviata! </div>
            <div class="row">
                  <div class="col-md-12 form-group" style="padding-left: 0px; padding-right: 0px">
                      <div class="input-icon right">
                        <i class="fa"></i>
                        <input name="nome" id="nome" type="text" class="form-control" placeholder="Nome *" value="" ></div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12 form-group" style="padding-left: 0px; padding-right: 0px">
                      <div class="input-icon right">
                        <i class="fa"></i>
                      <input name="cognome" id="cognome" type="text" class="form-control" placeholder="Cognome *" value="" ></div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12 form-group" style="padding-left: 0px; padding-right: 0px">
                      <input name="codice_cliente" id="codice_cliente" type="text" class="form-control" placeholder="Codice Fiscale o Codice Cliente" value="">
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12 form-group" style="padding-left: 0px; padding-right: 0px">
                      <input name="telefono" id="telefono" type="text" class="form-control" placeholder="Telefono *" value="" >
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12 form-group" style="padding-left: 0px; padding-right: 0px">
                      <input name="email" id="email" type="email" class="form-control" placeholder="E-Mail *" value="" >
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12 form-group" style="padding-left: 0px; padding-right: 0px">
                      <textarea name="messaggio" id="messaggio" class="form-control" placeholder="Messaggio" rows="5"></textarea>
                  </div>
            </div>

            <div class="form-actions right">
                <button type="submit" class="btn btn-lg yellow-crusta">Invia</a>
            </div>
            <input type="hidden" id="id_campagna" name="id_campagna" value="<?=$idCampagna?>">
            <input type="hidden" id="referer" name="referer" value="<?=$urlReferer?>">
          </form>
        </div>
        <?php } ?>
        <!-- END FOOTER -->
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

        <script src="<?= BASE_URL ?>/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>

        <!--<script src="<?= BASE_URL ?>/assets/pages/scripts/form-input-mask.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?= BASE_URL ?>/assets/apps/scripts/php.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script type="text/javascript">
        
        var FormValidation = function () {
            
            // validation using icons
            var handleValidation2 = function() {
                // for more info visit the official plugin documentation: 
                    // http://docs.jquery.com/Plugins/Validation

                    var form2 = $('#inviaRichiestaSito');
                    var error2 = $('.alert-danger', form2);
                    var success2 = $('.alert-success', form2);

                    form2.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block help-block-error', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",  // validate all fields including form hidden input
                        rules: {
                            nome: {
                                minlength: 2,
                                required: true
                            },
                            cognome: {
                                minlength: 2,
                                required: true
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            url: {
                                required: true,
                                url: true
                            },
                            telefono: {
                                minlength: 6,
                                required: false,
                                number: true
                            },
                            digits: {
                                required: true,
                                digits: true
                            },
                            creditcard: {
                                required: true,
                                creditcard: true
                            },
                        },

                        invalidHandler: function (event, validator) { //display error alert on form submit              
                            success2.hide();
                            error2.show();
                            App.scrollTo(error2, -200);
                        },

                        errorPlacement: function (error, element) { // render error placement for each input type
                            var icon = $(element).parent('.input-icon').children('i');
                            icon.removeClass('fa-check').addClass("fa-warning");  
                            icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                        },

                        highlight: function (element) { // hightlight error inputs
                            $(element)
                                .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
                        },

                        unhighlight: function (element) { // revert the change done by hightlight

                        },

                        success: function (label, element) {
                            var icon = $(element).parent('.input-icon').children('i');
                            $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                            icon.removeClass("fa-warning").addClass("fa-check");
                        },

                        submitHandler: function (form) {
                            success2.show();
                            error2.hide();
                            form.submit(); // submit the form
                        }
                    });


            }

            return {
                //main function to initiate the module
                init: function () {

                    handleValidation2();

                }

            };

        }();

        jQuery(document).ready(function() {
            FormValidation.init();
        });
        </script>
    </body>

</html>
