<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reglas extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('reglas_ventas_model');
		
		$this->load->library('grocery_CRUD');
	}

	public function reglas_abm() {
		$crud = new grocery_CRUD();

		$crud->set_table('regla_venta');
		$crud->columns(
			'id_tipo_cliente', 
			'tipo_regla', 
			'id_condicion_venta', 
			'monto', 
			'fecha_inicio',  
			'fecha_fin', 
			'fecha_baja_regla'
		);		
		$crud->display_as('id_tipo_cliente','Tipo Cliente');
		$crud->display_as('id_condicion_venta','Condicion Venta');

		$_COOKIE['tabla']='regla_venta';
		$_COOKIE['id']='id_regla_venta';

		$crud->callback_after_insert(array($this, 'insert_log'));
		$crud->callback_after_update(array($this, 'update_log'));
		$crud->callback_delete(array($this,'delete_log'));

		$crud->set_relation('id_tipo_cliente','tipo_cliente','tipo');
		$crud->set_relation('id_condicion_venta','tipo','tipo');

		$crud->add_action('Borrar Regla', '', '','icon-eraser', array($this, 'borrar_regla'));

		$crud->fields(
			'id_tipo_cliente',
			'tipo_regla',
			'id_condicion_venta', 
			'monto',
			'fecha_inicio',
			'fecha_fin',
			'comentario'
		);	

		$crud->required_fields(
			'id_tipo_cliente',
			'tipo_regla',
			'monto',
			'fecha_inicio'
		);
		
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->set_subject('Reglas Ventas');

		$output = $crud->render();

		$this->crudView($output);
	}


	function borrar_regla_db($id){
		$this->reglas_ventas_model->setBajaRegla($id);
		redirect('/reglas/reglas_abm', 'refresh');
	}

	function borrar_regla($id) {
		return site_url('/reglas/borrar_regla_db').'/'.$id;
	}

	public function getReglas($id_tipo_cliente){
		$reglas = $this->reglas_ventas_model->getReglas($id_tipo_cliente);
		echo json_encode($reglas);
	}
}
