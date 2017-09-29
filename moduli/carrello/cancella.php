<?php
ob_start();
session_start();
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        case "cancellaCarrelloWeb":
            $valore_del_cookie = $_GET['betaformazione_utente_carrello'];
            $idProdotto = $_GET['idProdotto'];
            
            $sql_0001 = "SELECT id, count(*) as rimanenti FROM lista_ordini WHERE campo_20='".$valore_del_cookie."' AND stato='In Corso'";
            $row_0001 = $dblink->get_row($sql_0001,true);
            $idOrdine = $row_0001['id'];
            $numProdottiDettaglio = $row_0001['rimanenti'];
            
            $ok = $dblink->delete("lista_ordini_dettaglio", array("id_ordine"=>$idOrdine, "id_prodotto"=>$idProdotto), 1);
            
            /*if($numProdottiDettaglio<=1){
                $ok = $dblink->delete("lista_ordini", array("id"=>$idOrdine), 1);
            }*/
            
            if($ok) header('Location:'.WP_DOMAIN_NAME.'/carrello/');
            else echo "error deleteWhere";
        break;
    }
}



?>