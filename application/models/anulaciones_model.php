<?php
class Anulaciones_model extends MY_Model {

	public function __construct(){
		parent::construct(
			'anulacion',
			'id_anulacion',
			'id_anulacion', //ver si esto esta bien
			'id_anulacion'
		);
	}

	function getAnulaciones($id) {
		$sql = "
			SELECT * FROM `anulacion`
				WHERE
				id_presupuesto = '$id'";

		return $this->getQuery($sql);
	}

	function suma_anulacion($inicio, $final, $id_cliente = NULL) {
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
			`anulacion`
		WHERE
			DATE_FORMAT(fecha, '%Y-%m') >= '$inicio' AND
			DATE_FORMAT(fecha, '%Y-%m') <= '$final'";

		return $this->getQuery($sql);
	}
}
?>
