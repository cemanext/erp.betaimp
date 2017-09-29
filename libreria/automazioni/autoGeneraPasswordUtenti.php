<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/config/connDB.php');
include_once(BASE_ROOT.'libreria/libreria.php');
include_once(BASE_ROOT.'classi/webservice/client.php');

echo '<li>DB_HOST = '.DB_HOST.'</li>';
echo '<li>DB_USER = '.DB_USER.'</li>';
echo '<li>DB_PASS = '.DB_PASS.'</li>';
echo '<li>DB_NAME = '.DB_NAME.'</li>';
echo '<hr>';
$moodle = new moodleWebService();
echo '<li>'.date('Y-m-d H:i:s').'</li>';
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
    echo '<h1>email = '.$row_lista_password_manuale['email'].'</h1>';
$id_lista_password_manuale = $row_lista_password_manuale['id'];
$username = $row_lista_password_manuale['username'];
$email = $row_lista_password_manuale['email'];
$firstname = $row_lista_password_manuale['nome'];
$lastname = $row_lista_password_manuale['cognome'];

$password = generaPassword(9);
$idnumber = $row_lista_password_manuale['id_professionista'];

 echo '<LI>$password = '.$password.'</LI>';

    
    $idUtenteMoodle = $moodle->creaUtenteMoodle($username, $email, $firstname, $lastname, $password, $idnumber);
    echo '<LI>$idUtenteMoodle = '.$idUtenteMoodle.'</LI>';
    if($idUtenteMoodle>0){
        echo '<li style="color: green;"> OK !</li>';
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
            echo '<li style="color: RED;">sql_aggiorna_lista_attivazioni_manuale KO !<br>'.$sql_aggiorna_lista_attivazioni_manuale.'</li>';
            $log->log_all_errors('sql_aggiorna_lista_attivazioni_manuale Errore','ERRORE');
            die();
        }
        
        
    }else{
        echo '<li style="color: RED;"> KO !</li>';
        //STAMPO L'ERRORE DEL WEBSERVICE
        echo $idUtenteMoodle;
        $log->log_all_errors('sql_lista_password_manuale -> utente NON creato/aggiornato [email = '.$email.']','ERRORE');
        die();
    }
    echo '<hr>';
}

$sql_lista_password_manuale_rimanenti = "SELECT * FROM lista_password 
WHERE DATE(data_scadenza)>=CURDATE()
AND livello LIKE 'cliente' AND (passwd IS NULL OR LENGTH(passwd)<=0 OR stato='In Attesa di Password')
AND id_moodle_user>0";
$rs_lista_password_manuale_rimanenti = $dblink->num_rows($sql_lista_password_manuale_rimanenti);
if($rs_lista_password_manuale_rimanenti>0){
    ECHO ' <meta http-equiv="refresh" content="5"><h3>rimangono '.$rs_lista_password_manuale_rimanenti .' password</h3>';
}else{
    ECHO 'FINITO !';
}
?>