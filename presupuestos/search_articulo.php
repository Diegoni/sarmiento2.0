<?php
include_once('config/conexion.php');
$term = trim(strip_tags(utf8_decode($_GET['term'])));

$query = "SELECT
	descripcion as value,
	id_articulo,
	precio_venta_sin_iva_con_imp,
	iva as porc_iva
FROM
	articulo
WHERE
	(descripcion LIKE '%".$term."%' OR
	cod_proveedor LIKE '%".$term."%') AND
	id_estado = 1
LIMIT
	20";
$result = $conn->query($query) ;//query the database for entries containing the term

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	  $row['value']	= $row['value'];
		$row['id'] 		= (int) $row['id_articulo'];
		$row['iva'] 	= (float) $row['porc_iva'];
		$row['precio']= (float) $row['precio_venta_sin_iva_con_imp'];
		$row_set[] 		= $row;
}

echo json_encode($row_set);//format the array into json data
?>
