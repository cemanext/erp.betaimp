<?php

session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/connDB.php');
include_once(BASE_ROOT . 'libreria/libreria.php');
include_once(BASE_ROOT . 'classi/webservice/client.php');

$moodle = new moodleWebService();

$sql_0001 = "CREATE TEMPORARY TABLE corsi(SELECT DISTINCT lista_fatture_dettaglio.id_professionista AS idProfessionista,
        lista_fatture_dettaglio.id_fattura AS 'idFattura',
        lista_fatture_dettaglio.id AS 'idFatturaDettaglio',
        lista_corsi.id AS 'idCorso',
        id_corso_moodle AS 'idCorsoMoodle',
        id_moodle_user AS 'idUtenteMoodle',
        lista_password.id_classe AS 'idClasseMoodle',
        IF(id_moodle_user<=0,'NON ATTIVARE', 'ATTIVARE') AS 'controllo',
        CONVERT(lista_corsi.nome_prodotto USING utf8) AS 'Prodotto'
        FROM lista_corsi INNER JOIN lista_fatture_dettaglio
        ON lista_corsi.id_prodotto = lista_fatture_dettaglio.id_prodotto 
        INNER JOIN lista_password ON lista_password.id_professionista = lista_fatture_dettaglio.id_professionista
        WHERE lista_fatture_dettaglio.id_professionista>0
        AND lista_password.id_moodle_user>0
        AND lista_fatture_dettaglio.id NOT IN (SELECT DISTINCT id_fattura_dettaglio FROM lista_iscrizioni WHERE 1));";
$rs_0001 = $dblink->query($sql_0001);

$sql_0002 = "CREATE TEMPORARY TABLE abbonamenti(SELECT DISTINCT lista_fatture_dettaglio.id_professionista  AS idProfessionista,
        lista_fatture_dettaglio.id_fattura AS 'idFattura',
        lista_fatture_dettaglio.id AS 'idFatturaDettaglio',
        '' AS 'idCorso',
        '' AS 'idCorsoMoodle',
        id_moodle_user AS 'idUtenteMoodle',
        lista_password.id_classe AS 'idClasseMoodle',
        IF(lista_password.id_classe<=0,'NON ATTIVARE','ATTIVARE') AS 'controllo',
        CONVERT(lista_prodotti.nome USING utf8) AS 'Prodotto'
        FROM lista_fatture_dettaglio INNER JOIN lista_prodotti ON lista_fatture_dettaglio.id_prodotto = lista_prodotti.id 
        INNER JOIN lista_password ON lista_password.id_professionista = lista_fatture_dettaglio.id_professionista
        WHERE  lista_fatture_dettaglio.id_professionista>0
        AND lista_password.id_moodle_user>0
        AND  lista_prodotti.gruppo LIKE 'ABBONAMENTO'
        AND lista_fatture_dettaglio.id NOT IN (SELECT DISTINCT id_fattura_dettaglio FROM lista_iscrizioni WHERE 1));";
$rs_0002 = $dblink->query($sql_0002);

$sql_0003 = "CREATE TEMPORARY TABLE attivazioniIscrizioni SELECT * FROM corsi
        UNION 
        SELECT *  FROM abbonamenti;";
$rs_0003 = $dblink->query($sql_0003);

$sql_00000000 = "SELECT DISTINCT idFattura, idFatturaDettaglio, idCorso, idCorsoMoodle, idUtenteMoodle, idClasseMoodle,

        (SELECT DISTINCT CONCAT(cognome, ' ', nome) FROM lista_professionisti WHERE id = id_professionista) as Professionista, Prodotto, controllo 
        FROM attivazioniIscrizioni WHERE 1 ORDER BY idFattura DESC";

$sql_00000000 = "SELECT DISTINCT 
        (SELECT DISTINCT CONCAT('<h3><b>',cognome, ' ', nome,'</b></h3>') FROM lista_professionisti WHERE id = id_professionista) as Professionista, 
        IF(LCASE(Prodotto) LIKE 'abbonamento%','ABBONAMENTO','SINGOLO CORSO') AS 'Tipo',
        CONCAT('<h3>',Prodotto,'</h3>') AS Prodotto,
        (SELECT DISTINCT nome FROM lista_classi WHERE id = idClasseMoodle) AS 'Classe',
        controllo
        FROM attivazioniIscrizioni WHERE 1 ORDER BY idFattura DESC";

$sql_00000000 = "SELECT DISTINCT *,
        IF(LCASE(Prodotto) LIKE 'abbonamento%','ABBONAMENTO','SINGOLO CORSO') AS 'Tipo'
        FROM attivazioniIscrizioni WHERE 1 ORDER BY idFattura DESC LIMIT 10";
stampa_table_datatables_responsive($sql_00000000, $titolo, 'tabella_base');

$rs_00000000 = $dblink->get_results($sql_00000000);
echo '<ol>';
foreach ($rs_00000000 AS $row_00000000) {
    //echo '<lI>'.$row_00000000['Professionista'].' ----> '.$row_00000000['Tipo'].' ----> '.$row_00000000['controllo'].' strlen(bottone)='.strlen($row_00000000['controllo']).'</li>';
    $idProfessionista = $row_00000000['idProfessionista'];
    $idFattura = $row_00000000['idFattura'];
    $idFatturaDettaglio = $row_00000000['idFatturaDettaglio'];
    $idCorso = $row_00000000['idCorso'];
    $idUtenteMoodle = $row_00000000['idUtenteMoodle'];
    $idCorsoMoodle = $row_00000000['idCorsoMoodle'];


    if ($row_00000000['controllo'] == 'ATTIVARE') {
        if ($row_00000000['Tipo'] == 'SINGOLO CORSO') {
            $ok = attivaCorsoFattura($idProfessionista, $idFattura, $idFatturaDettaglio, $idCorso, $idUtenteMoodle, $idCorsoMoodle);
            if ($ok) {
                echo '<li style="color: green;"> attivaCorsoFattura --> OK !</li>';
                $log->log_all_errors('attivaCorsoFattura -> Corso Attivato Correttamente [idCorsoMoodle = ' . $idCorsoMoodle . ']', 'OK');
            } else {
                echo '<li style="color: RED;"> attivaCorsoFattura --> KO !</li>';
                $log->log_all_errors('attivaCorsoFattura -> Corso NON Attivato [idCorsoMoodle = ' . $idCorsoMoodle . ']', 'ERRORE');
            }
        } elseif ($row_00000000['Tipo'] == 'ABBONAMENTO') {
            $ok = attivaAbbonamentoFattura($idProfessionista, $idFattura, $idFatturaDettaglio, $idUtenteMoodle);
            if ($ok) {
                echo '<li style="color: green;"> attivaAbbonamentoFattura --> OK !</li>';
                $log->log_all_errors('attivaAbbonamentoFattura -> Abbonamento Attivato Correttamente', 'OK');
            } else {
                echo '<li style="color: RED;"> attivaAbbonamentoFattura --> KO !</li>';
                $log->log_all_errors('attivaAbbonamentoFattura -> Abbonamento NON Attivato', 'ERRORE');
            }
        }
    } else {
        
    }
    sleep(5);
}
echo '</ol>';
?>