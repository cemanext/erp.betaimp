<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Corsi($tabella){
    global $table_listaCorsi, $table_listaClassi, $table_calendarioEsami, $table_documentiAttestati;
    
    switch($tabella){

        case 'lista_attestati':
            $tabella = "lista_attestati";
            $campi_visualizzati = $table_documentiAttestati['index']['campi'];
            $where = $table_documentiAttestati['index']['where'];
            $ordine = $table_documentiAttestati['index']['order'];
            $titolo = 'Elenco Attestati';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
        break;
        
        case 'calendario_esami':
            $tabella = "calendario";
            $campi_visualizzati = $table_calendarioEsami['index']['campi'];
            $where = $table_calendarioEsami['index']['where'];
            $ordine = $table_calendarioEsami['index']['order'];
            $titolo = 'Calendario Corsi';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
        break;
        
        case 'lista_corsi':
            $tabella = "lista_corsi";
            $campi_visualizzati = $table_listaCorsi['index']['campi'];
            $where = $table_listaCorsi['index']['where'];
            $ordine = $table_listaCorsi['index']['order'];
            $titolo = 'Elenco Corsi';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine";
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
        break;
    
        case 'lista_classi':
            $tabella = "lista_classi";
            $campi_visualizzati = $table_listaClassi['index']['campi'];
            $where = $table_listaClassi['index']['where'];
            $ordine = $table_listaClassi['index']['order'];
            $titolo = 'Elenco Classi';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine";
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
        break;

    }
}

