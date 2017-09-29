<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
if ($browser == true) {
    //echo 'Code You Want To Execute';
}

require_once(BASE_ROOT . 'classi/fpdf/fpdf.php');

class PDFAttestatoP extends FPDF {

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
        $this->Cell(0, 1, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

if (isset($_GET['idAttestato'])) {

    $idAttestato = $_GET['idAttestato'];

    $queryTot_3 = "SELECT * FROM lista_attestati WHERE id =" . $idAttestato;
    //ECHO '<LI>$queryTot_3 = '.$queryTot_3.'</LI>';
    $ris_totale_3 = $dblink->num_rows($queryTot_3);

    //$arr_totale_3	=	mysql_fetch_row($ris_totale_3);
    //$totale 		= 	$arr_totale_3[0];


    $sql_paginazione = "SELECT * FROM lista_attestati WHERE id =" . $idAttestato;
    ;
    $conteggio_paginazione = $dblink->num_rows($sql_paginazione);
    if ($conteggio_paginazione <= 0) {
        //header("Location: printFatturaPDFAnt.php?id=".$id_fattura);
    }
    $totale = ($ris_totale_3) + ($conteggio_paginazione);

    $pageSize = 12;
    $pagina = 1;
    $begin = ($pagina - 1) * $pageSize;
    $countPages = ceil($totale / $pageSize);

    $html = '';

    $pdf = new PDFAttestatoP();
    $pdf->AliasNbPages();

    $sql = "SELECT * FROM lista_attestati WHERE id =" . $idAttestato;
    $rs = $dblink->get_results($sql);

    if (!empty($rs)) {

        $pdf->AddPage();
        $pdf->SetFont('Times', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Image('attestato_verticale_001.jpg', 0, 0, 210);
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
if(!is_dir(BASE_ROOT . "media/lista_corsi")){
    mkdir(BASE_ROOT . "media/lista_corsi", 0777);
}
if(file_exists(BASE_ROOT . "media/lista_corsi/".$filename)){
    chmod(BASE_ROOT. 'media/lista_corsi/' . $filename, 0777);
}

$pdf->Output(BASE_ROOT . 'media/lista_corsi/' . $filename, 'F');
//$pdf->Output(BASE_ROOT."media/Fatture/Attive/".$anno_creazione_fattura."/".$mese_creazione_fattura."/".$filename, F);
//$pdf->Output(BASE_ROOT."media/Anagrafiche/Clienti/".$azienda_creazione_fattura."/".$filename, F);
$pdf->Output($filename, 'I');
