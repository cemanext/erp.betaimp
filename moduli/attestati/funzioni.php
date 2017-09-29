<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Attestati($tabella){
    global $table_listaAttestati, $table_listaCorsi, $table_listaClassi, $table_calendarioEsami, $table_documentiAttestati;
    
    switch($tabella){

        case 'lista_attestati':
            $tabella = "lista_attestati";
            $campi_visualizzati = $table_listaAttestati['index']['campi'];
            $where = $table_listaAttestati['index']['where'];
            $ordine = $table_listaAttestati['index']['order'];
            $titolo = 'Elenco Attestati';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
        break;
        

    }
}

function Stampa_HTML_Dettaglio_Attestati($tabella,$id){
    global $table_listaAttestati, $dblink;
    
    switch ($tabella) {
        case 'calendario_esami':
        $idAttestato = $_GET['idAttestato'];
        
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

    }
    return;
}
?>
