<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        case "#":
            $ok = true;
            $dblink->begin();
            $sql_inserisci_prodotto_dettaglio_abbonamento = "INSERT INTO lista_prodotti_dettaglio (id, dataagg, scrittore, id_prodotto_0, stato, gruppo, ordine) VALUES ('', NOW(), '".$_SESSION['cognome_nome_utente']."','".$_GET['idAbbonamento']."','Non Attivo', 'CORSO', '1000')";
            $ok = $dblink->query($sql_inserisci_prodotto_dettaglio_abbonamento);
            $lastId=$dblink->insert_id();
            if($ok){
                $ok = 1;
                $dblink->commit();
            }else{
                $ok = 0;
                $dblink->rollback();
            }
            header("Location:".$referer."");
        break;
        
        case "associaCommerciale":
            $ok = true;
            $dblink->begin();
            $ArrayIdCalendario = strlen($_POST['idCal'])>0 ? explode(":",$_POST['idCal']) : 0;
            $id_agente = strlen($_POST['id_commerciale'])>0 ? $_POST['id_commerciale'] : 0;
            $id_agente_old = strlen($_POST['idAgenteOld'])>0 ? $_POST['idAgenteOld'] : 0;
            
            if(is_array($ArrayIdCalendario) && $id_agente>0 && $id_agente_old>0){
            
                foreach($ArrayIdCalendario as $id_calendario) {
                    $row_0002 = $dblink->get_row("SELECT id as id_agente, CONCAT(cognome,' ', nome) as destinatario FROM lista_password WHERE id='".$id_agente_old."'", true);

                    $ok = $ok && $dblink->duplicateWhere("calendario", "id='$id_calendario'", 1);
                    //echo $dblink->get_query();
                    //echo "<br />";
                    $insetIdCalendario = $dblink->lastid();

                    $updateCal = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                        "etichetta" => "Nuova Richiesta Trasferita",
                        "stato" => "Trasferito",
                        "messaggio" => "CONCAT('Presa in carico da: <b>".$dblink->filter(getNomeAgente($id_agente))."</b>\\nAgente precedente: <b><i>".$dblink->filter($row_0002['destinatario'])."</i></b>\\n\\nMESSAGGIO ORIGINALE:\\n', messaggio)"
                    );

                    $ok = $ok && $dblink->update("calendario", $updateCal, array("id"=>$id_calendario));
                    //echo $dblink->get_query();
                    //echo "<br />";

                    $row_0001 = $dblink->get_row("SELECT id as id_agente, CONCAT(cognome,' ', nome) as destinatario FROM lista_password WHERE id='".$id_agente."'", true);
                    $row_0001["dataagg"] = date("Y-m-d H:i:s");
                    $row_0001["scrittore"] = $dblink->filter($_SESSION['cognome_nome_utente']);

                    $ok = $ok && $dblink->update("calendario", $row_0001, array("id"=>$insetIdCalendario));
                    
                    $ok = $ok && $dblink->update("lista_preventivi", array("id_calendario"=>$insetIdCalendario), array("id_calendario"=>$id_calendario));
                    $ok = $ok && $dblink->update("lista_preventivi_dettaglio", array("id_calendario"=>$insetIdCalendario), array("id_calendario"=>$id_calendario));
                    //echo $dblink->get_query();
                    //echo "<br />";
                }
                if($ok){
                    $dblink->commit();
                    echo "OK:OK";
                }else{
                    $dblink->rollback();
                    echo "KO:KO";
                }
            }else{
                $dblink->rollback();
                echo "KO2:KO2";
            }
        break;
    }
}
?>