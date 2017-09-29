<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$sql_00001 = "UPDATE calendario, lista_campagne 
SET calendario.id_prodotto = lista_campagne.id_prodotto,
calendario.id_tipo_marketing = lista_campagne.id_tipo_marketing
WHERE calendario.id_campagna>0
AND calendario.id_prodotto<=0
AND calendario.id_tipo_marketing<=0
AND calendario.id_campagna = lista_campagne.id";
$dblink->query($sql_00001);


$sql_00001 = "SELECT id, data, ora, oggetto, mittente, tipo_marketing, "
        . "campo_1, campo_2, campo_3, campo_4, campo_5, campo_6, campo_7, campo_9"
        . " FROM calendario WHERE etichetta='Nuova Richiesta' AND stato='In Attesa di Controllo Automatico' ORDER BY  id DESC";
$rs_00001 = $dblink->get_results($sql_00001);
//print_r($row);
if (DISPLAY_DEBUG) StampaSQL($sql_00001,'','');

$conto_00001 = count($rs_00001);
if (DISPLAY_DEBUG) echo '<li>$conto_00001 = '.$conto_00001.'</li>';

$ok = false;
foreach($rs_00001 as $row_0) {
    if (DISPLAY_DEBUG) echo '<div style="border:1px solid blue;">';
    
    $richiesta_id_calendario = $row_0['id'];
    $richiesta_nome = $row_0['campo_1'];
    $richiesta_cognome = $row_0['campo_2'];
    $richiesta_mittente = $row_0['mittente'];
    $richiesta_cognome_nome = $richiesta_cognome.' '.$richiesta_nome;
    $richiesta_codice_fiscale = $row_0['campo_3'];
    $richiesta_codice_utente = $row_0['campo_9'];
    $richiesta_telefono_cellulare = $row_0['campo_4'];
    $richiesta_email = $row_0['campo_5'];
    $richiesta_messaggio = $row_0['messaggio'];
    if (DISPLAY_DEBUG) echo '<h1>$richiesta_mittente = '.$richiesta_mittente.'</h1>';
    
    for ($i = 1; $i <= 5 ; $i++){
        if (DISPLAY_DEBUG) echo '<div style="border:1px solid green;">'. '<li>$i = '.$i.'</li>';
        
        switch ($i){
            case 1:
                if(strlen($richiesta_codice_fiscale)>10 || strlen($richiesta_codice_utente)>5){
                    if (DISPLAY_DEBUG) {
                        echo '<h1>PRIORITA '.$i.') CONTROLLO  CODICE FISCALE oppure CODICE_CLIENTE</h1>'
                        . 'Cerco lista_professionisti campi codice_fiscale oppure codice, se presente setto id_professionista';
                        echo '<h2>$richiesta_codice_fiscale = '.$richiesta_codice_fiscale.'</h2>';
                        echo '<h2>$richiesta_codice_utente = '.$richiesta_codice_utente.'</h2>';
                    }
                    if(strlen($richiesta_codice_fiscale)>10){
                        $Where = "codice_fiscale='$richiesta_codice_fiscale'";
                    }else if(strlen($richiesta_codice_utente)>5){
                        $Where = "codice = '$richiesta_codice_utente'";
                    }else $Where = "1";
                    $sql_00002 = "SELECT * FROM lista_professionisti WHERE $Where";
                    $rs_00002 = $dblink->get_results($sql_00002);

                    if (DISPLAY_DEBUG) echo '<li>conteggio = '.count($rs_00002).'</li>';

                    if(!empty($rs_00002)){
                        if (DISPLAY_DEBUG) StampaSQL($sql_00002,'','');

                        foreach($rs_00002 as $row_00002) {
                            if($row_00002['id']>0){
                                $id_professionista = $row_00002['id'];
                                $id_azienda =  ottieniIdAzienda($row_00002['id']);

                                if (DISPLAY_DEBUG) echo '<li>QUI SALVO IL FILE ED ESCO</li>';
                                $sql_00002_1 = "UPDATE calendario "
                                    . "SET id_professionista='".$id_professionista."',"
                                    . "id_azienda = '".$id_azienda."',"
                                    . "stato='In Attesa di Controllo' "
                                    . "WHERE id_professionista<=0 "
                                    . "AND stato = 'In Attesa di Controllo Automatico'"
                                    . "AND id=".$richiesta_id_calendario;
                                $rs_00002_1 = $dblink->query($sql_00002_1);

                                if($rs_00002_1){
                                    controllaRichiesteMultiple($richiesta_id_calendario);
                                    $ok = true;
                                    break 3;
                                }
                            }
                        }
                    }else{
                        if (DISPLAY_DEBUG) echo '<li style="color:red;">'.$i.') Nessun Record Trovato</li>';
                    }
                } 
            break;
            
            case 2:
            if(strlen($richiesta_email)>5 && verificaEmail($richiesta_email)){
                echo '<h1>PRIORITA '.$i.') CONTROLLO EMAIL</h1>'
                    . 'Cerco email in lista_indirizzi_email , se presente setto id_professionista o id_azienda';
                echo '<h2>$richiesta_email = '.$richiesta_email.'</h2>';
                $sql_00002 = "SELECT * FROM lista_professionisti WHERE email='$richiesta_email'";
                $rs_00002 = $dblink->get_results($sql_00002);
                if (DISPLAY_DEBUG) echo '<li>conteggio = '.count($rs_00002).'</li>';
                
                if(!empty($rs_00002)){
                    if (DISPLAY_DEBUG) StampaSQL($sql_00002,'','');
                    
                    foreach($rs_00002 as $row_00002) {
                        if (DISPLAY_DEBUG) echo '<li>QUI SALVO IL FILE ED ESCO</li>';
                        
                        if($row_00002['id']>=1){
                           $idAzienda =  ottieniIdAzienda($row_00002['id']);
                           $sql_00002_1 = "UPDATE calendario "
                                . "SET id_professionista='".$row_00002['id']."',"
                                . "id_azienda='".$idAzienda."',"
                                . "stato='In Attesa di Controllo' "
                                . "WHERE id_professionista<=0 "
                                . "AND stato = 'In Attesa di Controllo Automatico'"
                                . "AND id=".$richiesta_id_calendario; 
                            $rs_00002_1 = $dblink->query($sql_00002_1);
                            
                            if($rs_00002_1){
                                controllaRichiesteMultiple($richiesta_id_calendario);
                                $ok = true;
                                 break 3;
                            }
                        }
                    }
                }else{
                    if (DISPLAY_DEBUG) echo '<li style="color:red;">'.$i.') Nessun Record Trovato</li>';
                }
            }    
            break;
               
            case 3:
            if(strlen($richiesta_email)>5 && verificaEmail($richiesta_email)){
                echo '<h1>PRIORITA '.$i.') CONTROLLO EMAIL</h1>'
                    . 'Cerco email in lista_indirizzi_email , se presente setto id_professionista o id_azienda';
                echo '<h2>$richiesta_email = '.$richiesta_email.'</h2>';
                $sql_00002 = "SELECT * FROM lista_indirizzi_email WHERE email='$richiesta_email'";
                $rs_00002 = $dblink->get_results($sql_00002);
                if (DISPLAY_DEBUG) echo '<li>conteggio = '.count($rs_00002).'</li>';
                
                if(!empty($rs_00002)){
                    if (DISPLAY_DEBUG) StampaSQL($sql_00002,'','');
                    
                    foreach($rs_00002 as $row_00002) {
                        if (DISPLAY_DEBUG) echo '<li>QUI SALVO IL FILE ED ESCO</li>';
                        
                        if($row_00002['id_professionista']>=1){
                           $idAzienda =  ottieniIdAzienda($row_00002['id_professionista']);
                           $sql_00002_1 = "UPDATE calendario "
                                . "SET id_professionista='".$row_00002['id_professionista']."',"
                                . "id_azienda='".$idAzienda."',"
                                . "stato='In Attesa di Controllo' "
                                . "WHERE id_professionista<=0 "
                                . "AND stato = 'In Attesa di Controllo Automatico'"
                                . "AND id=".$richiesta_id_calendario;
                            $rs_00002_1 = $dblink->query($sql_00002_1);
                            if($rs_00002_1){
                                controllaRichiesteMultiple($richiesta_id_calendario);
                                $ok = true;
                                 break 3;
                            }
                        }
                        
                    }
                }else{
                    if (DISPLAY_DEBUG) echo '<li style="color:red;">'.$i.') Nessun Record Trovato</li>';
                }
            }    
            break;
                
            case 4:
            if(strlen($richiesta_telefono_cellulare)>5){
                
                echo '<h1>PRIORITA '.$i.') CONTROLLO  richiesta_telefono_cellulare</h1>'
                    . 'Cerco lista_professionisti campi telefono e cellulare , se presente setto id_professionista';
                echo '<h2>$richiesta_telefono_cellulare = '.$richiesta_telefono_cellulare.'</h2>';
                $sql_00002 = "SELECT * FROM lista_professionisti "
                        . "WHERE (telefono='$richiesta_telefono_cellulare' "
                        . "OR cellulare = '$richiesta_telefono_cellulare') AND telefono NOT IN (SELECT telefono FROM lista_numeri_telefono_controlli WHERE telefono = '$richiesta_telefono_cellulare')";
                $rs_00002 = $dblink->get_results($sql_00002);
                if (DISPLAY_DEBUG) echo '<li>conteggio = '.count($rs_00002).'</li>';
                
                if(!empty($rs_00002)){
                    if (DISPLAY_DEBUG) StampaSQL($sql_00002,'','');
                    
                    foreach($rs_00002 as $row_00002) {
                        if($row_00002['id']>=1){
                            $id_professionista = $row_00002['id'];
                            $id_azienda =  ottieniIdAzienda($row_00002['id']);
                            if (DISPLAY_DEBUG) echo '<li>QUI SALVO IL FILE ED ESCO</li>';
                            
                            $sql_00002_1 = "UPDATE calendario "
                                    . "SET id_professionista='".$id_professionista."',"
                                    . "id_azienda = '".$id_azienda."',"
                                    . "stato='In Attesa di Controllo' "
                                    . "WHERE stato = 'In Attesa di Controllo Automatico'"
                                    . "AND id=".$richiesta_id_calendario;
                            $rs_00002_1 = $dblink->query($sql_00002_1);
                            
                            if($rs_00002_1){
                                controllaRichiesteMultiple($richiesta_id_calendario);
                                $ok = true;
                                break 3;
                            }
                        }
                        
                    }
                }else{
                    if (DISPLAY_DEBUG) echo '<li style="color:red;">'.$i.') Nessun Record Trovato</li>';
                }
             }   
            break;
            
            case 5:
            if(strlen($richiesta_telefono_cellulare)>5){
                echo '<h1>PRIORITA '.$i.') CONTROLLO LISTA NUMERI TELEFONO</h1>'
                    . 'Cerco telefono in lista_numeri_telefono , se presente setto id_professionista o id_azienda';
                echo '<h2>$richiesta_telefono_cellulare = '.$richiesta_telefono_cellulare.'</h2>';
                $sql_00009 = "SELECT * FROM lista_numeri_telefono WHERE telefono='$richiesta_telefono_cellulare' AND telefono IN (SELECT telefono FROM lista_numeri_telefono_controlli WHERE telefono = '$richiesta_telefono_cellulare')";
                $rs_00009 = $dblink->get_results($sql_00009);
                if (DISPLAY_DEBUG) echo '<li>conteggio = '.count($rs_00009).'</li>';
                
                if(!empty($rs_00009)){
                    if (DISPLAY_DEBUG) StampaSQL($sql_00009,'','');
                    
                    foreach($rs_00009 as $row_00009) {
                        if (DISPLAY_DEBUG) echo '<li>QUI SALVO IL FILE ED ESCO</li>';
                        
                        if($row_00009['id_professionista']>=1){
                           $idAzienda =  ottieniIdAzienda($row_00009['id_professionista']);
                           $sql_00009_1 = "UPDATE calendario "
                                . "SET id_professionista='".$row_00009['id_professionista']."',"
                                . "id_azienda='".$idAzienda."',"
                                . "stato='In Attesa di Controllo' "
                                . "WHERE id_professionista<=0 "
                                . "AND stato = 'In Attesa di Controllo Automatico'"
                                . "AND id=".$richiesta_id_calendario;
                            $rs_00009_1 = $dblink->query($sql_00009_1);
                            
                            if($rs_00009_1){
                                controllaRichiesteMultiple($richiesta_id_calendario);
                                $ok = true;
                                 break 3;
                            }
                        }
                    }
                }else{
                    if (DISPLAY_DEBUG) echo '<li style="color:red;">'.$i.') Nessun Record Trovato</li>';
                }
            }    
            break;
                
              /*  
            case 4:
             if(strlen($richiesta_cognome)>2 and strlen($richiesta_nome)>2){
                 echo '<h1>PRIORITA '.$i.') CONTROLLO COGNOME NOME</h1>Cerco lista_professionisti campi cognome e nome , se presente setto id_professionista';
                echo '<h2>$richiesta_cognome = '.$richiesta_cognome.' / $richiesta_nome = '.$richiesta_nome.'</h2>';
                $sql_00002 = "SELECT * FROM lista_professionisti WHERE cognome='$richiesta_cognome' AND nome = '$richiesta_nome'";
                $rs_00002 = $dblink->get_results($sql_00002);
                if (DISPLAY_DEBUG) echo '<li>conteggio = '.count($rs_00002).'</li>';
               
                if(!empty($rs_00002)){
                    StampaSQL($sql_00002,'','');
                    foreach($rs_00002 as $row_00002) {
                        $id_professionista = $row_00002['id'];
                        $idAzienda =  ottieniIdAzienda($row_00009['id_professionista']);
                        if (DISPLAY_DEBUG) echo '<li>QUI SALVO IL FILE ED ESCO</li>';
               
                        $sql_00002_1 = "UPDATE calendario "
                                . "SET id_professionista='".$id_professionista."',"
                                . "id_azienda = '".$id_azienda."',"
                                . "stato='In Attesa di Controllo' "
                                . "WHERE stato = 'In Attesa di Controllo Automatico'"
                                . "AND id=".$richiesta_id_calendario;
                        $rs_00002_1 = $dblink->query($sql_00002_1)
                        if($rs_00002_1){
                                $ok = true;
                             break 3;
                        }
                    }
                }else{
                    if (DISPLAY_DEBUG) echo '<li style="color:red;">'.$i.') Nessun Record Trovato</li>';
                }
            }    
            break;
                */
            
        }
        if (DISPLAY_DEBUG) echo '<br></div><br>';
    }
    if (DISPLAY_DEBUG) echo '<br><br></div><br><br>';
    
    if(!$ok){
        $sql_00002_1 = "UPDATE calendario "
                . "SET  stato='In Attesa di Controllo', priorita='Alta' "
                . "WHERE stato = 'In Attesa di Controllo Automatico'"
                . "AND id=".$richiesta_id_calendario;
        $dblink->query($sql_00002_1);
        controllaRichiesteMultiple($richiesta_id_calendario);
    }
 
    $email_inviata = inviaEmailTemplate_Richiesta($richiesta_id_calendario, 'nuovaRichiesta');
    if($email_inviata){
        if (DISPLAY_DEBUG) echo '<li style="color: green;"> EMAIL INVIATA OK !</li>';
        //$log->log_all_errors('creaUtenteTotale -> utente creato correttamente [idProfessionista = '.$idProfessionista.']','OK');
    }else{
        if (DISPLAY_DEBUG) echo '<li style="color: RED;"> EMAIL NON INVIATA KO !</li>';
        //$log->log_all_errors('creaUtenteTotale -> utente NON creato [idProfessionista = '.$idProfessionista.']','ERRORE');
    }
}

