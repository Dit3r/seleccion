<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class Crear_pdf extends CI_Controller { 
    function __construct() 
    { 
    parent::__construct(); 
    $this->load->library('pdf'); // Load FPDF
    $this->pdf->fontpath = 'fonts/'; // Especifica el directorio font
    }
 
    public function index()
    {
    // Genera un PDF con 'Hola Mundo'
    $this->pdf->AddPage();
    $this->pdf->SetFont('Arial','B',16);
    $this->pdf->Cell(40,10,'Hola Mundo!');
    $this->pdf->Output();
    }
}
?>