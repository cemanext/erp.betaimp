<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
if ($browser == true) {
    //echo 'Code You Want To Execute';
}

if (isset($_GET['idA'])) {
    $id_area = $_GET['idA'];
} else {
    $id_area = 0;
}

if (isset($_GET['idFatt'])) {
    if (strlen($_SESSION['passwd_email_utente']) > 2 && strpos($_SESSION['email_utente'], "@betaimprese.com") > 0) {
        $mitt = $_SESSION['email_utente'];
    } else {
        $mitt = 'vitali@betaimprese.com';
    }

    creaFatturaPDF($_GET['idFatt'], false);

    $sql = "SELECT * FROM lista_fatture WHERE id='" . $_GET['idFatt'] . "'";
    $row = $dblink->get_row($sql, true);

    $n_progetto = str_replace("/", "-", $row['codice']);
    $filename = "BetaImprese_Fattura_" . $n_progetto . "-" . $row['sezionale'] . ".pdf";
    $filename_oggetto = "Fattura " . $row['codice'] . "-" . $row['sezionale'] . "";
    $causale = $row['causale'];

    $id_Fattura = $_GET['idFatt'];

    $sql_prev = "SELECT email FROM lista_aziende INNER JOIN lista_fatture ON lista_aziende.id=lista_fatture.id_azienda WHERE lista_fatture.id='" . $id_Fattura . "'";
    $emailDesti = $dblink->get_field($sql_prev);

    $dest = $emailDesti;
    $dest_cc = '';
    $dest_bcc = '';
    $ogg = 'Beta Imprese s.r.l. -  ';
    $mess = 'Gentile Cliente,<br>
            in allegato alla presente Le inviamo la copia (in formato PDF) della fattura relativa ai servizi da noi forniti.<br>
            Il presente invio SOSTITUISCE INTEGRALMENTE quello effettuato in modo tradizionale a mezzo servizio postale .<br>
            Tale operazione &egrave; ammessa dalla normativa fiscale in essere, relativa alla "Trasmissione delle Fatture" per via Telematica:<br>
            - R.M. n. 571134 del 19/07/88 - (posta elettronica)<br>
            - R.M. n. 450217 del 30/07/90 - (procedure informatizzate)<br>
            - R.M. n. 107 del 04/07/01 - (trasmissione fatture)<br>
            - R.M. n. 202/E del 04/12/01 - (archiviazione fatture)<br>
            Risoluzioni che forniscono chiarimenti in ordine alle condizioni necessarie per l\'ammissibilit&agrave; ai sensi dell\'art. 21 D.P.R. 26/10/72 n. 633 della procedura di trasmissione e memorizzazione delle fatture mediante sistemi combinati fondati sull\'impiego congiunto di supporti informatici, telefax e posta elettronica.<br>
            La normativa nazionale italiana ad oggi NON consente l\'archiviazione di alcun documento contabile in formato digitale.<br>
            Quindi &egrave; necessario GENERARNE UNA STAMPA e procedere alla relativa archiviazione come da prassi a norma di legge<br>
            Il file &egrave; in formato pdf di seguito il link del software gratuito per la visualizzazione e la stampa di questo formato:<br>
            http://get.adobe.com/it/reader/<br><br>

	<br><img src="http://betaimprese.com/wp-content/uploads/2017/03/BETA-IMPRESE-DEFINITIVO_ALTA-RISOLUZIONE-e1489148974244.png" alt="Beta Imprese s.r.l." title="Beta Imprese s.r.l." width="250px">
	<br>
        <b>Sede legale e operativa : via Risorgimento, 36 - 48022 Lugo (RA)<b><br>
        Tel. <b>0545 900600</b> - Fax <b>0545 900600</b> - <a href="http://www.betaimprese.com/">www.betaimprese.com</a>
        <h6>Le informazioni contenute in questa e-mail e negli eventuali allegati sono riservate e destinate esclusivamente alla persona sopraindicata. Qualora non foste il destinatario, siete pregati di distruggere questo messaggio e notificarci il problema immediatamente.<br>
        In ogni caso, non dovrete spedire a terzi, copiare, usare o diffondere il contenuto di questa e-mail e degli eventuali allegati. Si ricorda che la diffusione l\'utilizzo e/o la conservazione dei dati ricevuti per errore costituiscono violazione alle disposizioni del D.lgs. n. 196/2003 (Codice in materia di protezione dei dati personali) oltre a costituire violazione di carattere penale ai sensi dell\'art. 616 C.P.
        </h6>';
}
?>
<form action="salva.php?fn=inviaEmailFattura" method="post" enctype="multipart/form-data" class="form">
    <div class="modal-body">
        <h3 class="form-section">Invia Fattura </h3>
        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                    <input name="mitt" id="mitt" type="text" class="form-control tooltips" placeholder="Mittente" value="<?php echo $mitt; ?>" data-container="body" data-placement="top" data-original-title="MITTENTE"></div></div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                    <input name="dest" id="dest" type="text" class="form-control tooltips" placeholder="Destinatario" value="<?php echo $dest; ?>" data-container="body" data-placement="top" data-original-title="DESTINATARIO"></div></div>
        </div>
        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                    <input name="dest_cc" id="dest_cc" type="text" class="form-control tooltips" placeholder="CC" value="<?php echo $dest_cc; ?>" data-container="body" data-placement="top" data-original-title="CC"></div></div>
        </div>
        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-addon" style="background-color: #fff;"><i class="fa fa-user font-grey-mint"></i></span>
                    <input name="ogg" id="ogg" type="text" class="form-control tooltips" placeholder="Oggetto" value="<?php echo $ogg . str_replace('_', ' ', str_replace('.pdf', '', $filename_oggetto)) . ''; ?>" data-container="body" data-placement="top" data-original-title="OGGETTO"></div></div>
        </div>
        <div class="row" style="margin-bottom:10px;">
            <div class="col-md-9">
                <div class="mt-checkbox-inline">
                    <label class="mt-checkbox font-blue-steel">
                        <input type="checkbox" id="fileDoc" name="fileDoc" value="<?php echo $filename; ?>" checked> <?php echo $filename; ?>
                        <input type="HIDDEN" VALUE="<?php echo $id_fattura; ?>" NAME="id_fattura">
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="btn-set pull-left">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <span class="btn btn-primary btn-file btn-sm">
                                <span class="fileinput-new"> Seleziona Allegato </span>
                                <span class="fileinput-exists"> Cambia </span>
                                <input type="file" name="documentoAllegato1">
                            </span>
                            <span class="fileinput-filename"> </span> &nbsp;
                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                        </div>
                    </div>
                    <div class="btn-set pull-left">
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom:10px;">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea id="mess" name="mess" class="wysihtml5 form-control" rows="6"><?php echo $mess; ?></textarea>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Annulla</button>
            <button type="submit" name="Invia" value="Invia" class="btn green">Invia</button>
        </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        ComponentsEditors.init();
    });
</script>
