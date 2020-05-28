<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function isCuitValid($value) {
	if (!is_numeric($value)) {
		return false;
	}

	if (strlen($value) != 11) {
		return false;
	}

	$prefijo = (int) substr($value, 0,2);
	if (!in_array($prefijo, array(20,23,24,27))) {
		return false;
	}

	$coeficiente = array(5,4,3,2,7,6,5,4,3,2);
	$sum=0;
	for ($i=0; $i < 10 ; $i++) {
		$sum=$sum+($value[$i]*$coeficiente[$i]);
	}

	$resto=$sum % 11;
	if ($value[10] != 11-$resto) {
		return false;
	}

	return true;
}

function getTexto(){
		$CI =& get_instance();
		include_once('texto.php');

		return $texto;
}

function setMensaje($mensaje, $tipo=NULL){
		if ($tipo==NULL) {
			$tipo='info';
		}

		$return =	"<div class='alert alert-$tipo alert-dismissible' role='alert'>
				 		<button type='button' class='close' data-dismiss='alert'>
					 		<span aria-hidden='true'>&times;</span><span class='sr-only'>
					 			Cerrar
					 		</span>
					 	</button>
				  		$mensaje
					</div>";

		return $return;
	}

function getRealIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}

	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	if (!empty($_SERVER['REMOTE_ADDR'])){
		$ip=$_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}
