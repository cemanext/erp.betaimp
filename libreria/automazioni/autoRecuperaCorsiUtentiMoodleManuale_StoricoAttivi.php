<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/config/connDB.php');
include_once(BASE_ROOT.'libreria/libreria.php');
include_once(BASE_ROOT.'classi/webservice/client.php');

/*
$sql_0005 = "UPDATE lista_iscrizioni, lista_password 
SET lista_iscrizioni.id_utente_moodle = lista_password.id_moodle_user 
WHERE lista_password.id_professionista = lista_iscrizioni.id_professionista AND lista_iscrizioni.id_utente_moodle<=0";
$dblink->query($sql_0005);
*/

/*$sql_00055 = "UPDATE  ".DB_NAME.".lista_password, ".MOODLE_DB_NAME.".mdl_user_info_data
SET lista_password.data_scadenza = STR_TO_DATE(mdl_user_info_data.data,'%d/%m/%Y') 
WHERE lista_password.id_moodle_user = mdl_user_info_data.userid AND mdl_user_info_data.fieldid=1 AND mdl_user_info_data.data !=''";
$dblink->query($sql_00055);*/

echo '<h1>autoRecuperaCorsiUtentiMoodleManuale_StoricoNonAttivi</h1>';
echo '<li>DB_HOST = '.DB_HOST.'</li>';
echo '<li>DB_USER = '.DB_USER.'</li>';
echo '<li>DB_PASS = '.DB_PASS.'</li>';
echo '<li>DB_NAME = '.DB_NAME.'</li>';
echo '<li>DB_NAME = '.MOODLE_DB_NAME.'</li>';
echo '<li>DB_NAME = '.DURATA_CORSO_INGEGNERI.'</li>';
echo '<li>DB_NAME = '.DURATA_ABBONAMENTO.'</li>';
echo '<li>DB_NAME = '.DURATA_CORSO.'</li>';
echo '<hr>';
//AND lista_password.id_moodle_user=152
$moodle = new moodleWebService();
echo '<li>'.date('Y-m-d H:i:s').'</li>';

$sql_lista_attivazioni_manuale = "SELECT 
    mdl_enrol.courseid, 
    mdl_user_enrolments.* , 
    lista_password.*,
    mdl_user_enrolments.id AS 'id_user_enrolments',
    IF(mdl_user_enrolments.timeend > 0, 
        DATE(FROM_UNIXTIME(mdl_user_enrolments.timeend)), 
        DATE_ADD(FROM_UNIXTIME(mdl_user_enrolments.timestart), INTERVAL ".DURATA_CORSO." DAY)
    ) AS 'data_scadenza_corso',
    DATE(FROM_UNIXTIME(mdl_user_enrolments.timestart)) AS 'data_inizio_iscrizione_manuale',
    IF(mdl_user_enrolments.timeend > 0, 
        DATE(FROM_UNIXTIME(mdl_user_enrolments.timeend)), 
        DATE_ADD(FROM_UNIXTIME(mdl_user_enrolments.timestart), INTERVAL ".DURATA_CORSO." DAY)
    ) AS 'data_fine_iscrizione_manuale',
    IF(mdl_user_enrolments.timeend > 0, 
        DATE(FROM_UNIXTIME(mdl_user_enrolments.timeend)), 
        DATE_ADD(FROM_UNIXTIME(mdl_user_enrolments.timestart), INTERVAL ".DURATA_CORSO." DAY)
    ) AS 'data_scadenza_corso_singolo'
    
