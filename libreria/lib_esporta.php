<?php
/*
STAMPA FORM DA TABELLA CON DEFINIZIONE ARRAY
link:http://keenthemes.com/preview/metronic/theme/admin_1/form_controls.html
*/
function stampa_bootstrap_form_esporta($tabella,$id,$titolo,$action="".BASE_URL."/libreria/esporta.php"){
    global $dblink, $table_listaAziende, $table_listaProfessionisti, $table_listaProfessioni,$table_listaOrdini,
            $table_listaPassword, $table_calendario, $table_listaPreventivi, $table_listaPreventiviDettaglio,
            $table_listaAlbiProfessionali, $table_listaRichiesteStati, $table_listaCampagne, $table_listaProdotti, $table_listaCorsi, $table_listaCorsiDettaglio,
            $table_listaIscrizioni,$table_listaIscrizioniPartecipanti, $table_listaCommerciali, $table_listaFattureInvioMultiplo, $table_listaCosti;

   if($action==""){
       $action="".BASE_URL."/libreria/esporta.php";
   }

   $stile = 'singola';
   $stile = 'doppia';
   //$stile = 'tripla';

    if(isset($id) && $id!=""){
        $where = "id='".$id."'";
        $arrayReturn['id']=$id;
        $arrayReturn['tbl']=$tabella;
    }else{
        $where = "1";
        $tabella="";
    }

    switch($tabella){
        
        case 'lista_costi':
            $arrayReturn['tabella'] = "lista_costi";
            $arrayReturn['tbl'] = "lista_costi";
            $arrayReturn = get_campi_tabella($table_listaCosti['esporta'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Entrate / Uscite';
        break;
        
        case 'lista_iscrizioni':
            $arrayReturn['tabella'] = "lista_iscrizioni";
            $arrayReturn['tbl'] = "lista_iscrizioni";
            $arrayReturn = get_campi_tabella($table_listaIscrizioni['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Iscrizione';
        break;
        
        case 'lista_iscrizioni_partecipanti':
            $arrayReturn['tabella'] = "lista_iscrizioni";
            $arrayReturn['tbl'] = "lista_iscrizioni";
            $arrayReturn = get_campi_tabella($table_listaIscrizioniPartecipanti['esporta'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Iscrizioni Partecipanti';
        break;

         case 'lista_password_commerciali':
            $arrayReturn['tabella'] = "lista_password";
            $arrayReturn = get_campi_tabella($table_listaCommerciali['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Commerciali';
        break;

        case 'lista_corsi':
            $arrayReturn['tabella'] = "lista_corsi";
            $arrayReturn = get_campi_tabella($table_listaCorsi['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Corso';
        break;

        case 'lista_corsi_dettaglio':
            $arrayReturn['tabella'] = "lista_corsi_dettaglio";
            $arrayReturn = get_campi_tabella($table_listaCorsiDettaglio['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Durata Corso';
        break;

         case 'lista_prodotti':
            $arrayReturn['tabella'] = "lista_prodotti";
            $arrayReturn = get_campi_tabella($table_listaProdotti['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = 'Filtra Prodotto';
        break;

        case 'lista_preventivi_dettaglio':
            $arrayReturn['tabella'] = "lista_preventivi_dettaglio";
            $arrayReturn = get_campi_tabella($table_listaPreventiviDettaglio['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = $titolo;
        break;

        case 'lista_preventivi':
            $arrayReturn['tabella'] = "lista_preventivi";
            $arrayReturn = get_campi_tabella($table_listaPreventivi['esporta'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = $titolo;
        break;

        case 'lista_ordini':
            $arrayReturn['tabella'] = "lista_ordini";
            $arrayReturn = get_campi_tabella($table_listaOrdini['esporta'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = $titolo;
        break;

        case 'lista_aziende':
            $arrayReturn['tabella'] = "lista_aziende";
            $arrayReturn = get_campi_tabella($table_listaAziende['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = $titolo;
        break;

        case 'lista_professionisti':
            $arrayReturn['tabella'] = "lista_professionisti";
            $arrayReturn = get_campi_tabella($table_listaProfessionisti['esporta'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Professionista";
        break;

        case 'lista_professioni':
            $arrayReturn['tabella'] = "lista_professioni";
            $arrayReturn = get_campi_tabella($table_listaProfessioni['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Professione";
        break;

        case 'lista_albi_professionali':
            $arrayReturn['tabella'] = "lista_albi_professionali";
            $arrayReturn = get_campi_tabella($table_listaAlbiProfessionali['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Albo Professionale";
        break;

        case 'lista_password':
            $arrayReturn['tabella'] = "lista_password";
            $arrayReturn = get_campi_tabella($table_listaPassword['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Password";
        break;

        case 'calendario':
            $arrayReturn['tabella'] = "calendario";
            $arrayReturn = get_campi_tabella($table_calendario['esporta'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Richieste";
        break;

        case 'lista_richieste_stati':
            $arrayReturn['tabella'] = "lista_richieste_stati";
            $arrayReturn = get_campi_tabella($table_listaRichiesteStati['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Stati Richieste";
        break;

        case 'lista_campagne':
            $arrayReturn['tabella'] = "lista_campagne";
            $arrayReturn = get_campi_tabella($table_listaCampagne['modifica'], $arrayReturn);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = "Filtra Campagna";
        break;

        default:
            $campi_visualizzati = "";
            $campi_etichette = "";
            $conta_colonne      = 	$dblink->list_fields("SELECT * FROM ".$tabella."");
            $arrayReturn['campi_tipo'] = "";
            $campi_non_editabili = "";

                foreach($conta_colonne as $colonna){
                    $nome_colonna = $colonna->name;
                    $campi_visualizzati.= "`".$nome_colonna."`, ";
                    $campi_etichette.= "".ucfirst(pulisciCampoEtichetta($nome_colonna)).",";

                    $pos = strpos(strtolower($nome_colonna), "id_");
                    $tipo = $colonna->type;

                    switch (strtolower($tipo)){
                        case "252":
                        case "blob":
                            $arrayReturn['campi_tipo'].="text,";
                            break;
                        case "3":
                        case "16":
                        case "1":
                        case "2":
                        case "9":
                        case "8":
                        case "4":
                        case "5":
                        case "246":
                        case "int":
                        case "real":
                            $arrayReturn['campi_tipo'].="numerico,";
                        break;
                        case "12":
                        case "datetime":
                            $arrayReturn['campi_tipo'].="dataora,";
                        break;
                        case "10":
                        case "date":
                            $arrayReturn['campi_tipo'].="data,";
                        break;
                        case "11":
                        case "time":
                            $arrayReturn['campi_tipo'].="ora,";
                        break;
                        case "254":
                        case "253":
                        case "string":
                        default:
                            $arrayReturn['campi_tipo'].="input,";
                        break;
                    }
                }


            $arrayReturn['tabella'] = $tabella;
            $campi_visualizzati = substr($campi_visualizzati, 0, -2);
            $campi_etichette = substr($campi_etichette, 0, -1);
            $arrayReturn['campi_visualizzati'] = $campi_visualizzati;
            //$arrayReturn['campi_non_editabili'] = "id, dataagg, scrittore, codice".$campi_non_editabili;
            //print_r(explode(",",$campi_etichette));
            $arrayReturn['campi_etichette'] = explode(",",$campi_etichette);
            $arrayReturn['campi_etichette'] = $arrayReturn['campi_etichette'];

            //print_r($arrayReturn['campi_etichette']);
            //array_push($arrayReturn['campi_etichette'], $campi_etichette);
            //questo è quello sopra $arrayReturn['campi_etichette'] = array($campi_etichette);
            $arrayReturn['where'] = " `id` =".$id;
            $arrayReturn['titolo'] = $titolo;
        break;
    }


    $arrayReturn['referer'] = $_SERVER['HTTP_REFERER'];
    
    $campi_non_editabili = explode(",",$arrayReturn['campi_non_editabili']);
    $campi_visualizzati = explode(",",$arrayReturn['campi_visualizzati']);
    
    foreach ($campi_non_editabili as $search){
        $key = array_search("`".$search."`", $campi_visualizzati);
        unset($campi_visualizzati[$key]);
    }
    
    $campi_visualizzati = implode(",", $campi_visualizzati);

    $arrayReturn['esporta'] = false;
    $arrayReturn['esporta'] = true;
    $arrayReturn['num_campi'] = $dblink->num_fields("SELECT ".$campi_visualizzati." FROM ".$arrayReturn['tabella']." LIMIT 1");
    $arrayReturn['sql'] = "SELECT ".$campi_visualizzati." FROM ".$arrayReturn['tabella']." WHERE ".$arrayReturn['where'];
    $row = $dblink->get_row($arrayReturn['sql']);
?>
        <!-- INIZIO FORM-->
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <style>
            .form .form-bordered .form-group>div {
                padding: 10px ! important;
            }
            </style>
        <div class="portlet box <?= COLORE_PRIMARIO ?>">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-light"></i>
                    <span class="caption-subject font-light uppercase"><?=$arrayReturn['titolo']?></span>
                </div>
                <div style="float: right; padding-top: 5px;"><button class="dt-button buttons-print btn white btn-outline" onclick="SelezTT();">Seleziona Tutto</button></div>
                <div style="float: right; padding-top: 5px; margin-right: 5px;"><button class="dt-button buttons-print btn white btn-outline" onclick="DeSelezTT();">Deseleziona Tutto</button></div>
            </div>
            <div class="portlet-body form">
                <form id="fEsporta" name="fEsporta" class="form-inline form-bordered" enctype="multipart/form-data" role="form" action="<?=$action?>" method="POST">
                    <input type="HIDDEN" id="txt_tbl" name="txt_tbl" value="<?=$arrayReturn['tbl']?>">
                    <input type="HIDDEN" id="txt_id" name="txt_id" value="<?=$arrayReturn['id']?>">
                    <input type="HIDDEN" id="txt_where" name="txt_where" value="<?=$arrayReturn['where']?>">
                    <input type="HIDDEN" id="txt_tabella" name="txt_tabella" value="<?=$arrayReturn['tabella']?>">
                    <input type="HIDDEN" id="txt_referer" name="txt_referer" value="<?=$arrayReturn['referer']?>">
                    <input type="HIDDEN" id="txt_num_campi" name="txt_num_campi" value="<?=$arrayReturn['num_campi']?>">
                    <div class="form-body">
    <?php
    //inputTendina($label, $idInput, $nameInput, $sql);
    $arrayCampi = explode(",", $arrayReturn['campi_visualizzati']);
    foreach ($arrayCampi as $key => $value) {
        $arrayCampi[$key] = trim(str_replace("`", "", $value));
    }
    $arrayCampiNonEditabili = explode(",", $arrayReturn['campi_non_editabili']);

    foreach ($arrayCampiNonEditabili as $key => $value) {
        $arrayCampiNonEditabili[$key] = trim(str_replace("`", "", $value));
    }

    $arrayTipoCampi = explode(",", $arrayReturn['campi_tipo']);

    if($stile=="singola"){
        $colNum = 6;
    }else if($stile=="doppia"){ 
        $colNum = 3;
    }else{
        $colNum = 2;
    }

    $count = 1;
    $hidden = "";
    $countCampiAll = 0;
    $countCampiHiddenAll = 0;
    foreach ($arrayCampi as $key => $campo) {
        if(trim($arrayTipoCampi[$key])=="hidden"){
            $countCampiHiddenAll++;
        }
        $countCampiAll++;
    }
    $countCampi = $countCampiHiddenAll-1;
    echo "<div class=\"col-md-12 form-group\">";
    foreach ($arrayCampi as $key => $campo) {
        $countCampi++;
        /*if(trim($arrayTipoCampi[$key])=="hidden"){
                $hidden.=print_hidden($campo,(isset($_POST[$campo]) ? $_POST[$campo] : $row[$key]),false);
            continue;
        }*/
        
        /*if($countCampi > $countCampiAll){
            $colNum = (5-$count)*$colNum;
        }*/
        
        if(($stile=="tripla" && $count==7) || ($stile=="doppia" && $count==5) || ($stile=="singola" && $count==3)){
            echo "</div><div class=\"col-md-12 form-group\">";
            $count = 1;
        }
        //cro-> aggiunto controllo tipo campi vuoto
        if(strlen(trim($arrayTipoCampi[$key]))<=0){
            $arrayTipoCampi[$key] = 'input';
        }
        switch (trim($arrayTipoCampi[$key])) {
            default:
            case "input":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_input($campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['campi_etichette'][$key],'');
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : $arrayReturn['slk_default'][$campo]));
                echo "</div>
                    </div>";
            break;
        
            case "inner_select":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_hidden("inner_select_".$campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['campi_etichette'][$key],true);
                        echo "<span class=\"btn btn-outline black input-sm\" style=\"width: 100%; border: 1px solid #ccc;\">".$arrayReturn['campi_etichette'][$key]."</span>";
                        //print_select_static(array($arrayReturn['slk_default'][$campo] => $arrayReturn['slk_default'][$campo]), "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : (isset($arrayReturn['slk_default'][$campo]) ? $arrayReturn['slk_default'][$campo] : "")));
                echo "</div>
                    </div>";
            break;
        
            case "ora":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_input_ora($campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['campi_etichette'][$key],'');
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : $arrayReturn['slk_default'][$campo]));
                echo "</div>
                    </div>";
            break;
        
            case "data":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_input_date($campo,(isset($_POST[$campo]) ? $_POST[$campo] : (isset($arrayReturn['default'][$campo]) ? $arrayReturn['default'][$campo] : "")),$arrayReturn['campi_etichette'][$key],'');
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : (isset($arrayReturn['slk_default'][$campo]) ? $arrayReturn['slk_default'][$campo] : "")));
                echo "</div>
                    </div>";
            break;
        
            case "confronto_data":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        $arrayReturn['slk'][$campo]['BETWEEN'] = "BETWEEN";
                        $arrayReturn['slk'][$campo]['ESCLUDI'] = "ESCLUDI";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_input_date_between($campo,(isset($_POST[$campo]) ? $_POST[$campo] : (isset($arrayReturn['default'][$campo]) ? $arrayReturn['default'][$campo] : "")),$arrayReturn['campi_etichette'][$key],'');
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : (isset($arrayReturn['slk_default'][$campo]) ? $arrayReturn['slk_default'][$campo] : "")));
                echo "</div>
                    </div>";
            break;

            /*case "hidden":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">";
                        print_hidden($campo,$row[$key],$arrayReturn['campi_etichette'][$key],'');
                    echo "</div>";
            break;

            case "data":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">";
                        print_input_date($campo,$row[$key],$arrayReturn['campi_etichette'][$key],'');
                    echo "</div>";
            break;

            case "text":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <textarea id=\"$campo\" name=\"$campo\" class=\"form-control input-sm\" rows=\"3\" >$row[$key]</textarea>
                        </div>";
            break;

            case "password":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <input id=\"$campo\" name=\"$campo\" type=\"password\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" >
																<!--TODO creare variabili tema campo_stile_icona_background per sostituire (background-color: #fff;) e campo_colore_icona per sostituire (font-grey-mint)-->
																<span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-user font-grey-mint\"></i>
                                </span>
                            </div>
                        </div>";
            break;

            case "indirizzo":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-map-marker font-grey-mint\"></i>
                                </span>
                                <input id=\"$campo\" name=\"$campo\" type=\"text\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" >
														</div>
                        </div>";
            break;

            case "telefono":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-phone-square font-grey-mint\"></i>
                                </span>
                                <input id=\"$campo\" name=\"$campo\" type=\"tel\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" >
																<span class=\"input-group-btn\"><a href=\"tel:$row[$key]\" class=\"btn grey-mint\" target=\"_blank\"> Chiama <i class=\"fa fa-external-link\"></i></a></span>
                        		</div>
                        </div>";
            break;

            case "cellulare":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-mobile font-grey-mint\"></i>
                                </span>
                                <input id=\"$campo\" name=\"$campo\" type=\"tel\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" >
																<span class=\"input-group-btn\"><a href=\"tel:$row[$key]\" class=\"btn grey-mint\" target=\"_blank\"> Chiama <i class=\"fa fa-external-link\"></i></a></span>
                        		</div>
                        </div>";
            break;

            case "fax":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-fax font-grey-mint\"></i>
                                </span>
                                <input id=\"$campo\" name=\"$campo\" type=\"text\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" > </div>
                        </div>";
            break;

            case "email":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-envelope font-grey-mint\"></i>
                                </span>
                                <input id=\"$campo\" name=\"$campo\" type=\"email\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" >
																<span class=\"input-group-btn\"><a href=\"mailto:$row[$key]\" class=\"btn grey-mint\" target=\"_blank\"> Invia <i class=\"fa fa-external-link\"></i></a></span>
                        		</div>
                        </div>";
            break;

            case "web":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\">
                            <div class=\"input-group input-group-sm\">
                                <span class=\"input-group-addon\" style=\"background-color: #fff;\">
                                    <i class=\"fa fa-globe font-grey-mint\"></i>
                                </span>
                                <input id=\"$campo\" name=\"$campo\" type=\"url\" value=\"$row[$key]\" class=\"form-control input-sm\" placeholder=\"".$arrayReturn['campi_etichette'][$key]."\" >
																<span class=\"input-group-btn\"><a href=\"$row[$key]\" class=\"btn grey-mint\" target=\"_blank\"> Vedi <i class=\"fa fa-external-link\"></i></a></span>
                        		</div>
												</div>";
            break;

            case "radio":
                echo "<label class=\"col-md-$colNum control-label\">".$arrayReturn['campi_etichette'][$key]."</label>
                        <div class=\"col-md-$inputColNum\"><div class=\"mt-radio-inline\">";
                    foreach ($arrayReturn['campi_radio'][$campo] as $value) {
                        echo "<label class=\"mt-radio\">
                                <input type=\"radio\" name=\"$campo.$value\" id=\"$campo.$value\" value=\"$value\" ".(($value==$row[$key]) ? "checked=\"\"" : "")." ".(in_array($campo, $arrayCampiNonEditabili) ? "disabled=\"\"" : "")."> $value
                                <span></span>
                            </label>";
                    }
                echo "</div>";
            break;

            */
            case "select_static":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_select_static($arrayReturn['campi_select'][$campo],$campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]), "", true,"select2-allow-clear");
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : $arrayReturn['slk_default'][$campo]), "", true);
                echo "</div>
                    </div>";
            break;

            case "select2":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        if(!empty($arrayReturn['ajax'])){
                            print_select2($arrayReturn['campi_select'][$campo],$campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['ajax'][$campo],true,"select2-allow-clear");
                        }else{
                            print_select2($arrayReturn['campi_select'][$campo],$campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),"",true,"select2-allow-clear");
                        }
                        //print_input_date($campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['campi_etichette'][$key],'');
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : $arrayReturn['slk_default'][$campo]), "", true);
                echo "</div>
                    </div>";
            break;
        
            case "select_in":
                echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        $arrayReturn['slk'][$campo]['IN'] = "IN";
                        $arrayReturn['slk'][$campo]['NOT IN'] = "NOT IN";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_select2($arrayReturn['campi_select'][$campo],$campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['ajax'][$campo],true,"select2-allow-clear");
                        //print_input_date($campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]),$arrayReturn['campi_etichette'][$key],'');
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : $arrayReturn['slk_default'][$campo]), "", true);
                echo "</div>
                    </div>";
            break;

            case "bs-select":
              echo "<div class=\"col-md-$colNum\">
                        <label>".$arrayReturn['campi_etichette'][$key]."</label><br />
                        <div class=\"input-group\">
                        <span class=\"input-group-addon\">
                        <input type=\"checkbox\" id=\"chk_$campo\" name=\"chk_$campo\" ".(isset($_POST['chk_'.$campo]) ? "checked" : (!isset($_POST) && $arrayReturn['chk'][$campo] ? "checked" : ""))."><span></span>
                        </span>";
                        print_hidden("etk_".$campo,$arrayReturn['campi_etichette'][$key]);
                        print_bs_select($arrayReturn['campi_select'][$campo],$campo,(isset($_POST[$campo]) ? $_POST[$campo] : $arrayReturn['default'][$campo]));
                        print_select_static($arrayReturn['slk'][$campo], "slk_$campo", (isset($_POST["slk_$campo"]) ? $_POST["slk_$campo"] : $arrayReturn['slk_default'][$campo]));
                echo "</div>
                    </div>";
            break;
        }
        $count++;
    }
    
    echo "$hidden</div>";
    ?>
    <?php if($arrayReturn['esporta']){ ?>
                    <div class="form-actions right">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle btn-lg green-jungle"><i class="fa fa-check"></i> Filtra / Esporta</button>
                            </div>
                        </div>
                    </div>
    <?php } ?>
                </form>
            </div>
        </div>
    </div>
        <!-- END SAMPLE FORM PORTLET-->
        <!-- FINE FORM-->
<?php
    //ECHO '<H1>Fine Stampa_HTML_Form_Campi_Modificabili</H1>';
    return;
}