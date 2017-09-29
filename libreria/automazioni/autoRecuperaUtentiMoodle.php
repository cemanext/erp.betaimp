<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
include_once(BASE_ROOT . 'classi/webservice/client.php');

$moodle = new moodleWebService();

if (DISPLAY_DEBUG) {
    echo "START: ".date("H:i:s");
    echo "<br>";
}

$referer = recupera_referer();
//DELETE FROM `lista_password` WHERE id > 190 AND `id_moodle_user` = 0
$rows = $dblink->get_results("SELECT * FROM utenti_moodle_ultimo_anno WHERE id NOT IN (SELECT DISTINCT id_moodle_user FROM lista_password) AND id > 34644 LIMIT 10000");

$countOK = 0;
$countKO = 0;
foreach ($rows as $row) {
    //Verifico Se e siste su moodle
    $res = $moodle->get_user_by_field("email",$row['email']);
    //echo "<pre>";
    //print_r($res);
    //echo "</pre><br>";
    foreach ($res as $user) {
        
        $userUp = array(
            "data_ultimo_accesso" => date("Y-m-d H:i:s", $user->lastaccess),
            "email" => $user->email,
            "nome" => $dblink->filter($user->firstname),
            "cognome" => $dblink->filter($user->lastname),
            "livello" => "cliente",
            "stato" => "Attivo",
            "avatar" => $user->profileimageurlsmall,
        );
        
        if($row['id_moodle_user']==0){ $userUp['id_moodle_user'] = $user->id; }
        $userUp['data_creazione'] = date("Y-m-d H:i:s", $user->firstaccess);
        
        $rowEmail = $dblink->get_row("SELECT id FROM lista_professionisti WHERE email LIKE '$user->email'",true);
        
        if($row['id_professionista']==0){ $userUp['id_professionista'] = $rowEmail['id']; }
        
        foreach ($user->customfields as $userDetail) {
            if (DISPLAY_DEBUG) {
                echo "<pre>";
                print_r($userDetail);
                echo "</pre><br>";
            }
            switch ($userDetail->shortname) {
                case "subscriptionexpiry":
                    $tmpData = explode("/",$userDetail->value);
                    $dato1 = intval(mktime(23,59,59,$tmpData[1],$tmpData[0],$tmpData[2]));
                    $dato2 = intval(time());
                    if($dato2 > $dato1){
                        $userUp['id_moodle_user'] = 0;
                    }
                    $userUp['data_scadenza'] = $tmpData[2]."-".$tmpData[1]."-".$tmpData[0]." 23:59:59";
                break;
            
                case "Cohort":
                    if($row['id_classe']==0){
                        $tmpClasse = $userDetail->value;
                        $rowClasse = $dblink->get_row("SELECT id FROM lista_classi WHERE nome LIKE '$tmpClasse'",true);
                        $userUp['id_classe'] = $rowClasse['id'];
                    }
                break;

                default:
                break;
            }
        }
        
        if($userUp['id_moodle_user']>0){
            if (DISPLAY_DEBUG) {
                echo "<pre>";
                print_r($userUp);
                echo "</pre><br>";
                //die;
            }
            $ok = $dblink->insert("lista_password", $userUp);
            if($ok) $countOK ++;
            else $countKO ++;
            
            ob_flush();
        }
        if (DISPLAY_DEBUG) {
            echo $dblink->get_query();
            echo "<br />";
        }
    }
}

if (DISPLAY_DEBUG) {
    echo "OK: $countOK<br />";
    echo "ERROR: $countKO<br />";
    echo "END".date("H:i:s");
}
?>