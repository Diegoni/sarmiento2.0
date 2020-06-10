<?php
class Colors_model extends MY_Model {

	public function __construct(){
		parent::construct(
			'color',
			'id_color',
			'color', //ver si esto esta bien
			'color'
		);
	}

	public function getColorsCalendario($id_calendario){
		$sql = "
			SELECT
				backgroundColor
			FROM
				color
			INNER JOIN
				calendario ON (color.id_color = calendario.id_color)
			WHERE
				id_calendario = '$id_calendario'";

		return $this->getQuery($sql);
	}
}
?>
