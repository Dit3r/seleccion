<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabindex extends CI_Controller {

    private $_controlador;
    private $_metodo;
    private $_segmento;
      
    public function __construct(){
     
        
        echo base_url();
        exit;
     parent::__construct();
     $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
     $url = explode('/', $url);
     $url = array_filter($url);
     
     $this->_controlador = strtolower(array_shift($url));
     $this->_metodo      = strtolower(array_shift($url));
     $this->_argumentos  = $url;
     
     $rutaControlador    =  base_url($this->_controlador);  
     $this->load->model('bd_colaborador');
     $this->load->model('bd_oracle');
     $this->load->library('funciones');
    }


    public function _remap($method, $params = array()){
       if (method_exists(__CLASS__, $method)) {
               $this->$method($params);
       } else {
               $this->test_default();
       }
    }
           
    public function test_default(){
          redirect(base_url());
    }
    
    public function index(){
                      
        if($this->_metodo==""){
           redirect(base_url());
        }        
    } 
    
    public function consulta(){
         $this->load->view('temp/indexconsultas');
    }

 }    