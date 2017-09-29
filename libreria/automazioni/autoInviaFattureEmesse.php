<?php
ini_set('max_execution_time', 290); //5 minuti - 10 secondi
ini_set('memory_limit', '2048M'); // 2 Giga

include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
include_once(BASE_ROOT . 'classi/webservice/client.php');

if (DISPLAY_DEBUG) {
    echo '<li>'.date('Y-m-d H:i:s').'</li>';
    echo '<li>DB_HOST = '.DB_HOST.'</li>';
    echo '<li>DB_USER = '.DB_USER.'</li>';
    echo '<li>DB_PASS = '.DB_PASS.'</li>';
    echo '<li>DB_NAME = '.DB_NAME.'</li>';
    echo '<hr>';
}


$sql_aggiorna_sezionale_pa = "UPDATE lista_fatture SET stato_invio = '' 
WHERE stato_invio LIKE 'In Attesa di Invio' AND sezionale='PA'";
$ok = $dblink->query($sql_aggiorna_sezionale_pa);

    $sql_lista_fatture_invia = "SELECT * FROM lista_fatture WHERE stato_invio LIKE 'In Attesa di Invio' LIMIT 20";
    $rs_lista_fatture_invia = $dblink->get_results($sql_lista_fatture_invia);
    foreach ($rs_lista_fatture_invia AS $row_lista_fatture_invia){
        
        $idFattura = $row_lista_fatture_invia['id'];
        
        if (DISPLAY_DEBUG) echo '<li>creaFatturaPDF SEZIONALE = '.$row_lista_fatture_invia['codice_ricerca'].'</li>';
        
        creaFatturaPDF($idFattura, false);
        
        if (DISPLAY_DEBUG) echo '<li>$idFattura = '.$idFattura.'</li>';
        $ret = inviaEmailFatturaDaId($idFattura,false);
        if (DISPLAY_DEBUG) echo '<li>$ret = '.$ret.'</li>';
        
        if($ret){
            $sql_00002 = "UPDATE lista_fatture 
            SET stato_invio = 'Inviata', 
            dataagg = NOW(),
            data_invio = NOW()
            WHERE stato_invio LIKE 'In Attesa di Invio'
            AND id=".$idFattura;
            $ok = $dblink->query($sql_00002);
            if($ok){
                if (DISPLAY_DEBUG) echo '<li style="color:green;">idFattura = '.$idFattura.' Inviata !</li>';
            }else{
                if (DISPLAY_DEBUG) echo '<li style="color:red;">idFattura = '.$idFattura.' NON Inviata e NON Aggiornata !</li>';
            }
        }
        if (DISPLAY_DEBUG) echo '<hr>';
        
        sleep(1);
    }

if (DISPLAY_DEBUG) echo '<br>'.date("H:i:s");
?>
