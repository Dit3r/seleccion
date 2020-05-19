<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navegacion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	public function navegadorIncompatible()
	{
		$data['titlePage'] = "INGRESO";
        $this->load->view("template/tpl2-sb/navegador_no_compatible",$data);
	}

}