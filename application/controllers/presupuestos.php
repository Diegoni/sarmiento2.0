<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuestos extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('anulaciones_model');
		$this->load->model('articulos_model');
		$this->load->model('categorias_model');
		$this->load->model('clientes_model');
		$this->load->model('config_impresion_model');
		$this->load->model('devoluciones_model');
		$this->load->model('devoluciones_detalle_model');
		$this->load->model('empresas_model');
		$this->load->model('facturas_model');
		$this->load->model('intereses_model');
		$this->load->model('grupos_model');
		$this->load->model('proveedores_model');
		$this->load->model('presupuestos_model');
		$this->load->model('remitos_model');
		$this->load->model('remitos_detalle_model');
		$this->load->model('subcategorias_model');
		$this->load->model('renglon_presupuesto_model');
		$this->load->model('vendedores_model');

		$this->load->library('grocery_CRUD');
	}



/**********************************************************************************
 **********************************************************************************
 *
 * 				Presupuesto de Salida
 *
 * ********************************************************************************
 **********************************************************************************/

	public function insert() {
		if($this->session->userdata('logged_in')){
			$this->load->view('head.php');
			$this->load->view('menu.php');
			$this->load->view('presupuestos/presupuestos_salida.php');
			$this->load->view('footer.php');
		}else{
			redirect('/','refresh');
		}
	}


