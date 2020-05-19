<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prueba extends CI_Controller{
    public function __construct(){

        parent::__construct();
        $this->load->model('Prueba_m');

    }


    public function index(){

        $rrr = $this->Prueba_m->getPersonas();
        var_dump($rrr);
        exit();
    }
}