<?php

include_once('config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

//echo '---------->'.$_POST['cercaQualcosa'];
if((isset($_POST['cercaQualcosa']) and strlen($_POST['cercaQualcosa'])>4 ) or (isset($_GET['cercaQualcosa']) and strlen($_GET['cercaQualcosa'])>4)){

if(isset($_GET['cercaQualcosa']) && strlen($_GET['cercaQualcosa'])>4){
    $_POST['cercaQualcosa'] = $_GET['cercaQualcosa'];
}else{
    header("Location:".BASE_URL."/risultatiRicerca.php?cercaQualcosa=".$_POST['cercaQualcosa']);
    die;//$_GET['cercaQualcosa'] = $_POST['cercaQualcosa'];
}

$cercaQualcosa = $_GET['cercaQualcosa'];

if(strlen($cercaQualcosa)>4){
    
    $arrayCampoRicerca = array();
    if(strpos($cercaQualcosa," ")!==false){
        $arrayCampoRicerca = explode(" ",$cercaQualcosa);
    }else{
        if(strlen($cercaQualcosa)>0){
            $arrayCampoRicerca[] = $cercaQualcosa;
        }
    }

    //cerchiamo PROFESSIONISTA
    $sql_001 = 'SELECT nome, cognome, telefono, cellulare, codice, codice_fiscale, email FROM lista_professionisti LIMIT 1';
	$colonne = $dblink->list_fields($sql_001);
	if(!empty($colonne)){
		//$conta_colonne = mysql_num_fields($rs_001);
		$where_cerca_professionisti = '';
		//for ($b=0;$b<$conta_colonne;$b++){
                if(!empty($arrayCampoRicerca)){
                    foreach ($arrayCampoRicerca as $campoRicerca) {
                        
                        $where_cerca_professionisti.= " AND ( ";
                        
                        $campoRicerca = $dblink->filter(trim($campoRicerca));
                    
                        foreach ($colonne as $colonna) {
                            if(strlen($colonna->orgname)>0){
                                $nome_colonna = $colonna->orgname;
                            }else{
                                $nome_colonna = $colonna->name;
                            }

                            if($nome_colonna == 'stato' or $nome_colonna == 'scrittore'){
                                $where_cerca_professionisti .= " ";
                            }else{
                                $where_cerca_professionisti .= "`".$nome_colonna."` LIKE '%".$campoRicerca."%' OR ";
                            }

                        }
                        
                        $where_cerca_professionisti = substr($where_cerca_professionisti,0,(strlen($where_cerca_professionisti)-4))." ) ";
                    }
                }
		//$where_cerca_professionisti .= " CONCAT(cognome,' ',nome) LIKE '%".trim($_POST['cercaQualcosa'])."%' OR ";
		//$where_professionisti = ' AND ('.substr($where_cerca_professionisti,0,(strlen($where_cerca_professionisti)-4)).')';
		$where_professionisti = $where_cerca_professionisti;
		//echo '<h1>$where_professionisti = '.$where_professionisti.'</h1>';
	}
    //fine cerchiamo PROFESSIONISTA
	
        
    if(!isset($_GET['daTelefonata'])){
	//cerchiamo AZIENDA
        $sql_001 = 'SELECT ragione_sociale, email, telefono, partita_iva, codice_fiscale FROM lista_aziende LIMIT 1';
        $colonne = $dblink->list_fields($sql_001);
	if(!empty($colonne)){
            //$conta_colonne = mysql_num_fields($rs_001);
            $where_cerca_aziende = '';
            //for ($b=0;$b<$conta_colonne;$b++){
            if(!empty($arrayCampoRicerca)){
                    foreach ($arrayCampoRicerca as $campoRicerca) {
                        
                    $where_cerca_aziende.= " AND (";

                    $campoRicerca = $dblink->filter(trim($campoRicerca));
                    
                    foreach ($colonne as $colonna) {
                        if(strlen($colonna->orgname)>0){
                            $nome_colonna = $colonna->orgname;
                        }else{
                            $nome_colonna = $colonna->name;
                        }

                        if($nome_colonna == 'stato' or $nome_colonna == 'scrittore'){
                            $where_cerca_aziende .= "";
                        }else{
                            $where_cerca_aziende .= "`".$nome_colonna."` LIKE '%".$campoRicerca."%' OR ";
                        }

                    }
                    $where_cerca_aziende = substr($where_cerca_aziende,0,(strlen($where_cerca_aziende)-4)).')';
                }
            }
            //$where_aziende = ' AND ('.substr($where_cerca_aziende,0,(strlen($where_cerca_aziende)-4)).')';
            $where_aziende = $where_cerca_aziende;
            //echo '<h1>$where_aziende = '.$where_aziende.'</h1>';
        }
        //fine cerchiamo AZIENDA
    }
	
	//cerchiamo INDIRIZZI
    /*$sql_001 = 'SELECT  * FROM lista_indirizzi LIMIT 1';
	//$rs_001 = mysql_query($sql_001);
	if($rs_001){
		$conta_colonne = mysql_num_fields($rs_001);
		$where_cerca_professionisti = '';
		for ($b=0;$b<$conta_colonne;$b++){
		$nome_colonna = mysql_field_name($rs_001,$b);
			if($nome_colonna=='stato' or $nome_colonna=='scrittore'){
				$where_cerca_indirizzi .= "";
			}else{
				$where_cerca_indirizzi .= "`".$nome_colonna."` LIKE '%".trim($_POST['cercaQualcosa'])."%' OR ";
			}

		}
		$where_indirizzi .= ' AND ('.substr($where_cerca_indirizzi,0,(strlen($where_cerca_indirizzi)-4)).')';
		//echo '<h1>$where_aziende = '.$where_aziende.'</h1>';
	}*/
    //fine cerchiamo INDIRIZZI


    //cerchiamo un messaggio
    $sql_004 = 'SELECT mittente, destinatario, campo_1, campo_2, campo_3, campo_4, campo_5, tipo_marketing, telefono, email, cognome, nome FROM calendario WHERE id_campagna > 0';
	$colonne = $dblink->list_fields($sql_004);
	if(!empty($colonne)){
		//$conta_colonne = mysql_num_fields($rs_001);
		$where_cerca_commento = '';
		//for ($b=0;$b<$conta_colonne;$b++){
                if(!empty($arrayCampoRicerca)){
                    foreach ($arrayCampoRicerca as $campoRicerca) {
                        
                        $where_cerca_commento.= " AND (";

                        $campoRicerca = $dblink->filter(trim($campoRicerca));
                        
                        foreach ($colonne as $colonna) {
                            if(strlen($colonna->orgname)>0){
                                $nome_colonna = $colonna->orgname;
                            }else{
                                $nome_colonna = $colonna->name;
                            }

                            if($nome_colonna == 'stato' or $nome_colonna == 'scrittore'){
                                $where_cerca_commento .= "";
                            }else{
                                $where_cerca_commento .= "`".$nome_colonna."` LIKE '%".$campoRicerca."%' OR ";
                            }
                        }
                        $where_cerca_commento = substr($where_cerca_commento,0,(strlen($where_cerca_commento)-4)).')';
                    }
                }
		
		//$where_cerca_commento .= " CONCAT(cognome,' ',nome) LIKE '%".trim($_POST['cercaQualcosa'])."%' OR ";
		//$where_commenti = ' AND ('.substr($where_cerca_commento,0,(strlen($where_cerca_commento)-4)).')';
		$where_commenti = $where_cerca_commento;
		//echo '<h1>$where_commenti = '.$where_commenti.'</h1>';
	}

    //cerchiamo un documento
    }
}else{
    //header("Location:".BASE_URL."/risultatiRicerca.php?cercaQualcosa=".$_POST['cercaQualcosa']);
    //die;//$_GET['cercaQualcosa'] = $_POST['cercaQualcosa'];
}

if(isset($_GET['daTelefonata']) && $_GET['daTelefonata']=="1"){
    
    $query = "SELECT id FROM lista_professionisti WHERE stato NOT LIKE '%Elimin%' ".$where_professionisti." ORDER BY telefono ASC LIMIT 1";
    
    $rowProf = $dblink->get_row($query,true);
    
    
    if(empty($rowProf)){
        
        $query_02 = "SELECT id FROM calendario WHERE etichetta='Nuova Richiesta' ".$where_commenti." ORDER BY id DESC LIMIT 1";
    
        $rowRichiesta = $dblink->get_row($query_02,true);
        if(empty($rowRichiesta)){
            header("Location:".BASE_URL."/moduli/calendario/nuovo_tab.php?telefono=$cercaQualcosa");
        }else{
            header("Location:".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=".$rowRichiesta['id']);
        }
    }else{
        header("Location:".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=lista_professionisti&id=".$rowProf['id']);
    }
    die();
}

if($_SESSION['livello_utente']=='commerciale'){
    $livelloCommerciale = true;
    echo "<li>".$_SERVER['REQUEST_URI']."</li>";
}else{
    $livelloCommerciale = false;
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
        <title><?php echo $site_name; ?> | Risultati Ricerca</title>
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
        <!--<link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />-->
        <!--<link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?=BASE_URL?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?=BASE_URL?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?=BASE_URL?>/assets/pages/css/search.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?=BASE_URL?>/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=BASE_URL?>/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?=BASE_URL?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> 
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-105683587-1', 'auto');
          ga('send', 'pageview');

        </script>
        </head>
        
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white page-sidebar-fixed">
        <!-- BEGIN HEADER -->
        <?php include(BASE_ROOT.'assets/header_risultatiRicerca.php'); ?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <?php include(BASE_ROOT.'assets/sidebar.php'); ?>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL -->
                    <!-- END THEME PANEL -->
                    <!-- BEGIN PAGE BAR-->

                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE
                    <h3 class="page-title"> Ci sono "X" risultati
                        <small>della ricerca "xyz"</small>
                    </h3>-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->

                      <div class="search-page search-content-4">
                          <form action="risultatiRicerca.php" method="GET">
                              <div class="search-bar ">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="input-group">
                                              <input type="text" class="form-control" placeholder="Digita qui il criterio di ricerca..." id="cercaQualcosa" name="cercaQualcosa">
                                              <span class="input-group-btn">
                                                  <button type="submit" class="btn blue uppercase bold" type="button">Cerca</button>
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                        </form>


                        <div class="portlet">
                            <!--<div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-search"></i>Trovati "X" risultati
                                </div>
                                <div class="actions"> </div>
                            </div>-->
                            <div class="portlet-body">
                              <ul class="nav nav-pills">
                                  <?php if(!$livelloCommerciale) { ?>
                                    <li class="">
                                        <a href="#tab_aziende" data-toggle="tab" aria-expanded="false"> Aziende</a>
                                    </li>
                                  <?php } ?>
                                    <li class="active">
                                        <a href="#tab_professionisti" data-toggle="tab" aria-expanded="true"> Professionisti </a>
                                    </li>
                                    <!--<li class="">
                                        <a href="#tab_indirizzi" data-toggle="tab" aria-expanded="false"> Indirizzi </a>
                                    </li>-->
                                    <li class="">
                                        <a href="#tab_richieste" data-toggle="tab" aria-expanded="false"> Richieste </a>
                                    </li>
                                  
                              </ul>
                              <div class="tab-content">
                                  <?php if(!$livelloCommerciale) { ?>
                                    <div class="tab-pane fade in" id="tab_aziende">

                                    <?php
                                    if(isset($_POST['cercaQualcosa']) and strlen($_POST['cercaQualcosa'])>3){
                                        $query = "SELECT "
                                              . "CONCAT('<a href=\"moduli/anagrafiche/dettaglio.php?tbl=lista_aziende&id=',id,'\"><i class=\"icon-arrow-right font-blue\"></i></a>') AS 'vedi',"
                                              . " ragione_sociale, partita_iva, codice_fiscale, email FROM lista_aziende WHERE 1 ".$where_aziende;
                                        StampaSQL2017($query, 'Aziende Trovati');
                                    }
                                    ?>

                                    </div>
                                  <?php } ?>
                                    <div class="tab-pane fade active in" id="tab_professionisti">

                                    <?php
                                    if(isset($_POST['cercaQualcosa']) and strlen($_POST['cercaQualcosa'])>3){ 

                                    $sql_controllo_doppi_temp = "CREATE TEMPORARY TABLE professionistiDoppi(SELECT  COUNT(*) as conto, cognome, nome FROM lista_professionisti 
                                    WHERE stato NOT LIKE '%Elimin%' ".$where_professionisti." GROUP BY CONCAT(cognome,' ', nome) ORDER BY cognome, nome, email ASC);";
                                    $rs_controllo_doppi_temp = $dblink->query($sql_controllo_doppi_temp);
                                    $sql_controllo_doppi = "SELECT * FROM professionistiDoppi WHERE conto>1";


                                    if($dblink->num_rows($sql_controllo_doppi)>0){
                                      $sql_controllo_doppi = "SELECT 
                                      CONCAT('<h1>',conto,'</h1>') AS 'Conteggio',
                                      CONCAT('<h2>',cognome,'</h2>') AS 'cognome', 
                                        CONCAT('<h2>',nome,'</h2>') AS 'nome' FROM professionistiDoppi WHERE conto>1";
                                      $titolo = 'Nominativi Doppi Trovati ( Omonimi o Email errata? )';
                                      StampaSQL2017($sql_controllo_doppi, $titolo, 'red-thunderbird');
                                    }

									/*
                                    $query = "SELECT CONCAT('<a href=\"moduli/anagrafiche/dettaglio.php?tbl=lista_professionisti&id=',id,'\"><i class=\"icon-arrow-right font-blue\"></i></a>') AS 'vedi', 
                                    CONCAT('<span class=\"btn sbold uppercase btn-outline blue\">',codice,'</span>') AS 'Codice Cliente',
                                    CONCAT('<h3>',cognome,'</h2>') AS 'cognome', 
                                    CONCAT('<h3>',nome,'</h2>') AS 'nome', 
                                    CONCAT('<h4>',email,'</h3>') AS 'email',
                                    (SELECT IF(FROM_UNIXTIME(mdl_user.`timecreated`) LIKE '1970-%',CONCAT('<span class=\"btn sbold uppercase btn-outline red-thunderbird\">',FROM_UNIXTIME(mdl_user.`timecreated`),'</span>'),CONCAT('<span class=\"btn sbold uppercase btn-outline green\">',FROM_UNIXTIME(mdl_user.`timecreated`),'</span>')) FROM ".MOODLE_DB_NAME.".mdl_user WHERE idnumber = ".DB_NAME.".lista_professionisti.id )AS 'Creazione Moodle',
                                    (SELECT IF(FROM_UNIXTIME(mdl_user.`lastlogin`) LIKE '1970-%',CONCAT('<span class=\"btn sbold uppercase btn-outline red-thunderbird\">',FROM_UNIXTIME(mdl_user.`lastlogin`),'</span>'),CONCAT('<span class=\"btn sbold uppercase btn-outline green\">',FROM_UNIXTIME(mdl_user.`lastlogin`),'</span>')) FROM ".MOODLE_DB_NAME.".mdl_user WHERE idnumber = ".DB_NAME.".lista_professionisti.id )AS 'Ultimo Login Moodle',
                                    CONCAT('<a onclick=\"javascript: return confirm(\'Sei sicuro di voler cancellare definitivamente \\\\n',UCASE(cognome),' ',UCASE(nome),' - Codice: ',codice,' ?\');\" class=\"btn btn-circle btn-icon-only red-flamingo btn-outline\" href=\"moduli/anagrafiche/salva.php?tbl=lista_professionisti&idProfessionista=',id,'\&fn=eliminaUtenteMoodle&cercaQualcosa=".$cercaQualcosa."\"><i class=\"fa fa-trash\"></i></a>') AS 'Elimina'
                                    FROM lista_professionisti WHERE  stato NOT LIKE '%Elimin%'  ".$where_professionisti." ORDER BY cognome, nome, email ASC";
									*/
                                    //echo $query;
									
                                    $query = "SELECT CONCAT('<a href=\"moduli/anagrafiche/dettaglio.php?tbl=lista_professionisti&id=',id,'\"><i class=\"icon-arrow-right font-blue\"></i></a>') AS 'vedi', 
                                    CONCAT('<span class=\"btn sbold uppercase btn-outline blue\">',codice,'</span>') AS 'Codice Cliente',
                                    CONCAT('<h3>',cognome,'</h2>') AS 'cognome', 
                                    CONCAT('<h3>',nome,'</h2>') AS 'nome', 
                                    CONCAT('<h4>',email,'</h3>') AS 'email',
                                    (SELECT IF(data_creazione LIKE '1970-%',CONCAT('<span class=\"btn sbold uppercase btn-outline red-thunderbird\">',DATE(data_creazione),'</span>'),CONCAT('<span class=\"btn sbold uppercase btn-outline green\">',DATE(data_creazione),'</span>')) FROM lista_password WHERE lista_password.id_professionista=lista_professionisti.id AND livello='cliente' LIMIT 1) AS 'Creazione Moodle',
                                    (SELECT IF(data_ultimo_accesso LIKE '1970-%' OR data_ultimo_accesso LIKE '0000-00%',CONCAT('<span class=\"btn sbold uppercase btn-outline red-thunderbird\">',(data_ultimo_accesso),'</span>'),CONCAT('<span class=\"btn sbold uppercase btn-outline green\">',(data_ultimo_accesso),'</span>')) FROM lista_password WHERE lista_password.id_professionista=lista_professionisti.id AND livello='cliente' LIMIT 1) AS 'Ultimo Login  Moodle',
                                    CONCAT('<a onclick=\"javascript: return confirm(\'Sei sicuro di voler cancellare definitivamente \\\\n',UCASE(cognome),' ',UCASE(nome),' - Codice: ',codice,' ?\');\" class=\"btn btn-circle btn-icon-only red-flamingo btn-outline\" href=\"moduli/anagrafiche/salva.php?tbl=lista_professionisti&idProfessionista=',id,'\&fn=eliminaUtenteMoodle&cercaQualcosa=".addslashes($cercaQualcosa)."\"><i class=\"fa fa-trash\"></i></a>') AS 'Elimina'
                                    FROM lista_professionisti WHERE  stato NOT LIKE '%Elimin%' ".$where_professionisti." ORDER BY cognome, nome, email ASC";
                                    //echo $query;
									
                                    StampaSQL2017($query, 'Professionisti Trovati');
                                    }
                                    ?>

                                    </div>
                                    <!--<div class="tab-pane fade in" id="tab_indirizzi">

                                    <?php
                                    /*if(isset($_POST['cercaQualcosa']) and strlen($_POST['cercaQualcosa'])>0){
                                    $query = "SELECT CONCAT('<a href=\"moduli/base/dettaglio.php?tbl=lista_indirizzi&id=',id,'\"><i class=\"icon-arrow-right font-blue\"></i></a>') AS 'vedi', indirizzo, citta, provincia, cap FROM lista_indirizzi WHERE 1 ".$where_indirizzi;
                                    StampaSQL2017($query, $stile, 'Indirizzi Trovati');
                                    }
                                    
                                     */
                                    ?>

                                    </div>-->
                                    <div class="tab-pane fade in" id="tab_richieste">
                                    <?php
                                    if(isset($_POST['cercaQualcosa']) and strlen($_POST['cercaQualcosa'])>0){
                                    $query = "SELECT CONCAT('<a href=\"moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\"><i class=\"icon-arrow-right font-blue\"></i></a>') AS 'vedi', (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale',
                                            stato,
                                            mittente AS 'Mittente', (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Richiamo', ora AS 'Ora Richiamo', (SELECT nome FROM lista_campagne WHERE id = id_campagna) AS Campagna 
                                    FROM calendario
                                    WHERE etichetta LIKE 'Nuova Richiesta' ".$where_commenti;
                                    StampaSQL2017($query, 'Richieste Trovate');
                                    }
                                    ?>
                                    </div>
                              </div>

                              </div>
                        </div>

                      </div>
                  </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            
        </div>
        <!-- END CONTAINER -->
        <?php echo pageFooterCopy(); ?>
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
        <script src="<?=BASE_URL?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/pages/scripts/search.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?=BASE_URL?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?=BASE_URL?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>
