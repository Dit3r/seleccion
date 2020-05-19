<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desloguear extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		session_start();
		session_destroy();
		//jagl para la huapi $url = site_url();
		$url = "http://".$_SERVER['HTTP_HOST']."/intranet/portal";
		header("Location: $url");
	}
}