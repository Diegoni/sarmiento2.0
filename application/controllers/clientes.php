<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('clientes_model');
		$this->load->model('presupuestos_model');
		$this->load->model('devoluciones_model');
		$this->load->model('remitos_model');
		$this->load->model('reglas_ventas_model');
		

		$this->load->library('grocery_CRUD');
	}

	/*
	* CRUD CLIENTE
	* @return view
	*/
	public function cliente_abm() {
		$crud = new grocery_CRUD();

		$crud->where('cliente.id_estado = 1');
		$crud->set_table('cliente');
		$crud->columns('alias', 'telefono', 'cuil', 'id_tipo', 'id_condicion_iva');
		$crud->callback_column('alias', array($this,'_callback_unir_nombre'));
		$crud->callback_column('cuil', array($this,'_callback_cuil'));
		$crud->display_as('direccion','Dirección')
			 ->display_as('id_condicion_iva','Condición Iva')
			 ->display_as('id_tipo','Tipo')
			 ->display_as('id_estado','Estado');
		$crud->set_subject('cliente');
		$crud->required_fields(
					'nombre',
					'apellido',
					'alias',
					'cuil',
					'id_condicion_iva',
					'id_tipo'
		);

		$crud->set_relation('id_estado','estado','estado');
		$crud->set_relation('id_condicion_iva','condicion_iva','descripcion');
		$crud->set_relation('id_tipo','tipo_cliente','tipo');

		$crud->fields(
					'nombre',
					'apellido',
					'alias',
					'cuil',
					'direccion',
					'telefono',
					'celular',
					'mail',
					'id_condicion_iva',
					'id_tipo',
					'comentario'
		);

		$crud->add_action('Detalle', '', '','icon-exit', array($this, 'detalle'));

		$_COOKIE['tabla']='cliente';
		$_COOKIE['id']='id_cliente';

		$crud->callback_before_insert(array($this, 'control_insert_cliente'));
		$crud->callback_after_insert(array($this, 'insert_log'));
		$crud->callback_after_update(array($this, 'update_log'));
		$crud->callback_delete(array($this,'delete_log'));

		$this->permisos_model->getPermisos_CRUD('permiso_cliente', $crud);

		$output = $crud->render();

		$this->crudView($output);
	}

	public function _callback_unir_nombre($value, $row) {

		$alias = $row->nombre. " ".$row->apellido;

		if($row->alias != ''){
			if(	$row->alias == $row->nombre." ".$row->apellido){
				$alias = $row->alias;
			} else if($row->alias == $row->nombre && $row->alias != $row->apellido){
				$alias = $row->nombre. " ".$row->apellido;
			} else if($row->alias != $row->nombre && $row->alias == $row->apellido){
				$alias = $row->nombre. " ".$row->apellido;
			} else if($row->alias == $row->nombre && $row->alias == $row->apellido){
				$alias = $row->alias;
			} else {
				$alias = $row->alias.', '.$alias;
			}
		} 
		
		return $alias;
	}

	public function _callback_cuil($value, $row) {

		return ($row->cuil != '') ? $row->cuil : '-';
	}

	/*
	* Actualizar los precios por lote
	* @param id
	* @return view
	*/
	function detalle($id) {
		return site_url('/clientes/resumen').'/'.$id;
	}

	function control_insert_cliente($post_array) {
		$cuil = $post_array['cuil'];

		$query = "SELECT * FROM cliente WHERE 'cuil' = $cuil";
		$query = $this->db->query($query);

		if ($query->num_rows() > 0) {
			return FALSE;
		} else {
			return $post_array;
		}
	}


/**********************************************************************************
 **********************************************************************************
 *
 * 				CRUD CONDICION IVA
 *
 * ********************************************************************************
 **********************************************************************************/


	public function condicion_iva_abm(){
		$crud = new grocery_CRUD();

		$crud->set_table('condicion_iva');
		$crud->columns('descripcion');
		$crud->display_as('descripcion','Descripción');
		$crud->unset_delete();
		$crud->unset_add();
		$crud->set_subject('Condición Iva');

		$_COOKIE['tabla']='condicion_iva_abm';
		$_COOKIE['id']='id_condicion_iva_abm';

		$crud->callback_after_insert(array($this, 'insert_log'));
		$crud->callback_after_update(array($this, 'update_log'));
		$crud->callback_delete(array($this,'delete_log'));

		$this->permisos_model->getPermisos_CRUD('permiso_cliente', $crud);

		$output = $crud->render();

		$this->crudView($output);
	}


