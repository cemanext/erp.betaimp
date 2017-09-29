<?php
ini_set('display_errors', '1');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require BASE_ROOT.'classi/phpmailer/src/Exception.php';
require BASE_ROOT.'classi/phpmailer/src/PHPMailer.php';
require BASE_ROOT.'classi/phpmailer/src/SMTP.php';

if(strlen($_SESSION['passwd_email_utente'])>2 && strpos($_SESSION['email_utente'],"@betaformazione.com")>0){
    define("PASS_MAIL", $_SESSION['passwd_email_utente']);
    define("USER_MAIL", $_SESSION['email_utente']);
}else{
    define("USER_MAIL", "erp@xxxxxx.com");
    define("PASS_MAIL", 'xxxxxx');
}

//inviare email fattura
function inviaEmailPreventivo($mitt, $dest, $dest_cc, $dest_bcc, $ogg, $mess, $allegato_1, $allegato_2, $PasswdEmailUtente) {
    
    $verifica = verificaEmail($mitt);

    if ($verifica) {
        //require BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
        $messaggio = new PHPmailer();
        $messaggio->IsHTML(true);
        //$messaggio->IsSMTP();
        # I added SetLanguage like this
        $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
        //  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
        $messaggio->SMTPAuth = true;                  // enable SMTP authentication
        $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
        $messaggio->SMTPSecure = 'tls';
        $messaggio->Port = 587;
        // set the SMTP port for the GMAIL server
        $messaggio->Username = USER_MAIL; // SMTP account username
        $messaggio->Password = PASS_MAIL;        // SMTP account password
        //echo '<h2>$email_mittente = '.$email_mittente.'</h2>';
        //intestazioni e corpo dell'email
        $messaggio->From = $mitt;
        $messaggio->FromName = $mitt;
        $messaggio->ConfirmReadingTo = $mitt;
        $messaggio->AddReplyTo($mitt);

        if(EMAIL_DEBUG){
            $dest = trim(EMAIL_TO_SEND_DEBUG);
        }
        $dest = str_replace(' ', '', $dest);
        $dest = str_replace(';', ',', $dest);
        $string = trim($dest);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }

        if(!EMAIL_DEBUG){
            if (strlen($dest_cc) > 0) {
                //$messaggio->AddAddress($dest_cc);
                $dest_cc = str_replace(' ', '', $dest_cc);
                $dest_cc = str_replace(';', ',', $dest_cc);
                $string = trim($dest_cc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddAddress(trim($tok));
                    $tok = strtok(",");
                }
            }
        }

        if(!EMAIL_DEBUG){
            if (strlen($dest_bcc) > 0) {
                //$messaggio->AddBCC($dest_bcc);
                $dest_bcc = str_replace(' ', '', $dest_bcc);
                $dest_bcc = str_replace(';', ',', $dest_bcc);
                $string = trim($dest_bcc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddBCC(trim($tok));
                    $tok = strtok(",");
                }
            }
        }


//	echo '<li>$allegato_1 = '.$allegato_1.'</li>';
        if (strlen($allegato_1) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_1 = '.$allegato_1.'</li>';
            $messaggio->AddAttachment(BASE_ROOT . "media/lista_preventivi/" . $allegato_1);
            //$messaggio->AddAttachment("../media/lista_fatture/'.$allegato_1");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }
        if (strlen($allegato_2) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_2 = '.$allegato_2.'</li>';
            $messaggio->AddAttachment(BASE_ROOT . "media/lista_documenti/" . $_SESSION['id_utente'] . "/" . $allegato_2);
            //$messaggio->AddAttachment("'.$allegato_2.'");
        } else {
            
        }

        //if (strlen($allegato_3) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_3 = '.$allegato_3.'</li>';
            //$messaggio->AddAttachment("../doc_lista_commesse/".$idCommessaTLM."/".$idProcessoTLM."/Offerta.pdf");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        //} else {
            
        //}

        //$messaggio->AddBCC('staff@cemanext.it');
        //$messaggio->AddBCC(trim($mitt));
        $messaggio->Subject = $ogg;
        $messaggio->Body = stripslashes($mess);


        if (!$messaggio->Send()) {
            echo $messaggio->ErrorInfo;
        } else {
            //echo '<li>Email Inviata Correttamente !</li>';
        }
    }
}

