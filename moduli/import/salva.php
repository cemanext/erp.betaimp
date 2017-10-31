<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        
        case "importRichieste":
            
            $filename=time().'_'.$_FILES['importRichieste']['name'];
            
            if(!is_dir(BASE_ROOT . "media/import_richieste")){
                mkdir(BASE_ROOT . "media/import_richieste", 0777);
            }

            $path= BASE_ROOT.'media/import_richieste/'.$filename;

            if(move_uploaded_file($_FILES['importRichieste']['tmp_name'],$path)) {
                
                $fileName = $_FILES['importRichieste']['name'];
                $countOK = 0;
                $countKO = 0;
                $countSkip = 0;
                $row = 0;
                $headers = [];
                $filepath = $path;
                if (($handle = fopen($filepath, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
                        if (++$row == 1) {
                          //$headers = array_flip(str_replace(" ","_", str_replace("(","",str_replace(")","",$data)))); // Get the column names from the header.
                          //print_r($headers);
                          continue;
                        } else {
                          $row = explode(";",$data[0]);
                          if(strlen(trim($row[2]))>0 && count($row)>=12){
                              /*echo "<pre>";
                              print_r($row);
                              echo "</pre>";
                              die;*/
                              
                            if(strlen($row[0])>0){
                                $idCampagna = $dblink->get_row("SELECT id FROM lista_campagne WHERE LCASE(nome) LIKE LCASE('".$row[0]."')", true);
                            }else{
                                $idCampagna['id'] = 0;
                            }
                            
                            if(strlen($row[1])>0){
                                $idTipoMarketing = $dblink->get_row("SELECT id FROM lista_tipo_marketing WHERE LCASE(nome) LIKE LCASE('".$row[1]."')", true);
                            }else{
                                $idTipoMarketing['id'] = 0;
                            }
                              
                            if(strlen($row[9])>0){
                                $idClasse = $dblink->get_row("SELECT id FROM lista_classi WHERE LCASE(nome) LIKE LCASE('".$row[10]."')", true);
                            }else{
                                $idClasse['id'] = 0;
                            }
                            
                            if(strlen($row[8])>0){
                                $idProdotto = $dblink->get_row("SELECT id, codice, codice_esterno AS id_prod_moodle FROM lista_prodotti WHERE LCASE(codice) = LCASE('".trim($row[8])."')", true);
                                $nomeProdotto = $dblink->filter($row[8]);
                            }else{
                                $idProdotto['id'] = 0;
                                $idProdotto['codice'] = "";
                                $nomeProdotto = "";
                            }
                            
                            /*if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim($row[10])) && trim($row[10])!="0000-00-00") {
                                $dataInsert = true;
                            } else {
                                $dataInsert = false;
                            }*/
                            
                            if(strlen($row[0]) > 0 && strlen($row[1]) > 0 && strlen($row[2]) > 0 && strlen($row[3]) > 0 && strlen($row[10]) > 0 && strlen($row[11]) > 0){
                                $insert = array(
                                  "dataagg" => date("Y-m-d H:i:s"),
                                  "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                                  "stato" => "Importato",
                                  "id_campagna" => $idCampagna['id'],
                                  "nome_campagna" => $dblink->filter($row[0]),
                                  "id_tipo_marketing" => $idTipoMarketing['id'],
                                  "tipo_marketing" => $dblink->filter($row[1]),
                                  "cognome" => $dblink->filter($row[2]),
                                  "nome" => $dblink->filter($row[3]),
                                  "professione" => $dblink->filter($row[4]),
                                  "telefono" => preg_replace("/[^0-9]/", "", $row[5]),
                                  "email" => $dblink->filter($row[6]),
                                  "citta" => $dblink->filter($row[7]),
                                  "classe" => $dblink->filter($row[9]),
                                  "id_classe" => $idClasse['id'],
                                  "id_prodotto" => $idProdotto['id'],
                                  "codice_prodotto" => strtolower($idProdotto['codice']),
                                  "prodotto" => $nomeProdotto,
                                  "data_richiesta" => $row[10],
                                  "data_importazione" => date('Y-m-d'),
                                  "messaggio" => trim(stripslashes($row[11])),
                                  "nome_file" => $fileName,
                                );
                                
                                /*echo "<pre>";
                                print_r($insert);
                                echo "</pre>";
                                die;*/

                                $ok = $dblink->insert("lista_importazione_richieste", $insert);
                                if($ok) $countOK ++;
                                else $countKO ++;
                            }else{
                                $insert = array(
                                  "dataagg" => date("Y-m-d H:i:s"),
                                  "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                                  "stato" => "CAMPI OBBLIGATORI MANCANTI",
                                  "id_campagna" => $idCampagna['id'],
                                  "nome_campagna" => $dblink->filter($row[0]),
                                  "id_tipo_marketing" => $idTipoMarketing['id'],
                                  "tipo_marketing" => $dblink->filter($row[1]),
                                  "cognome" => $dblink->filter($row[2]),
                                  "nome" => $dblink->filter($row[3]),
                                  "professione" => $dblink->filter($row[4]),
                                  "telefono" => preg_replace("/[^0-9]/", "", $row[5]),
                                  "email" => $dblink->filter($row[6]),
                                  "citta" => $dblink->filter($row[7]),
                                  "classe" => $dblink->filter($row[9]),
                                  "id_classe" => $idClasse['id'],
                                  "id_prodotto" => $idProdotto['id'],
                                  "codice_prodotto" => strtolower($idProdotto['codice']),
                                  "prodotto" => $nomeProdotto,
                                  "data_richiesta" => $row[10],
                                  "data_importazione" => date('Y-m-d'),
                                  "messaggio" => trim(stripslashes($row[11])),
                                  "nome_file" => $fileName,
                                );

                                $ok = $dblink->insert("lista_importazione_richieste", $insert);
                                if($ok) $countOK ++;
                                else $countKO ++;
                            }
                          }else{
                              
                              if(strlen($row[0])>0){
                                $idCampagna = $dblink->get_row("SELECT id FROM lista_campagne WHERE LCASE(nome) LIKE LCASE('".$row[0]."')", true);
                            }else{
                                $idCampagna['id'] = 0;
                            }
                            
                            if(strlen($row[1])>0){
                                $idTipoMarketing = $dblink->get_row("SELECT id FROM lista_tipo_marketing WHERE LCASE(nome) LIKE LCASE('".$row[1]."')", true);
                            }else{
                                $idTipoMarketing['id'] = 0;
                            }
                              
                            if(strlen($row[9])>0){
                                $idClasse = $dblink->get_row("SELECT id FROM lista_classi WHERE LCASE(nome) LIKE LCASE('".$row[10]."')", true);
                            }else{
                                $idClasse['id'] = 0;
                            }
                            
                            if(strlen($row[8])>0){
                                $idProdotto = $dblink->get_row("SELECT id, codice, codice_esterno AS id_prod_moodle FROM lista_prodotti WHERE LCASE(codice) = LCASE('".trim($row[8])."')", true);
                                $nomeProdotto = $dblink->filter($row[8]);
                            }else{
                                $idProdotto['id'] = 0;
                                $idProdotto['codice'] = "";
                                $nomeProdotto = "";
                            }
                              
                            $insert = array(
                                "dataagg" => date("Y-m-d H:i:s"),
                                "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                                "stato" => "RIGA SALTATA - PER COLONNE MANCANTI",
                                "id_campagna" => $idCampagna['id'],
                                "nome_campagna" => $dblink->filter($row[0]),
                                "id_tipo_marketing" => $idTipoMarketing['id'],
                                "tipo_marketing" => $dblink->filter($row[1]),
                                "cognome" => $dblink->filter($row[2]),
                                "nome" => $dblink->filter($row[3]),
                                "professione" => $dblink->filter($row[4]),
                                "telefono" => preg_replace("/[^0-9]/", "", $row[5]),
                                "email" => $dblink->filter($row[6]),
                                "citta" => $dblink->filter($row[7]),
                                "classe" => $dblink->filter($row[9]),
                                "id_classe" => $idClasse['id'],
                                "id_prodotto" => $idProdotto['id'],
                                "codice_prodotto" => strtolower($idProdotto['codice']),
                                "prodotto" => $nomeProdotto,
                                "data_richiesta" => $row[10],
                                "data_importazione" => date('Y-m-d'),
                                "messaggio" => trim(stripslashes($row[11])),
                                "nome_file" => $fileName,
                              );

                              $ok = $dblink->insert("lista_importazione_richieste", $insert);
                            $countSkip++;
                          }
                          /*echo "<pre>";
                          print_r($insert);
                          echo "</pre>";*/
                        }
                    }
                    fclose($handle);
                }
                
                $log->log_all_errors("IMPORTAZIONE FILE ($fileName) - RIGHE IMPORTATE: $countOK - RIGHE CON ERRORI: $countKO - RIGHE SALTATE: $countSkip","OK");
                
                //unlink($path);
                
                /*$ok = $dblink->query("load data local infile '".$path."' INTO TABLE lista_attivazioni_manuale FIELDS TERMINATED BY ',' enclosed by '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES ( `azienda`, `nome`, `email`,  `note`, `numero_ordine`, `data_ordine`, `stato_ordine`, `commerciale`, `sezionale`, `tipo_marketing`, `classe`, `prodotto`, `ordine_omaggio`)");
                if($ok){
                    header("Location:$referer");
                }else{
                    echo "errore query";
                }*/
                header("Location:$referer&ok=$countOK&errore=$countKO&skip=$countSkip");
            }else{
                echo "FILE NON IMPORTATO";
            }
            
        break;
        
        case "importaSuCalendario":
            
            $importare = $dblink->get_results("SELECT * FROM lista_importazione_richieste WHERE stato LIKE 'Importato'");
            
            foreach ($importare as $riga) {
                
                if($riga['data_richiesta']!="0000-00-00"){
                    $explodeData = explode("-",$riga['data_richiesta']);

                    $insert = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                        "datainsert" => $riga['data_richiesta'],
                        "orainsert" => date("H:i:s"),
                        "data" => $riga['data_richiesta'],
                        "ora" => "10:00:00",
                        "etichetta" => 'Nuova Richiesta',
                        "oggetto" => $dblink->filter($riga['nome_campagna']),
                        "messaggio" => "Nome: ".$dblink->filter($riga['nome'])."\\nCognome: ".$dblink->filter($riga['cognome'])."\\nTelefono: ".$dblink->filter($riga['telefono'])."\\nE-Mail: ".$dblink->filter($riga['email'])."\\n\\nTipo Marketing: ".$dblink->filter($riga['tipo_marketing'])."\\nNome Campagna: ".$dblink->filter($riga['nome_campagna'])."\\n\\nMESSAGGIO\\n".$dblink->filter($riga['messaggio']),
                        "mittente" => $dblink->filter($riga['cognome'])." ".$dblink->filter($riga['nome']),
                        "destinatario" => "",
                        "priorita" => "Alta",
                        "stato" => "In Attesa di Controllo Automatico",
                        "tipo_marketing" => $dblink->filter(strtoupper($riga['tipo_marketing'])),
                        "id_campagna" => $riga['id_campagna'],
                        "id_prodotto" => $riga['id_prodotto'],
                        "id_tipo_marketing" => $riga['id_tipo_marketing'],
                        "giorno" => $explodeData[2],
                        "mese" => $explodeData[1],
                        "anno" => $explodeData[0],
                        "campo_1" => $dblink->filter($riga['nome']),
                        "campo_2" => $dblink->filter($riga['cognome']),
                        "campo_3" => "",
                        "campo_4" => $dblink->filter($riga['telefono']),
                        "campo_5" => $dblink->filter($riga['email']),
                        "campo_6" => $dblink->filter($riga['tipo_marketing']),
                        "campo_7" => $dblink->filter($riga['nome_campagna']),
                        "campo_8" => "",
                        "campo_9" => "",
                        "nome" => $dblink->filter($riga['nome']),
                        "cognome" => $dblink->filter($riga['cognome']),
                        "telefono" => $dblink->filter($riga['telefono']),
                        "email" => $dblink->filter($riga['email']),
                        "notifica_email" => "Si",
                        "notifica_sms" => "No"
                    );
                    
                    /*echo "<pre>";
                    print_r($insert);
                    echo "</pre>";
                    die;*/

                    $ok = true;
                    $ok = $ok && $dblink->insert("calendario", $insert);
                    $idCal = $dblink->lastid();

                    if($ok){
                        //echo "<li>riga['id'] = ".$riga['id']."</li>";
                        //echo "<li>idCal = $idCal</li>";
                        $dblink->update("lista_importazione_richieste",array("stato"=>"Caricata","id_calendario"=>$idCal),array("id"=>$riga['id']));
                        //echo $dblink->get_query(); echo "<br>";
                    }
                }else{
                    $dblink->update("lista_importazione_richieste",array("stato"=>"Errore Data"),array("id"=>$riga['id']));
                    //echo "SQL ELSE: ".$dblink->get_query(); echo "<br>";
                }
                
            }
            //die;
            if($ok){
                include_once(BASE_ROOT.'libreria/automazioni/autoCampagneStatistiche.php');
                include_once(BASE_ROOT.'libreria/automazioni/autoNuovaRichiestaControllo.php');


                header("Location:$referer&ret=1");
            }else{
                $log->log_all_errors("importa richieste da CSV -> Si è verificato un errore nella query non è stata inserita la richiesta.", "ERRORE");
                header("Location:$referer&ret=0");
            }
            
            
        break;    
        
        case "importAttivazioni":
            
            $filename=time().'_'.$_FILES['importAttivazioni']['name'];

            if(!is_dir(BASE_ROOT . "media/import")){
                mkdir(BASE_ROOT . "media/import", 0777);
            }
            
            $path= BASE_ROOT.'media/import/'.$filename;

            if(move_uploaded_file($_FILES['importAttivazioni']['tmp_name'],$path)) {
                
                $fileName = $_FILES['importAttivazioni']['name'];
                $countOK = 0;
                $countKO = 0;
                $countSkip = 0;
                $row = 0;
                $headers = [];
                $filepath = $path;
                if (($handle = fopen($filepath, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
                        if (++$row == 1) {
                          //$headers = array_flip(str_replace(" ","_", str_replace("(","",str_replace(")","",$data)))); // Get the column names from the header.
                          //print_r($headers);
                          continue;
                        } else {
                          $row = explode(";",$data[0]);
                          if(strlen(trim($row[2]))>0 && (count($row)>=15 || count($row)>=13)){
                              /*echo "<pre>";
                              print_r($row);
                              echo "</pre>";*/
                              $emails = array();
                              if(strpos($row[2],"|")){
                                  $emails = explode("|", $row[2]);
                              }
                              if(!empty($emails)){
                                  $email_sel = "";
                                  foreach ($emails as $email) {
                                      $numRowEmail =  $dblink->num_rows("SELECT id FROM lista_password WHERE LCASE(email) LIKE LCASE('".$dblink->filter(trim($email))."') ");
                                      
                                      if($numRowEmail>0){
                                          $email_sel = trim($email);
                                          break;
                                      }
                                  }
                                  if(strlen($email_sel)<=0){
                                      $row[2] = trim($emails[0]);
                                  }else{
                                      $row[2] = $email_sel;
                                  }
                                  
                              }
                              
                              if(strlen($row[2])>0){
                                  
                                $date = new DateTime();
                                $date = DateTime::createFromFormat("d/m/Y H:i", trim($row[5]));
                                $dateScadenza = new DateTime();
                                $dateScadenza = DateTime::createFromFormat("d/m/Y H:i", trim($row[5]));

                                $idClasse = $dblink->get_row("SELECT id FROM lista_classi WHERE LCASE(nome) LIKE LCASE('".$row[10]."')", true);
                                if(strtoupper($row[11])=="ABBONAMENTO"){
                                  $idProdotto = $dblink->get_row("SELECT id, codice, 0 AS id_prod_moodle FROM lista_prodotti WHERE LCASE(codice_esterno) = LCASE('abb_".$idClasse['id']."')", true);
                                  $nomeProdotto = $dblink->filter($row[11]);
                                  $dateScadenza->add(new DateInterval('P'.DURATA_ABBONAMENTO.'D'));
                                }else{
                                  $idProdotto = $dblink->get_row("SELECT id, codice, codice_esterno AS id_prod_moodle FROM lista_prodotti WHERE LCASE(codice) = LCASE('".trim($row[11])."')", true);
                                  $nomeProdotto = $dblink->filter($row[11]);
                                  $dateScadenza->add(new DateInterval('P'.DURATA_CORSO.'D'));
                                }

                                $rowListaAttivazioni = $dblink->get_row("SELECT id FROM lista_attivazioni_manuale WHERE LCASE(email) LIKE LCASE('".$dblink->filter($row[2])."') AND stato LIKE 'Importato' AND id_prodotto='".$idProdotto['id']."' ", true);

                                $insert = array(
                                  "dataagg" => date("Y-m-d H:i:s"),
                                  "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                                  "stato" => ($idProdotto>0 ? ($rowListaAttivazioni['id']>0 ? "Da Verificare - Doppio" : "Importato") : "Prodotto Non Trovato"),
                                  "azienda" => $dblink->filter($row[0]),
                                  "nome" => $dblink->filter($row[1]),
                                  "email" => $dblink->filter($row[2]),
                                  "numero_ordine" => $dblink->filter($row[4]),
                                  "data_ordine" => $date->format('Y-m-d'),
                                  "stato_ordine" => $dblink->filter($row[6]),
                                  "commerciale" => $dblink->filter($row[7]),
                                  "sezionale" => $dblink->filter($row[8]),
                                  "tipo_marketing" => $dblink->filter($row[9]),
                                  "classe" => $dblink->filter($row[10]),
                                  "id_classe" => $idClasse['id'],
                                  "id_prodotto" => $idProdotto['id'],
                                  "codice_prodotto" => strtolower($idProdotto['codice']),
                                  "prodotto" => $nomeProdotto,
                                  "ordine_omaggio" => $dblink->filter($row[12]),
                                  "data_creazione" => $date->format('Y-m-d'),
                                  "data_scadenza" => $dateScadenza->format('Y-m-d'),
                                  "id_corso_moodle" => $idProdotto['id_prod_moodle'],
                                  "nome_file" => $fileName,
                                );

                                $ok = $dblink->insert("lista_attivazioni_manuale", $insert);
                                if($ok) $countOK ++;
                                else $countKO ++;
                            }else{
                                $insert = array(
                                  "dataagg" => date("Y-m-d H:i:s"),
                                  "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                                  "stato" => "SALTATO",
                                  "azienda" => $dblink->filter($row[0]),
                                  "nome" => $dblink->filter($row[1]),
                                  "email" => $dblink->filter($row[2]),
                                  "numero_ordine" => $dblink->filter($row[4]),
                                  "data_ordine" => $date->format('Y-m-d'),
                                  "stato_ordine" => $dblink->filter($row[6]),
                                  "commerciale" => $dblink->filter($row[7]),
                                  "sezionale" => $dblink->filter($row[8]),
                                  "tipo_marketing" => $dblink->filter($row[9]),
                                  "classe" => $dblink->filter($row[10]),
                                  "id_classe" => $idClasse['id'],
                                  "id_prodotto" => $idProdotto['id'],
                                  "codice_prodotto" => strtolower($idProdotto['codice']),
                                  "prodotto" => $nomeProdotto,
                                  "ordine_omaggio" => $dblink->filter($row[12]),
                                  "data_creazione" => $date->format('Y-m-d'),
                                  "data_scadenza" => $dateScadenza->format('Y-m-d'),
                                  "id_corso_moodle" => $idProdotto['id_prod_moodle'],
                                  "nome_file" => $fileName,
                                );

                                $ok = $dblink->insert("lista_attivazioni_manuale", $insert);
                                $countSkip++;
                            }
                          }else{
                              $insert = array(
                                "dataagg" => date("Y-m-d H:i:s"),
                                "scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),
                                "stato" => "SALTATO",
                                "azienda" => $dblink->filter($row[0]),
                                "nome" => $dblink->filter($row[1]),
                                "email" => $dblink->filter($row[2]),
                                "numero_ordine" => $dblink->filter($row[4]),
                                "data_ordine" => $date->format('Y-m-d'),
                                "stato_ordine" => $dblink->filter($row[6]),
                                "commerciale" => $dblink->filter($row[7]),
                                "sezionale" => $dblink->filter($row[8]),
                                "tipo_marketing" => $dblink->filter($row[9]),
                                "classe" => $dblink->filter($row[10]),
                                "id_classe" => $idClasse['id'],
                                "id_prodotto" => $idProdotto['id'],
                                "codice_prodotto" => strtolower($idProdotto['codice']),
                                "prodotto" => $nomeProdotto,
                                "ordine_omaggio" => $dblink->filter($row[12]),
                                "data_creazione" => $date->format('Y-m-d'),
                                "data_scadenza" => $dateScadenza->format('Y-m-d'),
                                "id_corso_moodle" => $idProdotto['id_prod_moodle'],
                                "nome_file" => $fileName,
                              );

                              $ok = $dblink->insert("lista_attivazioni_manuale", $insert);
                              $countSkip++;
                          }
                          /*echo "<pre>";
                          print_r($insert);
                          echo "</pre>";*/
                        }
                    }
                    fclose($handle);
                }
                
                $log->log_all_errors("IMPORTAZIONE FILE ($fileName) - RIGHE IMPORTATE: $countOK - RIGHE CON ERRORI: $countKO - RIGHE SALTATE: $countSkip","OK");
                
                unlink($path);
                
                /*$ok = $dblink->query("load data local infile '".$path."' INTO TABLE lista_attivazioni_manuale FIELDS TERMINATED BY ',' enclosed by '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES ( `azienda`, `nome`, `email`,  `note`, `numero_ordine`, `data_ordine`, `stato_ordine`, `commerciale`, `sezionale`, `tipo_marketing`, `classe`, `prodotto`, `ordine_omaggio`)");
                if($ok){
                    header("Location:$referer");
                }else{
                    echo "errore query";
                }*/
                header("Location:$referer&ok=$countOK&errore=$countKO&skip=$countSkip");
            }else{
                echo "FILE NON IMPORTATO";
            }
            
        break;
        
        default:
        break;
    }
}


?>
