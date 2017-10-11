<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

global $dblink;

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {
        case "inserisciProdottoDettaglioPacchetto":
        case "inserisciProdottoDettaglioAbbonamento":
            $dblink->begin();
            $sql_inserisci_prodotto_dettaglio_abbonamento = "INSERT INTO lista_prodotti_dettaglio (dataagg, scrittore, id_prodotto_0, stato, gruppo, ordine) VALUES (NOW(), '".$_SESSION['cognome_nome_utente']."','".$_GET['idAbbonamento']."','Non Attivo', 'CORSO', '1000')";
            $ok = $dblink->query($sql_inserisci_prodotto_dettaglio_abbonamento);
            $lastId=$dblink->insert_id();
            if($ok){
                $ok = 1;
                $dblink->commit();
            }else{
                $ok = 0;
                $dblink->rollback();
            }
            header("Location:".$referer."");
        break;
        
        case "SalvaPacchettoDettaglio":
        case "SalvaAbbonamentoDettaglio":
            //print_r($_POST);
            $arrayRisultati = $_POST;
            $conto = 0;
            $tuttiCampi = array();
            foreach ($arrayRisultati as $key => $value) {
                //echo "<br>KEY: $key<br>";
                $pos_001 = strpos($key, "txt_");
                //echo "POS: ".$pos_001."<br>";
                if($pos_001 === false) {
                    
                }else{
                    $tmp = explode("_", $key);
                    //print_r($tmp);
                    $nome_campo = substr($key, (strlen("txt_".$tmp[1]."_")));
                    if(strpos($nome_campo, "data")!==false){
                        if(strlen($value)>0){
                            $tuttiCampi[$tmp[1]][$nome_campo] = GiraDataOra(trim(str_replace("`", "", $value)));
                        }else{
                            $tuttiCampi[$tmp[1]][$nome_campo] = "";
                        }
                    }else{
                        //echo "<br>$nome_campo<br>";
                        $tuttiCampi[$tmp[1]][$nome_campo] = $dblink->filter(trim(str_replace("`", "", $value)));
                    }
                }
                $conto++;
            }
            
            $count = 0;
            
            foreach($tuttiCampi as $record){
                $count++;
                /*foreach($record as $nomi_colonne => $valore){
                    echo '<lI>$nomi_colonne = '.$nomi_colonne.' / $valore = '.$valore.'</li>';
                }*/
            }
            
            for($r=0;$r<$count;$r++){
                
                $tuttiCampi[$r]['dataagg'] = date("Y-m-d H:i:s");
                $tuttiCampi[$r]['scrittore'] = $dblink->filter($_SESSION['cognome_nome_utente']);
                
                if($tuttiCampi[$r]['id']>0){
                    $idWhere = $tuttiCampi[$r]['id'];
                    //echo "<br>";
                    unset($tuttiCampi[$r]['id']);
                    
                    $sql_0002 = "SELECT nome, descrizione, gruppo, codice FROM lista_prodotti WHERE id=".$tuttiCampi[$r]['id_prodotto'];
                    $row_0002 = $dblink->get_row($sql_0002, true);
                    
                    $tuttiCampi[$r]['nome'] = $row_0002['nome'];
                    $tuttiCampi[$r]['descrizione'] = $row_0002['descrizione'];
                    $tuttiCampi[$r]['gruppo'] = $row_0002['gruppo'];
                    $tuttiCampi[$r]['codice'] = $row_0002['codice'];
                    $tuttiCampi[$r]['ordine'] = ($r+1);
                    
                    $ok = $dblink->update("lista_prodotti_dettaglio", $tuttiCampi[$r], array("id"=>$idWhere)); 
                    if(!$ok) echo "errore Database";
                    /*echo $dblink->get_query();
                    echo "<br>";*/ 
                }
            
            }
            
            header("Location:$referer");
        break;
    }
}


?>