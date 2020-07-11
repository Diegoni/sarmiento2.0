<?php
class Stock_model extends My_Model {

	public function __construct(){
		parent::construct(
				'stock',
				'id_stock',
				'id_stock',
				'id_stock'
		);
	}

	public function updateStock($registro){
		$sql = "
			SELECT
				*
			FROM
				articulo
			WHERE
				id_articulo = $registro[id_articulo] AND
				llevar_stock = 1;";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			// CAMBIOS EN STOCK - Actualizamos tabla articulo
			$sqlArticulo = "UPDATE articulo SET stock = stock + $registro[cantidad] WHERE id_articulo = $registro[id_articulo];";
			$count = $this->db->query($sqlArticulo);

			// CAMBIOS EN STOCK - Actualizamos tabla stock
			if($count){
				$sqlStock = " INSERT INTO stock_renglon ( id_comprobante, nro_comprobante, id_articulo, cantidad ) VALUES ( $registro[id_comprobante], $registro[nro_comprobante], $registro[id_articulo], $registro[cantidad] )";
				$this->db->query($sqlStock);
			}
		}
	}
}
?>
