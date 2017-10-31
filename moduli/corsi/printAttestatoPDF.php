<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
if ($browser == true) {
    //echo 'Code You Want To Execute';
}

 require_once '../../classi/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


if (isset($_GET['idIscrizione'])) {

    $idIscrizione = $_GET['idIscrizione'];
    
    $rowIscrizione = $dblink->get_row("SELECT * FROM lista_iscrizioni WHERE id = '$idIscrizione'", true);
    $idClasse = $rowIscrizione['id_classe'];
    $idCorso = $rowIscrizione['id_corso'];
    $idProfessinista = $rowIscrizione['id_professionista'];
    $dataInizioCorso = $rowIscrizione['data_inizio'];
    $dataCompletamento = $rowIscrizione['data_completamento'];
    
    $rowProfessionista = $dblink->get_row("SELECT * FROM lista_professionisti WHERE id = '$idProfessinista'", true);
    $titolo_professionista = $rowProfessionista['titolo'];
    $nome = $rowProfessionista['nome'];
    $cognome = $rowProfessionista['cognome'];
    $professione = $rowProfessionista['professione'];
    $dataDiNascita = $rowProfessionista['data_di_nascita'];
    $provinciaDiNascita = $rowProfessionista['provincia_di_nascita'];
    $luogoDiNascita = $rowProfessionista['luogo_di_nascita'];
    $codiceFiscale = $rowProfessionista['codice_fiscale'];
    $provinciaAlbo = $rowProfessionista['provincia_albo'];
    $numeroAlbo = $rowProfessionista['numero_albo'];
    $attestatoClasse = $rowProfessionista['attestato_classe'];
    
    $htmladd = '';
    
    if($attestatoClasse == "No"){
        $explodeProfessione = explode("-",$professione);
        
        foreach ($explodeProfessione as $valoreProfessione) {
            $rowCostiConfig = $dblink->get_row("SELECT * FROM lista_corsi_configurazioni WHERE id_corso = '$idCorso' AND professione = '$valoreProfessione' AND (((data_inizio<='$dataCompletamento' OR data_inizio='00-00-0000') AND (data_fine>='$dataCompletamento' OR data_fine='00-00-0000')) OR (data_inizio='00-00-0000' OR data_fine='00-00-0000')) ORDER BY data_fine DESC, data_inizio DESC", true);
            $queryLast = $dblink->get_query();
            $crediti = $rowCostiConfig['crediti'];
            $durata = $rowCostiConfig['durata_corso'];
            $codiceAccreditamento = $rowCostiConfig['codice_accreditamento'];
            $idAttestato = $rowCostiConfig['id_attestato'];
            $titolo = $rowCostiConfig['titolo'];
            $messaggio = $rowCostiConfig['messaggio'];

            $rowAttestati = $dblink->get_row("SELECT * FROM lista_attestati WHERE id = '$idAttestato'", true);
            $orientamento = $rowAttestati['orientamento'];
            $nomeFile = $rowAttestati['nome'];

            $rowCorso = $dblink->get_row("SELECT * FROM lista_corsi WHERE id = '$idCorso'", true);
            $nomeCorso = $rowCorso['nome_prodotto'];

            $tmp = explode(" ",GiraDataOra($dataInizioCorso));
            $dataInizio = $tmp[0];
            
            $professione = $valoreProfessione;

            $messaggio = str_replace('_XXX_TITOLO_XXX_', $titolo_professionista, $messaggio);
            $messaggio = str_replace('_XXX_PROFESSIONE_XXX_', $professione, $messaggio);
            $messaggio = str_replace('_XXX_COGNOME_XXX_', $cognome, $messaggio);
            $messaggio = str_replace('_XXX_NOME_XXX_', $nome, $messaggio);
            $messaggio = str_replace('_XXX_DATA_INIZIO_XXX_', $dataInizio, $messaggio);
            $messaggio = str_replace('_XXX_DATA_FINE_XXX_', GiraDataOra($dataCompletamento), $messaggio);
            $messaggio = str_replace('_XXX_DATA_NASCITA_XXX_', GiraDataOra($dataDiNascita), $messaggio);
            $messaggio = str_replace('_XXX_PROV_NASCITA_XXX_', $provinciaDiNascita, $messaggio);
            $messaggio = str_replace('_XXX_LUOGO_NASCITA_XXX_', $luogoDiNascita, $messaggio);
            $messaggio = str_replace('_XXX_NOME_CORSO_XXX_', strtoupper($nomeCorso), $messaggio);
            $messaggio = str_replace('_XXX_ORE_CORSO_XXX_', $durata, $messaggio);
            $messaggio = str_replace('_XXX_CODICE_ACCREDITAMENTO_XXX_', $codiceAccreditamento, $messaggio);
            $messaggio = str_replace('_XXX_NUMERO_CREDITI_XXX_', $crediti, $messaggio);
            $messaggio = str_replace('_XXX_CODICE_FISCALE_XXX_', $codiceFiscale, $messaggio);
            $messaggio = str_replace('_XXX_PROVINCIA_ALBO_XXX_', $provinciaAlbo, $messaggio);
            $messaggio = str_replace('_XXX_NUMERO_ORDINE_XXX_', $numeroAlbo, $messaggio);

            $htmlDiv = '<div class="pagebreakafter_always"><div class="cornice"><img src="'.BASE_URL.'/moduli/corsi/'.$nomeFile.'" /></div>
                        <div id="divid">
                            _XXX_MESSAGGIO_XXX_
                            <div id="firma">
                                _XXX_DATA_FIRMA_XXX_
                            </div>
                        </div></div>';

            $messaggio = str_replace('_XXX_MESSAGGIO_XXX_', $messaggio, $htmlDiv);
            $messaggio = str_replace('_XXX_DATA_FIRMA_XXX_', "Lugo (RA), ".GiraDataOra($dataCompletamento), $messaggio);

            $htmladd .= $messaggio;
        }
        
    }else{
        $rowCostiConfig = $dblink->get_row("SELECT * FROM lista_corsi_configurazioni WHERE id_corso = '$idCorso' AND id_classe = '$idClasse' AND (((data_inizio<='$dataCompletamento' OR data_inizio='00-00-0000') AND (data_fine>='$dataCompletamento' OR data_fine='00-00-0000')) OR (data_inizio='00-00-0000' OR data_fine='00-00-0000')) ORDER BY data_fine DESC, data_inizio DESC", true);
        $crediti = $rowCostiConfig['crediti'];
        $durata = $rowCostiConfig['durata_corso'];
        $codiceAccreditamento = $rowCostiConfig['codice_accreditamento'];
        $idAttestato = $rowCostiConfig['id_attestato'];
        $titolo = $rowCostiConfig['titolo'];
        $messaggio = $rowCostiConfig['messaggio'];
        
        $rowAttestati = $dblink->get_row("SELECT * FROM lista_attestati WHERE id = '$idAttestato'", true);
        $orientamento = $rowAttestati['orientamento'];
        $nomeFile = $rowAttestati['nome'];

        $rowCorso = $dblink->get_row("SELECT * FROM lista_corsi WHERE id = '$idCorso'", true);
        $nomeCorso = $rowCorso['nome_prodotto'];

        $tmp = explode(" ",GiraDataOra($dataInizioCorso));
        $dataInizio = $tmp[0];

        $messaggio = str_replace('_XXX_PROFESSIONE_XXX_', $professione, $messaggio);
        $messaggio = str_replace('_XXX_COGNOME_XXX_', $cognome, $messaggio);
        $messaggio = str_replace('_XXX_NOME_XXX_', $nome, $messaggio);
        $messaggio = str_replace('_XXX_DATA_INIZIO_XXX_', $dataInizio, $messaggio);
        $messaggio = str_replace('_XXX_DATA_FINE_XXX_', GiraDataOra($dataCompletamento), $messaggio);
        $messaggio = str_replace('_XXX_DATA_NASCITA_XXX_', GiraDataOra($dataDiNascita), $messaggio);
        $messaggio = str_replace('_XXX_PROV_NASCITA_XXX_', $provinciaDiNascita, $messaggio);
        $messaggio = str_replace('_XXX_LUOGO_NASCITA_XXX_', $luogoDiNascita, $messaggio);
        $messaggio = str_replace('_XXX_NOME_CORSO_XXX_', strtoupper($nomeCorso), $messaggio);
        $messaggio = str_replace('_XXX_ORE_CORSO_XXX_', $durata, $messaggio);
        $messaggio = str_replace('_XXX_CODICE_ACCREDITAMENTO_XXX_', $codiceAccreditamento, $messaggio);
        $messaggio = str_replace('_XXX_NUMERO_CREDITI_XXX_', $crediti, $messaggio);
        $messaggio = str_replace('_XXX_CODICE_FISCALE_XXX_', $codiceFiscale, $messaggio);
        $messaggio = str_replace('_XXX_PROVINCIA_ALBO_XXX_', $provinciaAlbo, $messaggio);
        $messaggio = str_replace('_XXX_NUMERO_ORDINE_XXX_', $numeroAlbo, $messaggio);
        
        $htmlDiv = '<div class="pagebreakafter_always"><div class="cornice"><img src="'.BASE_URL.'/moduli/corsi/'.$nomeFile.'" /></div>
                    <div id="divid">
                        _XXX_MESSAGGIO_XXX_
                        <div id="firma">
                            _XXX_DATA_FIRMA_XXX_
                        </div>
                    </div></div>';
        
        $messaggio = str_replace('_XXX_MESSAGGIO_XXX_', $messaggio, $htmlDiv);
        $messaggio = str_replace('_XXX_DATA_FIRMA_XXX_', "Lugo (RA), ".GiraDataOra($dataCompletamento), $messaggio);
        
        $htmladd .= $messaggio;
        
    }
        

    $totale = 1;

    $pageSize = 12;
    $pagina = 1;
    $begin = ($pagina - 1) * $pageSize;
    $countPages = ceil($totale / $pageSize);

    if($orientamento == "L"){

        $html = '<STYLE type="text/css">    
            
        body {
            font-family: \'Trajan Pro\';
            font-size: 14pt;
            background-color: #FFFFCC;
        }
        #divid{margin-top: 0px;margin-left: 0px;
            text-align:center;
            vertical-align: middle;
            width: 287mm;
            height: 200mm;
            font-size: 14pt;
         }
         .pagebreakafter_always {
            page-break-after: always;
            width: 297mm;
            height: 210mm;
         }
         .cornice{
            position: absolute;
            top: 0mm;
            left: 0mm;
            bottom: 0mm;
            right: 0mm;
            width: 297mm;
            height: 210mm;
        }
        .cornice img{
            width: 297mm;
            height: 210mm;
        }
         h1{
            font-size: 32pt;
            margin-bottom: 0px;
         }

         h2{
            font-size: 28pt;
            margin-bottom: 0px;
         }
         h3{
            font-size: 20pt;
         }

        #firma{
            text-align:left;
            margin-left: 150px;
            margin-top: 680px;
            font-size: 11pt;
            position: absolute;
            font-weight: bold;
        }
            </style>
            <html>
            <body>
                '.$htmladd.'
            </body>
            </html>';

        try {
            //$messaggio = str_replace('_XXX_MESSAGGIO_XXX_', $messaggio, $html);
            //$messaggio = str_replace('_XXX_DATA_FIRMA_XXX_', "Lugo (RA), ".GiraDataOra($dataCompletamento), $messaggio);
            $content = html_entity_decode($html);

            $html2pdf = new Html2Pdf($orientamento, 'A4', 'it', true, 'UTF-8',array(0, 0, 0, 0 ));
            $html2pdf->setDefaultFont('Times');
            $html2pdf->writeHTML($content);
            $html2pdf->output('Attestato_Crocco.pdf');
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }else{
        $html = '<STYLE type="text/css">    
           
        body {
            font-family: \'Trajan Pro\';
            font-size: 14pt;
            background-color: #FFFFCC;
        }
        #divid{margin-top: 0px;margin-left: 0px;
            text-align:center;
            vertical-align: top;
            margin-left: 9.5mm;
            margin-top: 45mm;
            padding: 0px;
            width: 190mm;
            height: 190mm;
            font-size: 14pt;
         }
         .pagebreakafter_always {
            page-break-after: always;
            width: 210mm;
            height: 297mm;
         }
         .cornice{
            position: absolute;
            top: 0mm;
            left: 0mm;
            bottom: 0mm;
            right: 0mm;
            width: 210mm;
            height: 297mm;
        }
        .cornice img{
            width: 210mm;
            height: 297mm;
        }
         h1{
            font-size: 26pt;
            margin-bottom: 0px;
         }

         h2{
            font-size: 18pt;
            margin-bottom: 0px;
         }
         h3{
            font-size: 18pt;
         }

        #firma{
            text-align:left;
            margin-left: 150px;
            margin-top: 920px;
            font-size: 11pt;
            position: absolute;
            font-weight: bold;
        }
            </style>
            <html>
            <body>
                '.$htmladd.'
            </body>
            </html>';

        try {
            //$messaggio = str_replace('_XXX_MESSAGGIO_XXX_', $messaggio, $html);
            //$messaggio = str_replace('_XXX_DATA_FIRMA_XXX_', "Lugo (RA), ".GiraDataOra($dataCompletamento), $messaggio);
            $content = html_entity_decode($html);

            $html2pdf = new Html2Pdf($orientamento, 'A4', 'it', true, 'UTF-8',array(0, 0, 0, 0 ));
            $html2pdf->setDefaultFont('Times');
            $html2pdf->writeHTML($content);
            $html2pdf->output('Attestato_Crocco.pdf');
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }

    $filename = 'Attestato_Crocco.pdf';
    
}

if(!is_dir(BASE_ROOT . "media")){
    mkdir(BASE_ROOT . "media", 0777);
}
if(!is_dir(BASE_ROOT . "media/lista_attestati")){
    mkdir(BASE_ROOT . "media/lista_attestati", 0777);
}
if(file_exists(BASE_ROOT . "media/lista_attestati/".$filename)){
    chmod(BASE_ROOT. 'media/lista_attestati/' . $filename, 0777);
}

//$pdf->Output(BASE_ROOT . 'media/lista_attestati/' . $filename, 'F');

//$pdf->Output($filename, 'I');



