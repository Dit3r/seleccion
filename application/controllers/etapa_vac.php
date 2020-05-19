<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etapa_vac extends CI_Controller {
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Etapa_vac_model');
		$this->load->helper('rut');
	}
	
	public function index()
	{
	}
	
	public function pendiente($idSolicitud = FALSE, $paso = 1)
	{
		$this->load->library('nativesession');
		$data['titlePage'] = "Solicitudes Vacantes Pendientes";
		$dataContenido['idSolicitud'] = $idSolicitud;
		$dataContenido['titlePage'] = $data['titlePage'];
		$stringEtapas = $this->Etapa_vac_model->getOvalos($idSolicitud);
		$dataContenido['ovalos'] = json_decode($stringEtapas[0]->STRING_ETAPAS);
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/ingreso-vacante/etapa_pend_vac_view",$dataContenido);
		$this->load->view("template/tpl2-sb/footer");
	}	
	
	public function fillEtapaLog()
	{
		$solicitud = $this->input->post("solicitud");
		$nroEtapa = $this->input->post("nroEtapa");
		$correlativo = $this->input->post("correlativo");
		$strJson = $this->Etapa_vac_model->getfillEtapaLog($solicitud,$nroEtapa,$correlativo);
		echo $strJson[0]->STRING_ETAPA_LOG;
	}	

	public function fillEtapaFrmVac()
	{
		$solicitud = $this->input->post("solicitud");
		$strJson = $this->Etapa_vac_model->getfillEtapaFrmVac($solicitud);
		echo $strJson[0]->STRING_FRM;
	}

	public function insertLog()
	{
		
		
		$insertLog = $this->Etapa_vac_model->putInsertLog($this->input->post());
		echo json_encode($insertLog);
	}
	
	public function goEtapa()
	{
		$nroSolicitud = $this->input->post('solicitud');
		$etapa = $this->Etapa_vac_model->getGoEtapa($nroSolicitud);
		echo json_encode($etapa);
	}
	
	public function graba_log_correo()
	{
		/*
			Array
			(
			[p_id_solicitud] => 24419
			[p_etapa_origen] => 6
			[p_etapa_destino] => 8
			[p_correo_desde] => workflow@sb.cl
			[p_correo_hasta] => xvergara@sb.cl
			[p_texto] => Texto de prueba de la modal
			)
			*/
		$dataLogMail = $this->input->post();
		$insertaLogCorreo = $this->Etapa_vac_model->put_graba_log_correo($dataLogMail);
	
		//print_r($dataLogMail);
		//die();
	}	
	
	
	
	
	
	public function enviaMailTodosParticipantes()
	{
		$this->load->library('email');
		
		$solicitud = $this->input->post('p_id_solicitud');
		$lstCorreos = $this->Etapa_vac_model->getCorreoParticipantes($solicitud);
		$lstCorreos = $lstCorreos[0]->CORREO_PARTICIPANTES;
		$lstCorreos = json_decode($lstCorreos);
		
		$correos = array();
		foreach ($lstCorreos as $k => $obj) {
			if ($obj->correo) {
				$correos[] = $obj->correo;
			}
		}
		
		if (count($correos) == 0) {
			
			$asunto = 		'POROBLEMA: no se enviara correo a ninguna persona';
			$contenido = 	'No hay correos en el tabla SEL_WF_LOG_CORREOS asociados a esta wf_key';
			
			
			$this->email->from('workflow@sb.cl', 'Workflow');
			$this->email->to('jgatica@sb.cl', 'Jorge Gatica');
			
			$this->email->subject($asunto);
			$this->email->message($contenido);			
			
		} else {

			//luego que aplique la libreria quitar
			//print_r($correos);
			//descomentar cuando este operativo $to = $this->input->post('to');
			$to = 'jorge@w7.cl';
			$correos = array_unique($correos);
			//-> con esta linea se le estaria enviando mail a todos los involucrados $this->email->to($correos);
		
			$asunto = 		$this->input->post('asunto');
			$contenido = 	$this->input->post('contenido');
		
		
			$this->email->from('workflow@sb.cl', 'Workflow');
			$this->email->to($to);
		
			$this->email->subject($asunto);
			$this->email->message($contenido);
			/*
				if($this->email->send())
				*/
			print_r($correos);
			echo $asunto;
			echo $contenido;
		}
	}//fin funcion enviaMailTodosParticipantes
	
	public function historico($idSolicitud = FALSE, $etapa)
	{
		$data['titlePage'] = "Solicitudes Vacantes Finalizadas";
		//77 88 o 99
		$dataContenido['estado'] = $this->Etapa_vac_model->getHistEtapa($idSolicitud);
		$dataContenido['estado'] = $dataContenido['estado'][0]->ETAPA_ACTUAL;
		$dataContenido['idSolicitud'] = $idSolicitud;
		$dataContenido['titlePage'] = $data['titlePage'];
		$stringEtapas = $this->Etapa_vac_model->getOvalos($idSolicitud);
		$dataContenido['ovalos'] = json_decode($stringEtapas[0]->STRING_ETAPAS);
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/ingreso-vacante/etapa_hist_view",$dataContenido);
		$this->load->view("template/tpl2-sb/footer");
	
	}	
	
} // fin Etapa_vac