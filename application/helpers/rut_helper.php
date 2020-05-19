<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getPuntosRut'))
{
	function getPuntosRut( $rut ){
		$rutTmp = explode( "-", $rut );
		return number_format( $rutTmp[0], 0, "", ".") . '-' . $rutTmp[1];
		/*
		se llama a la funcion de la siguiente forma
		echo getPuntosRut('12345678-9');
		El rut puede ser pasado con o sin guion
		*/
	}
}