function Stampa_HTML_Dettaglio_Corsi($tabella,$id){
    global $table_listaCorsi, $dblink;
    
    switch ($tabella) {
        case 'calendario_esami':
        $idCorso = $_GET['idCorso'];
        
            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT CONCAT('<H3>',nome_prodotto,'</H3>') AS 'Corso', 
            (SELECT codice FROM lista_prodotti WHERE id = id_prodotto LIMIT 1) AS 'Codice', 
            (SELECT codice_esterno FROM lista_prodotti WHERE id = id_prodotto LIMIT 1) AS 'ID MOODLE', durata, stato 
            FROM `lista_corsi` WHERE id =" . $idCorso;
            stampa_table_static_basic($sql_0001, '', 'Corso', 'green-haze');
            echo '</div></div>';
            
            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT   CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=calendario_esami&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
            data, ora, oggetto, numerico_10 AS 'Iscritti', stato
            FROM calendario
            WHERE id_corso=" . $idCorso." 
            AND etichetta LIKE 'Calendario Esami'
            ORDER BY data DESC, ora ASC";
            stampa_table_static_basic($sql_0001, '', 'Esami Disponibili', 'blue-steel');
            echo '</div></div>';
         
            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT 
            CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=calendario_iscrizioni&idCalendario=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
            data, ora, oggetto, 
            (SELECT CONCAT(cognome, ' ', nome) FROM lista_professionisti WHERE id=id_professionista) AS 'Iscritto', stato,
            CONCAT('<a class=\"btn btn-circle btn-icon-only red-thunderbird btn-outline\" href=\"cancella.php?tbl=calendario_esami&idCalendario=',id,'&idCalendarioCorso=',id_calendario_0,'&idIscrizione=',id_iscrizione,'\" title=\"DISISCRIVI DAL CORSO\" alt=\"DISISCRIVI DAL CORSO\"><i class=\"fa fa-user-times\"></i></a>') AS 'fa-user-times' 
            FROM calendario
            WHERE id_corso=" . $idCorso." 
            AND etichetta LIKE 'Iscrizione Esame'
            ORDER BY data DESC, ora ASC";
            stampa_table_static_basic($sql_0001, '', 'Esami - Iscrizioni', 'green');
            echo '</div></div>';
            
        break;
        
        case 'lista_corsi':
            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_00001 = "UPDATE lista_corsi, lista_prodotti
            SET lista_corsi.nome_prodotto = lista_prodotti.nome
            WHERE lista_corsi.id_prodotto = lista_prodotti.id";
            $rs_00001 = $dblink->query($sql_00001);
            
            $sql_00001 = "SELECT SUM(durata) as durata_corso FROM lista_corsi_dettaglio WHERE id_corso='".$id."'";
            $durata_corso = $dblink->get_field($sql_00001);
            
            $sql_00002 = "UPDATE lista_corsi
            SET lista_corsi.durata = '".$durata_corso."'
            WHERE lista_corsi.id=".$id."";
            $rs_00002 = $dblink->query($sql_00002);

            $sql_0001 = "SELECT CONCAT('<H3>',nome_prodotto,'</H3>') AS 'Corso', 
            (SELECT codice FROM lista_prodotti WHERE id = id_prodotto LIMIT 1) AS 'Codice', 
            (SELECT codice_esterno FROM lista_prodotti WHERE id = id_prodotto LIMIT 1) AS 'ID MOODLE', durata, stato 
            FROM `lista_corsi` WHERE id ='".$id."'";
            stampa_table_static_basic($sql_0001, '', 'Corso', 'green-haze');
            echo '</div></div>';
            echo '<center><a href="salva.php?tbl=lista_corsi&idCorso=' . $id . '&fn=aggiungiConfigurazioneCorso" class="btn green-meadow">
                        Aggiungi Configurazione
                        <i class="fa fa-plus"></i>
                        </a></center>';
            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT id, 
            CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=lista_corsi_configurazioni&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa fa-edit',
            CONCAT('<a class=\"btn btn-circle btn-icon-only red btn-outline\" href=\"cancella.php?tbl=lista_corsi_configurazioni&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><i class=\"fa fa-trash\"></i></a>') AS 'fa fa-trash', 
            codice_corso AS 'Codice', id_classe AS 'Classe', data_inizio, data_fine, crediti, durata_corso, avanzamento,  codice_accreditamento AS 'Cod. Accr.',
            id_attestato AS 'Attestato PDF' 
            FROM `lista_corsi_configurazioni` WHERE id_corso ='".$id."'";
            echo "<form enctype=\"multipart/form-data\" role=\"form\" action=\"salva.php?tbl=lista_corsi_configurazioni&idCorso=' . $id . '&fn=salvaConfigurazioneCorso\" method=\"POST\">";
            stampa_table_static_basic_input('lista_corsi_configurazioni', $sql_0001, '', 'Configurazione', 'green-haze');
            echo '<center><button type="submit" class="btn green-meadow">
                        <i class="fa fa-save"></i>
                        Salva Configurazione
                        </button></center>';
            echo '</div></div>';
             echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=calendario_esami&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
            data, ora, oggetto, stato
            FROM calendario
            WHERE id_corso='".$id."' 
            AND etichetta LIKE 'Calendario Esami'
            ORDER BY data DESC, ora ASC";
            stampa_table_static_basic($sql_0001, '', 'Esami', 'blue-steel');
            echo '<center><a href="salva.php?tbl=lista_corsi&idCorsoNuovoEsame=' . $id . '&fn=aggiungiEsameCorso" class="btn green-meadow">
                        Aggiungi Esame
                        <i class="fa fa-plus"></i>
                        </a></center>';
            echo '</div></div>';
            
             echo '<div class="row"><div class="col-md-12 col-sm-12">';
            //$sql_0001 = "SELECT id, `ordine`, `id_modulo`, `url`, `name`, `instance`, `visible`, `modicon`, `modname`, `modplural`, `availability`, `indent`
            //FROM lista_corsi_dettaglio WHERE id_corso=".$id;
            $sql_0001 = "SELECT 
            CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=lista_corsi_dettaglio&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit', 
            CONCAT('<H4>',`name`,'</H4>') AS 'Nome', 
            `modname` AS 'Tipo', durata,
            IF(`visible`>=1,'Attivo','Non Attivo') AS 'Stato',
             `ordine`, `id_modulo`,          
              `instance` AS 'ISTANCE MOODLE'
            FROM lista_corsi_dettaglio WHERE id_corso='".$id."' ORDER BY ordine ASC";
            stampa_table_static_basic($sql_0001, '', 'Moduli', 'green-haze');
            echo '</div></div>';
            echo '<div class="row">';
            echo '<div class="col-md-6 col-sm-6">';
            $sql_0002 = "SELECT SUM((prezzo_prodotto*quantita)) AS '&euro;' "
                    . "FROM lista_preventivi_dettaglio "
                    . "WHERE id_preventivo= '".$id."'";
            //stampa_table_static_basic($sql_0002, '', 'Totale Imponibile', 'red');
            echo '</div>';
            echo '<div class="col-md-6 col-sm-6">';
            $sql_0002 = "SELECT SUM((prezzo_prodotto*(1+(iva_prodotto/100)))*quantita) AS '&euro;' "
                    . "FROM lista_preventivi_dettaglio "
                    . "WHERE id_preventivo= '".$id."'";
            //stampa_table_static_basic($sql_0002, '', 'Totale Importo', 'green-meadow');
            echo '</div>';
            
            /*$sql_00006 = "SELECT CONCAT('<H3>',nome_prodotto,'</H3>') AS 'corso', 
            (SELECT codice FROM lista_prodotti WHERE id = id_prodotto LIMIT 1) AS 'codice', 
            (SELECT codice_esterno FROM lista_prodotti WHERE id = id_prodotto LIMIT 1) AS 'id_moodle', id_prodotto, durata, stato 
            FROM `lista_corsi` WHERE id ='".$id."' LIMIT 1";
            $rs_00006 = mysql_query($sql_00006);
            if ($rs_00006) {

                while ($row_00006 = mysql_fetch_array($rs_00006, MYSQL_BOTH)) {
                    $nome_Corso = $row_00006['corso'];
                    $codice_corso = $row_00006['codice'];
                    $id_moodle = $row_00006['id_moodle'];
                    $id_prodotto = $row_00006['id_prodotto'];
                }
            }*/
            
            /*echo '<center><a href="'.BASE_URL.'/libreria/automazioni/sinc_moodle.php?fn=lista_corsi_dettaglio&id='.$id_moodle.'&idProd='.$id_prodotto.'&idCorso='.$id.'" class="btn yellow-gold">
                Aggiorna Corso
                <i class="fa fa-refresh"></i>
                </a></center>';*/
            break;

    }
    return;
}
?>