//inviare email fattura
function inviaEmailFattura($mitt, $dest, $dest_cc, $dest_bcc, $ogg, $mess, $allegato_1, $allegato_2, $PasswdEmailUtente) {
    
    $verifica = verificaEmail($mitt);

    if ($verifica) {
        //require BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
        $messaggio = new PHPmailer();
        $messaggio->IsHTML(true);
        //$messaggio->IsSMTP();
        # I added SetLanguage like this
        $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
        //  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
        $messaggio->SMTPAuth = true;                  // enable SMTP authentication
        $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
        $messaggio->SMTPSecure = 'tls';
        $messaggio->Port = 587;
        // set the SMTP port for the GMAIL server
        $messaggio->Username = USER_MAIL; // SMTP account username
        $messaggio->Password = PASS_MAIL;        // SMTP account password
        //intestazioni e corpo dell'email
        $messaggio->From = $mitt;
        $messaggio->FromName = $mitt;
        $messaggio->ConfirmReadingTo = $mitt;
        $messaggio->AddReplyTo($mitt);

        if(EMAIL_DEBUG){
            $dest = trim(EMAIL_TO_SEND_DEBUG);
        }
        $dest = str_replace(' ', '', $dest);
        $dest = str_replace(';', ',', $dest);
        $string = trim($dest);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }
        
        if(!EMAIL_DEBUG){
            if (strlen($dest_cc) > 0) {
                //$messaggio->AddAddress($dest_cc);
                $dest_cc = str_replace(' ', '', $dest_cc);
                $dest_cc = str_replace(';', ',', $dest_cc);
                $string = trim($dest_cc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddAddress(trim($tok));
                    $tok = strtok(",");
                }
            }
        }

        if(!EMAIL_DEBUG){
            //$dest_bcc = EMAIL_TO_SEND_DEBUG.',contino@betaformazione.com';
            if (strlen($dest_bcc) > 0) {
                //$messaggio->AddBCC($dest_bcc);
                $dest_bcc = str_replace(' ', '', $dest_bcc);
                $dest_bcc = str_replace(';', ',', $dest_bcc);
                $string = trim($dest_bcc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddBCC(trim($tok));
                    $tok = strtok(",");
                }
            }
        }

        //	echo '<li>$allegato_1 = '.$allegato_1.'</li>';
        if (strlen($allegato_1) > 3) {
            //		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
            //echo '<li>----------> $allegato_1 = '.$allegato_1.'</li>';
            $messaggio->AddAttachment(BASE_ROOT . "media/lista_fatture/" . $allegato_1);
            //$messaggio->AddAttachment("../media/lista_fatture/'.$allegato_1");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }
        if (strlen($allegato_2) > 3) {
            //		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
            //echo '<li>----------> $allegato_2 = '.$allegato_2.'</li>';
            $messaggio->AddAttachment(BASE_ROOT . "media/lista_documenti/" . $_SESSION['id_utente'] . "/" . $allegato_2);
            //$messaggio->AddAttachment("'.$allegato_2.'");
        } else {
            
        }

        //if (strlen($allegato_3) > 3) {
            //		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
            //echo '<li>----------> $allegato_3 = '.$allegato_3.'</li>';
            //$messaggio->AddAttachment("../doc_lista_commesse/".$idCommessaTLM."/".$idProcessoTLM."/Offerta.pdf");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        //} else {
            
        //}

        //$messaggio->AddBCC('staff@cemanext.it');
        //$messaggio->AddBCC(trim($mitt));
        $messaggio->Subject = $ogg;
        $messaggio->Body = stripslashes($mess);


        if (!$messaggio->Send()) {
            echo $messaggio->ErrorInfo;
        } else {
            //echo '<li>Email Inviata Correttamente !</li>';
        }
    }
}

//inviare email
function inviaEmail($mitt, $dest, $dest_cc, $dest_bcc, $ogg, $mess, $allegato_1, $allegato_2, $allegato_3, $idCommessaTLM, $idProcessoTLM, $PasswdEmailUtente) {
    
    $verifica = verificaEmail($mitt);

    if ($verifica) {
        //require "phpmailer/class.phpmailer.php";
        $messaggio = new PHPmailer();
        $messaggio->IsHTML(true);
        //$messaggio->IsSMTP();
        # I added SetLanguage like this
        $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
        $messaggio->SMTPAuth = true;                  // enable SMTP authentication
        $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
        $messaggio->SMTPSecure = 'tls';
        $messaggio->Port = 587;
        // set the SMTP port for the GMAIL server
        $messaggio->Username = USER_MAIL; // SMTP account username
        $messaggio->Password = PASS_MAIL;        // SMTP account password
        //echo '<h2>$email_mittente = '.$email_mittente.'</h2>';
        //intestazioni e corpo dell'email
        $messaggio->From = $mitt;
        $messaggio->FromName = $mitt;
        $messaggio->ConfirmReadingTo = $mitt;
        $messaggio->AddReplyTo($mitt);

        if(EMAIL_DEBUG){
            $dest = trim(EMAIL_TO_SEND_DEBUG);
        }
        $dest = str_replace(' ', '', $dest);
        $dest = str_replace(';', ',', $dest);
        $string = trim($dest);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }

        if(!EMAIL_DEBUG){
            if (strlen($dest_cc) > 0) {
                //$messaggio->AddAddress($dest_cc);
                $dest_cc = str_replace(' ', '', $dest_cc);
                $dest_cc = str_replace(';', ',', $dest_cc);
                $string = trim($dest_cc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddAddress(trim($tok));
                    $tok = strtok(",");
                }
            }
        }


        if(!EMAIL_DEBUG){
            if (strlen($dest_bcc) > 0) {
                //$messaggio->AddBCC($dest_bcc);
                $dest_bcc = str_replace(' ', '', $dest_bcc);
                $dest_bcc = str_replace(';', ',', $dest_bcc);
                $string = trim($dest_bcc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddBCC(trim($tok));
                    $tok = strtok(",");
                }
            }
        }

//	echo '<li>$allegato_1 = '.$allegato_1.'</li>';
        if (strlen($allegato_1) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_1 = '.$allegato_1.'</li>';
            $messaggio->AddAttachment("../doc_lista_commesse/" . $idCommessaTLM . "/" . $idProcessoTLM . "/Presentazione.pdf");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }

        if (strlen($allegato_2) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_2 = '.$allegato_2.'</li>';
            $messaggio->AddAttachment("../doc_lista_commesse/" . $idCommessaTLM . "/" . $idProcessoTLM . "/Prodotto.pdf");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }

        if (strlen($allegato_3) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_3 = '.$allegato_3.'</li>';
            $messaggio->AddAttachment("../doc_lista_commesse/" . $idCommessaTLM . "/" . $idProcessoTLM . "/Offerta.pdf");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }

        //$messaggio->AddBCC('staff@cemanext.it');
        //$messaggio->AddBCC(trim($mitt));
        $messaggio->Subject = $ogg;
        $messaggio->Body = stripslashes($mess);


        if (!$messaggio->Send()) {
            echo $messaggio->ErrorInfo;
        } else {
            //echo '<li>Email Inviata Correttamente !</li>';
        }
    }
}

