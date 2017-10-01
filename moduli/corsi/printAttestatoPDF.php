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
    
    $rowCostiConfig = $dblink->get_row("SELECT * FROM lista_corsi_configurazioni WHERE id_corso = '$idCorso' AND id_classe = $idClasse AND (((data_inizio<='$dataCompletamento' OR data_inizio='00-00-0000') AND (data_fine>='$dataCompletamento' OR data_fine='00-00-0000')) OR (data_inizio='00-00-0000' OR data_fine='00-00-0000')) ORDER BY data_fine DESC, data_inizio DESC", true);
    $crediti = $rowCostiConfig['crediti'];
    $durata = $rowCostiConfig['durata_corso'];
    $codiceAccreditamento = $rowCostiConfig['codice_accreditamento'];
    $idAttestato = $rowCostiConfig['id_attestato'];
    $titolo = $rowCostiConfig['titolo'];
    $messaggio = $rowCostiConfig['messaggio'];
    
    $rowAttestati = $dblink->get_row("SELECT * FROM lista_attestati WHERE id = '$idAttestato'", true);
    $orientamento = $rowAttestati['orientamento'];
    $nomeFile = $rowAttestati['nome'];
    
    $rowProfessionista = $dblink->get_row("SELECT * FROM lista_professionisti WHERE id = '$idProfessinista'", true);
    $nome = $rowProfessionista['nome'];
    $cognome = $rowProfessionista['cognome'];
    $professione = $rowProfessionista['professione'];
    $dataDiNascita = $rowProfessionista['data_di_nascita'];
    $provinciaDiNascita = $rowProfessionista['provincia_di_nascita'];
    $luogoDiNascita = $rowProfessionista['luogo_di_nascita'];
    
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
    
    
    $totale = 1;

    $pageSize = 12;
    $pagina = 1;
    $begin = ($pagina - 1) * $pageSize;
    $countPages = ceil($totale / $pageSize);

    $html = '<STYLE type="text/css">    
        @page {
            size: A4;
            margin: 0;
          }
          @media print {
            html, body {
              width: 297mm;
              height: 210mm;
            }
          }
    #divid{margin-top: 0px;margin-left: 0px;
        background-image: url('.BASE_URL.'/moduli/corsi/'.str_replace('.jpg','_2.jpg',$nomeFile).');
        background-size: 30%;
        background-repeat: no-repeat;
        text-align:center;
        vertical-align: middle;
        width: 297mm;
        height: 210mm;
        font-size: 14pt;
     }
     h1{
        font-size: 34pt;
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
            <div id="divid">
                _XXX_MESSAGGIO_XXX_
                <div id="firma">
                    _XXX_DATA_FIRMA_XXX_
                </div>
            </div>
        </body>
        </html>';

    try {
        ob_start();
        $messaggio = str_replace('_XXX_MESSAGGIO_XXX_', $messaggio, $html);
        $messaggio = str_replace('_XXX_DATA_FIRMA_XXX_', "Lugo (RA), ".GiraDataOra($dataCompletamento), $messaggio);
        $content = html_entity_decode($messaggio);

        $html2pdf = new Html2Pdf($orientamento, 'A4', 'it', true, 'UTF-8',array(0, 0, 0, 0 ));
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($content);
        $html2pdf->output('Attestato_Crocco.pdf');
    } catch (Html2PdfException $e) {
        $formatter = new ExceptionFormatter($e);
        echo $formatter->getHtmlMessage();
    }

    /*$pdf = new PDFAttestato($orientamento,'mm','A4');
    $pdf->AliasNbPages();
    $pdf->SetMargins(20,20);
    
    
    $pdf->SetFont('Times','',14);
    $pdf->AddPage();
    
    $pdf->SetStyle("p","times","N",14,"0,0,0",0);
    $pdf->SetStyle("span","times","N",14,"0,0,0",0);
    $pdf->SetStyle("h1","times","BN",38,"0,0,0",0);
    $pdf->SetStyle("h2","times","BN",28,"0,0,0",0);
    $pdf->SetStyle("h3","times","BN",20,"0,0,0",0);
    $pdf->SetStyle("b","times","B",14,"0,0,0");
    $pdf->SetStyle("br","times","N",14,"0,0,0");
    $pdf->SetStyle("place","arial","U",0,"153,0,0");
    $pdf->SetStyle("vb","times","B",0,"102,153,153");
    
    if($orientamento == "L"){
        $pdf->Image($nomeFile, 0, 0, 297);
        $pdf->SetXY(20,55);
        //$pdf->WriteHTML(html_entity_decode($messaggio),0);
        $pdf->WriteTag(0,10,html_entity_decode($messaggio),0,"C",0,0);
        //$this->Cell(0,210,$cell,0,2,'',false);
    }else{
        $pdf->Image($nomeFile, 0, 0, 210);
        $pdf->SetXY(20,55);
        $pdf->WriteHTML(html_entity_decode($messaggio),0);
        //$this->Cell(0,297,$cell,0,0,'C',0,0);
    }
    
    //$pdf->MultiCell(250, 5, ''. html_entity_decode($messaggio).''  , 0, 'C', 0, true);
    
    
    //$pdf->Output();

    

        /*$pdf->AddPage();
        $pdf->SetFont('Times', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        if($orientamento == "L"){
            $pdf->Image($nomeFile, 0, 0, 297);
        }else{
            $pdf->Image($nomeFile, 0, 0, 210);
        }
        $pdf->SetXY(100, 100);
        $pdf->WriteHTML($messaggio);*/

        $filename = 'Attestato_Crocco.pdf';
    
}
//stampo
//$pdf->Output();

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



