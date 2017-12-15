<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

include_once(BASE_ROOT . 'moduli/preventivi/funzioni.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
if ($browser == true) {
    //echo 'Code You Want To Execute';
}

if (isset($_GET['idA'])) {
    $id_area = $_GET['idA'];
} else {
    $id_area = 0;
}

if (isset($_GET['idPrev'])) {

    creaPreventivoPDF($_GET['idPrev'], false);

    if (isset($_SESSION['email_utente'])) {
        $mitt = $_SESSION['email_utente'];
    } else {
        $mitt = 'vitali@betaimprese.com';
    }

    $sql = "SELECT codice FROM lista_preventivi WHERE id='" . $_GET['idPrev'] . "'";
    list($codice) = $dblink->get_row($sql);

    $n_progetto = str_replace("/", "-", $codice);
    $filename = "BetaImprese_Ordine_" . $_GET['idPrev'] . ".pdf";
    $filename_oggetto = "Ordine " . $_GET['idPrev'] . "";

    $id_Preventivo = $_GET['idPrev'];

    $sql_prev = "SELECT email, id_calendario FROM lista_professionisti INNER JOIN lista_preventivi ON lista_professionisti.id=lista_preventivi.id_professionista WHERE lista_preventivi.id='" . $id_Preventivo . "'";
    list($emailDesti, $id_calendario) = $dblink->get_row($sql_prev);

//echo '<h1>$emailDesti = '.$emailDesti.'</h1>';

    if (strlen($emailDesti) <= 1) {

        $sql_prev = "SELECT id_calendario FROM lista_preventivi WHERE id='" . $id_Preventivo . "'";
        list($id_calendario) = $dblink->get_row($sql_prev);

//echo '<h1>$id_calendario = '.$id_calendario.'</h1>';

        $sql_prev_cal = "SELECT campo_5 FROM calendario WHERE id='" . $id_calendario . "'";
        list($emailDesti) = $dblink->get_row($sql_prev_cal);
    }

//echo '<h1>$emailDesti = '.$emailDesti.'</h1>';
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
<form action="salva.php?fn=inviaEmailPreventivo" method="post" enctype="multipart/form-data" class="form">
    <div class="modal-body">
        <h3 class="form-section">Invia Preventivo </h3>
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
                        <input type="checkbox" id="fileDoc" name="fileDoc" value="<?php echo $filename; ?>"> <?php echo $filename; ?>
                        <input type="HIDDEN" VALUE="<?php echo $id_Preventivo; ?>" NAME="id_preventivo">
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
