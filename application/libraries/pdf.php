<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
    require('fpdf.php');
    require('invoice.php');
    class Pdf extends FPDF { 
    function __construct($orientation='P', $unit='mm', $size='A4') 
    { 
        // Call parent constructor 
        parent::__construct($orientation,$unit,$size); 
        $this->fontpath='fonts/';
    }
}
?>