//INSERISE LE MAIL DEI PROFESSIONISTI NELLA LISTA INDIRIZZI MAIL
$sql_indirizzi = "INSERT INTO  `lista_indirizzi_email` (`id`, `dataagg`, `scrittore`, `stato`, `id_professionista`, `id_azienda`, `email`)SELECT '', NOW(), 'autoNuovaRichiestaControllo', 'Attivo', `id_professionista`, `id_azienda`, `campo_5` FROM calendario WHERE `campo_5` LIKE '%@%' AND ( `id_professionista`>0 OR `id_azienda`>0) AND `campo_5` NOT IN (SELECT DISTINCT email FROM `lista_indirizzi_email`) ON DUPLICATE KEY UPDATE lista_indirizzi_email.email=calendario.campo_5";
$dblink->query($sql_indirizzi);

$sql_telefono = "INSERT INTO  `lista_numeri_telefono` (`id`, `dataagg`, `scrittore`, `stato`, `id_professionista`, `id_azienda`, `telefono`)SELECT '', NOW(), 'autoNuovaRichiestaControllo', 'Attivo', `id_professionista`, `id_azienda`, `campo_4` FROM calendario WHERE length(`campo_4`)>5 AND ( `id_professionista`>0 OR `id_azienda`>0) AND `campo_4` NOT IN (SELECT DISTINCT telefono FROM `lista_numeri_telefono`) ON DUPLICATE KEY UPDATE lista_numeri_telefono.telefono=calendario.campo_4";
$dblink->query($sql_telefono);

?>
