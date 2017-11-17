<?php
ini_set('max_execution_time', 600); //10 minuti
ini_set('memory_limit', '2048M'); // 2 Giga
ob_start();
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/config/connDB.php');
include_once(BASE_ROOT.'libreria/libreria.php');
include_once(BASE_ROOT.'classi/webservice/client.php');

$referer = recupera_referer();

$step = $_GET['step'];

echo '<li>DB_HOST = '.DB_HOST.'</li>';
echo '<li>DB_USER = '.DB_USER.'</li>';
echo '<li>DB_PASS = '.DB_PASS.'</li>';
echo '<li>DB_NAME = '.DB_NAME.'</li>';

$moodle = new moodleWebService();

echo '<li>'.date('Y-m-d H:i:s').'</li>';

switch ($step) {
    
    default:
        $processati = 0;
        $sql_lista_attivazioni_manuale = "SELECT *, 
        IF(prodotto LIKE 'ABBONAMENTO',1,0) AS controlloAbbonamento,
        UNIX_TIMESTAMP(data_scadenza) AS data_scadenza_corso_timestamp
        FROM lista_attivazioni_manuale WHERE id_utente_moodle <=0
        AND stato='Importato' GROUP BY email, codice_prodotto";
        //$sql_lista_attivazioni_manuale = "SELECT * FROM lista_attivazioni_manuale WHERE id_utente_moodle >0 LIMIT 2";
        $rs_lista_attivazioni_manuale = $dblink->get_results($sql_lista_attivazioni_manuale);
        foreach ($rs_lista_attivazioni_manuale AS $row_lista_attivazioni_manuale){

            //PER OGNI RIGA TRAMITE EMAIL VADO A CERCARE UTENTE IN MOODLE
            echo '<h1>email = '.$row_lista_attivazioni_manuale['email'].'</h1>';
            echo '<br>'.$id_lista_attivazioni_manuale = $row_lista_attivazioni_manuale['id'];
            echo '<br>'.$username = $row_lista_attivazioni_manuale['email'];
            echo '<br>'.$email = $row_lista_attivazioni_manuale['email'];
            echo '<br>'.$firstname = $row_lista_attivazioni_manuale['nome'];
            echo '<br>'.$lastname = ''.$row_lista_attivazioni_manuale['azienda'];
            echo '<br>'.$data_scadenza = ''.$row_lista_attivazioni_manuale['data_scadenza'];
            echo '<br>'.$controlloAbbonamento = ''.$row_lista_attivazioni_manuale['controlloAbbonamento'];
            echo '<br>'.$data_scadenza_corso_timestamp = ''.$row_lista_attivazioni_manuale['data_scadenza_corso_timestamp'];
            echo '<br>'.$nomeClasse = ''.$row_lista_attivazioni_manuale['classe'];
            echo '<br>'.$id_classe = $row_lista_attivazioni_manuale['id_classe'];
            echo '<br>'.$commerciale = $row_lista_attivazioni_manuale['commerciale'];
            echo '<br>'.$data_ordine = $row_lista_attivazioni_manuale['data_ordine'];
            echo '<br>'.$tipo_marketing = $row_lista_attivazioni_manuale['tipo_marketing'];
            echo '<br>'.$id_prodotto = $row_lista_attivazioni_manuale['id_prodotto'];
            echo '<br>'.$sezionale = "CN".$row_lista_attivazioni_manuale['sezionale'];


            $rowProfessionistaMail = $dblink->get_row("SELECT id_professionista AS idProfessionista FROM lista_indirizzi_email WHERE LCASE(email) = LCASE('$email') ",true);
            if(empty($rowProfessionistaMail)){
                $rowProfessionista = $dblink->get_row("SELECT id AS idProfessionista FROM lista_professionisti WHERE LCASE(email) = LCASE('$email') ",true);
                $idProfessionista = $rowProfessionista['idProfessionista'];
            }else{
                $idProfessionista = $rowProfessionistaMail['idProfessionista'];
            }


            echo '<br>$idProfessionista = '.$idProfessionista;

            if($idProfessionista<=0){
                
                /*$sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_attivazioni_manuale 
                    SET stato = 'Importato MAIL-USERNAME - Da Verificare'
                    WHERE id = '".$id_lista_attivazioni_manuale."'
                    AND stato='Importato'";
                    $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);*/

                echo "AVVISO: ".'autoAttivaUtentiMoodleManuale.php -> Impossibile trovare ID PROFESSIONISTA EMAIL o USERNAME [email = '.$email.']'."<br>";
                $log->log_all_errors('autoAttivaUtentiMoodleManuale.php -> Impossibile trovare ID PROFESSIONISTA EMAIL o USERNAME [email = '.$email.']','AVVISO');
                /*$idProfessionista = 0;*/
                $insetProf = array(
                    "dataagg" => date("Y-m-d H:i:s"),
                    "scrittore" => "autoAttivaUtentiMoodleManuale",
                    "nome" => $dblink->filter($firstname),
                    "cognome" => $dblink->filter($lastname),
                    "email" => strtolower($email),
                    "id_classe" => $id_classe,
                    "codice_fiscale" => strtolower($email),
                    "id_moodle_user" => "0"
                );
                $ok = $dblink->insert("lista_professionisti",$insetProf);
                $idProfessionista = $dblink->lastid();
                if($ok){
                    $sql_0006 = "UPDATE lista_professionisti
                                SET codice = CONCAT('".SUFFISSO_CODICE_CLIENTE."',RIGHT(concat('0000000000',id),6)) 
                                WHERE codice NOT LIKE '".SUFFISSO_CODICE_CLIENTE."'";
                    $dblink->query($sql_0006);
                    echo "<li>Professionsita creato ID: ($idProfessionista)</li>";
                }
            }else{
                $updateProf = array(
                    "dataagg" => date("Y-m-d H:i:s"),
                    "scrittore" => "autoAttivaUtentiMoodleManuale",
                    "id_classe" => $id_classe,
                );
                $ok = $dblink->update("lista_professionisti",$updateProf, array("id"=>$idProfessionista));
                
                $updatePass = array(
                    "dataagg" => date("Y-m-d H:i:s"),
                    "scrittore" => "autoAttivaUtentiMoodleManuale",
                    "id_classe" => $id_classe,
                );
                $ok = $dblink->update("lista_password",$updatePass, array("id_professionista"=>$idProfessionista));
                
                $sql_0006 = "UPDATE lista_professionisti
                            SET codice = CONCAT('".SUFFISSO_CODICE_CLIENTE."',RIGHT(concat('0000000000',id),6)) 
                            WHERE codice NOT LIKE '".SUFFISSO_CODICE_CLIENTE."'";
                $dblink->query($sql_0006);
            }

            
            
            if($idProfessionista>0){
                
                $rowProfessionista = $dblink->get_row("SELECT * FROM lista_professionisti WHERE id = '$idProfessionista' ",true);
                //echo '<br>idUtenteMoodle = '.$idUtenteMoodle = $rowProfessionista['id_moodle_user'];
                if($controlloAbbonamento>0){
                    if($id_classe == $rowProfessionista['id_classe']){

                    }else{
                        $sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_attivazioni_manuale 
                        SET 
                        stato = 'CLASSE ERRATA - RINNOVO'
                        WHERE id = '".$id_lista_attivazioni_manuale."'
                        AND stato='Importato'";
                        $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);

                        echo "ERRORE: ".'autoAttivaUtentiMoodleManuale.php -> Classe ID PROFESSIONISTA errata [idProfessionista = '.$idProfessionista.'] [classeMoodle = '.$rowProfessionista['id_classe'].'] [classeImport = '.$id_classe.']'."<br>";
                        $log->log_all_errors('autoAttivaUtentiMoodleManuale.php -> Classe ID PROFESSIONISTA errata [idProfessionista = '.$idProfessionista.'] [classeMoodle = '.$rowProfessionista['id_classe'].'] [classeImport = '.$id_classe.']','ERRORE');

                        $idProfessionista = 0;
                    }
                }
            }
            
            if($idProfessionista>0){
                $rowCampagna = $dblink->get_row("SELECT id, nome, id_prodotto FROM lista_campagne WHERE id_prodotto = '".$id_prodotto."'", true);
                $rowMarketing = $dblink->get_row("SELECT id, nome FROM lista_tipo_marketing WHERE LCASE(nome) = LCASE('".$tipo_marketing."')", true);

                $tmpData = explode("-",$data_ordine);

                $insert = array(
                    "dataagg" => date("Y-m-d H:i:s"),
                    "scrittore" => $dblink->filter("autoAttivaUtentiMoodleManuale"),
                    "datainsert" => $data_ordine,
                    "orainsert" => date("H:i:s"),
                    "data" => $data_ordine,
                    "ora" => date("H:i:s"),
                    "etichetta" => 'Nuova Richiesta',
                    "oggetto" => $dblink->filter($rowCampagna['nome']),
                    "messaggio" => "IMPORTAZIONE MANUALE DA TUSTENA ID ($id_lista_attivazioni_manuale)",
                    "mittente" => $dblink->filter($firstname),
                    "destinatario" => $dblink->filter($commerciale),
                    "priorita" => "Alta",
                    "stato" => "In Attesa di Venduto",
                    "tipo_marketing" => $dblink->filter(strtoupper($rowMarketing['nome'])),
                    "id_campagna" => $rowCampagna['id'],
                    "id_prodotto" => $id_prodotto,
                    "id_classe" => $id_classe,
                    "id_agente" => 7,
                    "id_professionista" => $idProfessionista,
                    "id_tipo_marketing" => $rowMarketing['id'],
                    "giorno" => $tmpData[2],
                    "mese" => $tmpData[1],
                    "anno" => $tmpData[0],
                    "campo_5" => $dblink->filter($email),
                    "campo_6" => $dblink->filter($rowMarketing['nome']),
                    "campo_7" => $dblink->filter($rowCampagna['nome']),
                    "email" => $dblink->filter($email),
                    "notifica_email" => "Si",
                    "notifica_sms" => "No"
                );

                $ok = true;
                $ok = $ok && $dblink->insert("calendario", $insert);
                $idCalendario = $dblink->lastid();
                $idCalendario_daPassare = $idCalendario;
                
                echo "CALENDARIO: ". $dblink->get_query();
                echo "<br>";

                //$codicePrev = nuovoCodicePreventivoWeb($sezionale);

                $sql_0013 = "INSERT INTO lista_preventivi (id, dataagg, data_creazione, data_scadenza, codice, sezionale, `id_professionista`, `id_azienda`, `id_campagna`, `id_calendario`, `imponibile`, `importo`,  `scrittore`, `id_agente`, `stato`, note)
                                SELECT '', NOW(), '$data_ordine', DATE_ADD('$data_ordine', INTERVAL 10 DAY), 'xxx', '$sezionale', `id_professionista`, `id_azienda`, `id_campagna`, `id`, '','', '".addslashes($commerciale)."', `id_agente`, 'In Attesa', messaggio
                                FROM calendario WHERE id=".$idCalendario_daPassare." AND calendario.etichetta='Nuova Richiesta' AND calendario.id_professionista>=0";
                $rs_00013 = $dblink->query($sql_0013, true);
                $idAutoPreventivoCampagna = $dblink->lastid();
                echo "PREVENTIVO: ". $dblink->get_query();
                echo "<br>";

                echo '<h1>idAutoPreventivoCampagna = '.$idAutoPreventivoCampagna.'</h1>';

                $sql_0014 = "INSERT INTO lista_preventivi_dettaglio (id, dataagg, id_preventivo, id_prodotto, quantita, id_campagna, id_calendario, scrittore, stato, id_professionista, sezionale)
                            SELECT '', NOW(), '".$idAutoPreventivoCampagna."',  id_prodotto, '1', id_campagna, id, '".addslashes($commerciale)."', 'In Attesa', id_professionista, '$sezionale'
                            FROM calendario WHERE id=".$idCalendario_daPassare." AND calendario.etichetta='Nuova Richiesta'";
                $rs_00014 = $dblink->query($sql_0014, true);

                echo "PREVENTIVO DETTAGLIO: ". $dblink->get_query();
                echo "<br>";
                
                $sql_00015 = "UPDATE lista_preventivi_dettaglio, lista_prodotti
                            SET lista_preventivi_dettaglio.nome_prodotto = lista_prodotti.nome,
                           lista_preventivi_dettaglio.prezzo_prodotto = lista_prodotti.prezzo_pubblico,
                           lista_preventivi_dettaglio.codice_prodotto = lista_prodotti.codice,
                           lista_preventivi_dettaglio.iva_prodotto = lista_prodotti.iva
                            WHERE LENGTH(lista_preventivi_dettaglio.nome_prodotto)<=1
                            AND lista_preventivi_dettaglio.id_prodotto = lista_prodotti.id
                            AND lista_preventivi_dettaglio.id_preventivo = '$idAutoPreventivoCampagna'";
                            $rs_000015 = $dblink->query($sql_00015, true);

                $sql_000100 = "SELECT SUM((prezzo_prodotto*quantita)) AS imponibile, SUM((prezzo_prodotto*(1+(iva_prodotto/100)))*quantita) AS 'importo' FROM lista_preventivi_dettaglio WHERE id_preventivo=".$idAutoPreventivoCampagna;
                $row_000100 = $dblink->get_row($sql_000100, true);
                echo $dblink->get_query();
                echo "<br>";
                $updatePreventivo=array(
                    "importo"=>$row_000100['importo'],
                    "imponibile"=>$row_000100['imponibile']
                );

                $dblink->update("lista_preventivi", $updatePreventivo, array("id"=>$idAutoPreventivoCampagna));

                echo "UPDATE TOTALI PREVENTIVO: ". $dblink->get_query();
                echo "<br>";
                
                $sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_attivazioni_manuale 
                SET dataagg = NOW(),
                id_calendario = '".$idCalendario."',
                id_preventivo = '".$idAutoPreventivoCampagna."',
                stato = 'In Attesa di Venduto'
                WHERE id = '".$id_lista_attivazioni_manuale."'
                AND stato='Importato'";
                $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);
                
                echo "UPDATE LISTA UTENTI MANUALI: ". $dblink->get_query();
                echo "<br>";
                $processati++;
            }
        }
        
        echo '<meta http-equiv="refresh" content="1; url='.BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=1".'" ><h3>Processati: '.$processati.'</h3>';
        //header("Location:".BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=1");
        
    break;
    
    case 1:
        echo "<h1>STEP - 1</h1>";
        $count = $_SESSION['processati_import']>0 ? $_SESSION['processati_import'] : 0;
        $sql_lista_attivazioni_manuale = "SELECT *, 
        IF(prodotto LIKE 'ABBONAMENTO',1,0) AS controlloAbbonamento,
        UNIX_TIMESTAMP(data_scadenza) AS data_scadenza_corso_timestamp
        FROM lista_attivazioni_manuale WHERE id_utente_moodle <=0 
        AND stato='In Attesa di Venduto' GROUP BY email, codice_prodotto LIMIT 1";
        //$sql_lista_attivazioni_manuale = "SELECT * FROM lista_attivazioni_manuale WHERE id_utente_moodle >0 LIMIT 2";
        $rs_lista_attivazioni_manuale = $dblink->get_results($sql_lista_attivazioni_manuale);
        if(!empty($rs_lista_attivazioni_manuale)){
            foreach ($rs_lista_attivazioni_manuale AS $row_lista_attivazioni_manuale){
                $id_lista_attivazioni_manuale = $row_lista_attivazioni_manuale['id'];
                $idCalendario = $row_lista_attivazioni_manuale['id_calendario'];
                $idPreventivo = $row_lista_attivazioni_manuale['id_preventivo'];
                $email = $row_lista_attivazioni_manuale['email'];

                $sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_attivazioni_manuale 
                SET dataagg = NOW(),
                stato = 'In Attesa di Chiuso'
                WHERE id = '".$id_lista_attivazioni_manuale."'
                AND stato='In Attesa di Venduto'";
                $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);
                
            }
            
            $count++;
            
            echo "<li>$count - Processo: $email</li><br>";
            $_SESSION['NOSTRO_HTTP_REFERER'] = BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=1";
            $_SESSION['processati_import'] = $count;
            echo ' <meta http-equiv="refresh" content="1; url='.BASE_URL."/moduli/anagrafiche/salva.php?idPreventivo=$idPreventivo&idCalendario=$idCalendario&fn=preventivoVenduto".'" >';
        
            //header("Location:".BASE_URL."/moduli/anagrafiche/salva.php?idPreventivo=$idPreventivo&idCalendario=$idCalendario&fn=preventivoVenduto");
        //
        }else{
            echo "HO FINITO";
            echo ' <meta http-equiv="refresh" content="1; url='.BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=2".'" ><h3>Processati: '.$_SESSION['processati_import'].'</h3>';
            //header("Location:".BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=2");
        }
    break;
    
    case 2:
        echo "<h1>STEP - 2</h1>";
        $count = $_SESSION['processati_import_step_2']>0 ? $_SESSION['processati_import_step_2'] : 0;
        $sql_lista_attivazioni_manuale = "SELECT *, 
        IF(prodotto LIKE 'ABBONAMENTO',1,0) AS controlloAbbonamento,
        UNIX_TIMESTAMP(data_scadenza) AS data_scadenza_corso_timestamp
        FROM lista_attivazioni_manuale WHERE id_utente_moodle <=0 
        AND stato='In Attesa di Chiuso' GROUP BY email, codice_prodotto LIMIT 1";
        //$sql_lista_attivazioni_manuale = "SELECT * FROM lista_attivazioni_manuale WHERE id_utente_moodle >0 LIMIT 2";
        $rs_lista_attivazioni_manuale = $dblink->get_results($sql_lista_attivazioni_manuale);
        if(!empty($rs_lista_attivazioni_manuale)){
            foreach ($rs_lista_attivazioni_manuale AS $row_lista_attivazioni_manuale){
                $id_lista_attivazioni_manuale = $row_lista_attivazioni_manuale['id'];
                $idCalendario = $row_lista_attivazioni_manuale['id_calendario'];
                $idPreventivo = $row_lista_attivazioni_manuale['id_preventivo'];
                $sezionale = "CN".$row_lista_attivazioni_manuale['sezionale'];
                $email = $row_lista_attivazioni_manuale['email'];

                $sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_attivazioni_manuale 
                SET dataagg = NOW(),
                stato = 'In Attesa di Moodle'
                WHERE id = '".$id_lista_attivazioni_manuale."'
                AND stato='In Attesa di Chiuso'";
                $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);
                
            }
            $count++;
            echo "<li>$count - Processo: $email</li><br>";
            $_SESSION['NOSTRO_HTTP_REFERER'] = BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=2";
            $_SESSION['processati_import_step_2'] = $count;
            echo ' <meta http-equiv="refresh" content="1; url='.BASE_URL."/moduli/preventivi/salva.php?tbl=lista_preventivi&idPreventivoFirmato=$idPreventivo&codSezionale=$sezionale&fn=preventivoFirmato".'" >';
            //header("Location:".BASE_URL."/moduli/preventivi/salva.php?tbl=lista_preventivi&idPreventivoFirmato=$idPreventivo&codSezionale=$sezionale&fn=preventivoFirmato");
        }else{
            echo "HO FINITO";
            echo ' <meta http-equiv="refresh" content="1; url='.BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=3".'" ><h3>Processati: '.$_SESSION['processati_import'].'</h3>';
            //header("Location:".BASE_URL."/libreria/automazioni/autoAttivaUtentiMoodleManuale.php?step=2");
        }
    break;
    
    case 3:
        unset($_SESSION['processati_import']);
        unset($_SESSION['processati_import_step_2']);
        $processati=0;
        $sql_lista_attivazioni_manuale = "SELECT *, 
        IF(prodotto LIKE 'ABBONAMENTO',1,0) AS controlloAbbonamento,
        UNIX_TIMESTAMP(data_scadenza) AS data_scadenza_corso_timestamp
        FROM lista_attivazioni_manuale WHERE id_utente_moodle <=0 
        AND stato='In Attesa di Moodle' GROUP BY email, codice_prodotto";
        //$sql_lista_attivazioni_manuale = "SELECT * FROM lista_attivazioni_manuale WHERE id_utente_moodle >0 LIMIT 2";
        $rs_lista_attivazioni_manuale = $dblink->get_results($sql_lista_attivazioni_manuale);
        if(!empty($rs_lista_attivazioni_manuale)){
            foreach ($rs_lista_attivazioni_manuale AS $row_lista_attivazioni_manuale){
                $id_lista_attivazioni_manuale = $row_lista_attivazioni_manuale['id'];
                $idCalendario = $row_lista_attivazioni_manuale['id_calendario'];
                $idPreventivo = $row_lista_attivazioni_manuale['id_preventivo'];
                $sezionale = "CN".$row_lista_attivazioni_manuale['sezionale'];
                $email = $row_lista_attivazioni_manuale['email'];
                $data_scadenza = ''.$row_lista_attivazioni_manuale['data_scadenza'];
                $data_creazione = ''.$row_lista_attivazioni_manuale['data_creazione'];
                
                $rowFattura = $dblink->get_row("SELECT id FROM lista_fatture WHERE id_preventivo='$idPreventivo'",true);
                
                
                $password = generaPassword(9); 

                
                $rowProfessionista = $dblink->get_row("SELECT id_professionista FROM calendario WHERE id='$idCalendario'",true);
                $rowCodice = $dblink->get_row("SELECT codice FROM lista_professionisti WHERE id='".$rowProfessionista['id_professionista']."'",true);
                
                echo "<li>".($processati+1)." - Processo: $email</li><br>";
                echo "<li>".($processati+1)." - CODICE: ".$rowCodice['codice']."</li><br>";

                $sql_cerca_in_lista_password = "SELECT DISTINCT * FROM lista_password WHERE id_professionista = '".$rowProfessionista['id_professionista']."'";
                $row_cerca_in_lista_password = $dblink->get_row($sql_cerca_in_lista_password,true);

                if(!empty($row_cerca_in_lista_password)){
                    
                    resetPasswordUtenteMoodle($row_cerca_in_lista_password['id_professionista'], true);
                    
                    echo '<li style="color:;">Aggiorniamo Password ! </li>';
                    //echo '<br>'.$password = $row_cerca_in_lista_password['passwd'];
                     echo '<br>'.$id_utente_moodle = $row_cerca_in_lista_password['id_moodle_user'];
                    
                    $idUtenteMoodle = $id_utente_moodle;

                }else{
                    $sql_00001 = "INSERT INTO `lista_password` (`id`, `dataagg`, `scrittore`, id_professionista, `id_classe`, `livello`, `nome`, `cognome`, `username`, `passwd`,  `email`, `stato`, `data_creazione`, `data_scadenza`)
                    SELECT DISTINCT '', NOW(), 'autoAttivaUtentiMoodleManuale', '".$rowProfessionista['id_professionista']."', `id_classe`, 'cliente', `nome`, `azienda`, '".strtolower($rowCodice['codice'])."', '".$password."', `email`, 'In Attesa di Moodle', data_creazione, data_scadenza FROM lista_attivazioni_manuale WHERE id=".$id_lista_attivazioni_manuale." LIMIT 1";
                    $ok = $dblink->query($sql_00001);
                    
                    $sql_cerca_in_lista_password = "SELECT DISTINCT * FROM lista_password WHERE id_professionista = '".$rowProfessionista['id_professionista']."'";
                    $row_cerca_in_lista_password = $dblink->get_row($sql_cerca_in_lista_password,true);
                    echo '<br>'.$username = $row_cerca_in_lista_password['username'];
                    echo '<br>'.$email = $row_cerca_in_lista_password['email'];
                    echo '<br>'.$firstname = $row_cerca_in_lista_password['nome'];
                    echo '<br>'.$lastname = $row_cerca_in_lista_password['cognome'];
                    echo '<br>'.$idnumber = $row_cerca_in_lista_password['id_professionista'];
                    echo '<br>'.$password = $row_cerca_in_lista_password['passwd'];

                    $idUtenteMoodle = $moodle->creaUtenteMoodle(strtolower($username), $email, $firstname, $lastname, $password, $idnumber);
                    
                }
                
                if($idUtenteMoodle>0){
                    $sqlpassword = "SELECT * FROM lista_password WHERE id_professionista = '".$rowProfessionista['id_professionista']."'";
                    $rowpassword = $dblink->get_row($sqlpassword,true);
                    $password = $rowpassword['passwd'];
                    echo '<li style="color: green;"> OK !</li>';
                    $sql_aggiorna_lista_attivazioni_manuale = "UPDATE lista_attivazioni_manuale 
                    SET id_utente_moodle = '".$idUtenteMoodle."' , 
                    stato = 'Attivo',
                    id_fattura = '".$rowFattura['id']."',
                    dataagg = NOW(),
                    password = '".$password."'
                    WHERE id = '".$id_lista_attivazioni_manuale."'
                    AND stato='In Attesa di Moodle'";
                    $ok = $dblink->query($sql_aggiorna_lista_attivazioni_manuale);
                    
                    $sql_aggiorna_password = "UPDATE lista_password 
                    SET id_moodle_user = '".$idUtenteMoodle."' , 
                    dataagg = NOW(),
                    stato = 'Attivo - Inviare Password'
                    WHERE id_professionista = '".$rowProfessionista['id_professionista']."'";
                    $ok = $dblink->query($sql_aggiorna_password);
                    $processati++;
                    $log->log_all_errors('autoAttivaUtentiMoodleManuale.php -> utente creato correttamente [idUtenteMoodle = '.$idUtenteMoodle.']','OK');
                }else{
                    echo '<li style="color: RED;"> KO !</li>';
                    $log->log_all_errors('autoAttivaUtentiMoodleManuale.php -> utente NON creato [email = '.$email.']','ERRORE');
                }
                
                echo '<hr>';
            }
        }
        
        //echo ' <meta http-equiv="refresh" content="1; url='.BASE_URL."/moduli/fatture/index.php?tbl=lista_attivazioni&idMenu=100".'" ><h3>Processati: '.$processati.'</h3>';
        //echo ' <meta http-equiv="refresh" content="2; url='.BASE_URL."/libreria/automazioni/autoAttivaCorsiAbbonamenti.php".'" ><h3>Processati: '.$processati.'</h3>';
        //header("Location:".BASE_URL."/libreria/automazioni/autoAttivaCorsiAbbonamenti.php");
        
    break;
    
}
?>