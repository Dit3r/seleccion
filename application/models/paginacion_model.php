<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginacion_model extends CI_Model {

	protected $table;
	protected $id;
	
	/*
		Constructor del modelo, aqui establecemos
		que tabla utilizamos y cual es su llave primaria.
	*/
	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('bdseleccion', true);
		//$this->table = 'ASIMILA_TMP';
		$this->table = 'ACTIVOS';
	}
	
	function get() {
		$resultado = $this->db->get($this->table);
		return $resultado->result();
	}	
	
	function get_funcion($nroCargo)
	{
		#parametro 1:EMPRESA,parametro 2:CARGO, de la tabla SEL_WF_SOLICITUD_TRASLADO
		$strQuery ="select SACA_DESC_CARGO(11,".$nroCargo.") as CARGO from dual";
		return $this->db->query($strQuery)->result();
	}
	
	function autocompletarSolicitudTraslado($rut)
	{
		#parametro 1:EMPRESA,parametro 2:CARGO, de la tabla SEL_WF_SOLICITUD_TRASLADO
		$strQuery ="select pkg_seleccion.trae_informacion_general(11,".$rut.") as INFOSOLICITUD from dual";
			return $this->db->query($strQuery)->result();
	}	
	
/*::::::::::::::::::: paginacion inicio:::::::::::::::::::::::*/
	public function get_filtrar_rango ($inicio = FALSE,$limite= FALSE)
	{
		$this->db->order_by('WF_KEY','DESC');
		
		if ($inicio !== FALSE && $limite !== FALSE) {
			$this->db->limit($limite, $inicio);
		}
		
		$consulta = $this->db->get('SEL_WF_SOLICITUD_TRASLADO');
		return $consulta->result();
		/*
		$sql = "select * FROM SEL_WF_SOLICITUD_TRASLADO";
		$resultado = $this->db->query($sql);
		return $resultado->result();
		*/
	
	}	
/*::::::::::::::::::: paginacion fin:::::::::::::::::::::::*/
	
	
}