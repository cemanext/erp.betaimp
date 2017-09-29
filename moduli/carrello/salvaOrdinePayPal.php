<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$id_utente = $_GET['id_utente'];
$id_azienda = $_GET['id_azienda'];
$id_ordine = $_GET['id_ordine'];

$rowProff = $dblink->get_row("SELECT * FROM lista_professionisti WHERE id='$id_utente'",true);
$rowAzienda = $dblink->get_row("SELECT * FROM lista_aziende WHERE id='$id_azienda'",true);
$rowOrdine = $dblink->get_row("SELECT * FROM lista_ordini WHERE id='$id_ordine'",true);
?>
<html>
<head><title>::AREA CLIENTI BETA FORMAZIONE - PayPal::</title></head>
<body onLoad="document.paypal_pagamento.submit();"><!--onLoad="document.paypal_pagamento.submit();"-->
<form action="<?= BASE_URL ?>/paypal/process.php" method="post" id="paypal_pagamento" class="form-horizontal" name="paypal_pagamento" autocomplete="off">
<input type="hidden" name="lastname" id="lastname" value="<?php echo $rowProff['cognome']; ?>">
<input type="hidden" name="firstname" id="firstname" value="<?php echo $rowProff['nome']; ?>">
<input type="hidden" name="address1" id="address1" value="<?php echo $rowAzienda['indirizzo']; ?>">
<input type="hidden" name="city" id="city" value="<?php echo $rowAzienda['citta']; ?>">
<input type="hidden" name="state" id="state" value="<?php echo $rowAzienda['provincia']; ?>">
<input type="hidden" name="zip" id="zip" value="<?php echo $rowAzienda['cap']; ?>">
<input type="hidden" name="phone1" id="phone1" value="<?php echo $rowAzienda['telefono']; ?>">
<input type="hidden" name="email" id="email" value="<?php echo $rowAzienda['email']; ?>">
<input type="hidden" name="item_name" id="item_name" value="<?php echo "Ordine n. ".$idOrdine."/".$rowOrdine['sezionale']; ?>">
<input type="hidden" name="quantity" id="quantity" value="1">
<!-- <input type="hidden" name="amount" id="amount" value="<?php echo round($rowOrdine['importo']*1.04,2); ?>"> -->
<input type="hidden" name="amount" id="amount" value="1">
<center><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="333333">In Processo . . . </font></center>
</form>
</body>
</html>