<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
include_once(BASE_ROOT . 'classi/webservice/client.php');

if (DISPLAY_DEBUG) {
    echo '<li>'.date('Y-m-d H:i:s').'</li>';
    echo '<li>DB_HOST = '.DB_HOST.'</li>';
    echo '<li>DB_USER = '.DB_USER.'</li>';
    echo '<li>DB_PASS = '.DB_PASS.'</li>';
    echo '<li>DB_NAME = '.DB_NAME.'</li>';
    echo '<hr>';
}
$moodle = new moodleWebService();

// livello LIKE 'cliente' AND (passwd IS NULL OR LENGTH(passwd)<=0 OR stato='In Attesa di Password')  
//AND id_moodle_user=152
$sql_lista_password_manuale = "SELECT * FROM lista_password 
WHERE DATE(data_scadenza)>=CURDATE()
AND livello LIKE 'cliente' AND (passwd IS NULL OR LENGTH(passwd)<=0 OR stato='In Attesa di Password')
AND id_moodle_user>0
LIMIT 1000";

$sql_lista_password_manuale = "SELECT * FROM lista_password 
WHERE 1
AND livello LIKE 'cliente' AND (passwd IS NULL OR LENGTH(passwd)<=0 OR stato='In Attesa di Password')
AND id_moodle_user>0
LIMIT 500";
$rs_lista_password_manuale = $dblink->get_results($sql_lista_password_manuale);
foreach ($rs_lista_password_manuale AS $row_lista_password_manuale){
    //PER OGNI RIGA TRAMITE EMAIL VADO A CERCARE UTENTE IN MOODLE
    if (DISPLAY_DEBUG) echo '<h1>email = '.$row_lista_password_manuale['email'].'</h1>';
    
    $id_lista_password_manuale = $row_lista_password_manuale['id'];
    $username = $row_lista_password_manuale['username'];
    $email = $row_lista_password_manuale['email'];
    $firstname = $row_lista_password_manuale['nome'];
    $lastname = $row_lista_password_manuale['cognome'];

    $password = generaPassword(9);
    $idnumber = $row_lista_password_manuale['id_professionista'];

    if (DISPLAY_DEBUG) echo '<LI>$password = '.$password.'</LI>';

    
    $idUtenteMoodle = $moodle->creaUtenteMoodle($username, $email, $firstname, $lastname, $password, $idnumber);
    if (DISPLAY_DEBUG) echo '<LI>$idUtenteMoodle = '.$idUtenteMoodle.'</LI>';
    if($idUtenteMoodle>0){
        if (DISPLAY_DEBUG) echo '<li style="color: green;"> OK !</li>';
        $sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_password 
        SET id_moodle_user = '".$idUtenteMoodle."' , 
        stato = 'Attivo - Inviare Password',
        dataagg = NOW(),
        data_creazione = NOW(),
        passwd = '".$password."'
        WHERE id = '".$id_lista_password_manuale."'";
        $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);
        if($ok){
            $log->log_all_errors('sql_lista_password_manuale -> utente creato/aggiornato correttamente [idUtenteMoodle = '.$idUtenteMoodle.']','OK');
        }else{
            if (DISPLAY_DEBUG) echo '<li style="color: RED;">sql_aggiorna_lista_attivazioni_manuale KO !<br>'.$sql_aggiorna_lista_attivazioni_manuale.'</li>';
            $log->log_all_errors('sql_aggiorna_lista_attivazioni_manuale Errore','ERRORE');
            
        }
        
        
    }else{
        if (DISPLAY_DEBUG) echo '<li style="color: RED;"> KO !</li>';
        //STAMPO L'ERRORE DEL WEBSERVICE
        if (DISPLAY_DEBUG) echo $idUtenteMoodle;
        $log->log_all_errors('sql_lista_password_manuale -> utente NON creato/aggiornato [email = '.$email.']','ERRORE');
        die();
    }
    if (DISPLAY_DEBUG) echo '<hr>';
}

$sql_lista_password_manuale_rimanenti = "SELECT * FROM lista_password 
WHERE DATE(data_scadenza)>=CURDATE()
AND livello LIKE 'cliente' AND (passwd IS NULL OR LENGTH(passwd)<=0 OR stato='In Attesa di Password')
AND id_moodle_user>0";
$rs_lista_password_manuale_rimanenti = $dblink->num_rows($sql_lista_password_manuale_rimanenti);
if($rs_lista_password_manuale_rimanenti>0){
    echo ' <meta http-equiv="refresh" content="5"><h3>rimangono '.$rs_lista_password_manuale_rimanenti .' password</h3>';
}else{
    echo 'FINITO !';
    if (DISPLAY_DEBUG) echo '<hr>'.date("H:i:s");
}
?>