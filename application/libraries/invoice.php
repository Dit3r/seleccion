<?php
require('fpdf.php');


class PDF_Invoice extends FPDF
{
// private variables
var $colonnes;
var $format;
var $angle=0;

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' || $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

// public functions
function sizeOfText( $texte, $largeur )
{
	$index    = 0;
	$nb_lines = 0;
	$loop     = TRUE;
	while ( $loop )
	{
		$pos = strpos($texte, "\n");
		if (!$pos)
		{
			$loop  = FALSE;
			$ligne = $texte;
		}
		else
		{
			$ligne  = substr( $texte, $index, $pos);
			$texte = substr( $texte, $pos+1 );
		}
		$length = floor( $this->GetStringWidth( $ligne ) );
		if($largeur==0 || $largeur==""){
		  $largeur=1;
		}
		$res = 1 + floor( $length / $largeur) ;
		$nb_lines += $res;
	}
	return $nb_lines;
}

// Company
function addSociete( $nom, $adresse )
{
	$x1 = 17;
	$y1 = 4;
	//Positionnement en bas
	//$this->Image('',16,10,30);
	$this->Image( $nom,16,10);
	$this->SetXY( $x1, $y1 + 34 ); //espacio
	$this->SetFont('Arial','B',10);
	$length = $this->GetStringWidth( $adresse );
	//Coordonnées de la société
	$lignes = $this->sizeOfText( $adresse, $length) ;
	$this->MultiCell($length, 4, $adresse);
}

function addClientAdresse( $adresse )
{
	$r1     = $this->w - 410;
	$r2     = $r1 + 58;
	$y1     = 32.5;
	$this->SetXY( $r1, $y1);
	$this->MultiCell( 60, 4, $adresse);
}

function addClientAdresse2( $adresse )
{
	$r1     = $this->w - 100;
	$r2     = $r1 + 58;
	$y1     = 32.5;
	$this->SetXY( $r1, $y1);
	$this->MultiCell( 60, 4, $adresse);
}


// Label and number of invoice/estimate
function fact_dev( $libelle, $texte )
{
    $r1  = $this->w - 80;
    $r2  = $r1 + 68;
    $y1  = 15;
    $y2  = $y1 + 2;
    $mid = ($r1 + $r2 ) / 2;
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 1.5, '');
    $this->SetXY( $r1+1, $y1+2);
}

function addSociete2( $nom, $adresse )
{
	$x1 = 70;
	$y1 = 14;
	//Positionnement en bas
	$this->SetXY( $x1, $y1 + 0 );
	$this->SetFont('Arial','',15);
	$length = $this->GetStringWidth( $adresse );
	//Coordonnées de la société
	$lignes = $this->sizeOfText( $adresse, $length) ;
	$this->MultiCell($length, 4, $adresse);
}


function addPageNumber( $page )
{
	$r1  = $this->w - 80;
	$r2  = $r1 + 19;
	$y1  = 17;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "PAGE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$page, 0,0, "C");
}


function addCols( $tab )
{
	//global $colonnes;
	
	$r1  = 20;//ancho cuadrado de factura
	$r2  = $this->w - ($r1 * 2) ;
	$y1  = 40;//bajar cuadrado de detalle factura
	$y2  = $this->h - 210 - $y1;//altura del cuadradado factura
	$this->SetXY( $r1, $y1 );
	$this->Rect( $r1, $y1, $r2, $y2, "D");
	$this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
	$this->Line( $r1, $y1+12, $r1+$r2, $y1+12);
	$this->Line( $r1, $y1+18, $r1+$r2, $y1+18);
	$this->Line( $r1, $y1+24, $r1+$r2, $y1+24);
	$this->Line( $r1, $y1+30, $r1+$r2, $y1+30);
	$this->Line( $r1, $y1+36, $r1+$r2, $y1+36);
	$colX = $r1;
	$colonnes = $tab;
	
	while ( list( $lib, $pos ) = each ($tab) )
	{
		$this->SetXY( $colX, $y1+2 );
		$this->Cell( $pos, 0, $lib, 0, 0, "L");
		$colX += $pos;
		$this->Line( $colX, $y1, $colX, $y1+$y2);
	}
}

function addLineFormat( $tab )
{
	global $format, $colonnes;
	
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		if ( isset( $tab["$lib"] ) )
			$format[ $lib ] = $tab["$lib"];
	}
}

function lineVert( $tab )
{
	global $colonnes;

	reset( $colonnes );
	$maxSize=0;
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		$texte = $tab[ $lib ];
		$longCell  = $pos -2;
		$size = $this->sizeOfText( $texte, $longCell );
		if ($size > $maxSize)
			$maxSize = $size;
	}
	return $maxSize;
}


function addLine( $ligne, $tab )
{
	global $colonnes, $format;

	$ordonnee     = 10;
	$maxSize      = $ligne;

	reset( $colonnes );
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		$longCell  = $pos -2;
		$texte     = $tab[ $lib ];
		$length    = $this->GetStringWidth( $texte );
		$tailleTexte = $this->sizeOfText( $texte, $length );
		$formText  = $format[ $lib ];
		$this->SetXY( $ordonnee, $ligne-1);
		$this->MultiCell( $longCell, 4 , $texte, 0, $formText);
		if ( $maxSize < ($this->GetY()  ) )
			$maxSize = $this->GetY() ;
		$ordonnee += $pos;
	}
	return ( $maxSize - $ligne );
}


//pie de pagina factura
function addCadreEurosFrancs($imgd,$total)
{
 global $mosConfig_live_site;
 ob_end_clean();
	$r1  = $this->w - 60;
	$r2  = $r1 + 50;
	$y1  = $this->h - 105;
	$y2  = $y1+10;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1+18,  $y1, $r1+18, $y2); // avant EUROS
	$this->SetFont( "Arial", "B", 6);
	$this->SetXY( $r1, $y1+3 );
	$this->Cell(19,4, "TOTAL :", 0, 0, "C");
	$this->Cell(30,4, "$".number_format($total,0,",","."), 0, 0, "R");
	$this->SetXY( $r1, $y1+10 );
	$r1  = $this->w - 200;
	$r2  = $r1 + 190;
	$y1  = 139;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
//IMAGEN PIE	
	$this->Line( $r1, $mid, $r2, $mid);
//	$this->Image("fotos/".$imgd,67,210,75,35,'','', false);
	$this->Image("fotos/".$imgd,50,210,100);
	//$this->Image("fotos/".$imgd,67,210,75,117,'','', true);
	$this->SetFont( "Arial", "B", 7);
	$this->SetXY( $r1, $y1+105 );
	$this->Cell(190,4, "Timbre Electrónico S.I.I. Res. Exe. N° 73 del 31 de Mayo del 2011\n ", 0, 0, "C");
	$this->SetXY( $r1, $y1+108 );
	$this->Cell(190,4, "Respete siempre su receta Médica", 0, 0, "C");
	$this->SetXY( $r1, $y1+115 );
	$this->Cell(190,4, "Verifique documento en www.salcobrand.cl", 0, 0, "C");	
	//unlink("fotos/".$imgd);
}


}
?>