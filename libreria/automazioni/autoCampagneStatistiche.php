<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT.'config/confAccesso.php');

$ok = true;
            
$sql_00001 = "SELECT id,nome FROM lista_campagne WHERE stato LIKE 'In Corso' OR stato LIKE 'Attiva' ORDER BY id ASC";
$rs_00001 = $dblink->get_results($sql_00001);
if(!empty($rs_00001)){
    $ok = true;
    if(DISPLAY_DEBUG){
        StampaSQL($sql_00001,'','');
    }
    
    $conto_00001 = count($rs_00001);
    if(DISPLAY_DEBUG) echo '<li>$conto_00001 = '.$conto_00001.'</li>';
    
    $ok = false;
    foreach($rs_00001  as $row_0){
    $idCampagna = $row_0['id'];
    
        //conto le visite alla pagina
        $sql_00002 = "SELECT COUNT(id) as conteggio_accessi FROM lista_accessi WHERE id_campagna=".$idCampagna." AND id_campagna!=0";
        $row_00002 = $dblink->get_row($sql_00002, true);
        $conteggio_accessi = $row_00002["conteggio_accessi"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_accessi = '.$conteggio_accessi.'</li>';
        }
       
        //conto le richieste arrivate
        $sql_00003 = "SELECT COUNT(id) as conteggio_richieste FROM calendario WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND etichetta LIKE 'Nuova Richiesta'";
        $row_00003 = $dblink->get_row($sql_00003, true);
        $conteggio_richieste = $row_00003["conteggio_richieste"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_richieste = '.$conteggio_richieste.'</li>';
        }
        
        if($conteggio_richieste<=0){
            $sql_000033 = "SELECT COUNT(id) as conteggio_richieste FROM lista_ordini WHERE id_campagna=".$idCampagna." AND id_campagna!=0";
            $row_000033 = $dblink->get_row($sql_000033, true);
            $conteggio_richieste = $row_000033["conteggio_richieste"];
            if(DISPLAY_DEBUG){
                echo '<li>conteggio_richieste = '.$conteggio_richieste.'</li>';
            }
        }
        
        //conto i preventivi chiusi
        //$sql_00004 = "SELECT COUNT(id) as conteggio_chiusi FROM lista_preventivi WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND (stato LIKE 'Chiuso' OR stato LIKE 'Venduto') AND id_calendario IN (SELECT id FROM calendario WHERE etichetta='Nuova Richiesta')";
        $sql_00004 = "SELECT COUNT(id) as conteggio_chiusi FROM lista_preventivi WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND (stato LIKE 'Chiuso' OR stato LIKE 'Venduto')";
        $row_00004 = $dblink->get_row($sql_00004, true);
        $conteggio_chiusi = $row_00004["conteggio_chiusi"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_chiusi = '.$conteggio_chiusi.'</li>';
        }
        
        //conto i preventivi in attesa
        $sql_00005 = "SELECT COUNT(id) as conteggio_preventivi_in_attesa FROM lista_preventivi WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato LIKE 'In Attesa'";
        $row_00005 = $dblink->get_row($sql_00005, true);
        $conteggio_preventivi_in_attesa = $row_00005["conteggio_preventivi_in_attesa"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_preventivi_in_attesa = '.$conteggio_preventivi_in_attesa.'</li>';
        }
        
        //conto i preventivi negativi
        $sql_00006 = "SELECT COUNT(id) as conteggio_preventivi_negativi FROM lista_preventivi WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato LIKE 'Negativo'";
        $row_00006 = $dblink->get_row($sql_00006, true);
        $conteggio_preventivi_negativi = $row_00006["conteggio_preventivi_negativi"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_preventivi_negativi = '.$conteggio_preventivi_negativi.'</li>';
        }
        
         //conto i fatture pagate
        $sql_00007 = "SELECT COUNT(id) as conteggio_pagate FROM lista_fatture WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato LIKE 'Pagata'";
        $row_00007 = $dblink->get_row($sql_00007, true);
        $conteggio_pagate = $row_00007["conteggio_pagate"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_pagate = '.$conteggio_pagate.'</li>';
        }
        
        //conto i fatture emesse
        $sql_00008 = "SELECT COUNT(id) as conteggio_emesse FROM lista_fatture WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato NOT LIKE 'In Attesa di Emissione'";
        $row_00008 = $dblink->get_row($sql_00008, true);
        $conteggio_pagate = $row_00007["conteggio_pagate"];
        if(DISPLAY_DEBUG){
            echo '<li>conteggio_emesse = '.$conteggio_emesse.'</li>';
        }
        
        //conto i fatture incassato
        $sql_00009 = "SELECT SUM(imponibile) as incassato FROM lista_fatture WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato  LIKE 'Pagata'";
        $row_00009 = $dblink->get_row($sql_00009, true);
        $incassato = $row_00009["incassato"];
        if(DISPLAY_DEBUG){
            echo '<li>incassato = '.$incassato.'</li>';
        }
        
        //conto i fatture da_incassare
        $sql_00010 = "SELECT SUM(imponibile) as da_incassare FROM lista_fatture WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato  LIKE 'In Attesa'";
        $row_00010 = $dblink->get_row($sql_00010, true);
        $da_incassare = $row_00010["da_incassare"];
        if(DISPLAY_DEBUG){
            echo '<li>da_incassare = '.$da_incassare.'</li>';
        }  
        
        //conto i fatture da_incassare
        //$sql_00011 = "SELECT SUM(imponibile) as da_confermare FROM lista_preventivi WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato  LIKE 'Venduto' AND id_calendario IN (SELECT id FROM calendario WHERE etichetta='Nuova Richiesta')";
        $sql_00011 = "SELECT SUM(imponibile) as da_confermare FROM lista_preventivi WHERE id_campagna=".$idCampagna." AND id_campagna!=0 AND stato  LIKE 'Venduto'";
        $row_00011 = $dblink->get_row($sql_00011, true);
        $da_confermare = $row_00011["da_confermare"];
        if(DISPLAY_DEBUG){
            echo '<li>da_confermare = '.$da_confermare.'</li>';
        }
        
        
        
        $conteggio_percentuale = rand(20,100);
        
        $conteggio_chiusi = $conteggio_chiusi > 0 ? $conteggio_chiusi : 0;
        $conteggio_richieste = $conteggio_richieste > 0 ? $conteggio_richieste : 0;
        $incassato = $incassato > 0 ? $incassato : 0;
        $da_incassare = $da_incassare > 0 ? $da_incassare : 0;
        $da_confermare = $da_confermare > 0 ? $da_confermare : 0;
        
        $conteggio_percentuale_media_chiusure = $conteggio_richieste > 0 ? round((($conteggio_chiusi * 100) / $conteggio_richieste), 2) : 0;
        $conteggio_percentuale_media_prezzo = $conteggio_chiusi > 0 ? round(($incassato+$da_incassare) / $conteggio_chiusi, 2) : 0;
        //aggiorno la tabella lista_campagne
        $sql_000040 = "UPDATE lista_campagne
        SET numerico_1 = '".$conteggio_accessi."',
        numerico_2 = '".$conteggio_richieste."',
        numerico_3 = '".$conteggio_chiusi."',
        numerico_4 = '".$conteggio_emesse."',
        numerico_5 = '".$conteggio_percentuale_media_chiusure."',
        numerico_6 = '".$conteggio_preventivi_in_attesa."',
        numerico_7 = '".$conteggio_preventivi_negativi."',
        numerico_50 = '".$conteggio_percentuale_media_chiusure."',
        numerico_60 = '".$conteggio_percentuale_media_prezzo."',
        numerico_90 = '".$da_confermare."',
        numerico_100 = '".$incassato."',
        numerico_110 = '".$da_incassare."',
        dataagg = NOW(),
        scrittore = 'autoCampagneStatistiche' 
        WHERE id =".$idCampagna;
        $dblink->query($sql_000040);
        if(DISPLAY_DEBUG){
            echo $dblink->get_query();
            echo "<br>";
        }
        

    }
}
?>
