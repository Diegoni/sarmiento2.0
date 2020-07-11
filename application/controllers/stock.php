<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends My_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('stock_model');
		$this->load->model('articulos_model');
	}

	/*
	* CRUD CATEGORIA
	* @return view
	*/
	public function insert() {
		$db = true;
		$this->setView('stock/insert', $db);
	}

	public function insertDetail() {
		$id_articulos	= $this->input->post('codigos_art');
		$cantidades		= $this->input->post('cantidades');
		$comentario		= $this->input->post('comentario');

		$registro = [
			'comentario' => $comentario,
			'date_add' => date('Y/m/d H:i:s'),
		];
		$id_stock = $this->stock_model->insert($registro);

		for ($i=0; $i < count($id_articulos); $i++ ) {
			$registro = [
				'id_comprobante' 	=> COMPROBANTES::MANUAL,
				'nro_comprobante'	=> $id_stock,
				'id_articulo'			=> $id_articulos[$i],
				'cantidad'				=> $cantidades[$i],
			];
			$this->stock_model->updateStock($registro);
		}
	}

	public function searchArticulo() {
		$articulos= $this->articulos_model->getArticulo($_GET['term']);
		if ($articulos) {
			foreach ($articulos as $rowArticulo) {
				$row['value']	= htmlentities(stripslashes($rowArticulo->value));
				$row['id'] = (int) $rowArticulo->id_articulo;
				$row['precio'] = (float) $rowArticulo->precio_venta_iva;
				$row_set[] = $row;//build an array
			}
			echo json_encode($row_set);
		} else {
			return FALSE;
		}
	}
}
