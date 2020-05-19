<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members_model extends CI_Model {

	protected $table;
	protected $id;
	
	/*
		Constructor del modelo, aqui establecemos
		que tabla utilizamos y cual es su llave primaria.
	*/
	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('local', true);
		$this->table = 'net_members';
		$this->id = 'id';
	}

	/*
		Con esta funcion comprobamos que exista el
		usuario en la base de datos, si es asi retornamos
		el contenido del registro, de lo contrario se 
		retorna FALSE.
	*/
	function get($username='', $password='') {

    $salt = substr($password, 0, 2);
    $password = crypt($password, $salt);
    
		return $this->db->get_where(
			$this->table, array(
				'login' => $username,
				'password' => $password
			)
		)->row();
	}

}