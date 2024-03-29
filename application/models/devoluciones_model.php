<?php
class Devoluciones_model extends MY_Model {

	public function __construct(){
		parent::construct(
				'devolucion',
				'id_devolucion',
				'id_devolucion', //ver si esto esta bien
				'id_devolucion'
		);
	}

	function getCliente($id_cliente, $all = NULL, $fechaDesde = null) {
		if($all == NULL) {
			$sql =
			"SELECT
					`devolucion`.`id_devolucion`,
					`devolucion`.`id_presupuesto`,
					`devolucion`.`fecha`,
					`devolucion`.`monto`,
					`devolucion`.`a_cuenta`,
					`devolucion`.`nota`
				FROM
					`devolucion`
				INNER JOIN
					`presupuesto` ON(devolucion.id_presupuesto = presupuesto.id_presupuesto)
				WHERE
					`presupuesto`.`id_cliente` = $id_cliente
					AND `devolucion`.`id_estado` = 1";
		} else {
			$sql =
			"SELECT
					`devolucion`.`id_devolucion`,
					`devolucion`.`id_presupuesto`,
					`devolucion`.`fecha`,
					`devolucion`.`monto`,
					`devolucion`.`a_cuenta`,
					`devolucion`.`nota`
				FROM
					`devolucion`
				INNER JOIN
					`presupuesto` ON(devolucion.id_presupuesto = presupuesto.id_presupuesto)
				WHERE
					`presupuesto`.`id_cliente` = $id_cliente";
		}

		if( $fechaDesde != null){
			$sql .= " AND DATE_FORMAT(devolucion.fecha, '%Y-%m-%d') >= '$fechaDesde'  ";
		}

		return $this->getQuery($sql);
	}


	function suma_devolucion($inicio, $final, $id_cliente = NULL) {
		if($id_cliente === NULL) {
			$inicio	= date('Y-m', strtotime($inicio));
			$final	= date('Y-m', strtotime($final));
		} else {
			$inicio	= date('Y-m-d', strtotime($inicio));
			$final	= date('Y-m-d', strtotime($final));
		}

		$sql = "
			SELECT
				*
			FROM
				`devolucion`
			WHERE
				DATE_FORMAT(fecha, '%Y-%m') >= '$inicio' AND
				DATE_FORMAT(fecha, '%Y-%m') <= '$final'";

		return $this->getQuery($sql);
	}
}
?>
