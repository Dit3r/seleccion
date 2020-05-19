<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$data['titlePage'] = "INGRESO";
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/inicio_view");
		$this->load->view("template/tpl2-sb/footer");
	}

}