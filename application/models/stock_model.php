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

	public function detailStock($id_articulo, $id_comprobante, $filter){
		switch ($id_comprobante) {
		  case COMPROBANTES::MANUAL:
				$sqlInner = " INNER JOIN stock ON(stock_renglon.nro_comprobante = stock.id_stock) ";
				$filtersFields = [
					'nro' => 'stock.id_stock',
					'additional' => 'comentario',
					'fecha' => 'stock.date_add',
				];
		    break;
		  case COMPROBANTES::PRESUPUESTO:
				$sqlInner = " INNER JOIN presupuesto ON(stock_renglon.nro_comprobante = presupuesto.id_presupuesto) ";
				$sqlInner .= " INNER JOIN cliente ON(presupuesto.id_cliente = cliente.id_cliente) ";
				$filtersFields = [
					'nro' => 'presupuesto.id_presupuesto',
					'additional' => 'cliente.alias',
					'fecha' => 'presupuesto.fecha',
				];
				break;
		  case COMPROBANTES::DEVOLUCION:
				$sqlInner = " INNER JOIN devolucion ON(stock_renglon.nro_comprobante = devolucion.id_devolucion) ";
				$filtersFields = [
					'nro' => 'devolucion.id_devolucion',
					'additional' => 'devolucion.nota',
					'fecha' => 'devolucion.fecha',
				];
		    break;
		  case COMPROBANTES::ANULACION:
				$sqlInner = " INNER JOIN presupuesto ON(stock_renglon.nro_comprobante = presupuesto.id_presupuesto)";
				$sqlInner .= " INNER JOIN cliente ON(presupuesto.id_cliente = cliente.id_cliente) ";
				$filtersFields = [
					'nro' => 'presupuesto.id_presupuesto',
					'additional' => 'cliente.alias',
					'fecha' => 'presupuesto.fecha',
				];
				break;
		}
		$sql = "
			SELECT
				$filtersFields[nro] as nro,
				$filtersFields[additional] as additional,
				$filtersFields[fecha] as fecha,
				stock_renglon.cantidad
			FROM
				stock_renglon ";
		$sql .= $sqlInner.' WHERE ';
		$sql .= ($filter['desde'] != '') ? $filtersFields['fecha']." >= '".date("Y-m-d",strtotime($filter['desde']))."' AND " : "";
		$sql .= ($filter['hasta'] != '') ? $filtersFields['fecha']." <= '".date("Y-m-d",strtotime($filter['hasta']))."' AND " : "";
		$sql .= ($filter['nro'] != '') ? $filtersFields['nro']." = '".$filter['nro']."' AND " : "";
		$sql .= ($filter['additional'] != '') ? $filtersFields['additional']." LIKE '%".$filter['additional']."%' AND " : "";
		$sql .= " stock_renglon.id_articulo = '$id_articulo' AND ";
		$sql .= " stock_renglon.id_comprobante = '$id_comprobante' ";

		return $this->getQuery($sql);
	}

	public function getDetail ($id){
		$sql = " SELECT * FROM stock_renglon INNER JOIN articulo ON (stock_renglon.id_articulo = articulo.id_articulo) WHERE nro_comprobante = '$id' AND id_comprobante = ".COMPROBANTES::MANUAL;
		return $this->getQuery($sql);
	}
}
?>
