<?php

/** FUNZIONI DI CROCCO */
function Stampa_HTML_index_Campagne($tabella){
    global $table_listaCampagne;
    
    $tabella = 'lista_campagne';
    
    switch($tabella){
        case 'lista_campagne':
            if(isset($_GET['whr_state'])){
                $where = " (MD5(stato)=('".$_GET['whr_state']."'))".$where_lista_campagne;
            }else{
                $where = $table_listaCampagne['index']['where'];
            }

            $campi_visualizzati = $table_listaCampagne['index']['campi'];

            $ordine = $table_listaCampagne['index']['order'];

            $titolo = 'Elenco Campagne';
            $sql_0001 = "SELECT ".$campi_visualizzati." FROM ".$tabella." WHERE $where $ordine LIMIT 1";
            //echo '<li>$sql_0001 = '.$sql_0001.'</li>';
            //stampa_table_datatables_responsive($sql_0001, $titolo, 'tabella_base');
            stampa_table_datatables_ajax($sql_0001, "#datatable_ajax", $titolo, '');
        break;
    }
}

function Stampa_HTML_Dettaglio_Campagne($tabella,$id){
    switch($tabella){
        case 'lista_campagne':
        echo '<div class="row"><div class="col-md-12 col-sm-12">';
        $sql_0001 = "SELECT IF(id>99,CONCAT('<a class=\"btn btn-circle btn-icon-only blue btn-outline\" href=\"modifica.php?tbl=lista_campagne&id=',id,'\" title=\"MODIFICA\" alt=\"MODIFICA\"><i class=\"fa fa-edit\"></i></a>'),'') AS 'fa-edit',
        CONCAT('<B>',`nome`,'</b>') AS Nome,
        (SELECT CONCAT('<B>',`nome`,'</b>') FROM `lista_tipo_marketing` WHERE `id` = `id_tipo_marketing` LIMIT 1) AS 'Marketing',
        (SELECT CONCAT('<B>',`nome` ,'</B><BR><SMALL>',codice,'</SMALL>')FROM `lista_prodotti` WHERE `id` = `id_prodotto` LIMIT 1) AS 'Prodotto',
        CONCAT('Inizio: ',`data_inizio`,'<br>Fine: ',`data_fine`) AS Tempo,
        CONCAT('<h3>',`numerico_5`,'%</h3>') AS '%',
        `stato`
        FROM lista_campagne WHERE id=".$id;
            stampa_table_static_basic($sql_0001,'','Campagna', '');
        echo '</div></div>';
         echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT 
            numerico_1 AS 'N° Accessi', 
            numerico_2 AS 'N° Tel / Richieste', 
            numerico_3 AS 'N° Iscritti / Venduti',
            numerico_4 AS 'Tot. Fatturato (Emesse)',
            numerico_6 AS 'N° Rich / Prev. In Attesa',
            numerico_7 AS 'N° Neg. / Prev. Negativi'
            FROM lista_campagne WHERE id=".$id;
            stampa_table_static_basic($sql_0001,'','SERENA', '');
        echo '</div></div>';
        
        echo '<div class="row">';
        echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
                        $sql_007 = "SELECT numerico_50 AS conteggio FROM lista_campagne WHERE id='".$id."'";
                        $titolo = 'Media % Chiusure';
                        $icona = 'fa fa-building';
                        $colore = 'green';
                        $link = '';
                        stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
        echo '</div>';
        echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
                        $sql_007 = "SELECT numerico_60 AS conteggio FROM lista_campagne WHERE id='".$id."'";
                        $titolo = 'Prezzo medio di vendita';
                        $icona = 'fa fa-building';
                        $colore = 'yellow';
                        $link = '';
                        stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
        echo '</div>';
        echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
                        $sql_007 = "SELECT numerico_110 AS conteggio FROM lista_campagne WHERE id='".$id."'";
                        $titolo = 'Tot. Fatture (Da Incassare)';
                        $icona = 'fa fa-building';
                        $colore = 'red';
                        $link = '';
                        stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
        echo '</div>';
        echo '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
                        $sql_007 = "SELECT (numerico_100+numerico_110) AS conteggio FROM lista_campagne WHERE id='".$id."'";
                        $titolo = 'Tot. Fatturato (Emesse)';
                        $icona = 'fa fa-building';
                        $colore = 'purple';
                        $link = '';
                        stampa_dashboard_stat_v2($sql_007, $titolo, $icona, $colore, $link);
        echo '</div></div>';
        
         echo '<div class="row"><div class="col-md-12 col-sm-12">';
            $sql_0001 = "SELECT 
            numerico_50 AS 'Media % Chiusure', 
            numerico_60 AS 'Media Prezzo Vendita', 
            numerico_90 AS 'Prev. Da Verificare',
            (numerico_100+numerico_110) AS 'Tot. Fatturato (Emesse)',
            numerico_100 AS 'Incassato',
            numerico_110 AS 'Da Incassare',
            numerico_4 AS 'In Attesa di Emissione'
            FROM lista_campagne WHERE id=".$id;
            stampa_table_static_basic($sql_0001,'','SERENA + BENEDETTO', '');
        echo '</div></div>';

        //echo '';
        echo '<!-- START ROW 4 - 2 COLUMN-->
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <!-- ESEMPIO STAMPIA GOOGLE CHART -->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-bar-chart"></i>
                            <span class="caption-subject bold uppercase">Esempio gchart_col_1</span>
                        </div>
                        <div class="actions">
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="gchart_col_1" style="height:500px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
              <!-- ESEMPIO STAMPIA GOOGLE CHART -->
              <div class="portlet light portlet-fit bordered">
                  <div class="portlet-title">
                      <div class="caption">
                          <i class="fa fa-pie-chart"></i>
                          <span class="caption-subject bold uppercase">Esempio gchart_pie_1</span>
                      </div>
                      <div class="actions">
                      </div>
                  </div>
                  <div class="portlet-body">
                      <div id="gchart_pie_1" style="height:500px;"></div>
                  </div>
              </div>
            </div>
        </div>
        <!-- END ROW 4 - 2 COLUMN-->
        <div class="clearfix"></div>';
        echo '';
        break;
    }
}
?>