FROM ".MOODLE_DB_NAME.".`mdl_user_enrolments` INNER JOIN ".MOODLE_DB_NAME.".mdl_enrol ON (mdl_user_enrolments.`enrolid`=mdl_enrol.id) 
INNER JOIN ".DB_NAME.".lista_password ON mdl_user_enrolments.userid = lista_password.id_moodle_user
WHERE data_scadenza>=NOW()
AND lista_password.id_moodle_user>0
AND lista_password.livello = 'cliente'
ORDER BY `mdl_user_enrolments`.`userid` ASC";
//AND DATE(FROM_UNIXTIME(mdl_user_enrolments.timecreated))=CURDATE()
//(IF(id_classe=10,DATE_ADD(FROM_UNIXTIME(mdl_user_enrolments.timestart), INTERVAL ".DURATA_CORSO_INGEGNERI." DAY),DATE_ADD(FROM_UNIXTIME(mdl_user_enrolments.timecreated), INTERVAL ".DURATA_ABBONAMENTO." DAY))) AS 'data_scadenza_corso',
//IF(id_classe>0,DATE_SUB(data_scadenza, INTERVAL ".DURATA_ABBONAMENTO." DAY),FROM_UNIXTIME(mdl_user_enrolments.timestart)) AS 'data_inizio_iscrizione_manuale',
//(IF(id_classe>0,data_scadenza,DATE_ADD(FROM_UNIXTIME(mdl_user_enrolments.timestart), INTERVAL ".DURATA_CORSO." DAY))) AS 'data_fine_iscrizione_manuale',
//DATE_ADD(FROM_UNIXTIME(timestart), INTERVAL ".DURATA_CORSO." DAY) AS 'data_scadenza_corso_singolo'

