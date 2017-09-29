<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');;

$ok = cancellaGenerale();

if($ok) header("Location:".$_SERVER['HTTP_REFERER']."");
       else echo "error deleteWhere";

?>