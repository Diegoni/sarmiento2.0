<script>
function printDiv(divName) {
   var printContents = document.getElementById(divName).innerHTML;
   var originalContents = document.body.innerHTML;
   document.body.innerHTML = printContents;
   window.print();
   document.body.innerHTML = originalContents;
}

$(function() {
	$( "#final_fecha" ).datepicker({
		maxDate: '0',
		changeMonth: true,
				changeYear: true,
		dateFormat: 'dd-mm-yy',
		onClose: function( selectedDate ) {
			$( "#inicio_fecha" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});

$(function() {
	$( "#inicio_fecha" ).datepicker({
		maxDate: '0',
		changeMonth: true,
				changeYear: true,
		dateFormat: 'dd-mm-yy',
		onClose: function( selectedDate ) {
			$( "#final_fecha" ).datepicker( "option", "minDate", selectedDate );
		}
	});
});
$(document).ready(function() {
	//$('#table_facturas').DataTable();
});
</script>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Detalle de factuas
        </div>
        <div class="panel-body">
					<div class="row" style="padding-bottom: 10px;">
					<form method="post">
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control" id="inicio_fecha" name="desde" value="<?php echo $desde;?>" placeholder="Desde">
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control" id="final_fecha" name="hasta" value="<?php echo $hasta;?>" placeholder="Hasta">
						</div>
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" id="numero_factura" name="numero_factura" value="<?php echo $numero_factura;?>" placeholder="Factura">
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" id="cliente" name="cliente" value="<?php echo $cliente;?>" placeholder="Cliente">
					</div>
					<div class="col-md-4">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#cabeceraModal" id="btnModalCabecera">
              Numeración
            </button>
						<button class="btn btn-primary" name="buscar">
							Cambiar
						</button>
						<button class="btn btn-default" type="button" onclick="printDiv('printableArea')"/>
	            <i class="fa fa-print"></i> Imprimir
	          </button>
					</div>
				</form>
				</div>
          <div  id="printableArea">
          <table id="table_facturas" class="table" border="1" cellpadding="5" cellspacing="5" width="100%" style="border-collapse:collapse;">
            <thead>
            <tr>
              <th>Fecha</th>
              <th>Tipo</th>
              <th>Nro</th>
              <th>Cliente</th>
              <th>Cuil</th>
              <th>Monto</th>
							<th>Presupuesto</th>
            </tr>
            </thead>
            <tbody>
              <?php
              if($facturas){
                foreach ($facturas as $factura) {
                  $letra = ($factura->tipo == 1) ? 'A' : 'B';
									$href_presupuesto = base_url().'index.php/presupuestos/update/'.$factura->id_presupuesto;
									$presupuesto = '<a title="ver Presupuesto" class="btn btn-default btn-xs" href="'.$href_presupuesto.'">'.$factura->id_presupuesto.'</a>';
									$href_cliente = base_url().'index.php/clientes/resumen/'.$factura->id_cliente;
									$cliente = '<a title="ver Cliente" class="btn btn-default btn-xs" href="'.$href_cliente.'">'.$factura->cliente.'</a>';

                  echo '<tr>';
                  echo '<td class="td-center">'.date("d-m-Y", strtotime($factura->fecha)).'</td>';
                  echo '<td class="td-center">FACTURA '.$letra.'</td>';
                  echo '<td class="td-center">'.str_pad($factura->pto_vta, 5, "0", STR_PAD_LEFT).'-'.str_pad($factura->nro, 8, "0", STR_PAD_LEFT).'</td>';
                  echo '<td>'.$cliente.'</td>';
                  echo '<td class="td-center">'.$factura->cuil.'</td>';
                  echo '<td class="td-right">$ '.number_format((float)$factura->monto, 2, '.', '').'</td>';
                  echo '<td class="td-center">'.$presupuesto.'</td>';
                  echo '</tr>';
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.td-right {
    text-align: right;
}

.td-center {
    text-align: center;
}
</style>


<div class="modal fade" id="cabeceraModal" tabindex="-1" role="dialog" aria-labelledby="myModalComentario">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalComentario">Numeración</h4>
      </div>
      <div class="modal-body">
        <table class="table">
          <tr>
          <th>Comprobante</th>
          <th>Numeracion</th>
          <tr>
          <?php if ($afip){
            foreach ($afip as $rowAfip) {
              echo '<tr>';
              echo '<td>'.$comprobantes[$rowAfip->tipo_comprobante].'</td>';
              echo '<td>'.$rowAfip->cbte_desde.'</td>';
              echo '</tr>';
            }
          }
          ?>
        </table>
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_cabecera">Cancelar</button>
       <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardar_cabecera" onclick="actualizar()">Actualizar</button>
      </div>
     </div>
   </div>
  </div>

<script>
function actualizar(){
  $.ajax({
      type: "POST",
      url: '<?php echo base_url()?>index.php/afipFactuaElectronica/synchronizeVoucherNumbering/',
      complete: function(response)
      {
         location.reload();
      }
  });
}
</script>
