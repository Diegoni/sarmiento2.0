<?php
include_once('config/conexion.php');
//connect to your database
$term = trim(strip_tags(utf8_decode($_GET['term'])));//retrieve the search term that autocomplete sends

$qstring = "
	SELECT
		alias as value,
		cuil as num_cuil,
		direccion,
		id_cliente,
		apellido,
		nombre,
		tipo_cliente.pertmite_cta_cte,
		tipo_cliente.monto_max_presupuesto
	FROM
		cliente
	LEFT JOIN
		tipo_cliente ON (cliente.id_tipo = tipo_cliente.id_tipo)
	WHERE
		(alias LIKE '%".$term."%' OR
			cuil LIKE '%".$term."%') AND id_estado = 1
	LIMIT 20";
$result = $conn->query($qstring) ;

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$row['value'] = stripslashes(utf8_encode($row['value']));
		$row['nom'] = stripslashes(utf8_encode($row['nombre']));
		$row['id'] = (int) $row['id_cliente'];
		$row['ape'] = stripslashes(utf8_encode($row['apellido']));
		$row['cuil'] = stripslashes(utf8_encode($row['num_cuil']));
		$row['pertmite_cta_cte'] = (float) $row['pertmite_cta_cte'];
		$row['monto_max_presupuesto'] = (float) $row['monto_max_presupuesto'];
		$row_set[] = $row;//build an array
}

echo json_encode($row_set);//format the array into json data

?>
