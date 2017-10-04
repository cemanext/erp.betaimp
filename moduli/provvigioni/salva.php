<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        case "salvaGeneraleProvvigioni":
            $ok = salvaGenerale();
            
            $idProvvigione = $_POST['txt_id'];
            
            if($idProvvigione > 0){
                $sql_0100 = "UPDATE lista_provvigioni 
                SET lista_provvigioni.nome_prodotto = (SELECT lista_prodotti.nome FROM lista_prodotti WHERE lista_prodotti.id = lista_provvigioni.id_prodotto LIMIT 1),
                lista_provvigioni.prezzo_prodotto = (SELECT lista_prodotti.prezzo_pubblico FROM lista_prodotti WHERE lista_prodotti.id = lista_provvigioni.id_prodotto LIMIT 1) 
                WHERE lista_provvigioni.id = '$idProvvigione'";
                $ok = $ok && $dblink->query($sql_0100);
            }
            
            if(isset($_POST['txt_referer']) && !empty($_POST['txt_referer'])){
                $referer = recupera_referer($_POST['txt_referer']);
            }
            
            if ($ok) {
                header("Location:" . $referer . "&res=1");
            } else {
                header("Location:" . $referer . "&res=0");
            }
        break;
        
        default:
        break;
    }
}


?>