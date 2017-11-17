<?php
session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

/* VERSIONE */
define("VERSIONE", "v2.0.0 (AREA SVILUPPO PHP ".phpversion().")");
define("COPYRIGHT", "CEMA NEXT Srl");
define("LAST_UPDATE", date("d-m-Y"));

/* SERVER GESTIONALE */
define("BASE_URL", (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME']."" : "http://".$_SERVER['SERVER_NAME']."");
define("BASE_ROOT", $_SERVER['DOCUMENT_ROOT']."/");
define('ERP_DOMAIN_NAME', 'http://dev.xxxxxxxx.com');

define( 'DB_HOST', 'localhost' ); // set database host
define( 'DB_USER', 'root' ); // set database user

define( 'DB_PASS', 'root' ); // set database password
define( 'DB_NAME', 'erp_betaform' ); // set database name

define('DURATA_CORSO', "190"); //6 MESI IN GIORNI
define('DURATA_CORSO_INGEGNERI', "190"); //6 MESI IN GIORNI
define('DURATA_ABBONAMENTO', "370"); //12 MESI IN GIORNI
define('DURATA_PASSWORD_UTENTE', "190"); //6 MESI IN GIORNI

define('SUFFISSO_CODICE_CLIENTE', "BF"); //BF - Betaformazione

/** CONFIGURAZIONE MOODLE DI BETAFORMAZIONE **/
define('MOODLE_DOMAIN_NAME', 'http://mdl.xxxxxxxx.com');
define('MOODLE_DB_NAME', 'dev_el_betaform');
define('MOODLE_TOKEN', '');

/** CONFIGURAZIONE SITO WORDPRESS DI BETAFORMAZIONE **/
define('WP_DOMAIN_NAME', 'http://demo.xxxxxxxx.com');
define('WP_DB_NAME', 'wp_betaform');


define( 'SEND_ERRORS_TO', '' ); //set email notification email address
define( 'DISPLAY_DEBUG', TRUE ); //display db errors?
require_once( BASE_ROOT.'classi/class.db.php' );

//NUOVA CONNESSIONE DATABASE VIA CLASSE
global $dblink;
$dblink = DB::getInstance();

define( 'COLORE_PRIMARIO', 'yellow-soft' );
define( 'COLORE_PRIMARIO_FONT', 'font-yellow-soft' );
define( 'COLORE_PRIMARIO_FONT_BACKGROUND', 'bg-yellow-soft bg-font-yellow-soft' );
define( 'COLORE_PRIMARIO_FONT_BORDER', 'border-yellow-soft' );

define('LOG_DEBUG_ALL', true ); //STAMPA AVVISO, OK ed ERRORE - False: Stampa solo ERRORE
require_once( BASE_ROOT.'classi/class.log.php' );
$log = new logerp();

define('EMAIL_DEBUG', true);
define("EMAIL_TO_SEND_DEBUG", "supporto@cemanext.it");

?>
