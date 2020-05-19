<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso_vacante extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Ingreso_vacante_model');
	}
	
	public function index() {

	}
	
	public function pendientes() {
		
		if(!isset($_SESSION)){
			session_start();
		}
		
		/*para pruebas test inicio*/
		if (!isset($_SESSION["rut"])) {
			redirect(site_url(), 'refresh');
		}
	
		//filtros
		$data['filtroCargos'] = $this->Ingreso_vacante_model->solVacPendFiltroCargo($_SESSION['rut']);
		$data['filtroUnidad'] = $this->Ingreso_vacante_model->solVacPendFiltroUnidad($_SESSION['rut']);

		$data['filas'] = $this->Ingreso_vacante_model->solVacantPend($_SESSION['rut']);
		
		
		foreach ($data['filas'] as $index => $val) {
			$descEstLarga[] = $this->Ingreso_vacante_model->getdescEstLarga($val->ID_FLUJO, $val->ETAPA_ACTUAL);
		}
		@$data['descEstLarga'] = $descEstLarga;
		
		
		$data['titlePage'] = "Vacantes - Solicitudes Pendientes";
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view('contenidos/ingreso-vacante/solicitud_ingr_vacant_view');
		$this->load->view("template/tpl2-sb/footer");
	}

	public function historico()
	{
		if(!isset($_SESSION)){
			session_start();
		}
	
		/*para pruebas test inicio*/
		if (!isset($_SESSION["rut"])) {
			redirect(site_url(), 'refresh');
		}
		/*para pruebas test fin*/
			
		//filtros
		//->$data['filtroCargos'] = $this->Ingreso_vacante_model->solVacHistFiltroCargo($_SESSION['rut']);
		//->$data['filtroUnidad'] = $this->Ingreso_vacante_model->solVacHistFiltroUnidad($_SESSION['rut']);
		
		//filas: todos los registros
		$data['filas'] = $this->Ingreso_vacante_model->solTrasHist($_SESSION['rut']);
		foreach ($data['filas'] as $index => $val) {
			//$descEstLarga[] = $this->Traslado_model->getdescEstLarga('TRAS',$val->ID_FLUJO, $val->ETAPA_ACTUAL);
			//echo $val->ID_FLUJO;
			//echo $val->ETAPA_ACTUAL;
			$descEstLarga[] = $this->Ingreso_vacante_model->getdescEstLarga($val->ID_FLUJO, $val->ETAPA_ACTUAL);
		}		

		$data['descEstLarga'] = $descEstLarga;
		$data_header['titlePage'] = "Solicitud Historica";
		$this->load->view("template/tpl2-sb/header",$data_header);
		$this->load->view("contenidos/ingreso-vacante/ingreso_vacantes_historica_view", $data);
		$this->load->view("template/tpl2-sb/footer");
	}//fin funcion historico

}

/* End of file ingreso_vacante.php */
/* Location: ./application/controllers/ingreso_vacante.php */