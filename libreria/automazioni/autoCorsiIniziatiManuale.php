<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'libreria/libreria.php');
include_once(BASE_ROOT.'classi/webservice/client.php');

$moodle = new moodleWebService();

echo date("H:i:s");

echo '<h1>autoCorsiIniziatiManuale</h1>';
echo '<li>DB_HOST = '.DB_HOST.'</li>';
echo '<li>DB_USER = '.DB_USER.'</li>';
echo '<li>DB_PASS = '.DB_PASS.'</li>';
echo '<li>DB_NAME = '.DB_NAME.'</li>';
echo '<li>DB_NAME = '.MOODLE_DB_NAME.'</li>';
echo '<li>DB_NAME = '.DURATA_CORSO_INGEGNERI.'</li>';
echo '<li>DB_NAME = '.DURATA_ABBONAMENTO.'</li>';
echo '<li>DB_NAME = '.DURATA_CORSO.'</li>';
echo '<hr>';

/*
$sql_000555= "UPDATE lista_iscrizioni 
SET lista_iscrizioni.stato = 'Scaduto'
WHERE lista_iscrizioni.data_fine_iscrizione<=NOW()
AND stato = 'In Attesa' OR stato = 'In Corso'";
$dblink->query($sql_000555);
*/

if($_GET['limit'] and isset($_GET['totale_record'])){
    $limit = $_GET['limit'];
    $totale_record = $_GET['totale_record'];
}else{
    $sql_aggiorna_corsi_dettaglio = "UPDATE lista_corsi_dettaglio, lista_corsi
    SET lista_corsi_dettaglio.id_corso_moodle = lista_corsi.id_corso_moodle
    WHERE lista_corsi_dettaglio.id_corso = lista_corsi.id";
    $rs_aggiorna_corsi_dettaglio = $dblink->query($sql_aggiorna_corsi_dettaglio);
    
    $limit = 0;
}



//$page_size = 10000;

$sql_iscritti = "SELECT DISTINCT id_utente_moodle, id_corso_moodle, id_modulo, instance 
FROM lista_iscrizioni INNER JOIN lista_corsi_dettaglio ON lista_iscrizioni.id_corso = lista_corsi_dettaglio.id_corso 
WHERE lista_iscrizioni.stato='In Attesa' 
AND (ordine=1 OR ordine=2) ORDER BY lista_iscrizioni.id_utente_moodle"; 
//LIMIT $page_size";
$rs_iscritti = $dblink->get_results($sql_iscritti);

//echo $sql_iscritti."<br>";

