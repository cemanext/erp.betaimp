<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        
        default:
            salvaGenerale();
            
            $referer = $_POST['txt_referer'];
            header("Location:".$referer."");
        break;
    }
}


?>