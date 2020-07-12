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

	public function totalStock($id_articulo){
		$sql = "
			SELECT
				stock_renglon.id_comprobante,
				comprobante,
				sum(cantidad) as cantidad
			FROM
				stock_renglon
			INNER JOIN
				comprobantes ON(stock_renglon.id_comprobante = comprobantes.id_comprobante)
			WHERE
				id_articulo = '$id_articulo'
			GROUP BY
				stock_renglon.id_comprobante";

		return $this->getQuery($sql);
	}

	public function detailStock($id_articulo, $id_comprobante){
		switch ($id_comprobante) {
		  case COMPROBANTES::MANUAL:
				$sql = "
					SELECT
						stock.id_stock as nro,
						comentario as additional,
						stock.date_add as fecha,
						stock_renglon.cantidad
					FROM
						stock_renglon
					INNER JOIN
						stock ON(stock_renglon.nro_comprobante = stock.id_stock)";
		    break;
		  case COMPROBANTES::PRESUPUESTO:
			$sql = "
				SELECT
					presupuesto.id_presupuesto as nro,
					cliente.alias as additional,
					presupuesto.fecha as fecha,
					stock_renglon.cantidad
				FROM
					stock_renglon
				INNER JOIN
					presupuesto ON(stock_renglon.nro_comprobante = presupuesto.id_presupuesto)
				INNER JOIN
					cliente ON(presupuesto.id_cliente = cliente.id_cliente)";
			break;
		    break;
		  case COMPROBANTES::DEVOLUCION:
				$sql = "
					SELECT
						devolucion.id_devolucion as nro,
						devolucion.nota as additional,
						devolucion.fecha as fecha,
						stock_renglon.cantidad
					FROM
						stock_renglon
					INNER JOIN
						devolucion ON(stock_renglon.nro_comprobante = devolucion.id_devolucion)";
		    break;
		  case COMPROBANTES::ANULACION:
				$sql = "
					SELECT
						presupuesto.id_presupuesto as nro,
						cliente.alias as additional,
						presupuesto.fecha as fecha,
						stock_renglon.cantidad
					FROM
						stock_renglon
					INNER JOIN
						presupuesto ON(stock_renglon.nro_comprobante = presupuesto.id_presupuesto)
					INNER JOIN
						cliente ON(presupuesto.id_cliente = cliente.id_cliente)";
					break;
		}

		$sql .= "
		WHERE
			stock_renglon.id_articulo = '$id_articulo' AND
			stock_renglon.id_comprobante = '$id_comprobante'";

		return $this->getQuery($sql);
	}
}
?>
