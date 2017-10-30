<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Import($tabella){
    global $dblink;

    switch($tabella){

        case "lista_importazione_richieste":
            $tabella = "lista_importazione_richieste";
            $titolo = 'Importazione Manuale delle Richieste da Excel (CSV)';
                
                $campi_visualizzati = "";
                $campi     = 	$dblink->list_fields("SELECT * FROM ".$tabella."");
                foreach ($campi as $nome_colonna) {
                     $campi_visualizzati.= "`".$nome_colonna->name."`, ";
                }


                $campi_visualizzati = substr($campi_visualizzati, 0, -2);
                $where = " 1 AND stato = 'Importato' ";
                $ordine = " ORDER BY id DESC";
                $titolo = "Elenco ".$tabella;
                $stile = "tabella_base";
                $colore_tabella = COLORE_PRIMARIO;
                //CONCAT('<a class=\"btn btn-circle btn-icon-only yellow btn-outline\" href=\"dettaglio.php?tbl=".$tabella."&id=',id,'\" title=\"DETTAGLIO\" alt=\"DETTAGLIO\"><i class=\"fa fa-search\"></i></a>') AS 'fa-search',
                //CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"duplica.php?tbl=".$tabella."&id=',id,'\" title=\"DUPLICA\" alt=\"DUPLICA\"><i class=\"fa fa-copy\"></i></a>') AS 'fa-copy',
                
                $sql_0001 = "SELECT
                CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=".$tabella."&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
                ".$campi_visualizzati.",
                CONCAT('<a class=\"btn btn-circle btn-icon-only red btn-outline\" href=\"cancella.php?tbl=".$tabella."&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><i class=\"fa fa-trash\"></i></a>') AS 'fa-trash'
                FROM ".$tabella." WHERE $where $ordine";
                //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
                ?>
                <div class="row" style="margin-bottom: 20px;">
                    <form name="formImportRichieste" id="formImportRichieste" method="POST" enctype="multipart/form-data" action="salva.php?tbl=<?=$tabella?>&fn=importRichieste">
                        <div class="col-md-6">
                            <label>Importa File Richieste CSV</label>
                            
                            <input type="file" class="input-lg" name="importRichieste" id="importRichieste">
                        </div>
                        <div class="col-md-3">
                            <input class="btn btn-outline green-jungle" type="submit" value="CARICA">
                        </div>
                        <div class="col-md-3">
                            <?php 
                            $importati = $dblink->num_rows($sql_0001);
                            if($importati>0){
                            
                                ?><a class="btn btn-outline purple-studio" href="<?=BASE_URL?>/moduli/import/salva.php?fn=importaSuCalendario"><i class="fa fa-external-link"></i> Carica le richieste importate (n°<?=$importati?>)</a><?php
                            }
                            ?>
                        </div>
                        
                    </form>
                </div>
                <div class="clearfix"></div>
                <div class="row" style="margin-bottom: 20px;">
                
                    <div class="col-md-12"><h5><b>ELENCO DELLE COLONNE CONSENTITE</b> (* campi obbligatori)</h5>
                        <p></p>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                            <tr>
                                <td>CAMPAGNA *</td>
                                <td>TIPO MARKETING *</td>
                                <td>COGNOME *</td>
                                <td>NOME *</td>
                                <td>PROFESSIONE</td>
                                <td>TELEFONO</td>
                                <td>EMAIL</td>
                                <td>CITTA</td>
                                <td>PRODOTTO</td>
                                <td>CORTE</td>
                                <td>DATA_RICHIESTA *</td>
                                <td>MESSAGGIO *</td>
                            </tr>
                            <tr>
                                <td>TELEFONO</td>
                                <td>TELEFONATE</td>
                                <td>Rossi</td>
                                <td>Mario</td>
                                <td>Architetto</td>
                                <td>021234454</td>
                                <td>mariorossi@gmail.com</td>
                                <td>Milano</td>
                                <td>abb_arch</td>
                                <td>Architetti</td>
                                <td>2017-09-01</td>
                                <td>Testo della richiesta per il commerciale</td>
                            </tr>
                        </table>
                        </div>
                </div>
                <div class="clearfix"></div>
                <?php

                stampa_table_datatables_ajax($sql_0001, "datatable_ajax", $titolo, '', '', false);
        break;
        
        case "lista_attivazioni_manuale":
            $tabella = "lista_attivazioni_manuale";
            $titolo = 'Attivazione Manuale ed Iscrizioni ai Corsi';
                
                $campi_visualizzati = "";
                $campi     = 	$dblink->list_fields("SELECT * FROM ".$tabella."");
                foreach ($campi as $nome_colonna) {
                     $campi_visualizzati.= "`".$nome_colonna->name."`, ";
                }


                $campi_visualizzati = substr($campi_visualizzati, 0, -2);
                $where = " 1 AND stato = 'Importato' ";
                $ordine = " ORDER BY id DESC";
                $titolo = "Elenco ".$tabella;
                $stile = "tabella_base";
                $colore_tabella = COLORE_PRIMARIO;
                $sql_0001 = "SELECT
                CONCAT('<a class=\"btn btn-circle btn-icon-only yellow btn-outline\" href=\"dettaglio.php?tbl=".$tabella."&id=',id,'\" title=\"DETTAGLIO\" alt=\"DETTAGLIO\"><i class=\"fa fa-search\"></i></a>') AS 'fa-search',
                CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=".$tabella."&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
                CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"duplica.php?tbl=".$tabella."&id=',id,'\" title=\"DUPLICA\" alt=\"DUPLICA\"><i class=\"fa fa-copy\"></i></a>') AS 'fa-copy',
                ".$campi_visualizzati.",
                CONCAT('<a class=\"btn btn-circle btn-icon-only red btn-outline\" href=\"cancella.php?tbl=".$tabella."&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><i class=\"fa fa-trash\"></i></a>') AS 'fa-trash'
                FROM ".$tabella." WHERE $where $ordine";
                //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
                ?>
                <div class="row" style="margin-bottom: 20px;">
                    <form name="formImportAttivazioni" id="formImportAttivazioni" method="POST" enctype="multipart/form-data" action="salva.php?tbl=<?=$tabella?>&fn=importAttivazioni">
                        <div class="col-md-6">
                            <label>Importa File Attivazioni CSV</label>
                            <input type="file" class="input-lg" name="importAttivazioni" id="importAttivazioni">
                        </div>
                        <div class="col-md-3">
                            <input class="btn btn-outline green-jungle" type="submit" value="CARICA">
                        </div>
                        <div class="col-md-3">
                            <?php 
                            $importati = $dblink->num_rows($sql_0001);
                            if($importati>0){
                            
                                ?><a class="btn btn-outline purple-studio" href="<?=BASE_URL?>/libreria/automazioni/autoAttivaUtentiMoodleManuale.php" target="_blank"><i class="fa fa-external-link"></i> Attiva gli utenti importati (n°<?=$importati?>)</a><?php
                            }
                            
                            $where2 = " 1 AND stato = 'Importazione Manuale' ";
                            $sql_0002 = "SELECT DISTINCT
                            ".$campi_visualizzati."
                            FROM ".$tabella." WHERE $where2 $ordine";
                            $importatiManuali = $dblink->num_rows($sql_0002);
                            if($importatiManuali>0){
                                ?><a class="btn btn-outline red-flamingo" href="<?=BASE_URL?>/libreria/automazioni/autoImportazioneManuale.php" target="_blank"><i class="fa fa-external-link"></i> Attiva Importazioni Manuali (n°<?=$importatiManuali?>)</a><?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
                <?php

                stampa_table_datatables_ajax($sql_0001, "datatable_ajax", $titolo, '', '', false);
        break;
            
            
        default:
            
            $campi_visualizzati = "";
            $campi     = 	$dblink->list_fields("SELECT * FROM ".$tabella."");
            foreach ($campi as $nome_colonna) {
                 $campi_visualizzati.= "`".$nome_colonna->name."`, ";
            }


            $campi_visualizzati = substr($campi_visualizzati, 0, -2);
            $where = " 1 ";
            $ordine = " ORDER BY id DESC";
            $titolo = "Elenco ".$tabella;
            $stile = "tabella_base";
            $colore_tabella = COLORE_PRIMARIO;
            $sql_0001 = "SELECT
            CONCAT('<a class=\"btn btn-circle btn-icon-only yellow btn-outline\" href=\"dettaglio.php?tbl=".$tabella."&id=',id,'\" title=\"DETTAGLIO\" alt=\"DETTAGLIO\"><i class=\"fa fa-search\"></i></a>') AS 'fa-search',
            CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=".$tabella."&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
            CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"duplica.php?tbl=".$tabella."&id=',id,'\" title=\"DUPLICA\" alt=\"DUPLICA\"><i class=\"fa fa-copy\"></i></a>') AS 'fa-copy',
            ".$campi_visualizzati.",
            CONCAT('<a class=\"btn btn-circle btn-icon-only red btn-outline\" href=\"cancella.php?tbl=".$tabella."&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><i class=\"fa fa-trash\"></i></a>') AS 'fa-trash'
            FROM ".$tabella." WHERE $where $ordine LIMIT 1";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            
            stampa_table_datatables_ajax($sql_0001, "datatable_ajax", $titolo, '', '', false);
            //stampa_table_datatables_responsive($sql_0001, $titolo, $stile, $colore_tabella);
        break;

    }
}
?>
