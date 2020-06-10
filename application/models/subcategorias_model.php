<?php
class Subcategorias_model extends My_Model {

	public function __construct(){
		parent::construct(
			'subcategoria',
			'id_subcategoria',
			'descripcion', //ver si esto esta bien
			'descripcion'
		);
	}
}
?>
