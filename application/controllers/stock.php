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

	public function search_articulo() {
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
