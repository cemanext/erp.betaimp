<?php

function StampaSQL2017($query, $titolo, $colore_tabella = COLORE_PRIMARIO) {
    global $dblink;
    
    $colore_tabella = strlen($colore_tabella)>0 ? $colore_tabella : COLORE_PRIMARIO;

    echo '<div class="portlet box ' . $colore_tabella . '">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>' . $titolo . '</div>
                    <div class="tools">
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-hover">';
    $esegui = $dblink->get_results($query);
    if(!empty($esegui)) {
        //$colonne = $dblink->list_fields($query);
        //$conta_colonne = mysql_num_fields($esegui);
        echo '<thead><tr>';
        foreach ($esegui[0] as $key => $riga) {
        //for ($b = 0; $b < count($colonne); $b++) {
            //$nome_colonna = $colonne[$b];
            $nome_colonna = $key;
            //echo '<th>' .$nome_colonna . '</th>';
            echo '<td scope="col">' . $nome_colonna . '</td>';
        }
        echo '</tr></thead>';
        $id = 0;
        echo '<tbody>';
        //while ($row = mysql_fetch_row($esegui)) {
        foreach ($esegui as $riga) {
            echo '<tr>';
            //for ($c = 0; $c < count($riga); $c++) {
            foreach ($riga as $key => $row) {
                echo '<td style="text-align:center; vertical-align:middle;">' . $row . '</td>';
            }
            echo '</tr>';
            $id++;
        }
        echo '</tbody>';
    }
    echo '</table></div></div></div>';
}

/** FUNZIONI CROCCO  */
function StampaSQL($query, $stile, $titolo) {
    global $dblink;
    
    $stile = strlen($stile)>0 ? $stile : '';
    $titolo = strlen($titolo)>0 ? $titolo : '';

    echo '<table cellpadding="5" cellspacing="5" width="99%" border="1">';
    $esegui = $dblink->get_results($query);
    $colonne = $dblink->list_fields($query);
    if(!empty($esegui)) {
        $conta_colonne = count($colonne);
        echo '<tr style=font-weight:bold;>';
        foreach ($colonne as $colonna) {
            $nome_colonna = $colonna->name;
            echo '<th style="text-transform:capitalize;">' . $nome_colonna . '</th>';
        }
        echo '</tr>';
        $id = 0;
        foreach ($esegui as $rows) {
            echo '<tr>';
            foreach ($rows as $row) {
                //pallinoverdek
                if (strtolower($row) == strtolower('Disponibile')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><a href="#" title="' . $row . '" alt="' . $row . '" class="smallButton" style="margin: 5px;"><img src="images/pallinoverdek.png"></a></td>';
                } elseif (strtolower($row) == strtolower('Ipotesi di Smarrimento')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><img src="images/pallinoSmarrito.png" ALT="' . $row . '" TITLE="' . $row . '" BORDER=0></td>';
                } elseif (strtolower($row) == strtolower('Chiuso') or strtolower($row) == strtolower('Attivo') or strtolower($row) == strtolower('Pagata') or strtolower($row) == strtolower('Lavorazione Terminata')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><a href="#" title="' . $row . '" alt="' . $row . '" class="smallButton" style="margin: 5px;"><img src="images/pallinoverdek_chiuso.png"></a></td>';
                } elseif (strtolower($row) == strtolower('Reso') or strtolower($row) == strtolower('Reso Totale')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><img src="images/pallinoverdek_reso.png" ALT="' . $row . '" TITLE="' . $row . '" BORDER=0></td>';
                } elseif (strtolower($row) == strtolower('Rientro') or strtolower($row) == strtolower('Rientro Totale') or strtolower($row) == strtolower('Disponibile da Rientro')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><img src="images/pallinoverdek_ritorno.png" ALT="' . $row . '" TITLE="' . $row . '" BORDER=0></td>';
                } elseif (strtolower($row) == strtolower('Spedito')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><img src="images/pallinoverdek_invio.png" ALT="FUORI PER SERVIZIO" TITLE="FUORI PER SERVIZIO" BORDER=0></td>';
                } elseif (strtolower($row) == strtolower('In Attesa') or strtolower($row) == strtolower('In Corso') or strtolower($row) == strtolower('In Lavorazione')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><a href="#" title="' . $row . '" alt="' . $row . '" class="smallButton" style="margin: 5px;"><img src="images/pallinogiallok.png"></a></td>';
                } elseif (strtolower($row) == strtolower('Negativo') or strtolower($row) == strtolower('Non Disponibile') or strtolower($row) == strtolower('Non Attivo')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><a href="#" title="' . $row . '" alt="' . $row . '" class="smallButton" style="margin: 5px;"><img src="images/pallinorossok.png"></a></td>';
                } elseif (strtolower($row) == strtolower('Consegnato')) {
                    echo '<td style="text-align:center; vertical-align:middle;"><img src="images/pallinoverdek_reso.png" ALT="' . $row . '" TITLE="' . $row . '" BORDER=0></td>';
                } else {
                    if (strpos(strtolower($row), "img")) {
                        echo '<td style="text-align:center; vertical-align:middle;">' . $row . '</td>';
                    } else {
                        echo '<td style="text-align:center; vertical-align:middle;">' . $row . '</td>';
                    }
                }
            }
            echo '</tr>';
            $id++;
        }
        echo '</table></div>';
    }
}

?>
