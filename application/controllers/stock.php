<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends My_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('stock_model');
		$this->load->model('articulos_model');
	}

	/*
	*
	* @return view
	*/
	public function insert($id = NULL) {
		if ($id != NULL) {
			$db['stock'] = $this->stock_model->getRegistro($id);
			$db['stock_renglon'] = $this->stock_model->getDetail($id);
		} else {
			$db['stock'] = false;
			$db['stock_renglon'] = false;
		}

		$this->setView('stock/insert', $db);
	}

	/*
	*
	* @return view
	*/
	public function insertDetail() {
		$id_articulos	= $this->input->post('codigos_art');
		$cantidades		= $this->input->post('cantidades');
		$comentario		= $this->input->post('comentario');
		$costos		= $this->input->post('costos');

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
			$this->articulos_model->updateByCosto($id_articulos[$i], $costos[$i]);
		}
	}
	/*
	*
	* @return view
	*/
	public function stockArticulo($id_articulo = null, $id_comprobante = null) {
		$filter = [
			'desde' => '',
			'hasta' => '',
			'nro' => '',
			'additional' => '',
			'filter' => false,
		];

		if ($this->input->post('filter')) {
			$filter = [
				'desde' => $this->input->post('filterDesde'),
				'hasta' => $this->input->post('filterHasta'),
				'nro' => $this->input->post('filterNro'),
				'additional' => $this->input->post('filterAdditional'),
				'filter' => true,
			];
		}
		if ($id_articulo != null) {
			$db['articulo'] = $this->articulos_model->getRegistro($id_articulo);
			$db['stock'] = $this->stock_model->totalStock($id_articulo);
			$db['detail'] = ($id_comprobante != null) ? $this->stock_model->detailStock($id_articulo, $id_comprobante, $filter) : false;
			$db['id_comprobante'] = $id_comprobante;
		} else {
			$db['articulo'] = false;
		}
		$db['filter'] = $filter;
		$this->setView('stock/articulo', $db);
	}
}
