<?php
require 'config/connDB.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require BASE_ROOT.'classi/phpmailer/src/Exception.php';
require BASE_ROOT.'classi/phpmailer/src/PHPMailer.php';
require BASE_ROOT.'classi/phpmailer/src/SMTP.php';

define("SERVER_HOST_MAIL", "smtp.fpcingegneri.com");
define("SECURE_SMTP_MAIL", '');
define("PORT_MAIL", "25");
define("PASS_MAIL", "form2018ttcme");
define("USER_MAIL", "erp@betaimprese.com");

/*define("SERVER_HOST_MAIL", "authsmtp.betaformazione.com");
define("SECURE_SMTP_MAIL", "");
define("PORT_MAIL", "25");
define("PASS_MAIL", "p8arEtha@1");
define("USER_MAIL", "smtp@betaformazione.com");*/

$mitt = USER_MAIL;
$dest = EMAIL_TO_SEND_DEBUG;

$messaggio = new PHPmailer();
$messaggio->IsHTML(true);
$messaggio->IsSMTP();
$messaggio->SMTPDebug  = 2;
# I added SetLanguage like this
$messaggio->SetLanguage('it', BASE_ROOT . 'classi/phpmailer/language/');
//  $messaggio->IsSMTP(); // telling the class to use SMTP			//$messaggio->IsSMTP();
$messaggio->SMTPAuth = true;                  // enable SMTP authentication
$messaggio->Host = SERVER_HOST_MAIL; // sets the SMTP server
$messaggio->SMTPSecure = SECURE_SMTP_MAIL;
$messaggio->Port = PORT_MAIL;
// set the SMTP port for the GMAIL server
$messaggio->Username = USER_MAIL; // SMTP account username
$messaggio->Password = PASS_MAIL;        // SMTP account password
//echo '<h2>$email_mittente = '.$email_mittente.'</h2>';
//intestazioni e corpo dell'email
$messaggio->From = $mitt;
$messaggio->FromName = $mitt;
$messaggio->ConfirmReadingTo = $mitt;
$messaggio->AddReplyTo($mitt);


$dest = str_replace(' ', '', $dest);
$dest = str_replace(';', ',', $dest);
$string = trim($dest);
/* Use tab and newline as tokenizing characters as well  */
$tok = strtok($string, ",");

while ($tok !== false) {
    $messaggio->AddAddress(trim($tok));
    $tok = strtok(",");
}

$messaggio->Subject = "MAIL DI TEST BETAIMPRESE";
$messaggio->Body = stripslashes("Messaggio di prova.");


if (!$messaggio->Send()) {
    echo $messaggio->ErrorInfo;
} else {
   echo '<li>Email Inviata Correttamente !</li>';
}

?>