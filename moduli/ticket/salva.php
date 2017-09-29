<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$referer = recupera_referer();

if(isset($_GET['fn'])){
    switch ($_GET['fn']) {

        case "rispostaTicket":
        case "nuovoTicket":
            //print_r($_POST);
            $arrayCampi = $_POST;
            $conto = 0;
            $idTicket = 0;
            $tuttiCampi = array();
            foreach ($arrayCampi as $key => $value) {
                $pos = strpos($key, "copia");
                if ($pos === false) {
                    $pos_001 = strpos($key, "_txt_");
                    if($pos_001 == true) {
                        $tmpArray = explode("_txt_", $key);
                        $tbl = $tmpArray[0];
                        $campo = $tmpArray[1];
                        if(strpos($campo,"data")!==false){
                            $tuttiCampi[$tbl][$campo] = GiraDataOra(trim(str_replace("`", "", $value)));
                        }else{
                            $tuttiCampi[$tbl][$campo] = $dblink->filter(trim(str_replace("`", "", $value)));
                        }
                    }
                }
            }

            $count = 0;

            /*echo "<pre>";
            print_r($tuttiCampi);
            echo "</pre>";
            /*
            die;*/
            /*foreach($tuttiCampi as $record){
                $count++;
            }*/

            if(!empty($tuttiCampi['lista_ticket'])){
                //print_r($_FILES['lista_ticket_txt_allegato']);
                if(!empty($_FILES['lista_ticket_txt_allegato']['tmp_name'])) {

                    $filename=time().'_'.$_FILES['lista_ticket_txt_allegato']['name'];

                    $year = date("Y");
                    $month = date("m");

                    $path = BASE_ROOT.'media/ticket_allegati/'.$year;
                    if(!is_dir($path)){
                        mkdir($path, 0777);
                    }

                    $path = $path.'/'.$month;
                    if(!is_dir($path)){
                        mkdir($path, 0777);
                    }

                    //echo $path."<br>";

                    if(move_uploaded_file($_FILES['lista_ticket_txt_allegato']['tmp_name'],$path."/".$filename)) {
                        $tuttiCampi['lista_ticket']['allegato'] = BASE_URL."/media/ticket_allegati/$year/$month/".$filename;

                        //echo "OK:".$tuttiCampi['lista_ticket']['allegato'];
                    }else{
                        //echo "NON IMPORTATO!";
                    }
                }

                //die;

                $tiketChiuso = false;

                $tuttiCampi['lista_ticket']['dataagg'] = date("Y-m-d H:i:s");
                $tuttiCampi['lista_ticket']['scrittore'] = $dblink->filter($_SESSION['cognome_nome_utente']);

                if($tuttiCampi['lista_ticket']['id']>0){

                    if($tuttiCampi['lista_ticket']['stato']=="Lavorazione Terminata"){
                        $tiketChiuso = true;
                    }

                    $idWhere = $tuttiCampi['lista_ticket']['id'];
                    //echo "<br>";
                    unset($tuttiCampi['lista_ticket']['id']);

                    $ok = $dblink->update("lista_ticket", $tuttiCampi['lista_ticket'], array("id"=>$idWhere));
                    //if(!$ok) echo "errore Database";
                    /*echo $dblink->get_query();
                    die;*/
                }else{
                    unset($tuttiCampi['lista_ticket']['id']);
                    $ok = $dblink->insert("lista_ticket", $tuttiCampi['lista_ticket']);
                    $idTicket = $dblink->lastid();
                    /*echo $dblink->get_query();
                    die;*/
                }

            }

            if(!empty($tuttiCampi['lista_ticket_dettaglio'])){

                if(!empty($_FILES['lista_ticket_dettaglio_txt_allegato']['tmp_name'])) {

                    $filename=time().'_'.$_FILES['lista_ticket_dettaglio_txt_allegato']['name'];

                    $year = date("Y");
                    $month = date("m");

                    $path = BASE_ROOT.'media/ticket_allegati/'.$year;
                    if(!is_dir($path)){
                        mkdir($path, 0777);
                    }

                    $path = $path.'/'.$month;
                    if(!is_dir($path)){
                        mkdir($path, 0777);
                    }

                    if(move_uploaded_file($_FILES['lista_ticket_dettaglio_txt_allegato']['tmp_name'],$path."/".$filename)) {
                        $tuttiCampi['lista_ticket_dettaglio']['allegato'] = BASE_URL."/media/ticket_allegati/$year/$month/".$filename;
                    }
                }
                
                if($tuttiCampi['lista_ticket_dettaglio']['notifica_email']=="Si"){
                    $inviaMail = true;
                }else{
                    $inviaMail = false;
                }

                $tuttiCampi['lista_ticket_dettaglio']['dataagg'] = date("Y-m-d H:i:s");
                $tuttiCampi['lista_ticket_dettaglio']['scrittore'] = $dblink->filter($_SESSION['cognome_nome_utente']);

                if($_SESSION['livello_utente']!='amministratore' && $tiketChiuso){
                    $ok = $dblink->update("lista_ticket", array("stato"=>"In Attesa"), array("id"=>$idWhere));
                }

                $ok = $dblink->insert("lista_ticket_dettaglio", $tuttiCampi['lista_ticket_dettaglio']);
                $idTicketDettaglio = $dblink->lastid();
                
                if($ok){
                    if($idTicketDettaglio>0 && $inviaMail) inviaEmailTemplate_Ticket($idTicketDettaglio,'rispostaTicketSupporto');
                    
                    header("Location:".$referer."&ret=1");

                }else{
                    header("Location:".$referer."&ret=0");
                }
            }else{

                if($ok){
                    if($idTicket>0) inviaEmailTemplate_Ticket($idTicket,'nuovoTicketSupporto');
                    
                    header("Location:".BASE_URL."/moduli/ticket/index.php?tbl=lista_ticket&ret=1");
                }else{
                    header("Location:".BASE_URL."/moduli/ticket/index.php?tbl=lista_ticket&ret=0");
                }
            }
        break;

        case "aggiornaTicket":
            $ok = true;
            $idTicket = $_GET['idTicket'];
            $labelStato = $_GET['labelStato'];
            if($idTicket>0){
                $ok = $ok && $dblink->update("lista_ticket", array("dataagg" => date("Y-m-d H:i:s"),"scrittore" => $dblink->filter($_SESSION['cognome_nome_utente']),"stato"=>"$labelStato"), array("id"=>$idTicket));
            }
            if($ok===true && $idTicket>0) header("Location:".$referer."&res=1");
            else header("Location:".$referer."&res=0");
        break;
    }
}


?>