echo '$sql_lista_attivazioni_manuale = '.$sql_lista_attivazioni_manuale;
$rs_lista_attivazioni_manuale = $dblink->get_results($sql_lista_attivazioni_manuale);
foreach ($rs_lista_attivazioni_manuale AS $row_lista_attivazioni_manuale){
 echo '<br>'.$id_utente_moodle = $row_lista_attivazioni_manuale['userid'];
  echo '<br>'.$id_corso_moodle = $row_lista_attivazioni_manuale['courseid'];
 echo '<br>'.$idClasse = $row_lista_attivazioni_manuale['id_classe']; 
 echo '<br>'.$idProfessionista = $row_lista_attivazioni_manuale['id_professionista'];
 echo '<br>'.$idAzienda = $row_lista_attivazioni_manuale['id_azienda'];
 echo '<br>'.$dataScadenza = $row_lista_attivazioni_manuale['data_scadenza'];
 echo '<br>'.$timestart = $row_lista_attivazioni_manuale['timestart'];
 echo '<br>'.$timecreated = $row_lista_attivazioni_manuale['timecreated'];
 echo '<br>'.$data_scadenza_corso_singolo = $row_lista_attivazioni_manuale['data_scadenza_corso_singolo'];
 echo '<br>'.$data_scadenza_corso = $row_lista_attivazioni_manuale['data_scadenza_corso'];
 echo '<br>'.$id_user_enrolments = $row_lista_attivazioni_manuale['id_user_enrolments'];
 echo '<br>'.$cognome_nome_professionista = $row_lista_attivazioni_manuale['cognome'].' '.$row_lista_attivazioni_manuale['nome'];
 echo '<br>'.$data_inizio_iscrizione_manuale = $row_lista_attivazioni_manuale['data_inizio_iscrizione_manuale'];
  echo '<br>'.$data_fine_iscrizione_manuale = $row_lista_attivazioni_manuale['data_fine_iscrizione_manuale'];
 
 if(empty($idClasse)){
 
    $sql_classe = "SELECT DISTINCT mdl_enrol.name, mdl_enrol.customint5 AS idClasse 
    FROM ".MOODLE_DB_NAME.".mdl_enrol INNER JOIN ".MOODLE_DB_NAME.".mdl_user_enrolments 
    ON ".MOODLE_DB_NAME.".mdl_user_enrolments.enrolid = ".MOODLE_DB_NAME.".mdl_enrol.id 
    WHERE mdl_user_enrolments.userid = '".$id_utente_moodle."' AND mdl_user_enrolments.timeend = '0' 
    AND mdl_enrol.customint5 > 0 ORDER BY mdl_enrol.name DESC";
    $row_classe = $dblink->get_row($sql_classe, true);
    $idClasse = $row_classe['idClasse'];
    if($idClasse>0){
        $ok = $dblink->updateWhere("lista_password", array("id_classe"=> $idClasse),  " id_moodle_user='".$id_utente_moodle."'");
        $ok = $dblink->updateWhere("lista_professionisti", array("id_classe"=> $idClasse),  " id_moodle_user='".$id_utente_moodle."'");
    }
 }
 
 
 $id_corso = $dblink->get_row("SELECT DISTINCT id, id_prodotto, nome_prodotto FROM lista_corsi WHERE id_corso_moodle = '".$id_corso_moodle."'", true);
 $id_classe = $dblink->get_row("SELECT DISTINCT nome FROM lista_classi WHERE id = '".$idClasse."'", true);
 
 $sql_controllo_se_attivazione_presente = "SELECT DISTINCT id, stato_completamento FROM lista_iscrizioni 
 WHERE id_utente_moodle = '".$id_utente_moodle."'
 AND id_corso = '".$id_corso['id']."'
 AND  data_fine_iscrizione>=CURDATE()";
 echo $sql_controllo_se_attivazione_presente;
 $controllo = $dblink->get_row($sql_controllo_se_attivazione_presente, true); 
 if($controllo['id']>0){
    //update
    $update = array(
 "dataagg" => date("Y-m-d H:i:s"),
 "scrittore" => "autoRecuperaCorsiUtentiMoodleManuale",
 "id_corso" => $id_corso['id'],
 "id_classe" => $idClasse,
 "id_professionista" => $idProfessionista,
 "data_inizio_iscrizione" => $data_inizio_iscrizione_manuale,
 "data_fine_iscrizione" => $data_fine_iscrizione_manuale,
  "data_inizio" =>  $idClasse==0 ? $data_inizio_iscrizione_manuale :  date("Y-m-d H:i:s", $timestart),
 "data_fine" => $idClasse==0 ? $data_fine_iscrizione_manuale :  $data_scadenza_corso,
 "nome_corso" => $dblink->filter($id_corso['nome_prodotto']),
 "nome_classe" => $dblink->filter($id_classe['nome']),
 "cognome_nome_professionista" => $dblink->filter($cognome_nome_professionista),
 "abbonamento" => $idClasse==0 ? '0' : '1',
 "id_utente_moodle" => $id_utente_moodle,
 "id_user_enrolments" => $id_user_enrolments,
 "stato" => "In Attesa",
 );
 
 if($controllo['stato_completamento'] != 'Completato'){
    $ok = $dblink->update(DB_NAME.".lista_iscrizioni", $update, array("id" => $controllo['id'], "stato"=>"In Attesa"));
 }else{
    $ok = true;
 }
 
 
 }else{
    //insert
    $insert = array(
 "dataagg" => date("Y-m-d H:i:s"),
 "scrittore" => "autoRecuperaCorsiUtentiMoodleManuale",
 "id_corso" => $id_corso['id'],
 "id_classe" => $idClasse,
 "id_professionista" => $idProfessionista,
 "data_inizio_iscrizione" => $data_inizio_iscrizione_manuale,
 "data_fine_iscrizione" => $data_fine_iscrizione_manuale,
  "data_inizio" =>  $idClasse==0 ? $data_inizio_iscrizione_manuale :  date("Y-m-d H:i:s", $timestart),
 "data_fine" => $idClasse==0 ? $data_fine_iscrizione_manuale :  $data_scadenza_corso,
 "nome_corso" => $dblink->filter($id_corso['nome_prodotto']),
 "nome_classe" => $dblink->filter($id_classe['nome']),
 "cognome_nome_professionista" => $dblink->filter($cognome_nome_professionista),
 "abbonamento" => $idClasse==0 ? '0' : '1',
 "id_utente_moodle" => $id_utente_moodle,
 "id_user_enrolments" => $id_user_enrolments,
 "stato" => "In Attesa",
 );
 
 $ok = $dblink->insert(DB_NAME.".lista_iscrizioni", $insert);
 }
 

    if($ok){
        echo '<li style="color: GREEN;"> OK !</li>';
        $log->log_all_errors('autoRecuperaCorsiUtentiMoodleManuale.php -> attivazione creata correttamente [id_utente_moodle = '.$id_utente_moodle.']','OK');
    }else{
        echo '<li style="color: RED;"> KO !</li>';
        $log->log_all_errors('autoRecuperaCorsiUtentiMoodleManuale.php -> attivazione NON creata [id_utente_moodle = '.$id_utente_moodle.']','ERRORE');
    }

    
    echo '<hr>';
}
?>