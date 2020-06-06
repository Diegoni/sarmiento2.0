<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facturas extends My_Controller {

	const ESTADO_CAE = 4;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('facturas_model');
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
	}
	function crud(){
		$fecha_actual = date("d-m-Y");
		$db['desde'] = empty($this->input->post('desde')) ? date("d-m-Y",strtotime($fecha_actual."- 7 days")) : $this->input->post('desde');
		$db['hasta'] = empty($this->input->post('hasta')) ? date("d-m-Y",strtotime($fecha_actual)) : $this->input->post('hasta');
		$db['numero_factura'] = $this->input->post('numero_factura');
		$db['cliente'] = $this->input->post('cliente');
		$db['facturas'] = $this->facturas_model->getFacturaDetalle($db['desde'], $db['hasta'], $db['numero_factura'], $db['cliente']);
		$this->setView('facturas/detalle', $db);
	}
}
