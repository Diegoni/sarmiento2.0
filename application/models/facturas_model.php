<?php
class Facturas_model extends MY_Model {

	public function __construct(){

		parent::construct(
				'factura',
				'id_factura',
				'id_factura',
				'id_factura'
		);
	}

	public function getFactura($id_presupuesto){
		$sql =
		"SELECT
			*
			FROM
				`factura`
			WHERE
				factura.id_presupuesto= ".$id_presupuesto;

		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		} else {
			return FALSE;
		}
	}
}
?>
