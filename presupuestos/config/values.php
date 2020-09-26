<?php

$sql = "
SELECT
  *
FROM
  `vendedor`
WHERE
    id_estado = 1";

$result_vendedores = $conn->query($sql) ;
$optionVendedores = '';

if($result_vendedores) {
  while ($row_vendedor = $result_vendedores->fetch_array(MYSQLI_ASSOC)) {
    $optionVendedores .= "<option value=".$row_vendedor['id_vendedor']."> ".$row_vendedor['vendedor']."</option>";
  }
}

$qstring = "
SELECT
    cantidad_inicial
FROM
    config
WHERE
    id_config = 1";
$result_config = $conn->query($qstring) ;//query the database for entries containing the term

while ($row = $result_config->fetch_array(MYSQLI_ASSOC)) {
    $cantidad_inicial = $row['cantidad_inicial'];
}
?>