function inviaEmail_Base($mittente, $destinatario, $oggetto_da_inviare, $messaggio_da_inviare) {
    
    $messaggio = new PHPmailer();
    $messaggio->IsHTML(true);

    $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
    $messaggio->SMTPAuth = true;                  // enable SMTP authentication
    $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
    $messaggio->SMTPSecure = 'tls';
    $messaggio->Port = 587;
    // set the SMTP port for the GMAIL server
    $messaggio->Username = USER_MAIL; // SMTP account username
    $messaggio->Password = PASS_MAIL;        // SMTP account password
    //echo '<h2>$email_mittente = '.$email_mittente.'</h2>';
    //intestazioni e corpo dell'email
    $messaggio->From = $mittente;
    $messaggio->FromName = $mittente;
    $messaggio->ConfirmReadingTo = $destinatario;
    $messaggio->AddReplyTo($mittente);
    if(EMAIL_DEBUG){
        $messaggio->AddAddress(trim(EMAIL_TO_SEND_DEBUG));
    }else{
        $messaggio->AddAddress(trim($destinatario));
    }

    $messaggio->Subject = $oggetto_da_inviare;
    $messaggio->Body = stripslashes(nl2br($messaggio_da_inviare));


    if (!$messaggio->Send()) {
        echo $messaggio->ErrorInfo;
        $return = false;
    } else {
        //echo '<li>Email Inviata Correttamente !</li>';
        $return = true;
    }

    return $return;
}

