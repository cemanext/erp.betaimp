<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        
        case "#########":
            $ok = true;
            $dblink->begin();
            $idCorso = $_GET['idCorso'];
            $sql_aggiungiConfigurazioneCorso = "INSERT INTO `lista_corsi_configurazioni` (`id`, `dataagg`, `scrittore`, `stato`, `id_corso`, `id_prodotto`) SELECT '', NOW(), '".addslashes($_SESSION['cognome_nome_utente'])."', 'Non Attivo', id, id_prodotto FROM lista_corsi WHERE id='".$idCorso."'";
            $ok = $ok && $dblink->query($sql_aggiungiConfigurazioneCorso);
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
    }
}

?>