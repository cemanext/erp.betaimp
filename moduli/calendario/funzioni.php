<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Calendario(){
    global $table_calendario, $where_calendario;
    
    $tabella = 'calendario';
    switch($tabella){
        case 'calendario':
            if(isset($_GET['whrStato'])){
                $tabella = "calendario";
                switch ($_GET['whrStato']) {
                    case MD5('In Attesa di Controllo'):
                        $campi_visualizzati = "id AS 'selezione', CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>') AS 'fa-book',
                                        CONCAT('<a class=\"btn btn-circle btn-icon-only purple-seance btn-outline\" onclick=\"associaCommercialeARichesta(',id,',',id_professionista,');\" title=\"ASSOCIA COMMERCIALE\" alt=\"ASSOCIA COMMERCIALE\"><i class=\"fa fa-sign-in\"></i></a>') AS 'Ass. Comm.',
                                        'fa-user', 'Messaggio', 
                                        'Marketing', 'Data Inserimento', 'Ora Inserimento',
                                        CONCAT('<a class=\"btn btn-circle btn-icon-only red btn-outline\" href=\"cancella.php?tbl=calendario&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><i class=\"fa fa-trash\"></i></a>') AS 'fa-trash'";

                        /*
                        $campi_visualizzati = "datainsert AS 'Data', orainsert AS 'Ora', oggetto AS 'Origine / Tipo marketing', mittente AS 'Nominativo', campo_4 AS 'Tel.', campo_5 AS 'Email', 
                        CONCAT('<span class=\"bg-green-jungle  bg-font-green-jungle font-lg sbold\">Si o No</div>') AS 'Cliente',
                        CONCAT('<span class=\"label label-sm label-warning\">',stato,'</div>') AS 'Stato'";
                        */
                        //$where = $table_calendario['index']['where'];
                        $where = " MD5(stato)='".$_GET['whrStato']."' $where_calendario";
                        //$ordine = $table_calendario['index']['order'];
                        $ordine = " ORDER BY datainsert DESC, orainsert ASC";
                        $limite = "LIMIT 1";
                        $titolo = 'Elenco Richieste in Attesa di Controllo';
                        $stile = "datatable_ajax";
                        $colore = "yellow-saffron";
                        $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";
                    break;
                
                    case MD5('Mai Contattato'):
                    case MD5('Richiamare'):
                        if($_SESSION['livello_utente']=="commerciale"){
                            $campi_visualizzati = "'fa-book', IF(id_agente='".$_SESSION['id_utente']."',
                                            CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>'),
                                            CONCAT('<a class=\"btn btn-circle btn-icon-only purple-seance btn-outline\" onclick=\"associaCommercialeARichesta(',id,',',id_professionista,');\" title=\"PRENDI IN CARICO\" alt=\"PRENDI IN CARICO\"><i class=\"fa fa-sign-in\"></i></a>')) AS 'Ass. Comm.',
                                            (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale', 
                                            stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Richiamo', ora AS 'Ora Richiamo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                        }else{
                            $campi_visualizzati = "CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>') AS 'fa-book',
                                            (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale', 
                                            stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Richiamo', ora AS 'Ora Richiamo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                        }
                        //$where = $table_calendario['index']['where'];
                        $where = " MD5(stato)='".$_GET['whrStato']."' $where_calendario";
                        //$ordine = $table_calendario['index']['order'];
                        $ordine = " ORDER BY datainsert DESC, orainsert ASC";
                        $limite = "LIMIT 1";
                        $titolo = ($_GET['whrStato']==MD5('Richiamare')) ? "Elenco Richieste Da Richiamare" : 'Elenco Richieste Mai Contattati';
                        $stile = "datatable_ajax";
                        $colore = ($_GET['whrStato']==MD5('Richiamare')) ? "green-jungle" : "red-flamingo";
                        $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";
                    break;
                    
                    case MD5('Venduto'):
                        if($_SESSION['livello_utente']=="commerciale"){
                            $campi_visualizzati = "'fa-book', IF(id_agente='".$_SESSION['id_utente']."',
                                            CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>'),
                                            CONCAT('<a class=\"btn btn-circle btn-icon-only purple-seance btn-outline\" onclick=\"associaCommercialeARichesta(',id,',',id_professionista,');\" title=\"PRENDI IN CARICO\" alt=\"PRENDI IN CARICO\"><i class=\"fa fa-sign-in\"></i></a>')) AS 'Ass. Comm.',
                                            (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale', 
                                            stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Iscritto', 'Importo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                        }else{
                            $campi_visualizzati = "CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>') AS 'fa-book',
                                            (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale', 
                                            stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Iscritto', 'Importo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                        }
                        //$where = $table_calendario['index']['where'];
                        $where = " MD5(stato)='".$_GET['whrStato']."' $where_calendario";
                        //$ordine = $table_calendario['index']['order'];
                        $ordine = " ORDER BY datainsert DESC, orainsert ASC";
                        $limite = "LIMIT 1";
                        $titolo = ($_GET['whrStato']==MD5('Venduto')) ? "Elenco Richieste Vendute" : 'Elenco Richieste Negative';
                        $stile = "datatable_ajax";
                        $colore = ($_GET['whrStato']==MD5('Venduto')) ? "blue-steel" : "red-intense";
                        $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";
                    break;
                    case MD5('Negativo'):
                        if($_SESSION['livello_utente']=="commerciale"){
                            $campi_visualizzati = "'fa-book', IF(id_agente='".$_SESSION['id_utente']."',
                                            CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>'),
                                            CONCAT('<a class=\"btn btn-circle btn-icon-only purple-seance btn-outline\" onclick=\"associaCommercialeARichesta(',id,',',id_professionista,');\" title=\"PRENDI IN CARICO\" alt=\"PRENDI IN CARICO\"><i class=\"fa fa-sign-in\"></i></a>')) AS 'Ass. Comm.',
                                            (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale', 
                                            stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Negativo', 'Importo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                        }else{
                            $campi_visualizzati = "CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>') AS 'fa-book',
                                            (SELECT CONCAT(lista_password.nome,' ',lista_password.cognome) FROM lista_password WHERE lista_password.id=calendario.id_agente) AS 'Commerciale', 
                                            stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Negativo', 'Importo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                        }
                        //$where = $table_calendario['index']['where'];
                        $where = " MD5(stato)='".$_GET['whrStato']."' $where_calendario";
                        //$ordine = $table_calendario['index']['order'];
                        $ordine = " ORDER BY datainsert DESC, orainsert ASC";
                        $limite = "LIMIT 1";
                        $titolo = ($_GET['whrStato']==MD5('Venduto')) ? "Elenco Richieste Vendute" : 'Elenco Richieste Negative';
                        $stile = "datatable_ajax";
                        $colore = ($_GET['whrStato']==MD5('Venduto')) ? "blue-steel" : "red-intense";
                        $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";
                    break;

                    case MD5('Chiusa In Attesa di Controllo'):
                        $campi_visualizzati = "id AS 'selezione', CONCAT('<a class=\"btn btn-circle btn-icon-only green btn-outline\" href=\"".BASE_URL."/moduli/anagrafiche/dettaglio_tab.php?tbl=calendario&id=',id,'\" title=\"SCHEDA\" alt=\"SCHEDA\"><i class=\"fa fa-book\"></i></a>') AS 'fa-book',
                                        'Commerciale',
                                        'fa-user', 'Messaggio', 
                                        'fa-check', 'Data Inserimento', 'Ora Inserimento'";
                        //$where = $table_calendario['index']['where'];
                        $where = " MD5(stato)='".$_GET['whrStato']."' $where_calendario";
                        //$ordine = $table_calendario['index']['order'];
                        $ordine = " ORDER BY datainsert DESC, orainsert ASC";
                        $limite = "LIMIT 1";
                        $titolo = 'Elenco Richieste Chiuse in Attesa di Controllo';
                        $stile = "datatable_ajax";
                        $colore = "yellow-soft";
                        $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";
                    break;
                
                    default:
                        if($_SESSION['livello_utente']=="commerciale"){
                            $campi_visualizzati = "'fa-book',
                                            'Commerciale', stato, 'fa-user', mittente AS 'Mittente',
                                            'Professionista', 
                                            (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id=calendario.id_prodotto) AS 'Prodotto', data AS 'Data Richiamo', ora AS 'Ora Richiamo', 
                                            (SELECT nome FROM lista_tipo_marketing WHERE id = id_tipo_marketing) AS Marketing";
                            $where = " 1 ";
                        }else{
                            $campi_visualizzati = $table_calendario['index']['campi'];
                            $where = $table_calendario['index']['where'];
                        }
                        $ordine = $table_calendario['index']['order'];
                        $limite = "LIMIT 1";
                        $titolo = 'Elenco Richieste';
                        $stile = "datatable_ajax";
                        $colore = COLORE_PRIMARIO;
                        $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine $limite";
                    break;
                }
            }
            
            stampa_table_datatables_ajax($sql_0001, "datatable_ajax", $titolo, $stile, $colore, false);
            
            //stampa_table_datatables_responsive($sql_0001, $titolo, $stile, $colore);
        break;
		/*
        default:
            $campi_visualizzati = "";
            $campi     = 	$dblink->list_fields("SELECT * FROM ".$tabella."");
            foreach ($campi as $nome_colonna) {
                 $campi_visualizzati.= "`".$nome_colonna->name."`, ";
            }
            
            $where = " 1 ";
            $ordine = " ORDER BY id DESC";
            $titolo = "Elenco ".$tabella;
            $stile = "tabella_base";
            $colore_tabella = COLORE_PRIMARIO;
            $sql_0001 = "SELECT 
            CONCAT('<a href=\"dettaglio.php?tbl=".$tabella."&id=',id,'\" title=\"DETTAGLIO\" alt=\"DETTAGLIO\"><button type=\"button\" class=\"btn yellow btn-warning mt-ladda-btn ladda-button btn-circle btn-icon-only\"><i class=\"fa fa-search\"></i></button></a>') AS '.:',
            CONCAT('<a href=\"modifica.php?tbl=".$tabella."&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><button type=\"button\" class=\"btn blue btn-warning mt-ladda-btn ladda-button btn-circle btn-icon-only\"><i class=\"fa fa-edit\"></i></button></a>') AS '::',
            CONCAT('<a href=\"duplica.php?tbl=".$tabella."&id=',id,'\" title=\"DUPLICA\" alt=\"DUPLICA\"><button type=\"button\" class=\"btn green btn-warning mt-ladda-btn ladda-button btn-circle btn-icon-only\"><i class=\"fa fa-copy\"></i></button></a>') AS '::',
            ".$campi_visualizzati.",
            CONCAT('<a href=\"cancella.php?tbl=".$tabella."&id=',id,'\" title=\"ELIMINA\" alt=\"ELIMINA\"><button type=\"button\" class=\"btn red btn-warning mt-ladda-btn ladda-button btn-circle btn-icon-only\"><i class=\"fa fa-trash\"></i></button></a>') AS ':. ,'
            FROM ".$tabella." WHERE $where $ordine";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            stampa_table_datatables_responsive($sql_0001, $titolo, $stile, $colore_tabella);
        break;
		*/
    }
}
?>