//inviare email fattura
function inviaEmailFatturaDaId($idFattura,$updateFattura) {
    global $dblink, $log;
    
    $mitt = USER_MAIL;

    $sql = "SELECT * FROM lista_fatture WHERE id='" . $idFattura . "'";
    $row = $dblink->get_row($sql,true);
    
    $n_progetto = str_replace("/", "-", $row['codice']);
    $filename = "BetaFormazione_Fattura_" . $row['codice'] . "-" . $row['sezionale'] . ".pdf";
    $allegato_1 = $filename;
    $filename_oggetto = "Fattura " . $row['codice'] . "/" . $row['sezionale'] . "";
    $causale = $row['causale'];

    $emailDesti = $dblink->get_row("SELECT email, ragione_sociale FROM lista_aziende WHERE id = '".$row['id_azienda']."'", true);

    if(empty($emailDesti)){
        $emailDesti = $dblink->get_row("SELECT email FROM lista_professionisti WHERE id = '".$row['id_professionista']."'", true);
    }

    $dest = $emailDesti['email'];
    $ragione_sociale = $emailDesti['ragione_sociale'];
    $ogg = 'Beta Formazione s.r.l. -  ' . $filename_oggetto;

    $sql_template = "SELECT * FROM lista_template_email WHERE nome = 'inviaEmailFatturaDaId'";
    $rs_template = $dblink->get_results($sql_template);
    foreach ($rs_template as $row_template) {
        $mittente = $row_template['mittente'];
        $reply = $row_template['reply'];
        $destinatario_admin = $row_template['destinatario'];
        $dest_cc = $row_template['cc'];
        $dest_bcc = $row_template['bcc'];
        $oggetto_da_inviare = $row_template['oggetto'];
        $messaggio_da_inviare = html_entity_decode($row_template['messaggio']);
    }
    
    $messaggio_da_inviare = str_replace('_XXX_', $ragione_sociale, $messaggio_da_inviare);

    $mitt = $mittente;
    $mess = $messaggio_da_inviare;
     
    if (DISPLAY_DEBUG) {
        echo '<li>$mitt = '.$mitt.'</li>';
        echo '<li>$destinatario_admin = '.$destinatario_admin.'</li>';
        echo '<li>$dest = '.$dest.'</li>';
        echo '<li>$ogg = '.$ogg.'</li>';
        echo '<li>$mess = '.$mess.'</li>';
    }
     
    $verifica = verificaEmail($mitt);
    if($verifica) {
        //require_once BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
        $messaggio = new PHPmailer();
        $messaggio->IsHTML(true);
        //$messaggio->SMTPDebug  = 2;
        //$messaggio->IsSMTP();
        # I added SetLanguage like this
        $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
        //  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
        $messaggio->SMTPAuth = true;                  // enable SMTP authentication
        $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
        $messaggio->SMTPSecure = 'tls';
        $messaggio->Port = 587;
        // set the SMTP port for the GMAIL server
        $messaggio->Username = USER_MAIL; // SMTP account username
        $messaggio->Password = PASS_MAIL;        // SMTP account password
        //echo '<h2>$email_mittente = '.$email_mittente.'</h2>';
        //intestazioni e corpo dell'email
        $messaggio->From = $mitt;
        $messaggio->FromName = $mitt;
        $messaggio->ConfirmReadingTo = $mitt;
        $messaggio->AddReplyTo($mitt);

        if(EMAIL_DEBUG){
            if (strlen($destinatario_admin) > 5) {
                $dest = $destinatario_admin;
            }else{
                $dest = trim(EMAIL_TO_SEND_DEBUG);
            }
        }
        
        $dest = str_replace(' ', '', $dest);
        $dest = str_replace(';', ',', $dest);
        $string = trim($dest);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }

        if(!EMAIL_DEBUG){
            if (strlen($dest_cc) > 0) {
                //$messaggio->AddAddress($dest_cc);
                $dest_cc = str_replace(' ', '', $dest_cc);
                $dest_cc = str_replace(';', ',', $dest_cc);
                $string = trim($dest_cc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddAddress(trim($tok));
                    $tok = strtok(",");
                }
            }
        }

        
        if(!EMAIL_DEBUG){
        //$dest_bcc = 'supporto@cemanext.it,contino@betaformazione.com';
            if (strlen($dest_bcc) > 0) {
                //$messaggio->AddBCC($dest_bcc);
                $dest_bcc = str_replace(' ', '', $dest_bcc);
                $dest_bcc = str_replace(';', ',', $dest_bcc);
                $string = trim($dest_bcc);
                /* Use tab and newline as tokenizing characters as well  */
                $tok = strtok($string, ",");

                while ($tok !== false) {
                    //echo "Word=$tok<br />";
                    $messaggio->AddBCC(trim($tok));
                    $tok = strtok(",");
                }
            }
        }


//	echo '<li>$allegato_1 = '.$allegato_1.'</li>';
        if (strlen($allegato_1) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_1 = '.$allegato_1.'</li>';
            $messaggio->AddAttachment(BASE_ROOT . "media/lista_fatture/" . $allegato_1);
            //$messaggio->AddAttachment("../media/lista_fatture/'.$allegato_1");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }

        if (strlen($allegato_2) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_2 = '.$allegato_2.'</li>';
            $messaggio->AddAttachment(BASE_ROOT . "media/lista_documenti/" . $_SESSION['id_utente'] . "/" . $allegato_2);
            //$messaggio->AddAttachment("'.$allegato_2.'");
        } else {
            
        }

        /*if (strlen($allegato_3) > 3) {
//		echo '<li>fileDoc = lista_fatture/'.$_POST['fileDoc'].'</li>';
//echo '<li>----------> $allegato_3 = '.$allegato_3.'</li>';
            //$messaggio->AddAttachment("../doc_lista_commesse/".$idCommessaTLM."/".$idProcessoTLM."/Offerta.pdf");
            //$messaggio->AddAttachment("CEMA-NEXT-BROCHURE-21X21-B.pdf");
        } else {
            
        }*/

        //$messaggio->AddBCC('staff@cemanext.it');
        //$messaggio->AddBCC(trim($mitt));
        $messaggio->Subject = $ogg;
        $messaggio->Body = stripslashes($mess);

        if (!$messaggio->Send()) {
            $log->log_all_errors('inviaEmailFatturaDaId -> Email NON Inviata [' . $messaggio->ErrorInfo . '] -> $destinatario = ' . $dest, 'ERRORE');
        
            $return = false;
        } else {
            $return = true;
        }
        
        $return = true;
    }
    
    return $return;
}

