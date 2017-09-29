<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
include_once(BASE_ROOT . 'classi/webservice/client.php');

if (DISPLAY_DEBUG) {
    echo '<hr>'.date("H:i:s");
    echo '<li>DB_HOST = '.DB_HOST.'</li>';
    echo '<li>DB_USER = '.DB_USER.'</li>';
    echo '<li>DB_PASS = '.DB_PASS.'</li>';
    echo '<li>DB_NAME = '.DB_NAME.'</li>';
    echo '<li>DB_NAME = '.MOODLE_DB_NAME.'</li>';
    echo '<li>DB_NAME = '.DURATA_CORSO_INGEGNERI.'</li>';
    echo '<li>DB_NAME = '.DURATA_ABBONAMENTO.'</li>';
    echo '<li>DB_NAME = '.DURATA_CORSO.'</li>';
    echo '<hr>';
}
/*
// AGGIORNO ID UTENTE MOODLE
$sql_0005 = "UPDATE lista_iscrizioni, lista_password 
            SET lista_iscrizioni.id_utente_moodle = lista_password.id_moodle_user 
            WHERE lista_password.id_professionista = lista_iscrizioni.id_professionista 
            AND lista_iscrizioni.id_utente_moodle<=0";
$dblink->query($sql_0005);
*/

$sql_004 = "SELECT lista_iscrizioni.id, lista_iscrizioni.id_professionista, lista_iscrizioni.id_utente_moodle, lista_corsi.id_corso_moodle 
FROM lista_iscrizioni  INNER JOIN lista_corsi
        ON lista_corsi.id=lista_iscrizioni.id_corso
        WHERE 1
        AND lista_iscrizioni.stato = 'In Corso'";
$rowsIscrizioni = $dblink->get_results($sql_004);

foreach ($rowsIscrizioni as $rowIscrizione) {

    $sql_000_moodle_001 = "SELECT cs.section, cs.sequence 
        FROM ".MOODLE_DB_NAME.".mdl_course_sections cs 
        WHERE cs.course = ".$rowIscrizione['id_corso_moodle']."
        AND cs.sequence != '' 
        ORDER BY cs.section DESC LIMIT 1";

    $row_000_moodle_001 = $dblink->get_row($sql_000_moodle_001, true);
    if(!empty($row_000_moodle_001)){
        $cmsequence = $row_000_moodle_001['sequence'];
        //in PHP, parse the comma separated list of cm ids and get the last cm id within the section
        $cmarray = explode(',', $cmsequence);
        $lastcmid = array_slice($cmarray, -1)[0];

        $sql_001_moodle_001 = "SELECT cm.id, cm.timemodified 
            FROM ".MOODLE_DB_NAME.".mdl_course_modules_completion cm 
            WHERE cm.coursemoduleid = $lastcmid 
            AND cm.userid = ".$rowIscrizione['id_utente_moodle']."
            AND cm.completionstate = 1";

        $rowCompleto = $dblink->get_row($sql_001_moodle_001, true);

        if($dblink->num_rows($sql_001_moodle_001)){
            $updateIscrizione = array(
                "dataagg" => date("Y-m-d H:i:s"),
                "scrittore"=>$dblink->filter("autoCorsiCompletati"),
                "stato" => "Completato",
                "data_completamento" => date("Y-m-d H:i:s", $rowCompleto['timemodified']),
                //"data_fine" => date("Y-m-d H:i:s", $rowCompleto['timemodified']),
                "stato_completamento" => "Completato"
            );

            $ok = $dblink->update("lista_iscrizioni", $updateIscrizione, array("id"=>$rowIscrizione['id']));
            //CORSO COMPLETATO
             if($ok){
                if (DISPLAY_DEBUG) echo '<li style="color: GREEN;"> OK !</li>';
                $log->log_all_errors('autoCorsiCompletati.php -> corso  completato correttamente [id_corso_moodle = '.$rowIscrizione['id_corso_moodle'].']','OK');
            }else{
                if (DISPLAY_DEBUG) echo '<li style="color: RED;"> KO !</li>';
                $log->log_all_errors('autoCorsiCompletati.php -> corso NON completato [id_corso_moodle = '.$rowIscrizione['id_corso_moodle'].']','ERRORE');
            }
        }
    }
}

if (DISPLAY_DEBUG) echo '<hr>'.date("H:i:s");
?>