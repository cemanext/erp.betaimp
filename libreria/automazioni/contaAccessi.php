<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

/*if(isset($_GET) && $_SERVER['REMOTE_ADDR']!="46.28.0.81" && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "wordpress")===false){
    
    print_r($_GET);
    
    $urlReferer = $_GET['url'];
    $idCampagna = $_GET['id'];

    $row_0001 = $dblink->get_row("SELECT id_tipo_marketing, id_prodotto, nome FROM lista_campagne WHERE id = $idCampagna ORDER BY id ASC",true);
    //echo $dblink->get_query();
    $insert = array(
        "dataagg" => date("Y-m-d H:i:s"),
        "scrittore" => "autoContaAccessi",
        "REMOTE_ADDR" => $_SERVER['REMOTE_ADDR'],
        "HTTP_REFERER" => $urlReferer,
        "PHP_SELF" => $_SERVER['PHP_SELF'],
        "HTTP_USER_AGENT" => $_SERVER['HTTP_USER_AGENT'],
        "HTTP_ACCEPT_LANGUAGE" => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        "REMOTE_PORT" => $_SERVER['REMOTE_PORT'],
        "id_campagna" => $idCampagna,
        "id_prodotto" => $row_0001['id_prodotto'],
        "id_tipo_marketing" => $row_0001['id_tipo_marketing']
    );
    
    $dblink->insert("lista_accessi", $insert);
}*/

?>
