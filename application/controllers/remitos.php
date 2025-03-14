<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remitos extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('articulos_model');
		$this->load->model('clientes_model');
		$this->load->model('proveedores_model');
		$this->load->model('grupos_model');
		$this->load->model('presupuestos_model');
		$this->load->model('remitos_model');
		$this->load->model('remitos_detalle_model');
		$this->load->model('categorias_model');
		$this->load->model('subcategorias_model');
		$this->load->model('config_impresion_model');
		$this->load->model('devoluciones_model');
		$this->load->model('devoluciones_detalle_model');
		$this->load->model('renglon_presupuesto_model');

		$this->load->library('grocery_CRUD');
	}

	/*
	CRUD REMITOS
	*/
	public function crud() {
		 $crud = new grocery_CRUD();

		 $crud->set_table('remito');
		 $crud->order_by('id_remito','desc');
		 $crud->columns('id_remito','fecha', 'monto','id_cliente');
		 $crud->display_as('id_cliente','Descripción')
				->display_as('id_remito','Número')
				->display_as('id_estado','Estado');
		 $crud->set_subject('remiro');
		 $crud->set_relation('id_cliente','cliente','{alias} - {nombre} {apellido}');
		 $crud->set_relation('id_estado','estado','estado');

		 $crud->unset_add();
		 $crud->unset_edit();
		 $crud->unset_read();
		 $crud->unset_delete();

		 $crud->add_action('Detalle', '', '','icon-exit', array($this, 'findRemito'));
		 $output = $crud->render();
		 $this->crudView($output);
	 }

	 /*
 		Buscar el remito
 	*/
	 function findRemito($id) {
		 return site_url('/remitos/view').'/'.$id;
	 }


 /**********************************************************************************
 **********************************************************************************
 *
 * 				Insert de devoluciones
 *
 * ********************************************************************************
 **********************************************************************************/

	function insert_devoluciones($id_remito) {
		$remitos		= $this->remitos_model->getRemito($id_remito);

		foreach ($remitos as $row) {
			$total		= $row->monto;
			$devoluciones	= $this->devoluciones_model->getCliente($row->id_cliente);
		}

		$total_dev = 0;

		foreach ($devoluciones as $row) {
			if ($total >0) {
				$resto_apagar	= $row->monto - $row->a_cuenta;

				if($resto_apagar < $total) {//Si el monto a pagar del presupuesto no supera el del pago
					$pago	= $resto_apagar;
					$total = $total - $resto_apagar;
					$a_cuenta	= $row->monto;
					$estado = ESTADO_PRESUPUESTO::CANCELADA;
				} else {//Si lo supera
					$pago	= $total;
					$a_cuenta	= $row->a_cuenta + $total;
					$total = 0;
					$estado	= ESTADO_PRESUPUESTO::FALTA_PAGO;
				}

				$remito_detalle = array(
					'id_remito'			=> $id_remito,
					'id_devolucion'		=> $row->id_devolucion,
					'monto'				=> -$pago,
					'estado'			=> 1
				);

				$this->remitos_detalle_model->insert($remito_detalle);//Insert detalle remito

				$update_dev = array(
					'a_cuenta'	=> $a_cuenta,
					'id_estado'	=> $estado
				);

				$this->devoluciones_model->update($update_dev, $row->id_devolucion);

				$total_dev =  $total_dev + $pago;
			}

			$update_remito = array(
				'devolucion'	=> $total_dev
			);

			$this->remitos_model->update($update_remito, $id_remito);
		}
	}


 /**********************************************************************************
 **********************************************************************************
 *
 * 				Vista final remito
 *
 * ********************************************************************************
 **********************************************************************************/


	function view($id, $id_cliente = NULL) {
		$db['remitos'] = $this->remitos_model->getRemito($id);
 		$db['remitos_detalle'] = $this->remitos_detalle_model->getRemitos($id);
 		$db['remitos_dev'] = $this->remitos_detalle_model->getRemitos($id, 'dev');
 		$db['impresiones'] = $this->config_impresion_model->getRegistro(1);

 		if($id_cliente === NULL) {
 			$remitos = $db['remitos'];

 			foreach ($remitos as $row) {
 				$id_cliente = $row->id_cliente;
 			}
 		}

 		$datos = array(
 			'id_cliente' => $id_cliente,
 			'tipo' => FORMAS_PAGO::CTA_CTE,
 			'estado' => ESTADO_PRESUPUESTO::FALTA_PAGO,
 		);
 		$db['presupuestos']	= $this->presupuestos_model->getBusqueda($datos, 'AND');

		$this->setView('remitos/detail.php', $db);
	}


 /**********************************************************************************
 **********************************************************************************
 *
 * 				Generar las devoluciones
 *
 * ********************************************************************************
 **********************************************************************************/

	function anular($id) {
		$condicion = array(
			'id_presupuesto' => $id
		);

		$db['texto'] = getTexto();
		$db['presupuestos']	= $this->presupuestos_model->getRegistro($id);
		$db['detalle_presupuesto'] = $this->renglon_presupuesto_model->getDetalle($id);
		$db['impresiones'] = $this->config_impresion_model->getRegistro(2);
		$db['devoluciones']	= $this->devoluciones_model->getBusqueda($condicion);

		$this->setView('presupuestos/anular_presupuestos.php', $db);
	}


	/**********************************************************************************
	 **********************************************************************************
	 *
	 * 				Remito Form
	 *
	 * ********************************************************************************
	 **********************************************************************************/


		public function insert($id_cliente = null) {
			$db['texto']		= getTexto();

			if($id_cliente != null) {
				
				$datos = array(
					'id_cliente'=> $id_cliente,
					'tipo'		=> FORMAS_PAGO::CTA_CTE,
					'estado'	=> ESTADO_PRESUPUESTO::FALTA_PAGO,
				);
				$db['id_cliente']		= $id_cliente;
				$db['presupuestos']	= $this->presupuestos_model->getBusqueda($datos, 'AND');
				$db['devoluciones']	= $this->devoluciones_model->getCliente($id_cliente);
				$db['clientes']	= $this->clientes_model->getRegistro($id_cliente);
		}

			$this->setView('remitos/insert.php', $db);
		}

		/*
  		Carga el detalle de lo que paga el cliente
  	*/
		public function insertProcess() {
 			$db['texto']		= getTexto();
 			$db['clientes']		= $this->clientes_model->getRegistros();

	 		if ($this->input->post('buscar')==1) {
		 		$id_cliente = 0;

		 		if ($this->input->post('cliente_alias') != 0) {
		 			$id_cliente = $this->input->post('cliente_alias');
		 		} else if($this->input->post('cliente_apellido') != 0) {
		 			$id_cliente = $this->input->post('cliente_apellido');
		 		}

		 		if ($id_cliente != 0) {
		 			$datos = array(
		 				'id_cliente'=> $id_cliente,
		 				'tipo' => FORMAS_PAGO::CTA_CTE,
		 				'estado' => ESTADO_PRESUPUESTO::FALTA_PAGO
		 			);
		 			$db['id_cliente']		= $id_cliente;
		 			$db['presupuestos']		= $this->presupuestos_model->getBusqueda($datos, 'AND');
		 			$db['devoluciones']		= $this->devoluciones_model->getCliente($id_cliente);
		 		}
	 		}

 			$this->setView('remitos/insert.php', $db);
 		}



	/**********************************************************************************
	 **********************************************************************************
	 *
	 * 				Remito insert
	 *
	 * ********************************************************************************
	 **********************************************************************************/


		public function insertFinal() {
			$id_cliente 	= $this->input->post('cliente');
			$total				= $this->input->post('total');
			$total_hidden	= $this->input->post('total_hidden');
			$total_dev		= $this->input->post('total_dev');

			$datos = array(
				'id_cliente'=> $id_cliente,
				'tipo'			=> FORMAS_PAGO::CTA_CTE,
				'estado'		=> ESTADO_PRESUPUESTO::FALTA_PAGO
			);

			$db['presupuestos']	= $this->presupuestos_model->getBusqueda($datos, 'AND');
			$presupuestos 		= $db['presupuestos'];

			if ($total_hidden == $total) {//No se realizo el pago automatico
				$remito = array(
					"fecha"				=> date('Y-m-d H:i:s'),
					"monto"				=> $total,
					"id_cliente"	=> $id_cliente,
					"id_estado"		=> 1
	      );

				$id_remito = $this->remitos_model->insert($remito);

				foreach ($presupuestos as $row) {
					if($this->input->post($row->id_presupuesto) != 0) {
						$remito_detalle = array(
							'id_remito'			=> $id_remito,
							'id_presupuesto'=> $row->id_presupuesto,
							'monto'					=> $this->input->post($row->id_presupuesto),
							'a_cuenta'			=> $row->a_cuenta,
							'id_estado_presupuesto'	=> ESTADO_PRESUPUESTO::FALTA_PAGO,
							'estado'				=> 1
						);

						$id_detalle = $this->remitos_detalle_model->insert($remito_detalle);//Insert detalle remito

						//Update del remito
						$a_cuenta	= $row->a_cuenta + $this->input->post($row->id_presupuesto);

						if ($a_cuenta == $row->monto) {//se completo el pago del presupuesto
							$update_pres = array(
									'a_cuenta'=> $a_cuenta,
									'estado'	=> ESTADO_PRESUPUESTO::CANCELADA
							);
							$this->presupuestos_model->update($update_pres, $row->id_presupuesto);

							$update_rem = array(
									'id_estado_presupuesto'	=> ESTADO_PRESUPUESTO::CANCELADA
							);
							$this->remitos_detalle_model->update($update_rem, $id_detalle);
						} else if($row->monto > $a_cuenta) {//el monto sigue siendo mayor al pago
							$update_pres = array(
								'a_cuenta'	=> $a_cuenta
							);

							$this->presupuestos_model->update($update_pres, $row->id_presupuesto);
						}
					}
				}
			} else {
				$remito = array(
					"fecha"			=> date('Y-m-d H:i:s'),
					"monto"			=> $total,
					"id_cliente"	=> $id_cliente,
					"id_estado"		=> 1
				);
				$id_remito = $this->remitos_model->insert($remito);

				foreach ($presupuestos as $row) {
					if ($total > 0) {//Verificamos que aun hay monto para pagar
						$resto_apagar	= $row->monto - $row->a_cuenta;

						if ($resto_apagar < $total) {//Si el monto a pagar del presupuesto no supera el del pago
							$pago		= $resto_apagar;
							$total		= $total - $resto_apagar;
							$a_cuenta	= $row->monto;
							$estado		= ESTADO_PRESUPUESTO::CANCELADA;
						} else { //Si lo supera
							$pago		= $total;
							$a_cuenta	= $row->a_cuenta + $total;
							$total		= 0;
							$estado		= ESTADO_PRESUPUESTO::FALTA_PAGO;
						}

						$remito_detalle = array(
							'id_remito'			=> $id_remito,
							'id_presupuesto'=> $row->id_presupuesto,
							'monto'					=> $pago,
							'a_cuenta'			=> $row->a_cuenta,
							'id_estado_presupuesto'	=> $estado,
							'estado'				=> 1
						);

						$this->remitos_detalle_model->insert($remito_detalle);//Insert detalle remito

						$update_pres = array(
							'a_cuenta'	=> $a_cuenta,
							'estado'	=> $estado
						);

						$this->presupuestos_model->update($update_pres, $row->id_presupuesto);

					}
				}
			}

			// Hacer los insert de devoluciones
			if ($total_dev > 0) {
				$this->insert_devoluciones($id_remito);
			}

			redirect('/remitos/view/'.$id_remito.'/'.$id_cliente,'refresh');//Redireccionamos para evitar problemas con la recarga de la pagina f5
		}
}
