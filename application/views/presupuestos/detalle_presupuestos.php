<script>
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>


<div class="container">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $texto['empresa_titulo'] ?></div>
			<div class="panel-body">

				<div id="printableArea">
					<?php
					$estadosPresupuesto = [
						'1' => 'warning',
						'2' => 'success',
						'3' => 'danger',
					];

					if ($presupuestos) {
						$nombre =  ($cliente) ? $cliente[0]->nombre : '';
						$apellido = ($cliente) ? $cliente[0]->apellido : '';
						$cuit = ($cliente) ? $cliente[0]->cuil : 0;
						$vendedor = ($vendedor) ?  $vendedor[0]->vendedor : '';
						$cabecera = $impresiones[0]->cabecera;

						$cabecera = str_replace("#presupuesto_nro#", $presupuestos[0]->id_presupuesto, $cabecera);
						$cabecera = str_replace("#presupuesto_descuento#", $presupuestos[0]->descuento, $cabecera);
						$cabecera = str_replace("#presupuesto_fecha#", date('d-m-Y', strtotime($presupuestos[0]->fecha)), $cabecera);
						$cabecera = str_replace("#presupuesto_monto#", $presupuestos[0]->monto, $cabecera);
						$cabecera = str_replace("#vendedor#", $vendedor, $cabecera);
						$cabecera = str_replace("#cliente_nombre#", $nombre, $cabecera);
						$cabecera = str_replace("#cliente_apellido#", $apellido, $cabecera);

						$pie = $impresiones[0]->pie;
						$id_presupuesto = $presupuestos[0]->id_presupuesto;
						$cabecera = "<table class='table table-hover'>" . $cabecera . "<hr>";

						$total = 0;
						$table = '';

						$table .= "<table class='table table-hover'>";
						$table .= "<tr>";
						$table .= "<th>" . $texto['articulo'] . "</th>";
						$table .= "<th>Descripción</th>";
						$table .= "<th>" . $texto['cantidad'] . "</th>";
						$table .= "<th>" . $texto['monto'] . "</th>";
						$table .= "<th>" . $texto['total'] . "</th>";
						$table .= "</tr>";

						if ($detalle_presupuesto) {
							foreach ($detalle_presupuesto as $row_detalle) {
								$precio = ($row_detalle->cantidad > 0) ? $row_detalle->precio / $row_detalle->cantidad : 0;
								$sub_total = $row_detalle->cantidad * $precio;
								$total = $total + $sub_total;

								$table .= "<tr>";
								$table .= "<td><a title='ver Articulo' class='btn btn-default btn-xs' href='" . base_url() . "index.php/articulos/articulo_abm/read/" . $row_detalle->id_articulo . "'>" . $row_detalle->cod_proveedor . "</a></td>";
								$table .= "<td>" . $row_detalle->descripcion . "</td>";
								$table .= "<td>" . $row_detalle->cantidad . "</td>";
								$table .= "<td>$ " . round($precio, 2) . "</td>";
								$table .= "<td>$ " . round($sub_total, 2) . "</td>";
								$table .= "</tr>";
							}
						}

						if ($interes_presupuesto) {
							foreach ($interes_presupuesto as $row_interes) {
								$total = $total + $row_interes->monto;

								$table .= "<tr>";
								$table .= "<td>-</td>";
								$table .= "<td>" . $row_interes->descripcion . "</td>";
								$table .= "<td>-</td>";
								$table .= "<td>-</td>";
								$table .= "<td>" . $row_interes->monto . "</td>";
								$table .= "</tr>";
							}
						}

						$table .= "<tr class='" . $estadosPresupuesto[$presupuestos[0]->estado] . "'>";
						$table .= "<td colspan='4'>" . $texto['total'] . "</td>";
						$table .= "<th>$ " . round($total, 2) . "</th>";
						$table .= "</tr>";
						$table .= "</table>";
						$table .= "<hr>";

						echo $cabecera;
						echo $table;
						echo $pie;

						if ($devoluciones) {
							$mensaje = $texto['si_devolucion'] . " <a class='btn btn-warning'>Ver devolución</a>";
							echo setMensaje($mensaje, 'warning');
						}

						if ($presupuestos[0]->facturado == 1) {
							$letra = ($factura[0]->cbte_tipo == 1) ? 'A' : 'B';
							echo '<a href="' . base_url() . 'index.php/presupuestos/setPDF/' . $id_presupuesto . '" target="_blank"><div class="well" style="color: #fff; background-color: #428bca;"><center>FACTURA ' . $letra . ': ' . str_pad($factura[0]->cbte_desde, 8, "0", STR_PAD_LEFT) . '</center></div></a>';
						}

						if ($presupuestos[0]->comentario != '') {
							if ($presupuestos[0]->com_publico == 1) {
								echo '<div class="well">Comentario: ' . $presupuestos[0]->comentario . "</div></div>";
							} else {
								echo '</div><div class="well">Comentario Privado: ' . $presupuestos[0]->comentario . "</div>";
							}
						} else {
							echo '</div>';
						}
					} else {
						echo setMensaje($texto['no_registro'], 'success');
						echo '</div>';
					}

					// BOTONES FINALES
					$arrayButtons = [];

					
					if (!$llamada) {
						$arrayButtons[] = "<input type='button' class='btn btn-default' value='Volver a la lista' onclick='window.history.back()'>";
					}

					// PRESUPUESTO NO ANULADO
					if ($presupuestos[0]->estado != 3) {

						// Dropdown con las opciones de imprimir
						$printButton = '<div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-print"></i> Imprimir <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="top: auto !important; bottom: 100% !important; margin-bottom: 1px;">
          ';

						// Si no esta facturado damos opcion de imprimir
						if ($presupuestos[0]->facturado != 1) {
							$printButton .= "<li><a href='#' onclick=\"printDiv('printableArea')\"><i class='fa fa-print'></i> Imprimir</a></li>";
						}

						$printButton .= "<li><a href='" . base_url() . "index.php/presupuestos/setPDF/" . $id_presupuesto . "' target='_blank'><i class='fa fa-file-pdf-o'></i> PDF</a></li>";
						$printButton .= "<li><a href='" . base_url() . "index.php/presupuestos/setTicket/" . $id_presupuesto . "' target='_blank'><i class='fa fa-ticket'></i> Ticket</a></li>";
						$printButton .= "</ul></div>";
						$arrayButtons[] = $printButton;

						// Presupuesto pendiente de pago
						if (!$llamada) {
							if ($presupuestos[0]->tipo == 2) {
								$arrayButtons[] = '<a href="' . base_url() . 'index.php/devoluciones/generar/' . $id_presupuesto . '" class="btn btn-default"/><i class="fa fa-thumbs-down"></i> Devolución</a>';
								$arrayButtons[] = '<a href="' . base_url() . 'index.php/presupuestos/interes/' . $id_presupuesto . '" class="btn btn-default" data-toggle="modal" data-target="#interesModal"/><i class="fa fa-angle-up"></i> Interes</a>';
							}

							// Presupuesto pagado
							if ($presupuestos[0]->tipo == 1 &&  $presupuestos[0]->facturado != 1) {
								$arrayButtons[] = '<a href="' . base_url() . 'index.php/presupuestos/anular/' . $id_presupuesto . '" class="btn btn-default"/><i class="fa fa-trash-o"></i> Anular</a>';
							}
						}

						// ENVIO DE MAIL
						$disabledMail = ($cliente[0]->mail == '') ? 'disabled' : '';
						$textMail = ($cliente[0]->mail == '') ? 'Completar mail' : 'Enviar mail a :'.$cliente[0]->mail;
						$arrayButtons[] = "<button title='".$textMail."' type='button' class='btn btn-default' onclick='mail(".$presupuestos[0]->id_presupuesto.")' ".$disabledMail."><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span> Mail</button>";

						// Si no esta facturado, opcion de obtener CAE
						if ($presupuestos[0]->facturado != 1) {
							$cuitValido = (isCuitValid($cuit)) ? 'class="btn btn-primary"' : 'class="btn btn-danger" disabled="disabled" title="El CUIT del cliente no es valido para emitir factura"';
							$arrayButtons[] = '<button type="button" id="btn-get-cae" value="' . $id_presupuesto . '" ' . $cuitValido . '/><i class="fa fa-cloud-upload"></i> Obtener CAE</button>';
						}
					} else {
						// Presupuesto Anulado
						if ($anulaciones) {
							foreach ($anulaciones as $row_a) {
								$mensaje  = "Nota de la anulación: " . $row_a->nota . "<br>";
								$mensaje .= "Fecha de la anulación: " . date('d-m-Y', strtotime($row_a->fecha)) . "<br>";
							}

							echo setMensaje($mensaje, 'danger');
						}
					}

					// Mostramos los botones dependiendo del estado del presupuesto
					foreach ($arrayButtons as $button) {
						echo $button;
					}
					?>
				</div>
			</div>
		</div>
	</div>
	</body>

	</html>



	<!-- Modal Interes -->

	<div class="modal fade" id="interesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form class="form-horizontal" method="post" action="<?php echo base_url() ?>index.php/presupuestos/detalle_presupuesto/<?php echo $id_presupuesto ?>">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Interes</h4>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-2 control-label">Descripcion</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="descripcion_monto" placeholder="Descripcion">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Tipo</label>
							<div class="col-sm-10">
								<select class="form-control" name="interes_tipo" required>
									<option value="porcentaje">Porcentaje %</option>
									<option value="valor">Valor $</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Interes</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" name="interse_monto" placeholder="Interes" required>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="<?php echo base_url() . 'librerias/afip/js/main.js' ?>"></script>
	<style>
		.btn {
			margin-right: 5px
		}
	</style>

<script>
function mail(id_presupuesto){
  $.ajax({
    type:"POST",
    url:'<?php echo base_url() ?>index.php/mails/enviar/'+id_presupuesto,
    data:{},
    success:function(datos){
      alert("Correo enviado");
     },
  })
}

</script>