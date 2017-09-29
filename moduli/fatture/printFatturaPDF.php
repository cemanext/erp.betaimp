<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
if ($browser == true){
	//echo 'Code You Want To Execute';
}

if(isset($_GET['idA'])){
    $id_area = $_GET['idA'];
}else{
    $id_area = 0;
}

creaFatturaPDF($_GET['idFatt'], true);

?>
