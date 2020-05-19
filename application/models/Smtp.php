<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SMTP extends CI_Model
{
    // private $email = null;

    public function __construct()
    {
        parent::__construct();

        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = '172.20.1.173';
        $config['smtp_user'] = '';
        $config['smtp_pass'] = '';
        $config['smtp_port'] = 25;
        $config['mailtype'] = "html";
        $this->load->library('email');

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->load->library('session');


        return $this->get();
    }

    public function get()
    {
        return $this->email;
    }
}
