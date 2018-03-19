<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

if (DISPLAY_DEBUG) {
    echo '<li>'.date('Y-m-d H:i:s').'</li>';
    echo '<li>DB_HOST = '.DB_HOST.'</li>';
    echo '<li>DB_USER = '.DB_USER.'</li>';
    echo '<li>DB_PASS = '.DB_PASS.'</li>';
    echo '<li>DB_NAME = '.DB_NAME.'</li>';
    echo '<hr>';
}

$filename="cdr.log";
            
if(!is_dir(BASE_ROOT . "media/lista_telefonate")){
    mkdir(BASE_ROOT . "media/lista_telefonate", 0777);
}

$path= BASE_ROOT.'media/lista_telefonate/'.$filename;

if(file_exists($path)) {

    $countOK = 0;
    $countKO = 0;
    $countSkip = 0;
    $row = 0;
    $headers = [];
    $filepath = $path;
    if (($handle = fopen($filepath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 100000, "\n")) !== FALSE) {
            if($countOK>10) exit;
            $row = explode(",",$data[0]);
            if(strlen(trim($row[2]))>0){
                  /*echo "<pre>";
                  var_dump($row);
                  echo "</pre>";
                  die;*/

                /*if(strlen($row[0])>0){
                    $idCampagna = $dblink->get_row("SELECT id FROM lista_password WHERE LCASE(numerico_1) LIKE LCASE('".$row[0]."')", true);
                }else{
                    $idCampagna['id'] = 0;
                }

                if(strlen($row[1])>0){
                    $idTipoMarketing = $dblink->get_row("SELECT id FROM lista_tipo_marketing WHERE LCASE(nome) LIKE LCASE('".$row[1]."')", true);
                }else{
                    $idTipoMarketing['id'] = 0;
                }

                if(strlen($row[9])>0){
                    $idClasse = $dblink->get_row("SELECT id FROM lista_classi WHERE LCASE(nome) LIKE LCASE('".$row[10]."')", true);
                }else{
                    $idClasse['id'] = 0;
                }

                if(strlen($row[8])>0){
                    $idProdotto = $dblink->get_row("SELECT id, codice, codice_esterno AS id_prod_moodle FROM lista_prodotti WHERE LCASE(codice) = LCASE('".trim($row[8])."')", true);
                    $nomeProdotto = $dblink->filter($row[8]);
                }else{
                    $idProdotto['id'] = 0;
                    $idProdotto['codice'] = "";
                    $nomeProdotto = "";
                }
                */
                /*if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim($row[10])) && trim($row[10])!="0000-00-00") {
                    $dataInsert = true;
                } else {
                    $dataInsert = false;
                }*/
                
                $tmpID = explode(" ", $row[0]);
                $idCodiceEsterno = $tmpID[1];
                $stato = $row[6];
                $destinarario = $row[14];
                $mittente = $row[11];
                $campo_1 = $data[0];
                $durataChiamata = $row[2];
                $dataInizioChiamata = str_replace("/", "-", $row[4]);
                $dataFineChiamata = str_replace("/", "-", $row[5]);
                  
                $rowEsiste = $dblink->get_row("SELECT id FROM lista_telefonate WHERE codice_esterno = '$idCodiceEsterno'",true);
                
                if(empty($rowEsiste) && $row[7] == "Ext.MakeCall"){
                    $insert = array(
                      "dataagg" => date("Y-m-d H:i:s"),
                      "scrittore" => $dblink->filter("importTelefonate"),
                      "stato" => $dblink->filter($stato),
                      "id_password" => 0,
                      "mittente" => $dblink->filter($mittente),
                      "destinatario" => $dblink->filter($destinarario),
                      "codice_esterno" => $idCodiceEsterno,
                      "data_chiamata_inizio" => $dataInizioChiamata,
                      "data_chiamata_fine" => $dataFineChiamata,
                      "durata_chiamata" => $durataChiamata,
                      "campo_1" => $campo_1,
                    );

                    $ok = $dblink->insert("lista_telefonate", $insert);
                    if($ok) $countOK ++;
                    else $countKO ++;
                }else{
                    $countSkip++;
                }
              }else{
                $countSkip++;
              }
        }
        fclose($handle);
    }

    //$log->log_all_errors("IMPORTAZIONE FILE ($fileName) - RIGHE IMPORTATE: $countOK - RIGHE CON ERRORI: $countKO - RIGHE SALTATE: $countSkip","OK");

    //unlink($path);

    //header("Location:$referer&ok=$countOK&errore=$countKO&skip=$countSkip");
}else{
    echo "FILE NON ESISTE";
}
    
if (DISPLAY_DEBUG) echo '</pl>'.date("H:i:s");
?>