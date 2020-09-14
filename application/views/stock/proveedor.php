<div class="container">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">Stock articulos por proveedor</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input class="form-control" type='text' placeholder="Proveedor" name='proveedor' value="<?php echo ($proveedor) ? $proveedor[0]->descripcion : ''?>" id='proveedor'/>
						</div>
					</div>
					<div class="col-md-3"></div>
					<div class="col-md-3">
						<div class="form-group">
							<button class="btn btn-default" type="button" onclick="printDiv('printableArea')"/>
								<i class="fa fa-print"></i> Imprimir
							</button>
						</div>
					</div>
				</div>
				<?php if ($proveedor) { ?>
				<div class="row">
					<div id="printableArea">
						<table class="table table table-striped" id="articulos">
							<thead>
								<tr>
									<th>Cod proveedor</th>
									<th>Descripción</th>
									<th>Stock</th>
									<th>Para stock minimo</th>
									<th>Para stock deseado</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
	$("#proveedor").autocomplete({
	  source: "<?php echo base_url()?>index.php/proveedores/searchProveedor",
	  minLength: 2,
	  select: function(event,ui) {
			window.location.replace("<?php echo base_url()?>index.php/stock/stockProveedor/"+ui.item.id);
		},
	});
});

function printDiv(divName) {
   var printContents = document.getElementById(divName).innerHTML;
   var originalContents = document.body.innerHTML;
   document.body.innerHTML = printContents;
   window.print();
   document.body.innerHTML = originalContents;
}


$(document).ready(function() {
	$("#proveedor").focus();
	$('#articulos').DataTable( {
    "ajax": {
        "url": "<?php echo base_url()?>index.php/stock/getArticulos/<?php echo ($proveedor) ? $proveedor[0]->id_proveedor : '' ?>",
        "dataSrc": ""
    },
    "columns": [
        { "data": "cod_proveedor" },
        { "data": "descripcion" },
        { "data": "stock" },
        { "data": "stock_minimo" },
        { "data": "stock_deseado" }
    ],
		"order": [[ 1, "asc" ]],
		"language": {
	       "sProcessing":    "Procesando...",
	       "sLengthMenu":    "Mostrar _MENU_ registros",
	       "sZeroRecords":   "No se encontraron resultados",
	       "sEmptyTable":    "Ningún dato disponible en esta tabla",
	       "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	       "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
	       "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
	       "sInfoPostFix":   "",
	       "sSearch":        "Buscar:",
	       "sUrl":           "",
	       "sInfoThousands":  ",",
	       "sLoadingRecords": "Cargando...",
	       "oPaginate": {
	           "sFirst":    "Primero",
	           "sLast":    "Último",
	           "sNext":    "Siguiente",
	           "sPrevious": "Anterior"
	       },
	       "oAria": {
	           "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
	           "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	       }
	   }
	 });
});
</script>
