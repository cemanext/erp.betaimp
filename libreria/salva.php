<?php
include_once('../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

if(isset($_POST['txt_id'])){
    $nome_id = $_POST['txt_id'];
}else{
    $nome_id = 0;
}

$nome_referer = recupera_referer();

$ok = salvaGenerale();

if($nome_id!=0){
    //$ok = $dblink->updateWhere($nome_tabella, $tuttiCampi, $nome_where);
     if($ok) header("Location:".$nome_referer."");
            else echo "error updateWhere";
}else{
    //$ok = $dblink->insert($nome_tabella, $tuttiCampi);
       if($ok) header("Location:".$nome_referer."");
            else echo "error insert";
}
?>