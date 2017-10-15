<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

//DEVE FARE UPADETE DI STATO ELIMINATO DEL RECORD AZIENDA
$referer = recupera_referer();

if(isset($_GET['tbl'])){
    switch ($_GET['tbl']) {
        
        case "calendario_esami":
        $idCalendario = $_GET['idCalendario'];
        $idCalendarioCorso = $_GET['idCalendarioCorso'];
            //cancellaGenerale();
            $sql_0002 = "DELETE FROM calendario WHERE id=".$idCalendario;
            $rs_0002 = $dblink->query($sql_0002);
            if ($rs_0002) {
                $sql_001 = "SELECT COUNT(*) AS conteggio FROM calendario WHERE id_calendario_0='".$idCalendarioCorso."' AND etichetta='Iscrizione Esame'";
                $numero_iscritti_al_corso = $dblink->get_field($sql_001);
             
                $sql_002 = "UPDATE calendario SET numerico_10 = '".$numero_iscritti_al_corso."' WHERE id='".$idCalendarioCorso."' AND etichetta LIKE 'Calendario Esami'";
                $rs_002 = $dblink->query($sql_002);
                
                header("Location:".$referer."");
            }
            
        break;
        
        case "calendario_corsi":
        $idCalendario = $_GET['idCalendario'];
        $idCalendarioCorso = $_GET['idCalendarioCorso'];
            //cancellaGenerale();
            $sql_0002 = "DELETE FROM calendario WHERE id=".$idCalendario;
            $rs_0002 = $dblink->query($sql_0002);
            if ($rs_0002) {
                $sql_001 = "SELECT COUNT(*) AS conteggio FROM calendario WHERE id_calendario_0='".$idCalendarioCorso."' AND etichetta='Iscrizione Corso'";
                $numero_iscritti_al_corso = $dblink->get_field($sql_001);
             
                $sql_002 = "UPDATE calendario SET numerico_10 = '".$numero_iscritti_al_corso."' WHERE id='".$idCalendarioCorso."' AND etichetta LIKE 'Calendario Corsi'";
                $rs_002 = $dblink->query($sql_002);
                
                header("Location:".$referer."");
            }
            
        break;
        
        case "lista_attestati":
            
            if(isset($_GET['id'])){
                $fileUnlink = $dblink->get_field("SELECT nome FROM lista_attestati WHERE id='".$_GET['id']."'");

                unlink($fileUnlink);
            }
            
            $ok = cancellaGenerale();
            //$referer = $_POST['txt_referer'];
            header("Location:".$referer."");
        break;

        default:
            $ok = cancellaGenerale();
            //$referer = $_POST['txt_referer'];
            header("Location:".$referer."");
        break;
    }
}
?>