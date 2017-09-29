<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
include_once(BASE_ROOT . 'classi/webservice/client.php');

if (DISPLAY_DEBUG) {
    echo date("H:i:s");

    echo '<h1>autoInvaPasswordUtenti</h1>';
    echo '<li>DB_HOST = ' . DB_HOST . '</li>';
    echo '<li>DB_USER = ' . DB_USER . '</li>';
    echo '<li>DB_PASS = ' . DB_PASS . '</li>';
    echo '<li>DB_NAME = ' . DB_NAME . '</li>';
    echo '<li>DB_NAME = ' . MOODLE_DB_NAME . '</li>';
    echo '<li>DB_NAME = ' . DURATA_CORSO_INGEGNERI . '</li>';
    echo '<li>DB_NAME = ' . DURATA_ABBONAMENTO . '</li>';
    echo '<li>DB_NAME = ' . DURATA_CORSO . '</li>';
    echo '<hr>';
}


$sql_00001 = "CREATE TEMPORARY TABLE moduliCompletati(SELECT 
`mdl_course_modules_completion`.`userid` AS 'id_utente_moodle',
FROM_UNIXTIME(`mdl_course_modules_completion`.`timemodified`) AS 'data_ora_modulo_completato',
DATE(FROM_UNIXTIME(`mdl_course_modules_completion`.`timemodified`)) AS 'data_modulo_completato',
`mdl_course_modules`.`course` AS 'id_corso_moodle' 
FROM `".MOODLE_DB_NAME."`.`mdl_course_modules_completion` INNER JOIN `".MOODLE_DB_NAME."`.`mdl_course_modules`
ON `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`coursemoduleid` = `".MOODLE_DB_NAME."`.`mdl_course_modules`.`id`
WHERE DATE(FROM_UNIXTIME(`timemodified`))='2017-07-06'
AND  `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`coursemoduleid` = `".MOODLE_DB_NAME."`.`mdl_course_modules`.`id`
AND `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`completionstate`=1)";

$sql_00001 = "CREATE TEMPORARY TABLE moduliCompletati(SELECT 
`mdl_course_modules_completion`.`userid` AS 'id_utente_moodle',
FROM_UNIXTIME(`mdl_course_modules_completion`.`timemodified`) AS 'data_ora_modulo_completato',
DATE(FROM_UNIXTIME(`mdl_course_modules_completion`.`timemodified`)) AS 'data_modulo_completato',
`lista_corsi_dettaglio`.`id_modulo`,
`lista_corsi_dettaglio`.`id_corso`,
`lista_corsi_dettaglio`.`nome`
FROM `".MOODLE_DB_NAME."`.`mdl_course_modules_completion` INNER JOIN `betaform_erp`.`lista_corsi_dettaglio`
ON `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`coursemoduleid` = `betaform_erp`.`lista_corsi_dettaglio`.`id_modulo`
WHERE DATE(FROM_UNIXTIME(`timemodified`))='2017-07-06'
AND `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`coursemoduleid` = `betaform_erp`.`lista_corsi_dettaglio`.`id_modulo`
AND `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`completionstate`=1)";


//print_r($row);
$sql_00001 = "SELECT 
coursemoduleid AS 'id_modulo',
userid AS 'id_utente_moodle',
FROM_UNIXTIME(`mdl_course_modules_completion`.`timemodified`) AS 'data_ora_modulo_completato',
DATE(FROM_UNIXTIME(`mdl_course_modules_completion`.`timemodified`)) AS 'data_modulo_completato'
FROM `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`
WHERE DATE(FROM_UNIXTIME(`timemodified`))=CURDATE()
AND `".MOODLE_DB_NAME."`.`mdl_course_modules_completion`.`completionstate`=1";
$rs_00001 = $dblink->get_results($sql_00001);
if (DISPLAY_DEBUG) StampaSQL($sql_00001,'','');

$conto_00001 = count($rs_00001);
if (DISPLAY_DEBUG) echo '<li>$conto_00001 = '.$conto_00001.'</li>';

$ok = false;

