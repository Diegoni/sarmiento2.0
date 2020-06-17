<?php
include_once('conexion.php');
//connect to your database
const CAE = 2;
$logsFile = "logs/".date('Y-m-d').'.log';
$fecha		= date('Y-m-d H:i:s');
$monto		= $_POST['total'];
$id_cliente	= $_POST['cliente'];
$tipo		= $_POST['tipo'];
$estado		= $_POST['estado'];
$dto		= $_POST['desc'];
$id_vendedor   = $_POST['vendedor'];
$comentario	= $_POST['comentario'];
$com_publico  = $_POST['com_publico'];

$codigos_a_cargar	= $_POST['codigos_art'];
$cant_a_cargar		= $_POST['cantidades'];
$precios_a_cargar	= $_POST['precios'];
$tipo_comprobante	= $_POST['tipo_comprobante'];

//CARGO PRESUPUESTO

$qstring	= "INSERT INTO presupuesto (fecha, monto, id_cliente,tipo,estado,descuento, id_vendedor, comentario, com_publico) VALUES('$fecha',$monto,$id_cliente,$tipo,$estado,$dto, '$id_vendedor', '$comentario', $com_publico)";
$result		= $conn->query($qstring);//query the database for entries containing the term

//CARGO PRESUPUESTO
//CARGO REGLON PRESUPUESTO //
$id_presupuesto = $conn->insert_id;

$codigos_cargados = array();

for ($i=0; $i<count($codigos_a_cargar); $i++ ) {
	if(in_array($codigos_a_cargar[$i], $codigos_cargados)) {
		$file = fopen($logsFile, "a");
		fwrite($file, date('Y-m-d H:i:s'). "El presupuesto nro ".$id_presupuesto." esta repitiendo los codigos\n" . PHP_EOL);
		fclose($file);
	} else {
		$qstring = "
		INSERT INTO
			reglon_presupuesto (
				id_presupuesto,
				id_articulo,
				cantidad,
				precio,
				estado
			)
		VALUES(
			$id_presupuesto,
			$codigos_a_cargar[$i],
			$cant_a_cargar[$i],
			$precios_a_cargar[$i],
			1
		)";

		$result = $conn->query($qstring);//query the database for entries containing the term
		$conn->insert_id;

		 $file = fopen($logsFile, "a");
		 fwrite($file, date('Y-m-d H:i:s').$qstring . PHP_EOL);
		 fwrite($file, date('Y-m-d H:i:s').$result . PHP_EOL);
		 fwrite($file, date('Y-m-d H:i:s'). $conn->insert_id . PHP_EOL);
		 fclose($file);
	}
}

if($tipo_comprobante == CAE){
	$url = 'http://localhost/sarmiento2.0/index.php/afipFactuaElectronica/getCAE/'.$id_presupuesto;
	$cURLConnection = curl_init();
	curl_setopt($cURLConnection, CURLOPT_URL, $url);
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($cURLConnection);
	fwrite($file, date('Y-m-d H:i:s').$result . PHP_EOL);
	curl_close($cURLConnection);
	$result = $id_presupuesto;
} else {
	$result = $id_presupuesto;
}

echo $result;
