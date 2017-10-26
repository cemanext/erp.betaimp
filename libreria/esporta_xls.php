<?php

if(isset($_GET['mese']) && isset($_GET['anno']) && isset($_GET['tipo'])){
    $mese = $_GET['mese'];
    $anno = $_GET['anno'];
    $tipo = $_GET['tipo'];
}else{
    $tipo = "Fattura";
    $mese = date("m");
    if($mese == "01"){
        $mese = "12";
        $anno = date('Y', strtotime('-1 years'));
    }else{
        $mese = date("m", strtotime('-1 month'));
        $anno = date('Y');
    }
}


if(isset($_GET['idCommerciale']) && $_GET['idCommerciale']>0){
    $whereCommerciale = " AND id_agente ='".$_GET['idCommerciale']."'";
}else{
    $whereCommerciale = '';
}
//$sql_0001 = creaSQLesporta();
$sql_0001 = "SELECT IF(tipo='Fattura', 'Fatt. Imm.', 'N. C.') AS 'Tipo doc.', sezionale AS 'Sz.', codice AS 'Nr.doc.', DATE_FORMAT(DATE(data_creazione), '%d/%m/%Y') AS 'Data Doc.',
            (SELECT CONCAT(ragione_sociale,' ',forma_giuridica) AS rag_soc FROM lista_aziende WHERE id = id_azienda) AS 'Ragione Sociale Anagrafica',
            REPLACE(ABS(imponibile), '.', ',') AS 'Tot. imponibile', REPLACE((ABS(importo)-ABS(imponibile)), '.', ',') AS 'Tot. Iva', REPLACE(ABS(importo), '.', ',') AS 'Tot. Documento',
            (SELECT CONCAT(cognome,' ',nome) FROM lista_password WHERE id = id_agente) AS 'Nome Commerciale'
            FROM lista_fatture 
            WHERE MONTH(data_creazione) = '$mese' AND YEAR(data_creazione) = '$anno' AND tipo LIKE '".$tipo."' AND sezionale NOT LIKE '%CN%' AND (stato LIKE 'In Attesa' OR stato LIKE 'Pagata%' OR stato LIKE 'Nota di%') ".$whereCommerciale;
$titolo = "Esportazione del ".date("d/m/Y H:i:s");
//stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');

$result = $dblink->get_results($sql_0001);
$fields = $dblink->list_fields($sql_0001);

//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$titolo.xls");
header("Pragma: no-cache"); 
header("Expires: 0");

$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
foreach ($fields as $field) {
    echo $field->name . "\t";
}
print("\n");    
//end of printing column names  
//start while loop to get data
    foreach($result as $rows)
    {
        $schema_insert = "";
        foreach($rows as $row)
        {
            if(!isset($row))
                $schema_insert .= "NULL".$sep;
            elseif ($row != "")
                $schema_insert .= "".mb_convert_encoding(htmlspecialchars_decode(html_entity_decode($row)), "UTF-8", "HTML-ENTITIES").$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }  
?>