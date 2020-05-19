<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pruebas extends CI_Controller {
	
	public function mail()
	{
		// El mensaje
		$mensaje = "HOla mundo";
	
		// Send
		if (mail('jorge@w7.cl', 'Mi título', $mensaje)) {
			echo 'mail enviado';
		} else {
			echo 'mail no enviado';
		}
	}
	
	public function mailci()
	{
		$this->load->library('email');
	
		$this->email->from('workflow@sb.cl', 'Workflow');
		$this->email->to('jgatica@yahoo.com');
		//$this->email->cc('otro@otro-ejemplo.com');
		//$this->email->bcc('ellos@su-ejemplo.com');
	
		$this->email->subject('Prueba desde codeIgniter');
		$this->email->message('Este es un correo de prueba enviando de codeIgniter.');
	
		
		if ($this->email->send()) {
			echo "Mail enviado correctamente";
		} else {
			echo "Mail No se pudo enviar";
		}
	
	}
	
	public function sesiones()
	{
		$this->load->library( 'nativesession' );
		$this->nativesession->set( 'mivariable', 'esta es una variable de sesion llamada mi variable' );
		echo $_SESSION["mivariable"];
	}
	
	public function oracle1()
	{
		$this->load->model('pruebabd_model');
		$mivariable_oracle1 = $this->pruebabd_model->getoracle1();
		 ;
		 echo "<pre>";
		 var_dump($mivariable_oracle1);
		 echo "</pre>";
		 die();
	}
}