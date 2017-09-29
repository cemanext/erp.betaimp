<?php
include_once('../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$sql_0001 = creaSQLesporta();
$titolo = "Esportazione del ".date("d/m/Y H:i:s");
//stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');

$result = $sblink->get_results($sql_0001);
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
                $schema_insert .= "".$row.$sep;
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