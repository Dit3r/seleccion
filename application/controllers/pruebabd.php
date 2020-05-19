<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pruebabd extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('Inicio_m');	
		$this->load->model('pruebabd_model');	
	}
	public function index()
	{
		$data['titlePage'] = "INGRESO";
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/inicio_view");
		$this->load->view("template/tpl2-sb/footer");

	}

	//prueba base de datos
	public function bd()
	{
		$this->output->enable_profiler(TRUE);
		$consulta = $this->pruebabd_model->get();

		foreach ($consulta as $row) {
			//echo $row->IT_PRODUCTO . "<br />";
			echo $row->NUMRUT_ACT . "<br />";
			
		}
	}
}