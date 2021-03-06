<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devoluciones extends My_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('articulos_model');
		$this->load->model('devoluciones_model');
		$this->load->model('devoluciones_detalle_model');
		$this->load->model('clientes_model');
		$this->load->model('proveedores_model');
		$this->load->model('grupos_model');
		$this->load->model('categorias_model');
		$this->load->model('subcategorias_model');
		$this->load->model('presupuestos_model');
		$this->load->model('remitos_model');
		$this->load->model('remitos_detalle_model');
		$this->load->model('renglon_presupuesto_model');
		$this->load->model('config_impresion_model');
		$this->load->model('stock_model');

		$this->load->library('grocery_CRUD');
	}

 /**********************************************************************************
 **********************************************************************************
 *
 * 				Generar las devoluciones
 *
 * ********************************************************************************
 **********************************************************************************/

	function crud() {
		$crud = new grocery_CRUD();

		$crud->set_table('devolucion');
		$crud->order_by('id_devolucion','desc');
		$crud->columns('id_devolucion', 'fecha', 'monto', 'id_presupuesto', 'nota','id_estado');
		$crud->display_as('id_devolucion','ID')
			 ->display_as('id_presupuesto','Presupuesto')
			 ->display_as('id_estado','Estado');
		$crud->set_subject('devolución');
		$crud->set_relation('id_estado','estado_devolucion','estado');

		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		$output = $crud->render();

		$this->crudView($output);
	}

 /**********************************************************************************
 **********************************************************************************
 *
 * 				Generar las devoluciones
 *
 * ********************************************************************************
 **********************************************************************************/

	function generar($id) {
			$db['texto'] = getTexto();
			$db['presupuestos']	= $this->presupuestos_model->getRegistro($id);

			$condicion = array(
				'id_presupuesto' => $id,
				'estado' => 1
			);
			$db['detalle_presupuesto'] = $this->renglon_presupuesto_model->getDetalle_where($condicion, 'AND');

			$this->setView('devoluciones/generar.php', $db);
	}


 /**********************************************************************************
 **********************************************************************************
 *
 * 				Insert las devoluciones
 *
 * ********************************************************************************
 **********************************************************************************/

	function insert() {
		$id_presupuesto = $this->input->post('presupuesto');

		$detalle_presupuesto = $this->renglon_presupuesto_model->getDetalle($id_presupuesto);

		$session_data = $this->session->userdata('logged_in');

		$registro = array(
			'id_presupuesto'=> $id_presupuesto,
			'fecha'					=> date('Y/m/d H:i:s'),
			'a_cuenta'			=> 0,
			'nota'					=> $this->input->post('nota'),
			'id_usuario'		=> $session_data['id_usuario'],
			'id_estado'			=> 1
		);

		$id_devolucion = $this->devoluciones_model->insert($registro);

		$monto_devolucion = 0;
		foreach ($detalle_presupuesto as $row) {
			if($this->input->post($row->id_renglon) > 0) {
				$precio = $row->precio / $row->cantidad;
				$monto = $this->input->post($row->id_renglon) * $precio;

				$registro = [
					'id_devolucion'	=> $id_devolucion,
					'id_articulo'		=> $row->id_articulo,
					'cantidad'			=> $this->input->post($row->id_renglon),
					'monto'					=> $monto
				];

				$monto_devolucion = $monto_devolucion + $monto;
				$this->devoluciones_detalle_model->insert($registro);

				$registro = [
					'estado'	=> 2
				];
				$this->renglon_presupuesto_model->update($registro, $row->id_renglon);

				// STOCK
				$registro = [
					'id_comprobante' 	=> COMPROBANTES::DEVOLUCION,
					'nro_comprobante'	=> $id_devolucion,
					'id_articulo'			=> $row->id_articulo,
					'cantidad'				=> $this->input->post($row->id_renglon),
				];
				$this->stock_model->updateStock($registro);
			}
		}
		$registro = array(
			'monto'		=> $monto_devolucion
		);

		$this->devoluciones_model->update($registro, $id_devolucion);

		redirect('/devoluciones/crud/','refresh');
	}
}