/**********************************************************************************
 **********************************************************************************
 *
 * 				CRUD TIPO
 *
 * ********************************************************************************
 **********************************************************************************/


	public function tipo_abm() {
		$crud = new grocery_CRUD();

		$crud->set_table('tipo_cliente');
		$crud->columns('tipo', 'permite_cta_cte', 'monto_max_presupuesto');
		$crud->callback_column('permite_cta_cte', array($this,'_completeCtaCte'));

		$crud->unset_delete();
		$crud->unset_add();
		$crud->set_subject('Tipo Cliente');

		$output = $crud->render();

		$this->crudView($output);
	}

	public function _completeCtaCte($value, $row) {
		return '<i class="fa fa-'.(($row->pertmite_cta_cte == 1) ? 'check-square-o' : 'square-o ').'" aria-hidden="true"></i>';
	}

	public function searchcliente() {
		$clientes= $this->clientes_model->searchcliente($_GET['term']);
		if ($clientes) {
			foreach ($clientes as $rowCliente) {
				$value = $rowCliente->apellido.', ';
				$value .= $rowCliente->nombre.' - ';
				$value .= $rowCliente->alias;

				$row['value']	= $value;
				$row['id'] = (int) $rowCliente->id_cliente;
				$row_set[] = $row;
			}
			echo json_encode($row_set);
		} else {
			return FALSE;
		}
	}



	function resumen($id_cliente) {
		$datos = array(
			'id_cliente'=> $id_cliente,
		);

		$db['fechaDesde']	= ($this->input->post('fechaDesde') != null) ? $this->input->post('fechaDesde') : "2015-01-01";
		$db['clientes']		= $this->clientes_model->getRegistro($id_cliente);
		$db['presupuestos']	= $this->presupuestos_model->getCliente($id_cliente, $db['fechaDesde']);
		$db['remitos']		= $this->remitos_model->getCliente($id_cliente, $db['fechaDesde']);
		$db['devoluciones']	= $this->devoluciones_model->getCliente($id_cliente, 'all', $db['fechaDesde']);// Arreglar esta chamchada

		$this->setView('clientes/resumen.php', $db);
	}


	function estado_cuentas() {

		$clientes = $this->clientes_model->getClientes();
		$presupuestos = $this->presupuestos_model->getPresupuestos();
		$db['estado_cuentas'] = [];

		foreach($clientes as $cliente){
			$presupuestos_cliente = [];
			foreach($presupuestos as $presupuesto){
				if($cliente->id_cliente == $presupuesto->id_cliente){
					$presupuestos_cliente[] = $presupuesto;
				}
			
			}
			
			$db['estado_cuentas'][$cliente->id_cliente]['alias'] = $this->_callback_unir_nombre('', $cliente);
			$db['estado_cuentas'][$cliente->id_cliente]['deuda'] = $this->calcularMontoDeuda($presupuestos_cliente);
		}


		$this->setView('clientes/estado_cuenta.php', $db);
	}

	function calcularMontoDeuda($presupuestos){
		$deuda = 0;

		if($presupuestos){
			$total_p_contado = 0;
			$total_p_tarjeta = 0;
			$total_p_ctacte = 0;
			$total_p_cuenta = 0;
			
			foreach ($presupuestos as $row){
				if($row->tipo == 1) {
					$row->a_cuenta = $row->monto;
					$total_p_contado = $total_p_contado + $row->monto;
				} else if($row->tipo == 2) {
					$total_p_ctacte = $total_p_ctacte + $row->monto;
					$total_p_cuenta = $total_p_cuenta + $row->a_cuenta;
				} else {
					$total_p_tarjeta = $total_p_tarjeta + $row->monto;
				}
			}

			$total_vendido = $total_p_contado + $total_p_tarjeta + $total_p_ctacte;
			$total_cobrado = $total_p_contado + $total_p_tarjeta + $total_p_cuenta;
			$deuda = $total_vendido - $total_cobrado;
		} 

		return $deuda;
	}
}