foreach($rs_00001 as $row_00001){
    
    if (DISPLAY_DEBUG) {
        echo '<br>'.$id_modulo = $row_00001['id_modulo'];
        echo '<br>'. $id_utente_moodle = $row_00001['id_utente_moodle'];
        echo '<br>'. $data_ora_modulo_completato = $row_00001['data_ora_modulo_completato'];
        echo '<br>'. $data_modulo_completato = $row_00001['data_modulo_completato'];
    }else{
        $id_modulo = $row_00001['id_modulo'];
        $id_utente_moodle = $row_00001['id_utente_moodle'];
        $data_ora_modulo_completato = $row_00001['data_ora_modulo_completato'];
        $data_modulo_completato = $row_00001['data_modulo_completato'];
    }
    $sql_00002_1 = "SELECT * FROM lista_corsi_dettaglio WHERE id_modulo = ".$id_modulo;
    $rs_00002_1 = $dblink->get_results($sql_00002_1);
    if (DISPLAY_DEBUG) StampaSQL($sql_00002_1,'','');
    foreach($rs_00002_1 as $row_00002_1){
       if (DISPLAY_DEBUG) echo '<br>'.$id_corso_nostro = $row_00002_1['id_corso'];
       else $id_corso_nostro = $row_00002_1['id_corso'];
    }
    
    //RECUPERO NOSTRO ID DEL CORSO
    $sql_00002 = "SELECT id,nome_prodotto,id_corso_moodle FROM lista_corsi WHERE id =".$id_corso_nostro;
    $row_00002 = $dblink->get_row($sql_00002, true);
    $id_corso_moodle = $row_00002['id_corso_moodle'];
    $nome_corso_nostro = $row_00002['nome_prodotto'];
    if (DISPLAY_DEBUG) echo '<li>$id_corso_nostro = '.$id_corso_nostro.' --> '.$nome_corso_nostro.'</li>';
    
    //RECUPERO NOSTRO ID DEL PROFESSIONISTA
    $sql_00003 = "SELECT id, cognome, nome FROM lista_professionisti WHERE id_moodle_user =".$id_utente_moodle;
    $row_00003 = $dblink->get_row($sql_00003, true);
    $id_professionista_nostro = $row_00003['id'];
    $cognome_professionista_nostro = $row_00003['cognome'];
    $nome_professionista_nostro = $row_00003['nome'];
    if (DISPLAY_DEBUG) echo '<li>$id_professionista_nostro = '.$id_professionista_nostro.' --> '.$cognome_professionista_nostro.' '.$nome_professionista_nostro.'</li>';
    
    $sql_00004 = "SELECT * FROM lista_iscrizioni 
    WHERE id_professionista =".$id_professionista_nostro." 
    AND id_corso = ".$id_corso_nostro."
    AND data_inizio_iscrizione <='".$data_modulo_completato."'
    AND data_fine_iscrizione >='".$data_modulo_completato."'";
    //echo $sql_00004;
    $rs_00004 = $dblink->get_results($sql_00004);
    if(!empty($rs_00004)){
        if (DISPLAY_DEBUG) StampaSQL($sql_00004,'','');
        
        foreach($rs_00004 as $row_00004){
            $id_iscrizione = $row_00004['id'];
            if (DISPLAY_DEBUG) {
                echo '<li>$id_iscrizione = '.$id_iscrizione.'</li>';

                echo '<li>$id_utente_moodle = '.$id_utente_moodle.'</li>';
                echo '<li>$id_corso_moodle = '.$id_corso_moodle.'</li>';
            }

            $percentuale_corso_utente = recupero_percentuale_avanzamento_corso_utente($id_utente_moodle, $id_corso_moodle, true);
            $sql_007_aggionro_percentuale = "UPDATE lista_iscrizioni
            SET `avanzamento_completamento` = '".$percentuale_corso_utente."'
            WHERE id=".$id_iscrizione."";
            if (DISPLAY_DEBUG) echo '<li>$percentuale_corso_utente = '.$percentuale_corso_utente.'</li>';
            $rs_007_aggionro_percentuale = $dblink->query($sql_007_aggionro_percentuale);
        }
    }
    if (DISPLAY_DEBUG) echo '<hr>';
}

if (DISPLAY_DEBUG) echo '<div>'.date("H:i:s").'</div>';
?>
