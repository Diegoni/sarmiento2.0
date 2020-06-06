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

	public function getFacturaDetalle($desde, $hasta, $numero_factura = NULL, $cliente = NULL){
		$desde = date("Y-m-d",strtotime($desde));
		$hasta = date("Y-m-d",strtotime($hasta));
		$sql =
		"SELECT
			factura.cbte_fch as fecha,
			factura.cbte_tipo as tipo,
			factura.pto_vta,
			factura.cbte_desde as nro,
			cliente.id_cliente,
			cliente.alias as cliente,
			cliente.cuil as cuil,
			factura.imp_total as monto,
			presupuesto.id_presupuesto
			FROM
				factura
			INNER JOIN
				presupuesto ON (factura.id_presupuesto = presupuesto.id_presupuesto)
			INNER JOIN
			 	cliente ON (presupuesto.id_cliente = cliente.id_cliente)";
		$sql .= " WHERE ";
		if($numero_factura != NULL && $numero_factura != NULL){
			$sql .= " factura.cbte_desde = '".$numero_factura."' ";
		}else{
			$sql .= " factura.cbte_fch >= '".$desde."' ";
			$sql .= " AND factura.cbte_fch <= '".$hasta."' ";

			if($cliente != NULL && $cliente != NULL){
				$sql .= " AND (cliente.alias LIKE '%".$cliente."%' OR cliente.cuil LIKE '%".$cliente."%')";
			}
		}

		$sql .= " ORDER BY factura.cbte_fch, factura.cbte_tipo, factura.cbte_desde ";

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
