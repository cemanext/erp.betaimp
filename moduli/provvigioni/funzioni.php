<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Provvigioni($tabella){
    global $dblink, $table_listaProvvigioni;

    switch($tabella){
        
        case "lista_provvigioni":
            $tabella = "lista_provvigioni";
            $where = $table_listaProvvigioni['index']['where'];
            $campi_visualizzati = $table_listaProvvigioni['index']['campi'];
            $titolo = 'Elenco Partener';
            $colore = COLORE_PRIMARIO;
            $ordine = $table_listaProvvigioni['index']['order'];
            $sql_0001 = "SELECT " . $campi_visualizzati . " FROM " . $tabella . " WHERE $where $ordine LIMIT 1";
            stampa_table_datatables_ajax($sql_0001, '#datatable_ajax', $titolo, 'datatable_ajax', $colore);
        break;

        default:
            
            $campi_visualizzati = "";
            $campi     =    $dblink->list_fields("SELECT * FROM ".$tabella."");
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
