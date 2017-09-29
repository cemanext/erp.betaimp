<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Docenti($tabella){
    global $table_listaDocenti, $dblink;

    switch($tabella){        
        case 'lista_docenti':
            $tabella = "lista_docenti";
            $campi_visualizzati = $table_listaDocenti['index']['campi'];
            $where = $table_listaDocenti['index']['where']. "";
            $ordine = $table_listaDocenti['index']['order'];
            $titolo = 'Elenco Docenti';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine LIMIT 1";
            $sql_0001_1 = "SELECT COUNT(id) AS conto FROM ".$tabella." WHERE $where $ordine";
            $conto = $dblink->get_field($sql_0001_1);
            stampa_table_datatables_ajax($sql_0001, "datatable_ajax", $titolo.' ['.$conto.' Record]', '', '', false);
            //stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
        break;
    }
}

function Stampa_HTML_Dettaglio_Docenti($tabella, $id) {
      switch ($tabella) {
        case 'lista_docenti':
            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=lista_docenti&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',
            CONCAT('<b>',`cognome`,' ',`nome`,'</b>') AS 'Docente', codice_fiscale, cellulare, telefono, email, stato
            FROM lista_docenti
            WHERE id='".$id."'";
            stampa_table_static_basic($sql_0001, '', 'Docente', '');
            echo '</div></div>';
            //CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=lista_iscrizioni&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>') AS 'fa-edit',

            echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT id, data
            FROM calendario
            WHERE id_docente = '".$id."' ORDER BY id DESC";
            stampa_table_static_basic($sql_0001, '', 'Esami', '');
            echo '</div></div>';
        break;
    }
}
?>
