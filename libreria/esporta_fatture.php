<?php

if(isset($_GET['mese']) && isset($_GET['anno']) && isset($_GET['sezionale']) && isset($_GET['stato'])){
    $mese = $_GET['mese'];
    $anno = $_GET['anno'];
    $sezionale = $_GET['sezionale'];
    $stato = $_GET['stato'];
}else{
    $sezionale = "00";
    $stato = "In Attesa";
    $mese = date("m", strtotime('-1 month'));
    if($mese == "01"){
        $anno = date('Y', strtotime('-1 years'));
    }else{
        $anno = date('Y');
    }
}

$fileName = "fatture_".str_replace(" ", "-", $stato)."_".$mese."_".$anno."_sezionale_".$sezionale."";

$rowsFatture = $dblink->get_results("SELECT * FROM lista_fatture WHERE YEAR(data_creazione) = '$anno' AND MONTH(data_creazione) = '$mese' AND sezionale='".$sezionale."' AND (stato LIKE '".$stato."') LIMIT 1");

$righe = "";

foreach ($rowsFatture as $rowFattura) {
        
    if(!empty($rowFattura)){
        
        $datiFatturazione = $dblink->get_row("SELECT * FROM lista_aziende WHERE id = '".$rowFattura['id_azienda']."'",true);
        $rsDatiPagamento = $dblink->get_results("SELECT data_creazione, entrate FROM lista_costi WHERE id_fattura = '".$rowFattura['id']."'");

        $riga = "";
        $riga .= "00000";       // TRF-DITTA
        $riga .= "3";           // TRF-VERSIONE
        $riga .= "0";           // TRF-TARC
        $riga .= "00000";       // TRF-COD-CLIFOR
        $riga .= aggiungiSpaziAllaStringa(mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['ragione_sociale']." ".$datiFatturazione['forma_giuridica'])), "UTF-8", "HTML-ENTITIES"), 32);  //TRF-RASO
        $riga .= aggiungiSpaziAllaStringa(mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['indirizzo'])), "UTF-8", "HTML-ENTITIES"), 30);  //TRF-IND
        $riga .= aggiungiSpaziAllaStringa($datiFatturazione['cap'], 5);  //TRF-CAP
        $riga .= aggiungiSpaziAllaStringa(mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['citta'])), "UTF-8", "HTML-ENTITIES"), 25);  //TRF-CITTA
        $riga .= aggiungiSpaziAllaStringa(mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['provincia'])), "UTF-8", "HTML-ENTITIES"), 2);  //TRF-PROV
        $riga .= aggiungiSpaziAllaStringa($datiFatturazione['codice_fiscale'], 16);  //TRF-COFI
        $riga .= aggiungiSpaziAllaStringa($datiFatturazione['partita_iva'], 11);  //TRF-PIVA
        $riga .= aggiungiSpaziAllaStringa(verificaSePersonaFisica($datiFatturazione['codice_fiscale']), 1);  //TRF-PF
        if(verificaSePersonaFisica($datiFatturazione['codice_fiscale']) == "S"){
            $riga .= aggiungiSpaziAllaStringa(strpos($datiFatturazione['ragione_sociale'], " "), 2, true, "0", false);  //TRF-DIVIDE
        }else{
            $riga .= aggiungiSpaziAllaStringa("00", 2, true, "0", false);  //TRF-DIVIDE
        }
        $riga .= aggiungiSpaziAllaStringa("0000", 4, true, "0", false);  //TRF-PAESE
        $riga .= aggiungiSpaziAllaStringa("", 12, false);  //TRF-PIVA-ESTERO
        $riga .= aggiungiSpaziAllaStringa("", 20, false);  //TRF-COFI-ESTERO
        $riga .= aggiungiSpaziAllaStringa("", 1, false);  //TRF-SESSO
        $riga .= aggiungiSpaziAllaStringa("", 8, false, "0");  //TRF-DTNAS
        $riga .= aggiungiSpaziAllaStringa("", 25, false);  //TRF-COMNA
        $riga .= aggiungiSpaziAllaStringa("", 2, false);  //TRF-PRVNA
        $riga .= aggiungiSpaziAllaStringa("", 4, false);  //TRF-PREF
        $riga .= aggiungiSpaziAllaStringa("", 20, false);  //TRF-NTELE-NUM
        $riga .= aggiungiSpaziAllaStringa("", 4, false);  //TRF-FAX-PREF
        $riga .= aggiungiSpaziAllaStringa("", 9, false);  //TRF-FAX-NUM
        $riga .= aggiungiSpaziAllaStringa("", 7, false, "0");  //TRF-CFCONTO
        $riga .= aggiungiSpaziAllaStringa("", 4, false, "0");  //TRF-CFCODPAG
        $riga .= aggiungiSpaziAllaStringa("", 5, false, "0");  //TRF-CFBANCA
        $riga .= aggiungiSpaziAllaStringa("", 5, false, "0");  //TRF-CFAGENZIA
        $riga .= aggiungiSpaziAllaStringa("", 1, false, "0");  //TRF-CFINTERM
        $riga .= aggiungiSpaziAllaStringa("001", 3, true, "0");  //TRF-CAUSALE
        $riga .= aggiungiSpaziAllaStringa("FATTURA DI VENDITA", 15, true);  //TRF-CAU-DES
        $riga .= aggiungiSpaziAllaStringa("", 18, false);  //TRF-CAU-AGG
        $riga .= aggiungiSpaziAllaStringa("", 34, false);  //TRF-CAU-AGG-1
        $riga .= aggiungiSpaziAllaStringa("", 34, false);  //TRF-CAU-AGG-2
        $riga .= aggiungiSpaziAllaStringa("", 8, false, "0");  //TRF-DATA-REGISTRAZIONE
        $riga .= aggiungiSpaziAllaStringa(str_replace("-", "", $rowFattura['data_creazione']), 8, true, "0");  //TRF-DATA-DOC
        $riga .= aggiungiSpaziAllaStringa("", 8, false, "0");  //TRF-NUM-DOC-FOR
        $riga .= aggiungiSpaziAllaStringa($rowFattura['codice'], 5, true, "0", false);  //TRF-NDOC
        $riga .= aggiungiSpaziAllaStringa($rowFattura['sezionale'], 2, true, "0", false);  //TRF-SERIE
        
        /*$document->addChild('CustomerPostcode', $datiFatturazione['cap']);
        $document->addChild('CustomerCity', mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['citta'])), "UTF-8", "HTML-ENTITIES"));
        $document->addChild('CustomerProvince', mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['provincia'])), "UTF-8", "HTML-ENTITIES"));
        $document->addChild('CustomerCountry', mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['nazione'])), "UTF-8", "HTML-ENTITIES"));
        $document->addChild('CustomerFiscalCode', $datiFatturazione['partita_iva']);
        $document->addChild('CustomerCellPhone', $datiFatturazione['cellulare']);
        $document->addChild('CustomerEmail', mb_convert_encoding(htmlspecialchars(html_entity_decode($datiFatturazione['email'])), "UTF-8", "HTML-ENTITIES"));
        $document->addChild('Date', $rowFattura['data_creazione']);
        $document->addChild('Number', $rowFattura['codice']);
        $document->addChild('TotalWithoutTax', $rowFattura['imponibile']);
        $document->addChild('VatAmount', ($rowFattura['importo']-$rowFattura['imponibile']));
        $document->addChild('Total', $rowFattura['importo']);
        $payments = $document->addChild('Payments');
        if($rowFattura['stato'] == "In Attesa"){
            $payment = $payments->addChild('Payment');
            $payment->addChild('Advance', 'false');
            $payment->addChild('Date', "_XXX_REPLACE_VOID_XXX_");
            $payment->addChild('Amount', "_XXX_REPLACE_VOID_XXX_");
            $payment->addChild('Paid', 'false');
        }else{
            foreach ($rsDatiPagamento as $rowDatiPagamento) {
                $payment = $payments->addChild('Payment');
                $payment->addChild('Advance', 'false');
                $payment->addChild('Date', $rowDatiPagamento['data_creazione']);
                $payment->addChild('Amount', $rowDatiPagamento['entrate']);
                $payment->addChild('Paid', 'true');
            }
        }
        $document->addChild('InternalComment', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('CustomField1', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('CustomField2', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('CustomField3', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('CustomField4', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('FootNotes', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('SalesAgent', "_XXX_REPLACE_VOID_XXX_");
        $document->addChild('DelayedVat','false');
        $rows = $document->addChild('Rows');

        $rsDettaglioFattura = $dblink->get_results("SELECT * FROM lista_fatture_dettaglio WHERE id_fattura = '".$rowFattura['id']."'");

        foreach ($rsDettaglioFattura as $rowDettaglioFattura) {
            $row = $rows->addChild('Row');
            $row->addChild('Code', "_XXX_REPLACE_VOID_XXX_");
            $row->addChild('Description', $rowDettaglioFattura['prezzo_prodotto']);
            $row->addChild('Qty', $rowDettaglioFattura['quantita']);
            $row->addChild('Um', 'nr');
            $row->addChild('Price', $rowDettaglioFattura['prezzo_prodotto']);
            $row->addChild('Discounts', "_XXX_REPLACE_VOID_XXX_");
            $VatCode = $row->addChild('VatCode', $rowDettaglioFattura['iva_prodotto']);
            $VatCode->addAttribute("Perc", $rowDettaglioFattura['iva_prodotto']);
            $VatCode->addAttribute("Class", 'Imponibile');
            $VatCode->addAttribute("Description", 'Aliquota '.$rowDettaglioFattura['iva_prodotto'].'%');
            $row->addChild('Total', $rowDettaglioFattura['prezzo_prodotto']);
            $row->addChild('Stock', 'false');
            $row->addChild('Notes', "_XXX_REPLACE_VOID_XXX_");
        }*/
    }
    
    $righe.=$riga."\r\n";
}

Header('Content-type: text/txt; charset=utf-8');
Header('Content-Disposition: attachment; filename='.$fileName);
print($righe);

function aggiungiSpaziAllaStringa($testo, $numSpazi = 0 , $contaStringa = true, $carattere = " ",$dopo = true){
    
    if($contaStringa){
        $numPartenza = strlen($testo);
    }else{
        $numPartenza = 0;
    }
    
    $spazi = "";
    
    if($numPartenza > $numSpazi){
        $testo = substr($testo, 0, $numSpazi);
    }else{
        for($i = $numPartenza; $i<$numSpazi; $i++){
            $spazi.=$carattere;
        }
    }
    if($dopo){
        return $testo.$spazi;
    }else{
        return $spazi.$testo;
    }
}

function verificaSePersonaFisica($codiceFiscale){
    global $dblink;
    
    $numRow = $dblink->num_rows("SELECT * FROM lista_professionisti WHERE codice_fiscale = '$codiceFiscale'");
    
    if($numRow > 0){
        return "S";
    }else{
        return "N";
    }
}

?>