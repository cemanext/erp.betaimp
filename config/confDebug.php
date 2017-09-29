<?php
include_once(BASE_ROOT.'libreria/libreria.php');

if(isset($_GET['mess']) and $_GET['mess']=='delok'){
	echo '<b class="operazioneOK">Record Eliminato Correttamente!</b>';
}

/* echo '<div id="debug" class="debug"><b onClick="document.getElementById(\'debug\').style.display = \'none\';">[X] </b> ['.date('d.M.Y H:i:s').']';
	echo '<li>utente = '.$_SESSION['utente'].'</li>';
	echo '<li>id_utente = '.$_SESSION['id_utente'].'</li>';
	echo '<li>livello_utente = '.$_SESSION['livello_utente'].'</li>';
	echo '<li>nome_utente = '.$_SESSION['nome_utente'].'</li>';
	echo '<li>cognome_utente = '.$_SESSION['cognome_utente'].'</li>';
	echo '<li>alias_utente = '.$_SESSION['alias_utente'].'</li>';
	echo '<li>cellulare_utente = '.$_SESSION['cellulare_utente'].'</li>';
	echo '<li>cognome_nome_utente = '.$_SESSION['cognome_nome_utente'].'</li>';
	echo '<li>email_utente = '.$_SESSION['email_utente'].'</li>';
	echo '<li>Salvaoba_utente = '.$_SESSION['Salvaoba_utente'].'</li>';
echo '</div>'; */
?>
