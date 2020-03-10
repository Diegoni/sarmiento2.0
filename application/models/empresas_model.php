<?php
class Empresas_model extends My_Model {

	public function __construct(){

		parent::construct(
				'empresa',
				'id_empresa',
				'empresa',
				'empresa'
		);
	}	
}
?>
