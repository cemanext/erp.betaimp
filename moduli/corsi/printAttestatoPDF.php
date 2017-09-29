<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
if ($browser == true) {
    //echo 'Code You Want To Execute';
}

require_once(BASE_ROOT . 'classi/fpdf/fpdf.php');

class PDFAttestato extends FPDF {

    var $B;
    var $I;
    var $U;
    var $HREF;

    function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        //Call parent constructor
        parent::__construct($orientation, $unit, $format);
        //Initialization
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
    }

    function Header() {
        //Logo
        //$this->Image('images/carta_intestata.jpg',0,0,210);
        //Times bold 15
        $this->SetFont('Times', 'B', 8);
        //Line break
        $this->Ln(45);
    }

    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-5);
        //Times italic 8
        $this->SetFont('Times', 'I', 6);
        //Page number
        //$this->Cell(0, 1, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

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
    $durata = $rowCostiConfig['durata'];
    $codiceAccreditamento = $rowCostiConfig['codice_accreditamento'];
    $idAttestato = $rowCostiConfig['id_attestato'];
    
    $rowAttestati = $dblink->get_row("SELECT * FROM lista_attestati WHERE id = '$idAttestato'", true);
    $orientamento = $rowAttestati['orientamento'];
    $nomeFile = $rowAttestati['nome'];
    
    $rowProfessionista = $dblink->get_row("SELECT * FROM lista_professionisti WHERE id = '$idProfessinista'", true);
    $nome = $rowProfessionista['nome'];
    $cognome = $rowProfessionista['cognome'];
    $professione = $rowProfessionista['professione'];
    $dataDiNascita = $rowProfessionista['data_di_nascita'];
    $provinciaDiNascita = $rowProfessionista['provinci_di_nascita'];
    $luogoDiNascita = $rowProfessionista['luogo_di_nascita'];
    
    $rowCorso = $dblink->get_row("SELECT * FROM lista_corsi WHERE id = '$idCorso'", true);
    $nomeCorso = $rowCorso['nome_prodotto'];
    
    $totale = 1;

    $pageSize = 12;
    $pagina = 1;
    $begin = ($pagina - 1) * $pageSize;
    $countPages = ceil($totale / $pageSize);

    $html = '';

    $pdf = new PDFAttestato($orientamento,'mm','A4');
    $pdf->AliasNbPages();

    $sql = "SELECT * FROM lista_attestati WHERE id =" . $idAttestato;
    $rs = $dblink->get_results($sql);

    if (!empty($rs)) {

        $pdf->AddPage();
        $pdf->SetFont('Times', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        if($orientamento == "L"){
            $pdf->Image($nomeFile, 0, 0, 297);
        }else{
            $pdf->Image($nomeFile, 0, 0, 210);
        }
        $pdf->SetXY(100, 100);
        $pdf->Cell(50, 5, '$idAttestato = ' . $idAttestato . ' ---> crocco simone', 0, 0, L, 0, 0);

        $filename = 'Attestato_Crocco.pdf';
    }
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

$pdf->Output(BASE_ROOT . 'media/lista_attestati/' . $filename, 'F');

$pdf->Output($filename, 'I');
