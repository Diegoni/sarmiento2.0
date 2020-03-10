<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendedores extends My_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->model('anulaciones_model');
		$this->load->model('articulos_model');
		$this->load->model('categorias_model');
		$this->load->model('clientes_model');
		$this->load->model('config_impresion_model');
		$this->load->model('devoluciones_model');
		$this->load->model('devoluciones_detalle_model');
		$this->load->model('facturas_model');
		$this->load->model('grupos_model');
		$this->load->model('intereses_model');
		$this->load->model('proveedores_model');
		$this->load->model('subcategorias_model');
		$this->load->model('presupuestos_model');
		$this->load->model('remitos_model');
		$this->load->model('remitos_detalle_model');
		$this->load->model('renglon_presupuesto_model');
		$this->load->model('vendedores_model');

		$this->load->library('grocery_CRUD');
	}

 /*
* Crud
 */
	public function crud() {
      $crud = new grocery_CRUD();

      $crud->set_table('vendedor');
      $crud->columns('id_vendedor','vendedor', 'id_estado');
      $crud->display_as('id_vendedor','ID')
           ->display_as('vendedor','Vendedor')
           ->display_as('id_estado','Estado');
      $crud->set_subject('vendedor');
      $crud->fields('vendedor');
      $crud->required_fields('vendedor','vendedor');
      $crud->set_relation('id_estado','estado','estado');
      $crud->add_action('Estadistica', '', '','icon-awstats', array($this, 'detalle_vendedor'));
      $output = $crud->render();

      $this->crudView($output);
    }

    function detalle_vendedor($id){
        return site_url('/estadisticas/mensual').'/'.$id;
    }
}
