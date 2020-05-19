<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginacion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
	}
	
	function show($id_city, $offset = 0){
		//her we verify if the we have to handle a serch term
		//or its comming from “show_search” with a seacr term
		if ( $this->session->userdata('var_search')){
			$search_term=$this->session->userdata('var_search');
			$this->session->unset_userdata('var_search');
		}else{
			$search_term ='';
		}
	
		$this->load->model('Paginacion_model');
	
		//Load the pagination library
		$this->load->library('pagination');
	
		//We defini the structure of the links in case we have a search term
		if ($search_term ==''){
			$config['base_url'] = base_url().'/index.php/person/show/'.$id_city.'/';
			$config['uri_segment'] = '4';
		}else{
			$config['base_url'] = base_url().'/index.php/person/show_search/'.$id_city.'/'.$search_term.'/';
			$config['uri_segment'] = '4';
		}
	
		//this function it’s up to you must count the results for the pagination
		//library
		$config['total_rows'] = $this->Paginacion_model->number_of_persons($id_city,$seach_term);
		$config['per_page'] = 25;
		$config['num_links'] =5;
		$config['next_link'] = '>';
		$config['prev_link'] = '<';
		$config['first_link'] = '<<';
		$config['last_link'] = '>>';
	
		$this->pagination->initialize($config);
		$data["page_links"] = $this->pagination->create_links();
	
		//we obtain the list of people using the offset data and the number of records by page, Again this is up to you
		$articulos=$this->m_personas->show_persons_paginated($id_city,$search_term,$offset,$config['per_page']);
		$data['persons']=$persons;
		$data['id_city']=$id_city;
		$this->load->vars($data);
	
		//cargamos nuestra vista
		$this->load->view('paginacion_view');
	}
}