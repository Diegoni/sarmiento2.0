<?php
class Proveedores_model extends MY_Model {

	public function __construct(){
		parent::construct(
				'proveedor',
				'id_proveedor',
				'descripcion', //ver si esto esta bien
				'descripcion'
		);
	}

	function getTotalArticulos(){
		$sql =
		"SELECT
			count(proveedor.id_proveedor) as suma,
			proveedor.descripcion
		FROM
			`articulo`
		INNER JOIN
			proveedor ON(articulo.id_proveedor = proveedor.id_proveedor)
		GROUP BY
			proveedor.id_proveedor
		ORDER BY
			suma DESC
		LIMIT
			0, 20";

		return $this->getQuery($sql);
	}

	public function getProveedor($data){
		$sql = "
			SELECT
				*
			FROM
				`proveedor`
			WHERE
				proveedor.descripcion LIKE '%".$data."%'";
			return $this->getQuery($sql);
	}

}
?>
