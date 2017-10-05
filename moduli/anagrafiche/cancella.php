<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

//DEVE FARE UPADETE DI STATO ELIMINATO DEL RECORD AZIENDA

$ok = cancellaGenerale();

if($_GET['tbl']=="lista_preventivi_dettaglio"){
    $idPreventivo = $_GET['idPreventivo'];
                
    $sql_0001 = "SELECT SUM((prezzo_prodotto*quantita)) AS imponibile, SUM((prezzo_prodotto*(1+(iva_prodotto/100)))*quantita) AS 'importo' FROM lista_preventivi_dettaglio WHERE id_preventivo=".$idPreventivo;
    $row_0001 = $dblink->get_row($sql_0001, true);
    //echo $dblink->get_query();
    //echo "<br>";
    $updatePreventivo=array(
        "dataagg" => date("Y-m-d H:i:s"),
        "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
        "importo"=>$row_0001['importo'],
        "imponibile"=>$row_0001['imponibile']
    );

    $ok = $ok && $dblink->update("lista_preventivi", $updatePreventivo, array("id"=>$idPreventivo));
    
    if($ok) header("Location:".recupera_referer());
       else echo "error deleteWhere";
    
}

if($ok) header("Location:".recupera_referer());
else echo "error deleteWhere";

?>