<?php
class Renglon_presupuesto_model extends MY_Model {

	public function __construct(){
		parent::construct(
				'reglon_presupuesto',
				'id_renglon',
				'monto', //ver si esto esta bien
				'id_presupuesto'
		);
	}

	function Ultimos($cantidad) {
		$sql = "
			SELECT
				*
			FROM
				`reglon_presupuesto`
			INNER JOIN
				articulo ON(reglon_presupuesto.id_articulo =  articulo.id_articulo)
			ORDER BY
				id_renglon LIMIT 0 , $cantidad";

		return $this->getQuery($sql);
	}

	function getDetalle($id) {
		$sql = "
			SELECT
				*
			FROM
				`reglon_presupuesto`
			INNER JOIN
				articulo ON(reglon_presupuesto.id_articulo =  articulo.id_articulo)
			WHERE
				reglon_presupuesto.id_presupuesto = '$id'";

		return $this->getQuery($sql);
	}


	function getDetalle_where($datos, $condicion = NULL) {
		if($condicion == NULL || $condicion != 'AND') {
			$condicion = 'OR';
		}

		if(is_array($datos)) {
			$query = "
				SELECT
					*
				FROM
					`reglon_presupuesto`
				INNER JOIN
					articulo ON(reglon_presupuesto.id_articulo =  articulo.id_articulo)
				WHERE ";
			foreach ($datos as $key => $value) {
				$query .= $this->_table.".".$key."='".$value."' ";
				$query.= $condicion." ";
			}
		}

		$sql = substr($query, 0, strlen($query)-(strlen($condicion)+1));

		return $this->getQuery($sql);
	}
}
