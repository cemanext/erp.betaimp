<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

//RECUPERO LA TABELLA
if(isset($_GET['tbl'])){
    $tabella = $_GET['tbl'];
}else{
    $tabella = "";
}

//RECUPERO L'ID
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $where = "id='".$_GET['id']."'";
}else{
    $id = '';
    $where = "1";
}
if($tabella!=""){
    $ok = $dblink->deleteWhere($tabella, $where, 1);
}else{
    $ok = true;
}
if($ok) header("Location:".recupera_referer()."");
       else echo "error deleteWhere";

?>