<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio_m extends CI_Model {

	protected $table;
	protected $id;
	
	/*
		Constructor del modelo, aqui establecemos
		que tabla utilizamos y cual es su llave primaria.
	*/
	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('default', true);
		$this->table = 'ASIMILA_TMP';
	}
	
	function get() {
		$resultado = $this->db->get($this->table);
		return $resultado->result();
	}	
}