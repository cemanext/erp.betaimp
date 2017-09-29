<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

$referer = recupera_referer();

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

if(isset($_GET['fn'])){
    $funzioneSel = $_GET['fn'];
}else{
    $funzioneSel = "";
}

switch ($funzioneSel) {
    case "cancellaPreventivoBase":
        $ok = true;
        $dblink->begin();
        $idPreventivo = $_GET['idDelPrev'];
        
        $ok = $ok && $dblink->delete("lista_preventivi_dettaglio", array("id_preventivo"=>$idPreventivo), 1);
        $ok = $ok && $dblink->delete("lista_preventivi", array("id"=>$idPreventivo), 1);
        
        if($ok){
            $dblink->commit();
            header("Location:".BASE_URL."/moduli/preventivi/index.php?tbl=lista_preventivi&ret=1");
        }else{
            $dblink->rollback();
            header("Location:".$referer."&ret=0");
        }
    break;
    case "cancellaPreventivo":
        $ok = true;
        $dblink->begin();
        $idPreventivo = $_GET['idDelPrev'];
        
        list($idFattura) = $dblink->get_row("SELECT id FROM lista_fatture WHERE id_preventivo = '$idPreventivo'");
        list($idCalendario) = $dblink->get_row("SELECT id_calendario FROM lista_preventivi WHERE id = '$idPreventivo'");
        
        $ok = $ok && $dblink->delete("calendario", array("id"=>$idCalendario), 1);
        $ok = $ok && $dblink->delete("calendario", array("id_preventivo"=>$idPreventivo), 1);
        $ok = $ok && $dblink->delete("lista_fatture_dettaglio", array("id_fattura"=>$idFattura), 1);
        $ok = $ok && $dblink->delete("lista_fatture", array("id"=>$idFattura), 1);
        $ok = $ok && $dblink->delete("lista_preventivi_dettaglio", array("id_preventivo"=>$idPreventivo), 1);
        $ok = $ok && $dblink->delete("lista_preventivi", array("id"=>$idPreventivo), 1);
        
        if($ok){
            $dblink->commit();
            header("Location:".BASE_URL."/moduli/preventivi/index.php?tbl=lista_preventivi&ret=1");
        }else{
            $dblink->rollback();
            header("Location:".$referer."&ret=0");
        }
    break;

    default:
        if($tabella!=""){
            $ok = $dblink->deleteWhere($tabella, $where, 1);
        }else{
            $ok = true;
        }
        if($ok) header("Location:".$referer."");
        else echo "error deleteWhere";
    break;
}




?>