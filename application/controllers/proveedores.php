<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proveedores extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('proveedores_model');
		$this->load->model('articulos_model');
		$this->load->library('grocery_CRUD');
	}


/**********************************************************************************
 **********************************************************************************
 *
 * 				CRUD PROVEEDORES
 *
 * ********************************************************************************
 **********************************************************************************/


	public function proveedor_abm()
	{
		$crud = new grocery_CRUD();

		$crud->where('proveedor	.id_estado = 1');
		$crud->set_table('proveedor');
		$crud->columns('descripcion', 'descuento');
		$crud->display_as('descripcion','Descripción')
			 ->display_as('descuento','Descuento %')
			 ->display_as('id_estado','Estado');
		$crud->set_subject('proveedor');
		$crud->required_fields('descripcion', 'id_estado');
		$crud->fields('descripcion','descuento', 'descuento2');
		$crud->set_relation('id_estado','estado','estado');

		$_COOKIE['tabla'] ='proveedor';
		$_COOKIE['id'] ='id_proveedor';

		$crud->callback_after_insert(array($this, 'insert_log'));
		$crud->callback_after_update(array($this, 'actualizar_precios'));
		$crud->callback_delete(array($this,'delete_log'));
		$crud->add_action('Stock', '', '','icon-archive', array($this, 'stockArticulo'));

		$this->permisos_model->getPermisos_CRUD('permiso_proveedor', $crud);

		$output = $crud->render();

		$this->crudView($output);
	}

	function stockArticulo($id)
	{
		return site_url('/stock/stockProveedor').'/'.$id;
	}

	public function actualizar_precios($datos, $id)
	{
		$this->articulos_model->updateByProvider($id);
		return true;
	}

	public function searchProveedor()
	{
		$proveedores= $this->proveedores_model->getProveedor($_GET['term']);
		if ($proveedores) {
			foreach ($proveedores as $rowProveedor) {
				$row['value']	= htmlentities(stripslashes($rowProveedor->descripcion));
				$row['id'] = (int) $rowProveedor->id_proveedor;
				$row_set[] = $row;
			}
			echo json_encode($row_set);
		} else {
			return FALSE;
		}
	}
}