//StampaSQL($sql_iscritti);
foreach($rs_iscritti as $row_iscritti){
            $id_iscritto = $row_iscritti['id_utente_moodle'];
            $id_corso_moodle = $row_iscritti['id_corso_moodle'];
            $instance = $row_iscritti['instance'];
            
            $sql_00001 = "SELECT mdl_scorm_scoes_track.id as track_id, 
            `userid` AS 'id_utente_moodle',
            scormid,
            mdl_scorm.course AS 'id_corso_moodle',
LEFT(FROM_UNIXTIME(`mdl_scorm_scoes_track`.value),19) AS 'data_ora_inizio',
DATE(FROM_UNIXTIME(`mdl_scorm_scoes_track`.value)) AS 'data_inizio'
            FROM ".MOODLE_DB_NAME.".`mdl_scorm_scoes_track` INNER JOIN ".MOODLE_DB_NAME.".`mdl_scorm`
            ON ".MOODLE_DB_NAME.".`mdl_scorm_scoes_track`.scormid = ".MOODLE_DB_NAME.".`mdl_scorm`.id
            WHERE `userid`='".$id_iscritto."'
            AND `mdl_scorm_scoes_track`.scormid ='".$instance."'
            AND `mdl_scorm`.course ='".$id_corso_moodle."'
            AND ".MOODLE_DB_NAME.".`mdl_scorm_scoes_track`.element='x.start.time'
            ORDER BY DATE(FROM_UNIXTIME(`mdl_scorm_scoes_track`.value)) ASC";
       //AND DATE(FROM_UNIXTIME(`mdl_scorm_scoes_track`.value))=CURDATE()
  //AND `userid`='35567'  
$rs_00001 = $dblink->get_results($sql_00001);

//echo $sql_00001."<br>";

//StampaSQL($sql_00001);
//print_r($row);
//StampaSQL($sql_00001,'','');
//$conto_00001 = mysql_num_rows($rs_00001);
//echo '<li>$conto_00001 = '.$conto_00001.'</li>';
$ok = false;
foreach($rs_00001 as $row_0){

    echo '<br>mdl_scorm_scoes_track della tabella id = '.$id_utente_moodle = $row_0['track_id'];
    echo '<br>id_utente_moodle = '.$id_utente_moodle = $row_0['id_utente_moodle'];
    echo '<br>data_inizio_corso = '.$data_inizio_corso = $row_0['data_inizio'];
    echo '<br>data_ora_inizio_corso = '.$data_ora_inizio_corso = $row_0['data_ora_inizio'];
    echo '<br>id_instance_moodle = '.$id_instance_moodle = $row_0['scormid'];
     echo '<br>id_corso_moodle = '.$id_corso_moodle = $row_0['id_corso_moodle'];
    
    //RECUPERO NOSTRO ID DEL CORSO
    
    
    //$sql_00002_1 = "SELECT id,id_corso FROM lista_corsi_dettaglio WHERE instance =".$id_instance_moodle." AND id_modulo='' AND modname='scorm'";
    $sql_00002_1 = "SELECT id,id_corso FROM lista_corsi_dettaglio WHERE id_corso_moodle =".$id_corso_moodle." AND  instance =".$id_instance_moodle." AND id_modulo !=''";
    $row_00002_1 = $dblink->get_row($sql_00002_1, true);
    $id_corso_nostro = $row_00002_1['id_corso'];
    echo '<li>$id_corso_nostro = '.$id_corso_nostro.' ---> $sql_00002_1  '.$sql_00002_1.'</li>';
    
    $sql_00002 = "SELECT id,nome_prodotto FROM lista_corsi WHERE id =".$id_corso_nostro;
    $row_00002 = $dblink->get_row($sql_00002, true);
    $id_corso_nostro = $row_00002['id'];
    $nome_corso_nostro = $row_00002['nome_prodotto'];
    echo '<li>$id_corso_nostro = '.$id_corso_nostro.' --> '.$nome_corso_nostro.'</li>';
    
    //RECUPERO NOSTRO ID DEL PROFESSIONISTA
    $sql_00003 = "SELECT id, cognome, nome FROM lista_professionisti WHERE id_moodle_user =".$id_utente_moodle;
    $row_00003 = $dblink->get_row($sql_00003, true);
    $id_professionista_nostro = $row_00003['id'];
    $cognome_professionista_nostro = $row_00003['cognome'];
    $nome_professionista_nostro = $row_00003['nome'];
    echo '<li>$id_professionista_nostro = '.$id_professionista_nostro.' --> '.$cognome_professionista_nostro.' '.$nome_professionista_nostro.'</li>';
    
    $percentuale_corso_utente = recupero_percentuale_avanzamento_corso_utente($id_utente_moodle, $id_corso_moodle, true);
            
    if($id_professionista_nostro>0){
    /*
        $sql_00004 = "SELECT * FROM lista_iscrizioni 
    WHERE id_professionista =".$id_professionista_nostro." 
    AND id_corso = ".$id_corso_nostro."
    AND data_inizio_iscrizione <='".$data_inizio_corso."'
    AND data_fine_iscrizione >='".$data_inizio_corso."'";
    */
     $sql_00004 = "SELECT * FROM lista_iscrizioni 
    WHERE id_professionista =".$id_professionista_nostro." 
    AND id_corso = ".$id_corso_nostro."
    AND DATE(data_inizio) <='".$data_inizio_corso."'
    AND DATE(data_fine) >='".$data_inizio_corso."'
    AND stato = 'In Attesa'";
    //echo $sql_00004."<br>";
    $rs_00004 = $dblink->get_results($sql_00004);
    if(!empty($rs_00004)){
        //StampaSQL($sql_00004,'','');
        //die();
        foreach($rs_00004 as $row_00004){
            $id_iscrizione = $row_00004['id'];
            $controlloAbbonamento = $row_00004['abbonamento'];
            $controlloClasse = $row_00004['id_classe'];
            
            $sql_00005 = "UPDATE lista_iscrizioni
            SET dataagg = NOW(),
            scrittore = 'autoCorsiIniziati',
            stato='In Corso',
            `avanzamento_completamento` = '".$percentuale_corso_utente."',
            data_inizio = '".$data_ora_inizio_corso."'
            WHERE id = ".$id_iscrizione."
            AND id_corso = ".$id_corso_nostro." 
            AND id_professionista = ".$id_professionista_nostro."
            AND stato = 'In Attesa'";
            $rs_00005 = $dblink->query($sql_00005);
            if($rs_00005){

                /*if($controlloAbbonamento>0 and $controlloClasse==10){
                    $sql_00006 = "UPDATE lista_iscrizioni SET data_fine = DATE_ADD(data_inizio, INTERVAL ".DURATA_CORSO_INGEGNERI." DAY) 
                    WHERE id = ".$id_iscrizione."";
                    $rs_00006 = $dblink->query($sql_00006);
                }elseif($controlloAbbonamento>0  and $controlloClasse!=10){  
                    $sql_00006 = "UPDATE lista_iscrizioni SET data_fine = data_fine_iscrizione
                    WHERE id = ".$id_iscrizione."";
                    $rs_00006 = $dblink->query($sql_00006);
                }else{  
                    $sql_00006 = "UPDATE lista_iscrizioni SET data_fine_iscrizione = DATE_ADD(data_inizio, INTERVAL ".DURATA_CORSO." DAY) 
                    WHERE id = ".$id_iscrizione."";
                    $rs_00006 = $dblink->query($sql_00006);
                }

                if(!$rs_00006){
                    echo '<li style="color:red; border:1px solid red; padding:7px;">Errore $sql_00006 -->'.$sql_00006.'</li>';
                    //echo $sql_00006;
                }*/
            }else{
                echo '<li style="color:red; border:1px solid red; padding:7px;">Errore $sql_00005 -->'.$sql_00005.'</li>';
            }
        }
    }else{
        echo '<li style="color:red; border:1px solid red; padding:7px;">Errore 2 $sql_00004 -->'.$sql_00004.'</li>';
    }   
    }else{
    /*
        $sql_00004 = "SELECT * FROM lista_iscrizioni 
    WHERE id_utente_moodle =".$id_utente_moodle." 
    AND id_corso = ".$id_corso_nostro."
    AND data_inizio_iscrizione <='".$data_inizio_corso."'
    AND data_fine_iscrizione >='".$data_inizio_corso."'";
    */
            $sql_000044 = "SELECT * FROM lista_iscrizioni 
    WHERE id_utente_moodle =".$id_utente_moodle." 
    AND id_corso = ".$id_corso_nostro."
    AND DATE(data_inizio) <='".$data_inizio_corso."'
    AND DATE(data_fine) >='".$data_inizio_corso."'
    AND stato = 'In Attesa'";
    echo $sql_000044;
    $rs_000044 = $dblink->get_results($sql_000044);
    if(!empty($rs_000044)){
        //StampaSQL($sql_000044,'','');
        foreach($rs_000044 as $row_000044){
            $id_iscrizione = $row_000044['id'];
            $controlloAbbonamento = $row_000044['abbonamento'];
            $controlloClasse = $row_000044['id_classe'];
            
            $sql_00005 = "UPDATE lista_iscrizioni
            SET dataagg = NOW(),
            scrittore = 'autoCorsiIniziati',
            stato='In Corso',
             `avanzamento_completamento` = '".$percentuale_corso_utente."',
            data_inizio = '".$data_ora_inizio_corso."'
            WHERE id = ".$id_iscrizione."
            AND id_corso = ".$id_corso_nostro." 
            AND id_utente_moodle = ".$id_utente_moodle."
            AND stato = 'In Attesa'";
            $rs_00005 = $dblink->query($sql_00005);
            if($rs_00005){
            
                /*if($controlloAbbonamento>0 and $controlloClasse==10){
                    $sql_00006 = "UPDATE lista_iscrizioni SET data_fine = DATE_ADD(data_inizio, INTERVAL ".DURATA_CORSO_INGEGNERI." DAY) 
                    WHERE id = ".$id_iscrizione."";
                    $rs_00006 = $dblink->query($sql_00006);
                }elseif($controlloAbbonamento>0  and $controlloClasse!=10){  
                    $sql_00006 = "UPDATE lista_iscrizioni SET data_fine = data_fine_iscrizione
                    WHERE id = ".$id_iscrizione."";
                    $rs_00006 = $dblink->query($sql_00006);
                }else{  
                    $sql_00006 = "UPDATE lista_iscrizioni SET data_fine_iscrizione = DATE_ADD(data_inizio, INTERVAL ".DURATA_CORSO." DAY) 
                    WHERE id = ".$id_iscrizione."";
                    $rs_00006 = $dblink->query($sql_00006);
                }
            
                if(!$rs_00006){
                    echo '<li style="color:red; border:1px solid red; padding:7px;">Errore $sql_00006 -->'.$sql_00006.'</li>';
                }*/
            
            }else{
                echo '<li style="color:red; border:1px solid red; padding:7px;">Errore $sql_00005 -->'.$sql_00005.'</li>';
            }
        }
    }else{
        echo '<li style="color:red; border:1px solid red; padding:7px;">Errore $sql_000044 -->'.$sql_000044.'</li>';
    }   
    }


    
    
    echo '<hr>';
}


}
echo date("H:i:s");
/*
$sql_iscritti_conto = "SELECT DISTINCT id_utente_moodle, id_corso_moodle, id_modulo, instance 
FROM lista_iscrizioni INNER JOIN lista_corsi_dettaglio ON lista_iscrizioni.id_corso = lista_corsi_dettaglio.id_corso 
WHERE lista_iscrizioni.stato='In Attesa' 
AND (ordine=1 OR ordine=2) ORDER BY lista_iscrizioni.id_utente_moodle ";
$totale_record = $dblink->num_rows($sql_iscritti_conto);

if($totale_record > 0){
    ob_flush();
    $rimangono = $totale_record;
    //$limit = ($limit+$page_size) -1 ;
    sleep('2');
    ob_clean();
    echo ' <meta http-equiv="refresh" content="3; url='.BASE_URL."/libreria/automazioni/autoCorsiIniziatiManuale.php".'" ><h3>rimangono '.$rimangono.' corsi </h3>';
    
}else{*/
    echo '<h3>$page_size = '.$page_size.'</h3>';
    echo '<h3>$limit = '.$limit.'</h3>';
    echo '<h1>FATTO !</h1>';
//}
?>
