<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');

$browser = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
if ($browser == true) {
    //echo 'Code You Want To Execute';
}

require_once(BASE_ROOT . 'classi/fpdf/fpdf.php');

function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['V']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

class PDFAttestato extends FPDF {

    var $B;
    var $I;
    var $U;
    var $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;

    function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        //Call parent constructor
        parent::__construct($orientation, $unit, $format);
        //Initialization
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
        $this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
        $this->issetfont=false;
        $this->issetcolor=false;
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
    
    function Scrivi($h, $txt, $link='')
    {
            // Output text in flowing mode
            if(!isset($this->CurrentFont))
                    $this->Error('No font has been set');
            $cw = &$this->CurrentFont['cw'];
            $w = $this->w-$this->rMargin-$this->x;
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r",'',$txt);
            $nb = strlen($s);
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $nl = 1;
            while($i<$nb)
            {
                    // Get next character
                    $c = $s[$i];
                    if($c=="\n")
                    {
                            // Explicit line break
                            $this->Cell($w,$h,substr($s,$j,$i-$j),1,2,'C',false,$link);
                            $i++;
                            $sep = -1;
                            $j = $i;
                            $l = 0;
                            if($nl==1)
                            {
                                    $this->x = $this->lMargin;
                                    $w = $this->w-$this->rMargin-$this->x;
                                    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
                            }
                            $nl++;
                            continue;
                    }
                    if($c==' ')
                            $sep = $i;
                    $l += $cw[$c];
                    if($l>$wmax)
                    {
                            // Automatic line break
                            if($sep==-1)
                            {
                                    if($this->x>$this->lMargin)
                                    {
                                            // Move to next line
                                            $this->x = $this->lMargin;
                                            $this->y += $h;
                                            $w = $this->w-$this->rMargin-$this->x;
                                            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
                                            $i++;
                                            $nl++;
                                            continue;
                                    }
                                    if($i==$j)
                                            $i++;
                                    $this->Cell($w,$h,substr($s,$j,$i-$j),1,2,'C',false,$link);
                            }
                            else
                            {
                                    $this->Cell($w,$h,substr($s,$j,$sep-$j),1,2,'C',false,$link);
                                    $i = $sep+1;
                            }
                            $sep = -1;
                            $j = $i;
                            $l = 0;
                            if($nl==1)
                            {
                                    $this->x = $this->lMargin;
                                    $w = $this->w-$this->rMargin-$this->x;
                                    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
                            }
                            $nl++;
                    }
                    else
                            $i++;
            }
            // Last chunk
            if($i!=$j)
                    $this->Cell($l/1000*$this->FontSize,$h,substr($s,$j),1,0,'C',false,$link);
    }
    
    function WriteHTML($html,$larghezza)
    {
        //HTML parser
        $html=strip_tags($html,"<h1><h2><h3><h5><h4><b><u><i><a><img><p><br><strong><em><font><table><tr><th><td><blockquote><center><p><div>"); //supprime tous les tags sauf ceux reconnus
        $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else{
                    $this->MultiCell($larghezza, 1, ''. stripslashes(txtentities($e)).''  , 0, 'C', 0, true);
                    //$this->Scrivi(5,stripslashes(txtentities($e)));
                }
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract attributes
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        //Opening tag
        switch($tag){
            case 'H1':
                $this->SetFont('Times','B',38);
                break;
            case 'H2':
                $this->SetFont('Times','B',28);
                break;
            case 'H3':
                $this->Ln(3);
                $this->SetFont('Times','B',20);
                break;
            case 'STRONG':
                $this->SetFont('Times','B',14);
                break;
            case 'EM':
                $this->SetFont('Times','I',14);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag,true);
            break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
        }
    }

    function CloseTag($tag)
    {
        switch ($tag) {
            case "H1":
                $this->Ln(10);
                $this->SetFont('Times','',14);
            break;
        
            case "H2":
                $this->Ln(10);
                $this->SetFont('Times','',14);
            break;
        
            case "H3":
                $this->Ln(10);
                $this->SetFont('Times','',14);
            break;
        
            case "FONT":
                if ($this->issetcolor==true) {
                    $this->SetTextColor(0);
                }
                if ($this->issetfont) {
                    $this->SetFont('Times');
                    $this->issetfont=false;
                }
            break;
            
            case "B":
            case "I":
            case "U":
                $this->SetStyle($tag,false);
            break;
            
            case "A":
                $this->HREF='';
                $this->SetFont('Times','',14);
            break;
        
            default:
                $this->Ln(10);
                $this->SetFont('Times','',14);
            break;
        }
        //Closing tag
        /*if($tag=='H1')
            $this->Ln(10);
            $this->SetFont('Times','',12);
        if($tag=='H2')
            $this->Ln(10);
            $this->SetFont('Times','',12);
        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetFont('Times','',12);
            //$this->SetStyle($tag,false);
            //$this->SetFontSize(12);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('Times');
                $this->issetfont=false;
            }
        }else{
            $this->Ln(10);              
        }*/
    }

    function SetStyle($tag, $enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
        {
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
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

    $html = '';

    $pdf = new PDFAttestato($orientamento,'mm','A4');
    $pdf->AliasNbPages();
    $pdf->SetMargins(20,20);
    
    
    $pdf->SetFont('Times','',12);
    $pdf->AddPage();
    if($orientamento == "L"){
        $pdf->Image($nomeFile, 0, 0, 297);
        $pdf->SetXY(20,55);
        $pdf->WriteHTML(html_entity_decode($messaggio),0);
        //$this->Cell(0,210,$cell,0,2,'',false);
    }else{
        $pdf->Image($nomeFile, 0, 0, 210);
        $pdf->SetXY(20,55);
        $pdf->WriteHTML(html_entity_decode($messaggio),0);
        //$this->Cell(0,297,$cell,0,0,'C',0,0);
    }
    
    //$pdf->MultiCell(250, 5, ''. html_entity_decode($messaggio).''  , 0, 'C', 0, true);
    
    
    $pdf->Output();

    

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

$pdf->Output(BASE_ROOT . 'media/lista_attestati/' . $filename, 'F');

$pdf->Output($filename, 'I');