/**********************************************************************************
 **********************************************************************************
 *
 * 				Presupuesto de Salida
 *
 * ********************************************************************************
 **********************************************************************************/

	public function search_articulo($id) {
		$query = $this->db->query("
				SELECT descripcion as value,id_articulo,precio_venta_iva FROM articulo WHERE descripcion LIKE '%".$id."%' or cod_proveedor LIKE '%".$id."%' limit 20
			");

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$row['value']	= htmlentities(stripslashes($row['value']));
				$row['id']		= (int)$row['id_articulo'];
				$row['precio']	= (float)$row['precio_venta_iva'];
				$row_set[]		= $row;//build an array
			}
			echo json_encode($row_set);
		} else {
			return FALSE;
		}
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
			if($total >0) {
				$resto_apagar	= $row->monto - $row->a_cuenta;

				if($resto_apagar < $total) {//Si el monto a pagar del presupuesto no supera el del pago
					$pago		= $resto_apagar;
					$total		= $total - $resto_apagar;
					$a_cuenta	= $row->monto;
					$estado		= ESTADO_PRESUPUESTO::CANCELADA;
				} else {//Si lo supera
					$pago		= $total;
					$a_cuenta	= $row->a_cuenta + $total;
					$total		= 0;
					$estado		= ESTADO_PRESUPUESTO::FALTA_PAGO;
				}

				$remito_detalle = array(
					'id_remito'		=> $id_remito,
					'id_devolucion'=> $row->id_devolucion,
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
 * 				Generar las devoluciones
 *
 * ********************************************************************************
 **********************************************************************************/

	function anular($id = NULL) {
		if($this->input->post('nota')) {
			$registro = array(
				'id_presupuesto'	=> $this->input->post('id_presupuesto'),
				'fecha'				=> date('Y-m-d H:i:s'),
				'monto'				=> $this->input->post('monto'),
				'nota'				=> $this->input->post('nota'),
			);
			$this->anulaciones_model->insert($registro);

			$presupuesto = array(
				'estado' => ESTADO_PRESUPUESTO::ANULADA
			);
			$this->presupuestos_model->update($presupuesto, $registro['id_presupuesto']);
			redirect('presupuestos/crud/success/','refresh');
		}

		$condicion = array(
			'id_presupuesto' => $id
		);

		$db['texto']				= getTexto();
		$db['presupuestos']			= $this->presupuestos_model->getRegistro($id);
		$db['detalle_presupuesto']	= $this->renglon_presupuesto_model->getDetalle($id);
		$db['impresiones']			= $this->config_impresion_model->getRegistro(2);
		$db['devoluciones']			= $this->devoluciones_model->getBusqueda($condicion);


		$this->setView('presupuestos/anular_presupuestos.php', $db);
	}

	/**
	 * Web services con la afip para la obtencion del Cae
	 *
	 * @param int $id_presupuesto genera el pdf para imprimir con los dato
	 * @return pdf genera el pdf final.
	 */
	public function setPDF($id_presupuesto){
		$detalle_presupuesto	= $this->renglon_presupuesto_model->getDetalle($id_presupuesto);
		$presupuesto = $this->presupuestos_model->getRegistro($id_presupuesto);
		$presupuesto = $presupuesto[0];
		$rengloPresupuesto = $this->renglon_presupuesto_model->getDetalle($id_presupuesto);

		if($presupuesto->facturado == 1){
			$factura = $this->facturas_model->getBusqueda(['id_presupuesto' => $id_presupuesto]);
			$factura = $factura[0];
			$vendedores = false;
		} else {
			$factura = false;
			$vendedores = $this->vendedores_model->getRegistros();
		}
		$cliente = $this->clientes_model->getCliente($presupuesto->id_cliente);
		$cliente = $cliente[0];

		$empresa = $this->empresas_model->getRegistros(1);
		$empresa = $empresa[0];

		$fpdf = $this->load->library('/fpdf/fpdf');
		$caePdf = $this->load->library('/fpdf/CAEPdf');
		$pdf = new CAEPdf();
		$pdf->setValues($presupuesto, $rengloPresupuesto, $cliente, $empresa, $factura, $vendedores);
		$pdf->AddPage();
		$pdf->detail();
		$pdf->Output();
	}



	/**********************************************************************************
	**********************************************************************************
	*
	* 				CRUD Presupuestos
	*
	* ********************************************************************************
	**********************************************************************************/


	 public function crud() {
		 $query = $this->db->query("SELECT dias_pago FROM config WHERE id_config = 1 ");

		 if($query->num_rows() > 0) {
			 foreach ($query->result() as $fila) {
				 $this->diasPago = $fila->dias_pago;
			 }
		 }

		 $crud = new grocery_CRUD();

		 $crud->set_table('presupuesto');

		 $crud->order_by('id_presupuesto','desc');

		 $crud->columns('id_presupuesto', 'fecha', 'monto', 'descuento','id_cliente', 'tipo', 'id_estado', 'id_vendedor');

		 $crud->display_as('id_cliente','Cliente')
				->display_as('id_presupuesto','Número')
				->display_as('id_estado','Estado')
				->display_as('id_vendedor','Vendedor');

		 $crud->set_subject('remiro');

		 $crud->set_relation('id_cliente','cliente','{alias} - {nombre} {apellido}');
		 $crud->set_relation('tipo','tipo','tipo');
		 $crud->set_relation('id_vendedor','vendedor','vendedor');

		 $_COOKIE['tabla']='remito';
		 $_COOKIE['id']='id_remito';

		 $crud->unset_read();
		 $crud->unset_add();
		 $crud->unset_edit();
		 $crud->unset_delete();

		 $crud->add_action('Detalle', '', '','icon-exit', array($this, 'buscar_presupuestos'));
		 $crud->callback_column('fecha',array($this,'_calcularatraso'));
		 $crud->callback_column('id_estado',array($this,'facturaIcon'));

		 $output = $crud->render();

		 $this->crudView($output);
	 }

	 function facturaIcon($value, $row) {
		 $data['1'] = '<label class="label label-warning">Falta de pago</label>';
		 $data['2'] = '<label class="label label-success">Falta de pago</label>';
		 $data['3'] = '<label class="label label-danger">Falta de pago</label>';
		 $data['4'] = '<label class="label label-primary">CAE</label>';

		 $estado =  $data[$row->estado];
		 $estado .= ($row->facturado == 1) ? '<i class="fa fa-file-text-o" aria-hidden="true" title="Facturado"></i>' : '';

		 return  $estado;
	 }

	 function _calcularatraso($value, $row) {
		 $fecha = date('Y-m-d', strtotime($row->fecha));
		 $nuevafecha = strtotime ( '+'.$this->diasPago.' day' , strtotime ( $fecha ) ) ;
		 $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

		 if($nuevafecha < date('Y-m-d') && $row->estado == 1) {
			 $datetime1 = date_create($fecha);
			 $datetime2 = date_create(date('Y-m-d'));
			 $interval = date_diff($datetime1, $datetime2);

			 return '<label class="label label-danger">'.date('d-m-Y', strtotime($row->fecha)).'</label> <span class="badge">'.$interval->format('%R%a días').'</span>';
		 } else {
			 return date('d-m-Y', strtotime($row->fecha));
		 }
	 }


	 function buscar_presupuestos($id) {
		 return site_url('/presupuestos/update').'/'.$id;
	 }


	 /**********************************************************************************
	 **********************************************************************************
	 *
	 * 				Muestra el detalle del presupuesto
	 *
	 * ********************************************************************************
	 **********************************************************************************/


		function update($id, $llamada = NULL) {
			$_presupuesto = $this->presupuestos_model->getRegistro($id);
			if($_presupuesto){
				if($this->input->post('interes_tipo')){

					foreach ($_presupuesto as $_row) {
						$presupuesto_monto = $_row->monto;
					}

					if($this->input->post('interes_tipo') == 'porcentaje'){
						$interes_monto = $presupuesto_monto * $this->input->post('interse_monto') / 100 ;
					}else{
						$interes_monto = $this->input->post('interse_monto');
					}

					if($this->input->post('descripcion_monto') == ""){
						$descripcion = date('d-m-Y').' Intereses generados por atraso';
					}else{
						$descripcion = date('d-m-Y').' '.$this->input->post('descripcion_monto');
					}

					$interes = array(
						'id_presupuesto'	=> $id,
						'id_tipo'			=> 1,
						'monto'				=> $interes_monto,
						'descripcion'		=> $descripcion,
						'fecha'				=> date('Y-m-d H:i:s'),
						'id_usuario'		=> 1, //agregar nombre de usuario
					);

					$this->intereses_model->insert($interes);

					$_presupuesto = array(
						'monto'				=> $presupuesto_monto + $interes_monto,
					);

					$this->presupuestos_model->update($_presupuesto, $id);
				}

				$condicion = array(
					'id_presupuesto' => $id
				);

				$db['texto']				= getTexto();
				$db['presupuestos']			= $this->presupuestos_model->getRegistro($id);
				$db['detalle_presupuesto']	= $this->renglon_presupuesto_model->getDetalle($id);
				$db['interes_presupuesto']	= $this->intereses_model->getInteres($id);
				$db['impresiones']			= $this->config_impresion_model->getRegistro(2);
				$db['devoluciones']			= $this->devoluciones_model->getBusqueda($condicion);
				$db['anulaciones']			= $this->anulaciones_model->getAnulaciones($id);
				$db['factura']	= $this->facturas_model->getFactura($id);
				$db['llamada'] = ($llamada == NULL) ? FALSE : TRUE;

				$this->setView('presupuestos/detalle_presupuestos.php', $db);
			}else{
				redirect('/','refresh');
			}
		}



		/**********************************************************************************
		 **********************************************************************************
		 *
		 * 				Presupuesto de Salida
		 *
		 * ********************************************************************************
		 **********************************************************************************/
			//
			// public function search_articulo($id) {
			// 	$query = $this->db->query("
			// 			SELECT descripcion as value,id_articulo,precio_venta_iva FROM articulo WHERE descripcion LIKE '%".$id."%' or cod_proveedor LIKE '%".$id."%' limit 20
			// 		");
			//
			// 	if($query->num_rows() > 0){
			// 		foreach ($query->result() as $row) {
			// 			$row['value']	= htmlentities(stripslashes($row['value']));
			// 			$row['id']		= (int)$row['id_articulo'];
			// 			$row['precio']	= (float)$row['precio_venta_iva'];
			// 			$row_set[]		= $row;//build an array
			// 		}
			// 		echo json_encode($row_set);
			// 	}else{
			// 		return FALSE;
			// 	}
			// }
}
