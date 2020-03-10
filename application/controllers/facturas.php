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


	public function crud() {
		$crud = new grocery_CRUD();
		$crud->set_table('factura');
		$crud->order_by('id_factura','desc');
		$crud->columns('letra', 'cbte_desde', 'id_presupuesto', 'imp_total', 'cbte_fch', 'cae');
		$crud->display_as('cbte_desde','Factura')
			 ->display_as('id_presupuesto','Presupuesto')
			 ->display_as('imp_total','Importe')
			 ->display_as('cbte_fch','Fecha');
		$crud->set_subject('factura');

		$_COOKIE['tabla']='factura';
		$_COOKIE['id']='id_factura';

		$crud->unset_read();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();

		$crud->callback_column('letra', array($this,'_completarLetra'));

		$crud->add_action('Detalle', '', '','icon-exit', array($this, 'buscar_facturas'));
		$output = $crud->render();
		$this->crudView($output);
	}

	function _completarLetra($value, $row) {
		return ($row->cbte_tipo == 1) ? 'A' : 'B';
	}

	function buscar_facturas($id) {
		$factura = $this->facturas_model->getRegistro($id);
		return site_url('/presupuestos/update').'/'.$factura[0]->id_presupuesto;
	}
}
