<?php

if(!isset($_SESSION['cognome_nome_utente']) && $_SERVER['REQUEST_URI']!="/login.php" && $_SERVER['REQUEST_URI']!="/login"){
    header("location: ".BASE_URL."/login.php");
    die();
}

if(empty($_SESSION['id_utente'])){
    header("location: ".BASE_URL."/logout.php");
    die();
}else{
    $dblink->update("lista_password",array("data_ultimo_accesso" => date("Y-m-d H:i:s")), array("id" => $_SESSION['id_utente']));
}

if(!controllaPermessi()){
    header("location:".$_SERVER['HTTP_REFERER']);
    die();
}

function controllaPermessi() {
    global $dblink;
    
    if(!empty($_GET['tbl']) && isset($_GET['tbl'])){
        $row = $dblink->get_row("SELECT * FROM lista_permessi WHERE pagina LIKE '".basename($_SERVER['PHP_SELF'])."' AND tabella LIKE '".($_GET['tbl'])."%' AND livello = '".$_SESSION['livello_utente']."'", true);
    }
    if(!empty($row)){
        return $row['permesso'];
    }else{
        return true;
    }
    
}

?>