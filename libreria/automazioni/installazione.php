<?php
ini_set('max_execution_time', 600); //10 minuti
ini_set('memory_limit', '2048M'); // 2 Giga

ob_start();

include_once('../../config/connDB.php');
include_once(BASE_ROOT.'libreria/libreria.php');
include_once(BASE_ROOT.'classi/webservice/client.php');

$moodle = new moodleWebService();

define("INSTALLA_LOG", false);

$referer = recupera_referer();

$i = $_GET['step'];

echo '<li>'.date('Y-m-d H:i:s').'</li>';

//for($i=3;$i<=4;$i++){
    
    switch ($i) {
        ///1 RECUPERO I PRODOTTI - I CORSI - IL DETTAGLIO DEI CORSI - LA CONFIGURAZIONE DEI CORSI
        case 1:
            $corsi = $dblink->get_results("SELECT * FROM ".MOODLE_DB_NAME.".mdl_course");
            if (DISPLAY_DEBUG) {
                /*echo "<pre>";
                print_r($corsi);
                echo "<pre/><br>";*/
            }
            //die;
            foreach ($corsi as $corso) {
                if(strlen($corso['id'])<=1) continue;

                $rowProdotti = $dblink->get_row("SELECT id FROM lista_prodotti WHERE codice_esterno = '".$corso['id']."'",true);

                $rowImmagine = $dblink->get_row("SELECT * FROM ".MOODLE_DB_NAME.".mdl_files AS f LEFT JOIN ".MOODLE_DB_NAME.".mdl_files_reference AS r ON f.referencefileid = r.id WHERE f.contextid IN (SELECT id FROM ".MOODLE_DB_NAME.".mdl_context WHERE instanceid='".$corso['id']."') AND f.filesize > 0 AND f.component = 'course' AND f.filearea='overviewfiles' ORDER BY f.filename", true);

                if(!empty($rowImmagine)){
                    $urlImmagine = MOODLE_DOMAIN_NAME."/pluginfile.php/".$rowImmagine['contextid']."/".$rowImmagine['component']."/".$rowImmagine['filearea']."/".$rowImmagine['filename'];
                } else {
                    $urlImmagine = "";
                }

                $nomeCategoria = $dblink->get_row("SELECT name FROM ".MOODLE_DB_NAME.".mdl_course_categories WHERE id='".$corso['category']."' LIMIT 1");
                $nomeCategoria = $nomeCategoria[0];

                $prezzo = $dblink->get_row("SELECT `Listino 1` AS prezzo_listino FROM elenco_prodotti WHERE LCASE(Cod)=LCASE('".$corso['betaformazione_courseid']."') LIMIT 1", true);

                if($rowProdotti['id']>0){
                    $update = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome" => $dblink->filter($corso['shortname']),
                        "descrizione" => $dblink->filter($corso['summary']),
                        "descrizione_breve" => $dblink->filter($corso['fullname']),
                        "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                        "gruppo" => "CORSO",
                        "categoria" => $nomeCategoria,
                        "tipologia" => "e-learning",
                        "codice" => $corso['betaformazione_courseid'],
                        "codice_esterno" => $corso['id'],
                        "tempo_1" => $corso['time_to_complete'],
                        "url_immagine" => $urlImmagine
                    );

                    $where = array(
                        "id" => $rowProdotti['id']
                    );

                    $dblink->update("lista_prodotti", $update, $where);
                    $idProdotto = $rowProdotti['id'];
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }

                }else{

                    $insert = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome" => $dblink->filter($corso['shortname']),
                        "descrizione" => $dblink->filter($corso['summary']),
                        "descrizione_breve" => $dblink->filter($corso['fullname']),
                        "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                        "gruppo" => "CORSO",
                        "categoria" => $nomeCategoria,
                        "prezzo_pubblico" => $prezzo['prezzo_listino'],
                        "tipologia" => "e-learning",
                        "codice" => $corso['betaformazione_courseid'],
                        "codice_esterno" => $corso['id'],
                        "tempo_1" => $corso['time_to_complete'],
                        "url_immagine" => $urlImmagine
                    );

                    $dblink->insert("lista_prodotti", $insert);
                    $idProdotto = $dblink->lastid();
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }
                }

                $rowCorsi = $dblink->get_row("SELECT id FROM lista_corsi WHERE id_corso_moodle = '".$corso['id']."'",true);

                if($rowCorsi['id']>0){
                    $updateCorsi = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome_prodotto" => $dblink->filter($corso['shortname']),
                        "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                        "durata" => $corso['time_to_complete'],
                        "id_prodotto" => $idProdotto,
                        "id_corso_moodle" => $corso['id'],
                    );

                    $whereCorsi = array(
                        "id" => $rowCorsi['id']
                    );

                    $dblink->update("lista_corsi", $updateCorsi, $whereCorsi);
                    $idCorso = $rowCorsi['id'];
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }

                }else{
                    $insertCorsi = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome_prodotto" => $dblink->filter($corso['shortname']),
                        "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                        "durata" => $corso['time_to_complete'],
                        "id_prodotto" => $idProdotto,
                        "id_corso_moodle" => $corso['id'],
                    );

                    $dblink->insert("lista_corsi", $insertCorsi);
                    $idCorso = $dblink->lastid();
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }
                }

                $arrayCredits = json_decode($corso['credits']);
                if(!empty($arrayCredits)){
                    foreach($arrayCredits as $arrayCredit) {
                        $idClasse = false;
                        foreach ($arrayCredit as $key => $value) {
                            $tmp = explode("_",$key);
                            $idClasse = $tmp[1];
                            $crediti = $value;
                        }
                        if($idClasse!==false && !is_array($idClasse)){

                            $rowConf = $dblink->get_row("SELECT id FROM lista_corsi_configurazioni WHERE id_corso = '".$idCorso."' AND id_prodotto = '".$idProdotto."' AND id_classe = '".$idClasse."'",true);

                            if($rowConf['id']>0){
                                /*$updateConf = array(
                                    "dataagg" => date("Y-m-d H:i:s"),
                                    "scrittore" => $dblink->filter("autoImport"),
                                    "id_corso" => $idCorso,
                                    "id_prodotto" => $idProdotto,
                                    "id_classe" => $idClasse,
                                    "crediti" => $crediti,
                                    "avanzamento" => "80.0",
                                    "stato" => "Attivo",
                                );

                                $whereConf = array(
                                    "id"=>$rowConf['id']
                                );

                                $dblink->update("lista_corsi_configurazioni", $updateConf, $whereConf);
                                if(DISPLAY_DEBUG){
                                    echo $dblink->get_query();
                                    echo "<br />";
                                }*/
                            }else{
                                $insertConf = array(
                                    "dataagg" => date("Y-m-d H:i:s"),
                                    "scrittore" => $dblink->filter("autoImport"),
                                    "id_corso" => $idCorso,
                                    "id_prodotto" => $idProdotto,
                                    "id_classe" => $idClasse,
                                    "crediti" => $crediti,
                                    "avanzamento" => "80.0",
                                    "stato" => "Attivo",
                                );

                                $dblink->insert("lista_corsi_configurazioni", $insertConf);
                                if(DISPLAY_DEBUG){
                                    echo $dblink->get_query();
                                    echo "<br />";
                                }
                            }
                        }
                    }
                }

                $moduli = $moodle->get_all_lesson($corso['id']);
                $ordine = 1;
                $timeCorso = 0;
                foreach ($moduli as $lezioni) {
                    if(count($lezioni->modules)>=1) {
                        foreach ($lezioni->modules as $lezione) {
                            $row = $dblink->get_row('SELECT id_modulo FROM lista_corsi_dettaglio WHERE id_modulo='.$lezione->id.' AND id_corso='.$idCorso.' AND id_prodotto='.$idProdotto, true);

                            $arrayTimeModules = json_decode($corso['time_to_complete_modules']);

                            foreach ($arrayTimeModules as $arrayTimeModule) {
                                //if(strlen($corso->id)<1) continue;
                                if(DISPLAY_DEBUG){
                                    echo "<li>ID_NUMBER: ".$arrayTimeModule->istance_id." == ID_CORSO: ".$lezione->instance."</li>";
                                    echo "<br />";
                                }
                                if("".$arrayTimeModule->istance_id == "".$lezione->instance){
                                    $timeCorso = $arrayTimeModule->value;
                                    if($timeCorso>0) break;
                                }
                            }

                            if($row['id_modulo']>0){
                                $update = array(
                                    "dataagg" => date("Y-m-d H:i:s"),
                                    "scrittore" => $dblink->filter("autoImport"),
                                    "stato" => "Attivo",
                                    "ordine" => $ordine,
                                    "gruppo" => "MODULO",
                                    "durata" => $timeCorso,
                                    "nome" => $dblink->filter($lezione->name),
                                    "descrizione" => $dblink->filter($lezione->description),
                                    "url" => $lezione->url,
                                    "name" => $dblink->filter($lezione->name),
                                    "instance" => $lezione->instance,
                                    "visible" => $lezione->visible,
                                    "modicon" => $lezione->modicon,
                                    "modname" => $lezione->modname,
                                    "modplural" => $lezione->modplural,
                                    "availability" => $dblink->filter($lezione->availability),
                                    "indent" => $lezione->indent
                                );
                                $where = array(
                                    "id_modulo" => $lezione->id,
                                    "id_corso" => $idCorso,
                                    "id_prodotto" => $idProdotto
                                );
                                $dblink->update("lista_corsi_dettaglio", $update, $where);
                                if(DISPLAY_DEBUG){
                                    echo $dblink->get_query();
                                    echo "<br />";
                                }
                            }else{
                                $insert = array(
                                    "dataagg" => date("Y-m-d H:i:s"),
                                    "scrittore" => $dblink->filter("autoImport"),
                                    "stato" => "Attivo",
                                    "ordine" => $ordine,
                                    "gruppo" => "MODULO",
                                    "durata" => $timeCorso,
                                    "id_modulo" => $lezione->id,
                                    "id_corso" => $idCorso,
                                    "id_prodotto" => $idProdotto,
                                    "nome" => $dblink->filter($lezione->name),
                                    "descrizione" => $dblink->filter($lezione->description),
                                    "url" => $lezione->url,
                                    "name" => $dblink->filter($lezione->name),
                                    "instance" => $lezione->instance,
                                    "visible" => $lezione->visible,
                                    "modicon" => $lezione->modicon,
                                    "modname" => $lezione->modname,
                                    "modplural" => $lezione->modplural,
                                    "availability" => $dblink->filter($lezione->availability),
                                    "indent" => $lezione->indent
                                );
                                $dblink->insert("lista_corsi_dettaglio", $insert);
                                if(DISPLAY_DEBUG){
                                    echo $dblink->get_query();
                                    echo "<br />";
                                }
                            }
                            $timeCorso = 0;
                            $ordine++;
                        }
                    }
                }

                /*$update = array(
                    "codice_esterno" => $corso->id
                );
                $where = array(
                    "codice" => $corso->idnumber
                );

                $dblink->update("lista_prodotti", $update, $where);
                echo $dblink->get_query();
                echo "<br />";*/
                if(DISPLAY_DEBUG){
                    echo "<hr />";
                }
            }
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPORTAZIONE PRODOTTI - CORSI - DETTAGLIO CORSI - CONFIGURAZIONE CORSI</h5>
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=2">CONTINUA STEP 2</a>
            <?php
            
        break;

        //RECUPERO GLI ABBONAMENTI
        case 2:
            $abbonamenti = $dblink->get_results("SELECT * FROM ".MOODLE_DB_NAME.".mdl_cohort");
        
            foreach ($abbonamenti as $abbonamento) {

                $rowClassi = $dblink->get_row("SELECT id FROM lista_classi WHERE codice_esterno = '".$abbonamento['id']."'",true);

                if($rowClassi['id']>0){
                    $updateClasse = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome" => $dblink->filter($abbonamento['name']),
                        "codice_esterno" => $abbonamento['id']
                    );

                    $whereClasse = array(
                        "id" => $rowClassi['id']
                    );

                    $dblink->update("lista_classi", $updateClasse, $whereClasse);
                    $idClasse = $rowClassi['id'];
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }

                }else{

                    $insertClasse = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome" => $dblink->filter($abbonamento['name']),
                        "stato" => ($abbonamento['visible']) ? "Attivo" : "Non Attivo",
                        "codice_esterno" => $abbonamento['id']
                    );

                    $dblink->insert("lista_classi", $insertClasse);
                    $idClasse = $dblink->lastid();
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }
                }

                $rowAbbonamenti = $dblink->get_row("SELECT id FROM lista_prodotti WHERE codice_esterno = 'abb_".$abbonamento['id']."'",true);

                if($rowAbbonamenti['id']>0){
                    $update = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "stato" => ($abbonamento['visible']) ? "Attivo" : "Non Attivo",
                        "gruppo" => "ABBONAMENTO",
                        "categoria" => "ABBONAMENTI",
                        "tipologia" => "e-learning",
                        "codice_esterno" => "abb_".$abbonamento['id']
                    );

                    $where = array(
                        "id" => $rowAbbonamenti['id']
                    );

                    $dblink->update("lista_prodotti", $update, $where);
                    $idAbbonamento = $rowAbbonamenti['id'];
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }

                }else{

                    $insert = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "nome" => "Abbonamento ".$dblink->filter($abbonamento['name']),
                        "descrizione" => "Abbonamento ".$dblink->filter($abbonamento['name']),
                        "descrizione_breve" => "Abbonamento ".$dblink->filter($abbonamento['name']),
                        "stato" => ($abbonamento['visible']) ? "Attivo" : "Non Attivo",
                        "gruppo" => "ABBONAMENTO",
                        "categoria" => "ABBONAMENTI",
                        "tipologia" => "e-learning",
                        "prezzo_pubblico" => "144",
                        "codice_esterno" => "abb_".$abbonamento['id']
                    );

                    $dblink->insert("lista_prodotti", $insert);
                    $idAbbonamento = $dblink->lastid();
                    if(DISPLAY_DEBUG){
                        echo $dblink->get_query();
                        echo "<br />";
                    }
                }

                $corsi = $dblink->get_results("SELECT * FROM ".MOODLE_DB_NAME.".mdl_course WHERE id IN (SELECT courseid FROM ".MOODLE_DB_NAME.".mdl_enrol WHERE customint5 = '".$abbonamento['id']."')");

                foreach ($corsi as $corso) {
                    if(strlen($corso['id'])<=1) continue;

                    $rowProdotti = $dblink->get_row("SELECT id FROM lista_prodotti WHERE codice_esterno = '".$corso['id']."'",true);

                    $rowImmagine = $dblink->get_row("SELECT * FROM ".MOODLE_DB_NAME.".mdl_files AS f LEFT JOIN ".MOODLE_DB_NAME.".mdl_files_reference AS r ON f.referencefileid = r.id WHERE f.contextid IN (SELECT id FROM ".MOODLE_DB_NAME.".mdl_context WHERE instanceid='".$corso['id']."') AND f.filesize > 0 AND f.component = 'course' AND f.filearea='overviewfiles' ORDER BY f.filename", true);

                    if(!empty($rowImmagine)){
                        $urlImmagine = MOODLE_DOMAIN_NAME."/pluginfile.php/".$rowImmagine['contextid']."/".$rowImmagine['component']."/".$rowImmagine['filearea']."/".$rowImmagine['filename'];
                    } else {
                        $urlImmagine = "";
                    }

                    $nomeCategoria = $dblink->get_row("SELECT name FROM ".MOODLE_DB_NAME.".mdl_course_categories WHERE id='".$corso['category']."' LIMIT 1");
                    $nomeCategoria = $nomeCategoria[0];

                    $prezzo = $dblink->get_row("SELECT `Listino 1` AS prezzo_listino FROM elenco_prodotti WHERE LCASE(Cod)=LCASE('".$corso['betaformazione_courseid']."') LIMIT 1", true);

                    if($rowProdotti['id']>0){
                        $update = array(
                            "dataagg" => date("Y-m-d H:i:s"),
                            "scrittore" => $dblink->filter("autoImport"),
                            "nome" => $dblink->filter($corso['shortname']),
                            "descrizione" => $dblink->filter($corso['summary']),
                            "descrizione_breve" => $dblink->filter($corso['fullname']),
                            "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                            "gruppo" => "CORSO",
                            "categoria" => $nomeCategoria,
                            "tipologia" => "e-learning",
                            "codice" => $corso['betaformazione_courseid'],
                            "codice_esterno" => $corso['id'],
                            "tempo_1" => $corso['time_to_complete'],
                            "url_immagine" => $urlImmagine
                        );

                        $where = array(
                            "id" => $rowProdotti['id']
                        );

                        $dblink->update("lista_prodotti", $update, $where);
                        $idProdotto = $rowProdotti['id'];
                        if(DISPLAY_DEBUG){
                            echo $dblink->get_query();
                            echo "<br />";
                        }

                    }else{

                        $insert = array(
                            "dataagg" => date("Y-m-d H:i:s"),
                            "scrittore" => $dblink->filter("autoImport"),
                            "nome" => $dblink->filter($corso['shortname']),
                            "descrizione" => $dblink->filter($corso['summary']),
                            "descrizione_breve" => $dblink->filter($corso['fullname']),
                            "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                            "gruppo" => "CORSO",
                            "categoria" => $nomeCategoria,
                            "tipologia" => "e-learning",
                            "prezzo_pubblico" => $prezzo['prezzo_listino'],
                            "codice" => $corso['betaformazione_courseid'],
                            "codice_esterno" => $corso['id'],
                            "tempo_1" => $corso['time_to_complete'],
                            "url_immagine" => $urlImmagine
                        );

                        $dblink->insert("lista_prodotti", $insert);
                        $idProdotto = $dblink->lastid();
                        if(DISPLAY_DEBUG){
                            echo $dblink->get_query();
                            echo "<br />";
                        }
                    }

                    $rowProdottiDettaglio = $dblink->get_row("SELECT id FROM lista_prodotti_dettaglio WHERE id_prodotto_0 = '".$idAbbonamento."' AND id_prodotto='$idProdotto'",true);

                    if($rowProdottiDettaglio['id']>0){
                        $updateProdottiDett = array(
                            "dataagg" => date("Y-m-d H:i:s"),
                            "scrittore" => $dblink->filter("autoImport"),
                            "nome" => $dblink->filter($corso['shortname']),
                            "descrizione" => $dblink->filter($corso['summary']),
                            "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                            "gruppo" => "CORSO",
                            "codice" => $corso['betaformazione_courseid'],
                            "id_prodotto_0" => $idAbbonamento,
                            "id_prodotto" => $idProdotto,
                            "url" => $urlImmagine
                        );

                        $whereProdottiDett = array(
                            "id" => $rowProdottiDettaglio['id']
                        );

                        $dblink->update("lista_prodotti_dettaglio", $updateProdottiDett, $whereProdottiDett);
                        if(DISPLAY_DEBUG){
                            echo $dblink->get_query();
                            echo "<br />";
                        }

                    }else{
                        $insertProdottiDett = array(
                            "dataagg" => date("Y-m-d H:i:s"),
                            "scrittore" => $dblink->filter("autoImport"),
                            "nome" => $dblink->filter($corso['shortname']),
                            "descrizione" => $dblink->filter($corso['summary']),
                            "stato" => ($corso['visible'] && strlen($corso['betaformazione_courseid'])>0) ? "Attivo" : "Non Attivo",
                            "gruppo" => "CORSO",
                            "codice" => $corso['betaformazione_courseid'],
                            "id_prodotto_0" => $idAbbonamento,
                            "id_prodotto" => $idProdotto,
                            "url" => $urlImmagine
                        );

                        $dblink->insert("lista_prodotti_dettaglio", $insertProdottiDett);
                        if(DISPLAY_DEBUG){
                            echo $dblink->get_query();
                            echo "<br />";
                        }
                    }
                }
                if(DISPLAY_DEBUG){
                    echo "<hr />";
                }
            }
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPORTAZIONE ABBONAMENTI</h5>
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=3">CONTINUA STEP 3</a>
            <?php
            
        break;
        
        //RIPULISCO TABELLA UTENTI MOODLE
        case 3:
            $ok = $dblink->query("UPDATE ".MOODLE_DB_NAME.".`mdl_user` SET `auth` = 'manual' WHERE `auth` = 'bfsso'");
            
            if($ok){
                echo "<li>CAMBIO UTENTI DA bfsso -> manual</li>";
            }
            
            $ok = $dblink->query("UPDATE ".MOODLE_DB_NAME.".`mdl_user` SET `deleted` = 1 WHERE ( `email` LIKE '%@leolearning.com%' OR  `username` LIKE '%@leostaging.com%' OR `email` LIKE '%@test.%' OR `email` LIKE '%@email.com%' OR `username` LIKE '%@leolearning.com%' OR `username` LIKE '%@test.%' OR `username` LIKE '%test@%' OR `username` LIKE '%joeb%') AND username != 'ludtest@tin.it'");
            
            if($ok){
                echo "<li>SETTO A CANCELLATI GLI UTENTI LEOLEARNING E TEST</li>";
            }
            
            $ok = $dblink->query("UPDATE ".MOODLE_DB_NAME.".`mdl_external_services` SET `shortname` = 'betaformazione_webservice' WHERE `id` = '2'");
            if($ok){
                echo "<li>TOKEN ADMIN NOME WEBSERVICE MODIFICATO</li>";
            }
            
            $ok = $dblink->query("UPDATE ".MOODLE_DB_NAME.".`mdl_user` SET `deleted` = 0, email='supporto@cemanext.it', username='supporto@cemanext.it', lastname='Cema' WHERE id='2'");
            
            if($ok){
                echo "<li>UTENTE ID:2 ADMIN MODIFICATO CON DATI CEMANEXT</li>";
            }
            
            $ok = $moodle->creaUtenteMoodle("admincema", "supporto@cemanext.it", "admin", "cema", "CemaN3xt?2017", "1");
            
            if($ok){
                echo "<li>UTENTE ADMIN 2 RESETTO PASSWORD CON WEBSERVICE </li>";
                $dblink->query("UPDATE ".MOODLE_DB_NAME.".`mdl_user` SET username='admincema', `idnumber` = '' WHERE id='2'");
            }else{
                echo "<li>ERRORE - UTENTE ADMIN 2 NON RESETTATO </li>";
            }
            
            //Elimino tutti i deleted = 1
            //$dblink->delete("utenti_moodle", array("deleted"=>"1"));
            
            //Elimino i leolearnig
            //$dblink->deleteWhere("utenti_moodle", "`deleted` = 0 AND ( `email` LIKE '%@leolearning.com%' OR `email` LIKE '%@test.%' OR `username` LIKE '%@leolearning.com%' OR `username` LIKE '%@test.%' OR `username` LIKE '%test@%' OR `username` LIKE '%joeb%') AND username != 'ludtest@tin.it'");
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>RIPULISCO TABELLA UTENTI MOODLE E SETTO IMPOSTAZIONI BASE WEBSERVICE</h5>
            <p>mdl_user COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=4">CONTINUA STEP 4</a>
            <?php
            
        break;
        
        //RECUPERO TUTTI GLI UTENTI MOODLE ATTIVI DA MOODLE E LI BUTTO IN LISTA PASSWORD
        case 4:
            $ok = true;
            
            $rows = $dblink->get_results("SELECT * FROM ".MOODLE_DB_NAME.".`mdl_user` WHERE id > 58 AND `deleted` = 0 ");
            //$rows = $dblink->get_results("SELECT * FROM utenti_moodle LIMIT 29999, 10000");

            //echo "START: ".date("H:i:s");
            //echo "<br>";
            $countOK = 0;
            $countKO = 0;
            foreach ($rows as $row) {
                
                $sub = $dblink->get_row("SELECT id FROM ".MOODLE_DB_NAME.".mdl_user_info_field WHERE shortname='subscriptionexpiry'",true);
                    $expirydate_exists = $dblink->get_row("SELECT data FROM ".MOODLE_DB_NAME.".mdl_user_info_data WHERE userid = '".$row['id']."' AND fieldid = '".$sub['id']."'", true);
                
                if(!empty($expirydate_exists['data'])){
                    $dataExpire = GiraDataIta($expirydate_exists['data']);
                }else{
                    $dataExpireTmp = $dblink->get_row("SELECT timeend FROM ".MOODLE_DB_NAME.".mdl_user_enrolments WHERE userid = '".$row['id']."' AND timeend > 0 ORDER BY timeend DESC LIMIT 1", true);
                    $dataExpire = date("Y-m-d",$dataExpireTmp['timeend']);
                }
                
                $cohortsFieldId = $dblink->get_row("SELECT id FROM ".MOODLE_DB_NAME.".mdl_user_info_field WHERE shortname='Cohort'", true);
                $cohort = $dblink->get_row("SELECT data FROM ".MOODLE_DB_NAME.".mdl_user_info_data WHERE userid = '".$row['id']."' AND fieldid = '".$cohortsFieldId['id']."'", true);
                
                $rowUtenti = $dblink->get_row("SELECT id FROM lista_password WHERE id_moodle_user = '".$row['id']."'",true);
                $rowClasse = $dblink->get_row("SELECT id AS idClasse FROM lista_classi WHERE nome LIKE '".$cohort['data']."'",true);
                
                if(empty($rowClasse['idClasse'])){
                    $rowClasse = $dblink->get_row("SELECT DISTINCT mdl_enrol.name, mdl_enrol.customint5 AS idClasse FROM ".MOODLE_DB_NAME.".mdl_enrol INNER JOIN ".MOODLE_DB_NAME.".mdl_user_enrolments ON mdl_user_enrolments.enrolid = mdl_enrol.id WHERE mdl_user_enrolments.userid = '".$row['id']."' AND mdl_user_enrolments.timeend = '0' AND mdl_enrol.customint5 > 0 ORDER BY mdl_enrol.name DESC",true);
                }
                
                if($rowUtenti['id']>0){
                    $updateUtente = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "livello" => "cliente",
                        "nome" => $dblink->filter($row['firstname']),
                        "cognome" => $dblink->filter($row['lastname']),
                        "username" => $dblink->filter($row['username']),
                        "email" => $dblink->filter($row['email']),
                        "stato" => "In Attesa di Password",
                        "id_moodle_user" => $row['id'],
                        "id_classe" => $rowClasse['idClasse'],
                        "data_creazione" => date("Y-m-d H:i:s",$row['timecreated']),
                        "data_ultimo_accesso" => date("Y-m-d H:i:s",$row['lastaccess']),
                        "data_scadenza" => $dataExpire." 00:00:00",
                    );
                    
                    $ok = $dblink->update("lista_password", $updateUtente, array("id" => $rowUtenti['id']));
                    
                }else{
                    $insertUtente = array(
                        "dataagg" => date("Y-m-d H:i:s"),
                        "scrittore" => $dblink->filter("autoImport"),
                        "livello" => "cliente",
                        "nome" => $dblink->filter($row['firstname']),
                        "cognome" => $dblink->filter($row['lastname']),
                        "username" => $dblink->filter($row['username']),
                        "email" => $dblink->filter($row['email']),
                        "stato" => "In Attesa di Password",
                        "id_moodle_user" => $row['id'],
                        "id_classe" => $rowClasse['idClasse'],
                        "data_creazione" => date("Y-m-d H:i:s",$row['timecreated']),
                        "data_ultimo_accesso" => date("Y-m-d H:i:s",$row['lastaccess']),
                        "data_scadenza" => $dataExpire." 00:00:00",
                    );
                    
                    $ok = $dblink->insert("lista_password", $insertUtente);
                }
                
                if($ok){
                    $countOK++;
                }else{
                    $countKO++;
                }
                
            }
            
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPORTO UTENTI MOODLE IN LISTA PASSWORD</h5>
            <p>COMPLETATO!!!</p>
            <?php
            echo "UTENTI OK: ".$countOK."<br>";
            echo "UTENTI ERRORE: ".$countKO."<br>";
            ?>
            <a style="btn" href="installazione.php?step=50">CONTINUA STEP 4-bis</a>
            <?php
            
            /*INSERT INTO lista_professionisti (nome, cognome, email, telefono, codice_fiscale, luogo_di_nascita, provincia_di_nascita, lista_professionisti.cellulare, numero_albo, provincia_albo, data_di_nascita) SELECT `Nome Persona fisica`, `Cognome Persona fisica`, `E-mail`, `Telefono`, `Codice fiscale`, `LUOGO DI NASCITA`, `PROVINCIA DI NASCITA`, a.`Cellulare`, `N ISCRIZIONE ALBO`, `PROVINCIA ALBO`, STR_TO_DATE(`DATA DI NASCITA`,'%d/%m/%Y') FROM anagrafiche AS a JOIN lista_password AS p ON (a.`E-mail` = p.email) WHERE a.`Codice fiscale`!=''
             * 
            UPDATE lista_password AS U1, lista_professionisti AS U2 
            SET U1.id_professionista = U2.id
            WHERE U2.email = U1.email
            */
        break;
        
        case 5:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPOSTO LE PASSWORD SU LISTA PASSWORD E IN UTENTI MOODLE</h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoGeneraPasswordUtenti.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=6">CONTINUA STEP 6</a>
            <?php
        break;
    
        //IMPORTIAMO STORICO UTENTI NON PIU' ATTIVI O SCADUTI AL 31/07/2017
        case 6:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPORTIAMO STORICO UTENTI NON PIU' ATTIVI O SCADUTI AL <?=date("d-m-Y")?></h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoRecuperaCorsiUtentiMoodleManuale_StoricoNonAttivi.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=7">CONTINUA STEP 7</a>
            <?php
        break;
    
        //SETTIAMO LE DATE DALLO SCORM DEI CORSI INIZIATI
        case 7:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>SETTIAMO LE DATE DALLO SCORM DEI CORSI INIZIATI DELLO STORICO</h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoCorsiIniziatiManuale.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=8">CONTINUA STEP 8</a>
            <?php
        break;
    
        //SETTIAMO I CORSI COMPLETATI AL 100% E IN STATO COMPLETATO
        case 8:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>SETTIAMO I CORSI COMPLETATI AL 100% E IN STATO COMPLETATO</h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoCorsiCompletatiManuale.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=60">CONTINUA STEP 8 Bis</a>
            <?php
        break;
    
        case 9:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>DISABILITO I CORSI E GLI ABBONAMENTI IN STATO SCADUTO</h5>
            
            <?php
            /*$sql_007_update = "UPDATE lista_iscrizioni, lista_professionisti
            SET lista_iscrizioni.id_classe = lista_professionisti.id_classe,
            lista_iscrizioni. cognome_nome_professionista = CONCAT(lista_professionisti.cognome,' ',lista_professionisti.nome)
            WHERE lista_iscrizioni.id_professionista = lista_professionisti.id";
            $rs_007_update = mysql_query($sql_007_update);

            $sql_007_update = "UPDATE lista_iscrizioni, lista_classi
            SET lista_iscrizioni. nome_classe = lista_classi.nome
            WHERE lista_iscrizioni.id_classe = lista_classi.id";
            $rs_007_update = mysql_query($sql_007_update);

            $sql_007_update = "UPDATE lista_iscrizioni, lista_corsi
            SET lista_iscrizioni.nome_corso = lista_corsi.nome_prodotto
            WHERE lista_iscrizioni.id_corso = lista_corsi.id";
            $rs_007_update = mysql_query($sql_007_update);*/
            
            
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoAnnullaCorsiAbbonamenti.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=10">CONTINUA STEP 10</a>
            <?php
        break;
    
        //IMPORTIAMO STORICO UTENTI ATTIVI AL 31/07/2017
        case 10:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPORTIAMO STORICO UTENTI ATTIVI AL <?=date("d-m-Y")?></h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoRecuperaCorsiUtentiMoodleManuale_StoricoAttivi.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=11">CONTINUA STEP 11</a>
            <?php
        break;
    
        //SETTIAMO LE DATE DALLO SCORM DEI CORSI INIZIATI
        case 11:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>SETTIAMO LE DATE DALLO SCORM DEI CORSI INIZIATI DELLO STORICO</h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoCorsiIniziatiManuale.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=70">CONTINUA STEP 11 Bis</a>
            <?php
        break;
    
        //SETTIAMO I CORSI COMPLETATI AL 100% E IN STATO COMPLETATO
        case 12:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>SETTIAMO I CORSI COMPLETATI AL 100% E IN STATO COMPLETATO</h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoCorsiCompletati.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=13">CONTINUA STEP 13</a>
            <?php
        break;
    
        case 13:
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>DISABILITO I CORSI E GLI ABBONAMENTI IN STATO SCADUTO</h5>
            
            <?php
            ob_flush();
            require_once BASE_ROOT.'libreria/automazioni/autoAnnullaCorsiAbbonamenti.php';
            ob_flush();
            ?>
            
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=14">CONTINUA STEP 14</a>
            <?php
        break;
        
        
        //INSERISCO I PROFESSIONISTI IN LISTA_PROFESSIONISTI DOVE TROVO UNA CORRISPONDENZA PER MAIL E CODICE FISCALE COMPILATO
        case 50:
            //AGGIONRO ID UTENTE MOODLE IN LISTA PASSWORD
            
            $rowsUtenti = $dblink->get_results("SELECT id FROM lista_password WHERE livello = 'cliente'");

            foreach ($rowsUtenti as $rowUtente) {
                
                $rowProfessionista = $dblink->get_row("SELECT id FROM lista_professionisti WHERE email = '".$rowUtente['email']."'", true);
                
                if($rowProfessionista['id']>0){
                    $ok = $dblink->query("UPDATE lista_professionisti SET id_moodle_user = '".$rowUtente['id_moodle_user']."' WHERE id = '".$rowProfessionista['id']."'");
                }else{
                    $ok = $dblink->query("INSERT INTO lista_professionisti (nome, cognome, email, codice_fiscale, id_classe, id_moodle_user) SELECT nome, cognome, email, email, id_classe, id_moodle_user FROM lista_password WHERE livello = 'cliente' AND id = '".$rowUtente['id']."'");
                }
            }
            $countOK = 0; 
            $countKO = 0;
            
            /*$limit = 0;
            $limitPage = 100;*/
            
            //while($limit<36000){
                
                $ok = $dblink->query("UPDATE lista_password, lista_professionisti "
                        . "SET lista_password.id_professionista = lista_professionisti.id "
                        . "WHERE lista_professionisti.id_moodle_user = lista_password.id_moodle_user "
                        . "AND lista_password.id_professionista <= 0 AND lista_password.livello = 'cliente' ");
                
                /*$rows = $dblink->get_results("SELECT id, id_moodle_user FROM lista_professionisti ORDER BY id");
                foreach ($rows as $row) {
                    $ok = $dblink->update("lista_password", array("id_professionista"=>$row['id']), array("id_moodle_user" => $row['id_moodle_user']));
                    
                }*/
                /*if($ok) $countOK = $countOK+$limitPage;
                else $countKO = $countKO+$limitPage;
                $limit = $limit + $limitPage;
            }*/
            
            //AGGIONRO ID PROFESSIONISTA IN LISTA PASSWORD
            //$ok = $dblink->query("UPDATE lista_password, lista_professionisti SET lista_password.id_professionista = lista_professionisti.id WHERE lista_professionisti.id_moodle_user = lista_password.id_moodle_user AND lista_professionisti.id_moodle_user>0 AND lista_password.livello = 'cliente'");
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>IMPORTO UTENTI MOODLE IN LISTA PASSWORD</h5>
            <?php
            echo "UTENTI OK: ".$countOK."<br>";
            echo "UTENTI ERRORE: ".$countKO."<br>";
            ?>
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=5">CONTINUA STEP 5</a>
            <?php
            
        break;
        
        case 60:
            //AGGIONRO ID UTENTE MOODLE IN LISTA PASSWORD
            //$ok = $dblink->query("UPDATE lista_password, lista_professionisti SET lista_professionisti.id_moodle_user = lista_password.id_moodle_user WHERE lista_password.id_professionista = lista_professionisti.id AND lista_password.id_professionista <=0 AND lista_password.livello = 'cliente' ");
            
            $countOK = 0; 
            $countKO = 0; 
            
            $rows = $dblink->get_results("SELECT * FROM lista_iscrizioni WHERE abbonamento=1 GROUP BY id_utente_moodle, abbonamento ORDER BY id");
            foreach ($rows as $row) {
                $insert = array(
                    "dataagg" => date("Y-m-d H:i:s"),
                    "scrittore" => $dblink->filter("installazione9bis"),
                    "stato" => "Configurazione",
                    "id_professionista" => $row['id_professionista'],
                    "data_inizio_iscrizione" => $row['data_inizio_iscrizione'],
                    "data_fine_iscrizione" => $row['data_fine_iscrizione'],
                    "abbonamento" => "1",
                    "id_utente_moodle" => $row['id_utente_moodle'],
                    "id_classe" => $row['id_classe'],
                    "nome_classe" => $dblink->filter($row['nome_classe']),
                    "cognome_nome_professionista" => $dblink->filter($row['cognome_nome_professionista']),
                    "nome_corso" => $dblink->filter($row['nome_corso']),
                );
                $ok = $dblink->insert("lista_iscrizioni", $insert);
                if($ok) $countOK++;
                else $countKO++;
            }
            
            //AGGIONRO ID PROFESSIONISTA IN LISTA PASSWORD
            //$ok = $dblink->query("UPDATE lista_password, lista_professionisti SET lista_password.id_professionista = lista_professionisti.id WHERE lista_professionisti.id_moodle_user = lista_password.id_moodle_user AND lista_professionisti.id_moodle_user>0 AND lista_password.livello = 'cliente'");
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>CREO RIGA DI CONFIGURAZIONE ISCRIZIONE ABBONAMENTO</h5>
            <?php
            echo "RIGA CONFIGURAZIONE ABBONAMENTO OK: ".$countOK."<br>";
            echo "RIGA CONFIGURAZIONE ABBONAMENTO ERRORE: ".$countKO."<br>";
            ?>
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=9">CONTINUA STEP 9</a>
            <?php
            
        break;
        
        case 70:
            //AGGIONRO ID UTENTE MOODLE IN LISTA PASSWORD
            //$ok = $dblink->query("UPDATE lista_password, lista_professionisti SET lista_professionisti.id_moodle_user = lista_password.id_moodle_user WHERE lista_password.id_professionista = lista_professionisti.id AND lista_password.id_professionista <=0 AND lista_password.livello = 'cliente' ");
            
            $countOK = 0; 
            $countKO = 0; 
            
            $rows = $dblink->get_results("SELECT * FROM lista_iscrizioni WHERE abbonamento=1 AND (stato='In Attesa' OR stato = 'In Corso') GROUP BY id_utente_moodle, abbonamento ORDER BY id");
            foreach ($rows as $row) {
                $insert = array(
                    "dataagg" => date("Y-m-d H:i:s"),
                    "scrittore" => $dblink->filter("installazione9bis"),
                    "stato" => "Configurazione",
                    "id_professionista" => $row['id_professionista'],
                    "data_inizio_iscrizione" => $row['data_inizio_iscrizione'],
                    "data_fine_iscrizione" => $row['data_fine_iscrizione'],
                    "abbonamento" => "1",
                    "id_utente_moodle" => $row['id_utente_moodle'],
                    "id_classe" => $row['id_classe'],
                    "nome_classe" => $dblink->filter($row['nome_classe']),
                    "cognome_nome_professionista" => $dblink->filter($row['cognome_nome_professionista']),
                    "nome_corso" => $dblink->filter($row['nome_corso']),
                );
                $ok = $dblink->insert("lista_iscrizioni", $insert);
                if($ok) $countOK++;
                else $countKO++;
            }
            
            //AGGIONRO ID PROFESSIONISTA IN LISTA PASSWORD
            //$ok = $dblink->query("UPDATE lista_password, lista_professionisti SET lista_password.id_professionista = lista_professionisti.id WHERE lista_professionisti.id_moodle_user = lista_password.id_moodle_user AND lista_professionisti.id_moodle_user>0 AND lista_password.livello = 'cliente'");
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <h3>STEP <?=$i?></h3>
            <h5>CREO RIGA DI CONFIGURAZIONE ISCRIZIONE ABBONAMENTO</h5>
            <?php
            echo "RIGA CONFIGURAZIONE ABBONAMENTO OK: ".$countOK."<br>";
            echo "RIGA CONFIGURAZIONE ABBONAMENTO ERRORE: ".$countKO."<br>";
            ?>
            <p>COMPLETATO!!!</p>
            <a style="btn" href="installazione.php?step=12">CONTINUA STEP 12</a>
            <?php
            
        break;
        
        default:
            /*
            $ok = $dblink->query("TRUNCATE `calendario`");
            $ok = $dblink->query("TRUNCATE `lista_accessi`");
            $ok = $dblink->query("TRUNCATE `lista_attivazioni_manuale`");
            $ok = $dblink->query("TRUNCATE `lista_aziende`");
            $ok = $dblink->query("TRUNCATE `lista_corsi`");
            $ok = $dblink->query("TRUNCATE `lista_corsi_configurazioni`");
            $ok = $dblink->query("TRUNCATE `lista_corsi_dettaglio`");
            $ok = $dblink->query("TRUNCATE `lista_costi`");
            $ok = $dblink->query("TRUNCATE `lista_documenti`");
            $ok = $dblink->query("TRUNCATE `lista_fatture`");
            $ok = $dblink->query("TRUNCATE `lista_fatture_dettaglio`");
            $ok = $dblink->query("TRUNCATE `lista_indirizzi_email`");
            $ok = $dblink->query("TRUNCATE `lista_iscrizioni`");
            $ok = $dblink->query("TRUNCATE `lista_iscrizioni_dettaglio`");
            $ok = $dblink->query("TRUNCATE `lista_ordini`");
            $ok = $dblink->query("TRUNCATE `lista_ordini_dettaglio`");
            $ok = $dblink->query("TRUNCATE `lista_password`");
            $ok = $dblink->query("TRUNCATE `lista_preventivi`");
            $ok = $dblink->query("TRUNCATE `lista_preventivi_dettaglio`");
            $ok = $dblink->query("TRUNCATE `lista_prodotti`");
            $ok = $dblink->query("TRUNCATE `lista_prodotti_dettaglio`");
            $ok = $dblink->query("TRUNCATE `lista_professionisti`");
            $ok = $dblink->query("TRUNCATE `matrice_aziende_professionisti`");
            
            if($ok){
                echo "<li>SVUOTATE LE TABELLE</li>";
            }
            
            $insPasswordBase = "INSERT INTO `lista_password` (`id`, `dataagg`, `scrittore`, `id_professionista`, `id_classe`, `livello`, `nome`, `cognome`, `username`, `passwd`, `passwd_email`, `cellulare`, `email`, `stato`, `numerico_1`, `numerico_2`, `numerico_3`, `numerico_4`, `numerico_5`, `nickname`, `avatar`, `id_moodle_user`, `data_creazione`, `data_scadenza`, `data_ultimo_accesso`) VALUES
                    (1, '0000-00-00 00:00:00', '', 0, 0, 'amministratore', 'CROCCO', 'CEMA NEXT', 'cemanext', 'cemanext', '', '', 'supporto@cemanext.it', 'Attivo', 0, 0, 0, 0, 0, 'cemanext', 'cemanext', 0, '2017-05-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
                    (4, '2017-07-31 19:37:18', 'CROCCO CEMA NEXT', 0, 0, 'amministratore', 'Serena', 'Rontini', 'serena', 'serena', '', '', 'rontini@betaformazione.com', 'Attivo', 0, 0, 0, 0, 0, 'serena', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
                    (5, '2017-07-31 19:38:57', 'CROCCO CEMA NEXT', 0, 0, 'amministratore', 'Benedetto', 'Pirrone', 'benedetto', 'benedetto', '', '', 'pirrone@betaformazione.com', 'Attivo', 0, 0, 0, 0, 0, 'benedetto', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
                    (6, '2017-07-31 19:38:56', 'CEMA NEXT CROCCO', 0, 0, 'amministratore', 'Martina', 'Dall&#039;Oio', 'martina', 'martina', '', '', 'dallolio@betaformazione.com', 'Attivo', 0, 0, 0, 0, 0, 'dallolio', 'dallolio', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
                    (2, '0000-00-00 00:00:00', '', 0, 0, 'commerciale', 'Marco', 'Caravita', 'Caravita', 'Caravita', '', '', 'mc.consultingbetaformazione@gmail.com', 'Attivo', 0, 0, 0, 0, 0, '', '', 0, '2017-07-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
                    (3, '0000-00-00 00:00:00', '', 0, 0, 'commerciale', 'Gianfranco', 'Garofalo', 'Garofalo', 'Garofalo', '', '', 'garofalo@betaformazione.com', 'Attivo', 0, 0, 0, 0, 0, '', '', 0, '2017-07-31 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
                    (7, '2017-08-02 10:09:09', 'CEMA NEXT CROCCO', 0, 0, 'commerciale', 'Commerciale', 'CEMA NEXT', 'commercialecemanext', 'commercialecemanext', '', '', 'staff@cemanext.it', 'Attivo', 0, 0, 0, 0, 0, 'Commerciale CEMA NEXT', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');";
            $ok = $dblink->query($insPasswordBase);
            
            if($ok){
                echo "<li>INSERITI UTENTI AMMINISTRATORI IN LISTA PASSWORD</li>";
            }*/
            
            ?>
            <h2>INSTALLAZIONE <?=ERP_DOMAIN_NAME?></h2>
            <a style="btn" href="installazione.php?step=1">INIZIA STEP 1</a>
            <?php
        break;
    }
//}


echo '<li>'.date('Y-m-d H:i:s').'</li>';

?>