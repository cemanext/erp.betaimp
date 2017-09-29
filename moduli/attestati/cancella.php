<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

//DEVE FARE UPADETE DI STATO ELIMINATO DEL RECORD AZIENDA
$referer = recupera_referer();

if(isset($_GET['tbl'])){
    switch ($_GET['tbl']) {
        default:
            $ok = cancellaGenerale();
            //$referer = $_POST['txt_referer'];
            header("Location:".$referer."");
        break;
    }
}
?>