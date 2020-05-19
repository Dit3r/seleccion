<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pruebabd_model extends CI_Model {

	protected $table;
	protected $id;
	private $Bdseleccion_db;
	
	/*
		Constructor del modelo, aqui establecemos
		que tabla utilizamos y cual es su llave primaria.
	*/
	/*
	function __construct() {
		parent::__construct();
		$this->db_desa = $this->load->database('bdseleccion', true);
		//$this->table = 'ASIMILA_TMP';
		$this->table = 'ACTIVOS';
	}
	*/
	
	public function __construct(){
		$this->Bdseleccion_db = $this->load->database( 'bdseleccion', TRUE, TRUE );
	
		// Pass reference of database to the CI-instance
		$CI =& get_instance();
		$CI->Bdseleccion_db =& $this->Bdseleccion_db;
	}	
	
	function get() {
		$strQuery ="Select * From ACTIVOS";
		return $this->Bdseleccion_db->query($strQuery)->result();
	}	
	
	function autocompletarSolicitudTraslado($nroCargo)
	{
	#parametro 1:EMPRESA,parametro 2:CARGO, de la tabla SEL_WF_SOLICITUD_TRASLADO
	$strQuery ="select pkg_seleccion.trae_informacion_general(11,12254041) as INFOSOLICITUD from dual";
			return $this->db->query($strQuery)->result();
	}	
	
	function getoracle1()
	{
	#parametro 1:EMPRESA,parametro 2:CARGO, de la tabla SEL_WF_SOLICITUD_TRASLADO
		$p_id_solicitud;
		$pkg  = "BEGIN PKG_SELECCION.INSERTAR_TRASLADO (11477812,11447788,11,21,";
		$pkg  .= "'CCCCCCCCCC','PPP01',360000,1,";
		$pkg  .= "112233,5,112233,1,'01-08-2014',";
		$pkg  .= "11111111,11111111,22222222,44444444,";
		$pkg  .= "55555555,66666666,'MOV','CUI',null,";
		$pkg  .= "'PRUEBA INSERT 1',5,4,2,1,";
		$pkg  .= "7,6,1,2,";
		$pkg  .= "200,";
		$pkg  .= ":p_id_solicitud,";
		$pkg  .= ":p_respon_etapa,";
		$pkg  .= ":p_correo_respon);";
		$pkg  .= "END;";
		$stmt = oci_parse($pkg);
		return $p_id_solicitud;
		//$pkg = "select * from activos";'
		//return $this->db->query($pkg);
	//return $strQuery;
		//return "dfsdfsdf";
	}	
}