//INVIO EMAIL BASE DA TEMPLATE
function inviaEmailTemplate_Base($idProfessionista, $nome_tamplate, $idFatturaDettaglio = 0){
    global $dblink, $moodle, $log;
    //require_once BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
    $messaggio = new PHPmailer();
    $messaggio->IsHTML(true);

    $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
    $messaggio->SMTPAuth = true;                  // enable SMTP authentication
    $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
    $messaggio->SMTPSecure = 'tls';
    $messaggio->Port = 587;
    // set the SMTP port for the GMAIL server
    $messaggio->Username = USER_MAIL; // SMTP account username
    $messaggio->Password = PASS_MAIL;        // SMTP account password
    //echo '<h2>$email_mittente = '.$email_mittente.'</h2>';
    //SELECT `id`, `dataagg`, `scrittore`, `stato`, `nome`, `mittente`, `reply`, `destinatario`, `cc`, `bcc`, `oggetto`, `messaggio`, `allegato_1`, `allegato_2`, `allegato_3` FROM `lista_template_email` WHERE 1
    $sql_template = "SELECT * FROM lista_template_email WHERE nome = '" . $nome_tamplate . "'";
    $row_template = $dblink->get_row($sql_template, true);
    //while ($row_template = mysql_fetch_array($rs_template, MYSQL_BOTH)) {
        $mittente = $row_template['mittente'];
        $reply = $row_template['reply'];
        $destinatario_admin = $row_template['destinatario'];
        $dest_cc = $row_template['cc'];
        $dest_bcc = $row_template['bcc'];
        $oggetto_da_inviare = $row_template['oggetto'];
        $messaggio_da_inviare = html_entity_decode($row_template['messaggio']);
    //}

    $sql_professionista = "SELECT * FROM lista_professionisti WHERE id = '" . $idProfessionista . "'";
    $row_professionista = $dblink->get_row($sql_professionista, true);
    //while ($row_professionista = mysql_fetch_array($rs_professionista, MYSQL_BOTH)) {
        $destinatario = $row_professionista['email'];
        $cognome = $row_professionista['cognome'];
        $nome = $row_professionista['nome'];
        $cognome_nome_professionista = $row_professionista['cognome'] . ' ' . $row_professionista['nome'];
    //}

    $sql_password = "SELECT * FROM lista_password WHERE id_professionista = '" . $idProfessionista . "'";
    $row_password = $dblink->get_row($sql_password, true);
    //while ($row_password = mysql_fetch_array($rs_password, MYSQL_BOTH)) {
        $username = $row_password['username'];
        $passwd = $row_password['passwd'];
        $dati_credenziali = "
        Indirizzo: " . MOODLE_DOMAIN_NAME . "
        Username: " . $username . "
        Password: " . $passwd . "
        ";
    //}

    if ($idFatturaDettaglio > 0) {
        $sql_nome_corso = "SELECT * FROM lista_fatture_dettaglio WHERE id = '" . $idFatturaDettaglio . "'";
        $rs_nome_corso = $dblink->get_fields($sql_nome_corso);
        $nome_del_corso = "";
        foreach ($rs_nome_corso as $row_nome_corso) {
            $nome_del_corso.= $row_nome_corso['nome_prodotto'] . ' [' . $row_nome_corso['codice_prodotto'] . ']<br>';
        }
    }

    if(EMAIL_DEBUG){
        if (strlen($destinatario_admin) > 5) {
            $destinatario = $destinatario_admin;
        }else{
            $destinatario = trim(EMAIL_TO_SEND_DEBUG);
        }
    }

    $messaggio_da_inviare = str_replace('_XXX_', $cognome_nome_professionista, $messaggio_da_inviare);
    $messaggio_da_inviare = str_replace('_CREDENZIALI_', $dati_credenziali, $messaggio_da_inviare);
    $messaggio_da_inviare = str_replace('_NOME_DEL_CORSO_', $nome_del_corso, $messaggio_da_inviare);
    $messaggio_da_inviare = str_replace('_NOME_ABBONAMENTO_', $nome_del_corso, $messaggio_da_inviare);

    //intestazioni e corpo dell'email
    $messaggio->From = $mittente;
    $messaggio->FromName = $mittente;
    $messaggio->ConfirmReadingTo = $destinatario;
    $messaggio->AddReplyTo($reply);
    //$messaggio->AddAddress(trim($destinatario));

    if (strlen($destinatario) > 0) {
        $destinatario = str_replace(' ', '', $destinatario);
        $destinatario = str_replace(';', ',', $destinatario);
        $string = trim($destinatario);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_cc) > 0) {
            //$messaggio->AddAddress($dest_cc);
            $dest_cc = str_replace(' ', '', $dest_cc);
            $dest_cc = str_replace(';', ',', $dest_cc);
            $string = trim($dest_cc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddAddress(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    if(!EMAIL_DEBUG){
        //$dest_bcc = 'simone.crocco@cemanext.it';
        if (strlen($dest_bcc) > 0) {
            //$messaggio->AddBCC($dest_bcc);
            $dest_bcc = str_replace(' ', '', $dest_bcc);
            $dest_bcc = str_replace(';', ',', $dest_bcc);
            $string = trim($dest_bcc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddBCC(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    $messaggio->Subject = $oggetto_da_inviare;
    $messaggio->Body = stripslashes(nl2br($messaggio_da_inviare));


    if (!$messaggio->Send()) {
        $log->log_all_errors('attivaCorsoFattura -> Email NON Inviata [' . $messaggio->ErrorInfo . '] -> $destinatario = ' . $destinatario, 'ERRORE');
        $return = false;
    } else {
        $return = true;
    }

    return $return;
}

//INVIO EMAIL RICHIESTA DA TEMPLATE
function inviaEmailTemplate_Richiesta($idCalendario, $nome_tamplate) {
    global $dblink, $moodle, $log;
    //require_once BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
    $messaggio = new PHPmailer();
    $messaggio->IsHTML(true);

    # I added SetLanguage like this
    $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
    $messaggio->SMTPAuth = true;                  // enable SMTP authentication
    $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
    $messaggio->SMTPSecure = 'tls';
    $messaggio->Port = 587;
    // set the SMTP port for the GMAIL server
    $messaggio->Username = USER_MAIL; // SMTP account username
    $messaggio->Password = PASS_MAIL;        // SMTP account password
    //SELECT `id`, `dataagg`, `scrittore`, `stato`, `nome`, `mittente`, `reply`, `destinatario`, `cc`, `bcc`, `oggetto`, `messaggio`, `allegato_1`, `allegato_2`, `allegato_3` FROM `lista_template_email` WHERE 1
    $sql_template = "SELECT * FROM lista_template_email WHERE nome = '" . $nome_tamplate . "'";
    $row_template = $dblink->get_row($sql_template, true);
    //while ($row_template = mysql_fetch_array($rs_template, MYSQL_BOTH)) {
        $mittente = $row_template['mittente'];
        $reply = $row_template['reply'];
        $destinatario_admin = $row_template['destinatario'];
        $dest_cc = $row_template['cc'];
        $dest_bcc = $row_template['bcc'];
        $oggetto_da_inviare = $row_template['oggetto'];
        $messaggio_da_inviare = html_entity_decode($row_template['messaggio'])."<br><br>";
    //}

    $sql_calendario = "SELECT messaggio FROM calendario WHERE id = '" . $idCalendario . "'";
    $row_calendario = $dblink->get_row($sql_calendario, true);
    //while ($row_calendario = mysql_fetch_array($rs_calendario, MYSQL_BOTH)) {
        $messaggio_da_inviare .= $row_calendario['messaggio'];
    //}

    if(EMAIL_DEBUG || $nome_tamplate=="nuovaRichiesta"){
        if (strlen($destinatario_admin) > 5) {
            $destinatario = $destinatario_admin;
        }else{
            $destinatario = trim(EMAIL_TO_SEND_DEBUG);
        }
    }

    //$messaggio_da_inviare = str_replace('_XXX_', $cognome_nome_professionista, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_CREDENZIALI_', $dati_credenziali, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_NOME_DEL_CORSO_', $nome_del_corso, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_NOME_ABBONAMENTO_', $nome_del_corso, $messaggio_da_inviare);

    //intestazioni e corpo dell'email
    $messaggio->From = $mittente;
    $messaggio->FromName = $mittente;
    $messaggio->ConfirmReadingTo = $destinatario;
    $messaggio->AddReplyTo($reply);
    //$messaggio->AddAddress(trim($destinatario));

    if (strlen($destinatario) > 0) {
        $destinatario = str_replace(' ', '', $destinatario);
        $destinatario = str_replace(';', ',', $destinatario);
        $string = trim($destinatario);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_cc) > 0) {
            //$messaggio->AddAddress($dest_cc);
            $dest_cc = str_replace(' ', '', $dest_cc);
            $dest_cc = str_replace(';', ',', $dest_cc);
            $string = trim($dest_cc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddAddress(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_bcc) > 0) {
            //$messaggio->AddBCC($dest_bcc);
            $dest_bcc = str_replace(' ', '', $dest_bcc);
            $dest_bcc = str_replace(';', ',', $dest_bcc);
            $string = trim($dest_bcc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddBCC(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    $messaggio->Subject = $oggetto_da_inviare;
    $messaggio->Body = stripslashes(nl2br($messaggio_da_inviare));


    if (!$messaggio->Send()) {
        $log->log_all_errors('attivaCorsoFattura -> Email NON Inviata [' . $messaggio->ErrorInfo . '] -> $destinatario = ' . $destinatario, 'ERRORE');
        $return = false;
    } else {
        $return = true;
    }

    return $return;
}

//INVIO EMAIL PASSWORD DA TEMPLATE
function inviaEmailTemplate_Password($idListaPassword, $nome_tamplate) {
    global $dblink, $moodle, $log;
    
    //require_once BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
    $messaggio = new PHPmailer();
    $messaggio->IsHTML(true);

    # I added SetLanguage like this
    $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
    $messaggio->SMTPAuth = true;                  // enable SMTP authentication
    $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
//    $messaggio->SMTPSecure = 'tls';
    $messaggio->Port = 587;
    // set the SMTP port for the GMAIL server
    $messaggio->Username = USER_MAIL; // SMTP account username
    $messaggio->Password = PASS_MAIL;        // SMTP account password
    //SELECT `id`, `dataagg`, `scrittore`, `stato`, `nome`, `mittente`, `reply`, `destinatario`, `cc`, `bcc`, `oggetto`, `messaggio`, `allegato_1`, `allegato_2`, `allegato_3` FROM `lista_template_email` WHERE 1
    $sql_template = "SELECT * FROM lista_template_email WHERE nome = '" . $nome_tamplate . "'";
    $row_template = $dblink->get_row($sql_template, true);
    //while ($row_template = mysql_fetch_array($rs_template, MYSQL_BOTH)) {
        $mittente = $row_template['mittente'];
        $reply = $row_template['reply'];
        $destinatario_admin = $row_template['destinatario'];
        $dest_cc = $row_template['cc'];
        $dest_bcc = $row_template['bcc'];
        $oggetto_da_inviare = $row_template['oggetto'];
        $messaggio_da_inviare = html_entity_decode($row_template['messaggio']);
    //}

    $sql_password = "SELECT * FROM lista_password WHERE id = '" . $idListaPassword . "'";
    $row_password = $dblink->get_row($sql_password, true);
    //while ($row_password = mysql_fetch_array($rs_password, MYSQL_BOTH)) {
        $username = $row_password['username'];
        $passwd = $row_password['passwd'];
        $cognome_nome_professionista = $row_password['cognome'] . ' ' . $row_password['nome'];
        $dati_credenziali = "
        Indirizzo: " . MOODLE_DOMAIN_NAME . "
        Username: <b>" . $username . "</b>
        Password: " . $passwd . "
        ";
        $destinatario = $row_password['email'];
    //}

    if(EMAIL_DEBUG){
        if (strlen($destinatario_admin) > 5) {
            $destinatario = $destinatario_admin;
        }else{
            $destinatario = trim(EMAIL_TO_SEND_DEBUG);
        }
    }

    $messaggio_da_inviare = str_replace('_XXX_', $cognome_nome_professionista, $messaggio_da_inviare);
    $messaggio_da_inviare = str_replace('_CREDENZIALI_', $dati_credenziali, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_NOME_DEL_CORSO_', $nome_del_corso, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_NOME_ABBONAMENTO_', $nome_del_corso, $messaggio_da_inviare);

    //intestazioni e corpo dell'email
    $messaggio->From = $mittente;
    $messaggio->FromName = $mittente;
    $messaggio->ConfirmReadingTo = $destinatario;
    $messaggio->AddReplyTo($reply);
    
    $messaggio->AddAddress(trim($destinatario));

    if (strlen($destinatario) > 0) {
        $destinatario = str_replace(' ', '', $destinatario);
        $destinatario = str_replace(';', ',', $destinatario);
        $string = trim($destinatario);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_cc) > 0) {
            //$messaggio->AddAddress($dest_cc);
            $dest_cc = str_replace(' ', '', $dest_cc);
            $dest_cc = str_replace(';', ',', $dest_cc);
            $string = trim($dest_cc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddAddress(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_bcc) > 0) {
            //$messaggio->AddBCC($dest_bcc);
            $dest_bcc = str_replace(' ', '', $dest_bcc);
            $dest_bcc = str_replace(';', ',', $dest_bcc);
            $string = trim($dest_bcc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddBCC(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    $messaggio->Subject = $oggetto_da_inviare;
    $messaggio->IsHTML(true);
    $messaggio->Body = stripslashes(nl2br($messaggio_da_inviare));


    if (!$messaggio->Send()) {
        $log->log_all_errors('inviaEmailTemplate_Password -> Email NON Inviata [' . $messaggio->ErrorInfo . '] -> $destinatario = ' . $destinatario, 'ERRORE');
        $return = false;
    } else {
        $return = true;
    }

    return $return;
}

function inviaEmailTemplate_Ticket($idTicket, $nome_tamplate) {
    global $dblink, $moodle, $log;
    
    //require_once BASE_ROOT . "classi/phpmailer/class.phpmailer.php";
    $messaggio = new PHPmailer();
    $messaggio->IsHTML(true);

    # I added SetLanguage like this
    $messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
    $messaggio->SMTPAuth = true;                  // enable SMTP authentication
    $messaggio->Host = "tls://smtp.office365.com"; // sets the SMTP server
    $messaggio->SMTPSecure = 'tls';
    $messaggio->Port = 587;
    // set the SMTP port for the GMAIL server
    $messaggio->Username = USER_MAIL; // SMTP account username
    $messaggio->Password = PASS_MAIL;        // SMTP account password
    //SELECT `id`, `dataagg`, `scrittore`, `stato`, `nome`, `mittente`, `reply`, `destinatario`, `cc`, `bcc`, `oggetto`, `messaggio`, `allegato_1`, `allegato_2`, `allegato_3` FROM `lista_template_email` WHERE 1
    $sql_template = "SELECT * FROM lista_template_email WHERE nome = '" . $nome_tamplate . "'";
    $row_template = $dblink->get_row($sql_template, true);
    //while ($row_template = mysql_fetch_array($rs_template, MYSQL_BOTH)) {
        $mittente = $row_template['mittente'];
        $reply = $row_template['reply'];
        $destinatario_admin = $row_template['destinatario'];
        $dest_cc = $row_template['cc'];
        $dest_bcc = $row_template['bcc'];
        $oggetto_da_inviare = $row_template['oggetto'];
        $messaggio_da_inviare = html_entity_decode($row_template['messaggio']);
    //}

    if($nome_tamplate == "nuovoTicketSupporto"){
        $sql_ticket = "SELECT * FROM lista_ticket WHERE id = '" . $idTicket . "'";
        $row_ticket = $dblink->get_row($sql_ticket, true);
        
        $destinatario = $destinatario_admin;

        $oggetto_da_inviare = str_replace('_TICKET_', "Ticket [ID:".$row_ticket['id']."]", $oggetto_da_inviare);
        $messaggio_da_inviare = str_replace('_URL_TICKET_', "<a href=\"".BASE_URL."/moduli/ticket/dettaglio.php?tbl=lista_ticket&id=".$row_ticket['id']."\">Vedi il Ticket [ID:".$row_ticket['id']."]<a>", $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_OGGETTO_TICKET_', $row_ticket['oggetto'], $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_MITTENTE_TICKET_', $row_ticket['mittente'], $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_DATA_TICKET_', GiraDataOra($row_ticket['dataagg']), $messaggio_da_inviare);
    }else{
        $sql_ticket_dett = "SELECT * FROM lista_ticket_dettaglio WHERE id = '" . $idTicket . "'";
        $row_ticket_dett = $dblink->get_row($sql_ticket_dett, true);
        
        if($_SESSION['livello_utente']=='amministratore'){
            $sql_ticket = "SELECT id_mittente FROM lista_ticket WHERE id = '" . $row_ticket_dett['id_ticket'] . "'";
            $row_ticket = $dblink->get_row($sql_ticket, true);
            $destRisp = $dblink->get_row("SELECT email FROM lista_password WHERE id = '" . $row_ticket['id_mittente'] . "'", true);
            $destinatario = $destRisp['email'];
        }else{
            $destinatario = $destinatario_admin;
        }

        $oggetto_da_inviare = str_replace('_TICKET_', "Ticket [ID:".$row_ticket_dett['id_ticket']."]", $oggetto_da_inviare);
        $messaggio_da_inviare = str_replace('_URL_TICKET_', "<a href=\"".BASE_URL."/moduli/ticket/dettaglio.php?tbl=lista_ticket&id=".$row_ticket_dett['id_ticket']."\">Vedi il Ticket [ID:".$row_ticket_dett['id_ticket']."]<a>", $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_OGGETTO_TICKET_', $row_ticket_dett['oggetto'], $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_MESSAGGIO_TICKET_', $row_ticket_dett['messaggio'], $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_MITTENTE_TICKET_', $row_ticket_dett['mittente'], $messaggio_da_inviare);
        $messaggio_da_inviare = str_replace('_DATA_TICKET_', GiraDataOra($row_ticket_dett['dataagg']), $messaggio_da_inviare);
    }
    
    $mittBcc = $dblink->get_row("SELECT email FROM lista_password WHERE id = '" . $_SESSION['id_utente'] . "'", true);
    $dest_bcc = $mittBcc['email'];
    
    if(EMAIL_DEBUG){
        if (strlen($destinatario_admin) > 5) {
            $destinatario = $destinatario_admin;
        }else{
            $destinatario = trim(EMAIL_TO_SEND_DEBUG);
        }
    }

    //$messaggio_da_inviare = str_replace('_XXX_', $cognome_nome_professionista, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_CREDENZIALI_', $dati_credenziali, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_NOME_DEL_CORSO_', $nome_del_corso, $messaggio_da_inviare);
    //$messaggio_da_inviare = str_replace('_NOME_ABBONAMENTO_', $nome_del_corso, $messaggio_da_inviare);

    //intestazioni e corpo dell'email
    $messaggio->From = $mittente;
    $messaggio->FromName = $mittente;
    $messaggio->ConfirmReadingTo = $destinatario;
    $messaggio->AddReplyTo($reply);
    //$messaggio->AddAddress(trim($destinatario));

    if (strlen($destinatario) > 0) {
        $destinatario = str_replace(' ', '', $destinatario);
        $destinatario = str_replace(';', ',', $destinatario);
        $string = trim($destinatario);
        /* Use tab and newline as tokenizing characters as well  */
        $tok = strtok($string, ",");

        while ($tok !== false) {
            //echo "Word=$tok<br />";
            $messaggio->AddAddress(trim($tok));
            $tok = strtok(",");
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_cc) > 0) {
            //$messaggio->AddAddress($dest_cc);
            $dest_cc = str_replace(' ', '', $dest_cc);
            $dest_cc = str_replace(';', ',', $dest_cc);
            $string = trim($dest_cc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddAddress(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    if(!EMAIL_DEBUG){
        if (strlen($dest_bcc) > 0) {
            //$messaggio->AddBCC($dest_bcc);
            $dest_bcc = str_replace(' ', '', $dest_bcc);
            $dest_bcc = str_replace(';', ',', $dest_bcc);
            $string = trim($dest_bcc);
            /* Use tab and newline as tokenizing characters as well  */
            $tok = strtok($string, ",");

            while ($tok !== false) {
                //echo "Word=$tok<br />";
                $messaggio->AddBCC(trim($tok));
                $tok = strtok(",");
            }
        }
    }

    $messaggio->Subject = $oggetto_da_inviare;
    $messaggio->Body = stripslashes(nl2br($messaggio_da_inviare));


    if (!$messaggio->Send()) {
        //echo $messaggio->ErrorInfo;
        $log->log_all_errors('attivaCorsoFattura -> Email NON Inviata [' . $messaggio->ErrorInfo . '] -> $destinatario = ' . $destinatario, 'ERRORE');
        $return = false;
    } else {
        //echo '<li>Email Inviata Correttamente !</li>';
        $return = true;
    }

    return $return;
}

?>
