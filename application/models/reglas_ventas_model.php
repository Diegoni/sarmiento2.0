<?php
class Reglas_ventas_model extends MY_Model {

	public function __construct(){
		parent::construct(
			'regla_venta',
			'id_regla_venta',
			'id_regla_venta', //ver si esto esta bien
			'id_regla_venta'
		);
	}

	public function setBajaRegla($id_regla_venta ){
		$sql = "
			UPDATE 
				regla_venta 
				SET fecha_baja_regla = '".date('Y-m-d')."'
			WHERE
				id_regla_venta  = '$id_regla_venta'";

		$this->db->query($sql);;
	}

	public function getReglas($id_tipo_cliente){
		$sql = "
		SELECT 
			* 
		FROM 
			`regla_venta` 
		WHERE 
			fecha_baja_regla IS NULL AND
			fecha_inicio <= '".date('Y-m-d')."' AND
			id_tipo_cliente = '".$id_tipo_cliente."'";

		return $this->getQuery($sql);
	}
}
?>
