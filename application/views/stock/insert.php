<div class="container">
<div class="col-md-12">
	<div class="panel panel-primary">
		<div class="panel-heading">Movimientos Stock</div>
		<div class="panel-body">

				<?php
				if ($stock) {
					echo '<div class="row">';
					echo '<div class="col-md-12">';
					echo '<table class="table table-striped">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>NRO</th>';
					echo '<th>'.$stock[0]->id_stock.'</th>';
					echo '<th>Fecha</th>';
					echo '<th >'.date("d-m-Y", strtotime($stock[0]->date_add	)).'</th>';
					echo '</tr>';
					echo '<tr>';
					echo '<th>Comentario</th>';
					echo '<th colspan="3">'.$stock[0]->comentario.'</th>';
					echo '</tr>';
					echo '</thead>';
					echo '</table>';
					echo '</div>';

					echo '<div class="col-md-12">';
					echo '<table class="table table-striped">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>Codigo</th>';
					echo '<th>Articulo</th>';
					echo '<th>Movimiento</th>';
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach ($stock_renglon as $rowStockRenglon) {
						echo '<tr>';
						echo '<td><a title="ver Articulo" class="btn btn-default btn-xs" href="'.base_url().'index.php/articulos/articulo_abm/edit/'.$rowStockRenglon->id_articulo.'">'.$rowStockRenglon->cod_proveedor.'</a></td>';
						echo '<td>'.$rowStockRenglon->descripcion.'</td>';
						echo '<td><a title="ver Stock" class="btn btn-default btn-xs" href="'.base_url().'index.php/stock/stockArticulo/'.$rowStockRenglon->id_articulo.'">'.$rowStockRenglon->cantidad.'</a></td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
					echo '</div>';

				} else {

				?>
			<div class="row">
				<div class="col-md-8">
					<div class="form-group">
						<label class="col-sm-2 control-label">Articulo:</label>
						<input class="form-control" type='text' placeholder="Cod o Detalle" name='articulo' value='' id='articulo'/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Cod:</label>
						<input class="form-control" type='text' name='cod_proveedor' id='cod_proveedor' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Venta:</label>
						<input class="form-control" type='number' name='precio_venta_iva' id='precio_venta_iva' disabled/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Costo:</label>
						<input class="form-control" type='number' name='precio_costo' id='precio_costo' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Margen:</label>
						<input class="form-control" type='number' name='margen' id='margen' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Iva:</label>
						<input class="form-control" type='number' name='iva' id='iva' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Impuesto:</label>
						<input class="form-control" type='number' name='impuesto' id='impuesto' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Descuento:</label>
						<input class="form-control" type='text' name='descuento' id='descuento' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Descuento2:</label>
						<input class="form-control" type='text' name='descuento2' id='descuento2' disabled/>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Cantidad:</label>
						<input class="form-control" type='number' name='cantidad' id='cantidad' value="0" disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipo:</label>
						<select class="form-control" name='tipo' id='tipo' disabled/>
							<option value="1">Movimiento</option>
							<option value="2">Total</option>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Stock:</label>
						<input class="form-control" type='number' name='stock' id='stock' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Minimo:</label>
						<input class="form-control" type='number' name='stock_minimo' id='stock_minimo' disabled/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Deseado:</label>
						<input class="form-control" type='number' name='stock_deseado' id='stock_deseado' disabled/>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label" style="opacity: -1;">T</label>
						<button onclick="carga(item_elegido)" type='button' id="carga_articulo" class="btn btn-default form-control"/>CARGAR ITEM</button>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-8">
					<div class="form-group">
						<label class="col-sm-2 control-label">Comentario:</label>
						<textarea class="form-control" rows="1" id="comentario" name="comentario"></textarea>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-sm-2 control-label" style="opacity: -1;">T</label>
						<button id="cont_boton" onclick="carga_presupuesto()" hidden="true" class="btn btn-primary form-control">CARGAR STOCK</button>
					</div>
				</div>
			</div>
			<hr>
			<div id="reglon_factura" class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-2 control-label">Detalle</label>
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
						<label class="col-sm-2 control-label">Costo</label>
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
						<label class="col-sm-2 control-label">Stock Actual</label>
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
						<label class="col-sm-2 control-label">Movimiento</label>
					</div>
				</div>
				<div class="col-md-1">
					<div class="form-group">
						<label class="col-sm-2 control-label">Stock Nuevo</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Eliminar</label>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</div>
<script>
var items_reglon= [];
var nuevo				= true;
var cantidad_r	= [];
var codigo_r		= [];
var costos_r	= [];

$(function() {
	$('#articulo').focus();

	$("#articulo").autocomplete({
	  source: "<?php echo base_url()?>index.php/articulos/searchArticulo",
	  minLength: 2,//search after two characters
	  select: function(event,ui) {
			llevar_stock = ui.item.llevar_stock;

			if(llevar_stock == 1){
				item_elegido = ui.item;
		    este = ui.item.id;
				stock = ui.item.stock;
				stock_minimo = ui.item.stock_minimo;
				stock_deseado = ui.item.stock_deseado;
				pos = items_reglon.indexOf(este);
				loadPrices(ui);
				setEnabledEditItems();
				$('#precio_costo').focus();
				$('#precio_costo').select();
				$('#stock').val(stock);
				$('#stock_minimo').val(stock_minimo);
				$('#stock_deseado').val(stock_deseado);

				if(pos!=-1){
		  		nuevo = false;
		  		cant_cargada = $('#cant_'+este).val();
					$('#cantidad').val(cant_cargada);
					$('#cantidad').select();
				}
			} else {
				alert('El articulo seleccionado no lleva stock');
			}
		},

		close: function( event, ui ) {
			$("#articulo").val('');
		}
	});

	$("#precio_costo").keypress(function( event ) {
		if (event.which == 13 ) {
			$('#cantidad').focus();
			$('#cantidad').select();
		}
	});

	$("#precio_costo").change(function( event ) {
		calculatePrices();
	});

	$("#cantidad").keypress(function( event ) {
		if ( event.which == 13 ) {
			cantidad = parseInt($("#cantidad").val());
			if(Number.isInteger(cantidad)){
				$("#carga_articulo").click();
				setDisabledEditItems();
			} else {
				$("#cantidad").val('');
				alert('por favor ingrese cantidad');
			}
		}
	});
});

function setEnabledEditItems(){
	$("#cantidad").removeAttr('disabled');
	$("#precio_costo").removeAttr('disabled');
	$("#tipo").removeAttr('disabled');
}

function setDisabledEditItems(){
	$("#cantidad").attr('disabled','disabled');
	$("#precio_costo").attr('disabled','disabled');
	$("#tipo").attr('disabled','disabled');
}

function loadPrices(ui){
	$('#cod_proveedor').val(ui.item.cod_proveedor);
	$('#precio_costo').val(ui.item.precio_costo);
	$('#iva').val(ui.item.iva);
	$('#impuesto').val(ui.item.impuesto);
	$('#margen').val(ui.item.margen);
	$('#descuento').val(ui.item.descuento);
	$('#descuento2').val(ui.item.descuento2);
	$('#precio_venta_iva').val(ui.item.precio_venta_iva);
}

function resetPrices(){
	$('#cod_proveedor').val('');
	$('#precio_costo').val('');
	$('#iva').val('');
	$('#impuesto').val('');
	$('#margen').val('');
	$('#descuento').val('');
	$('#descuento2').val('');
	$('#precio_venta_iva').val('');
}

function carga(elem) {
	var cantidad = parseFloat($('#cantidad').val());
	var tipo = parseFloat($('#tipo').val());
	var stock = parseFloat($('#stock').val());
	var precio_costo = parseFloat($('#precio_costo').val());
	if($("#articulo").val().length >= 1) {
		este = elem.id;
		if(nuevo){
			items_reglon.push(este);
		}
		agrega_a_reglon(este, elem.value, cantidad, tipo, stock, nuevo, precio_costo);
	}
	reset_item();
	resetPrices();
}

function agrega_a_reglon(este, texto, cantidad, tipo, stock, bandera, precio_costo) {
	if(bandera){
		if (tipo == 2){
			cantidad = cantidad - stock;
		}
		var newStock = stock + cantidad;
		largo	= $('#reglon_factura').height();
		largo	= largo + 30;
		//console.log("largo despúes:"+largo);

		$('#reglon_factura').height(largo);
		$('#reglon_factura').append('<div class="row">');
		$('#reglon_factura').append('<div id="cont_borra'+este+'" class="cont_reglon_item_presup row" style="padding-left: 15px"></div>');
		$('#cont_borra'+este).append('<span class="item_reglon col-md-6" id='+este+' >'+texto+'</span>');
		$('#cont_borra'+este).append('<input class="precio_costo_reglon" id=precio_costo_'+este+' value='+precio_costo+' type="hidden">');
		$('#cont_borra'+este).append('<input class="cant_item_reglon" id=cant_'+este+' value='+cantidad+' type="hidden">');
		$('#cont_borra'+este).append('<div class="col-md-1">'+precio_costo+'</div>');
		$('#cont_borra'+este).append('<div class="col-md-1">'+stock+'</div>');
		$('#cont_borra'+este).append('<div class="col-md-1">'+cantidad+'</div>');
		$('#cont_borra'+este).append('<div class="col-md-1">'+newStock+'</div>');
		$('#cont_borra'+este).append('<div class="col-md-2" id=cont_botones'+este+'></div>');
		$('#cont_botones'+este).append('<button title="Borrar linea" class="ico_borra btn btn-danger btn-xs pull-left" onclick="borra_reglon('+este+')" id="ico_borra'+este+'"></button>');
		$('#reglon_factura').append('</div><hr class="hrLine" id="hrLine'+este+'">');
		$('#ico_borra'+este).append('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>');
  } else {
		$('#cant_'+este).val(cantidad);
		nuevo = true;
	}

	reset_item();
	resetPrices();
}

function reset_item() {
    $("#articulo").val('');
		$("#cantidad").val(0);
		$("#stock").val('');
		$("#stock_minimo").val('');
		$("#stock_deseado").val('');
    $("#articulo").focus();
}

function carga_presupuesto() {
	if(items_reglon.length > 0){
		guarda_detalle();
		guarda_costos();
		fin_presupuesto();
	} else {
		alert("No se han cargado elementos");
	}
}

function guarda_detalle() {
	$('.cant_item_reglon').each(function (index) {
		cantidad_r.push($(this).val());
		temp_id		= this.id;
		cod_prod	= parseInt(temp_id.slice(5));
		codigo_r.push(cod_prod);
	});
}

function guarda_costos() {
	$('.precio_costo_reglon').each(function (index) {
			costos_r.push(parseFloat($(this).val()));
	});
}

function fin_presupuesto() {
	comentario	= $("#comentario").val();
	$.ajax({
		url : 'insertDetail',
		type: 'POST',
		data : {
			codigos_art:codigo_r,
			cantidades:cantidad_r,
			comentario:comentario,
			costos:costos_r
		}
  }).success( function( data ) {
		alert('Se guardaron correctamente los movimientos');
		location.reload();
	});
}

function borra_reglon(a) {
	pos = items_reglon.indexOf(a);
	items_reglon.splice( pos, 1 );

	$('#cont_borra'+a).empty();
	$('#cont_borra'+a).remove();
	$('#hrLine'+a).remove();
	var nuevo_largo = $('#reglon_factura').height();
	nuevo_largo = nuevo_largo - 30;
	$('#reglon_factura').height(nuevo_largo);
	$('#articulo').focus();
}

function calculatePrices(){
	var cod_proveedor = $('#cod_proveedor').val();
	var precio_costo = $('#precio_costo').val();
	var iva = $('#iva').val();
	var impuesto = $('#impuesto').val();
	var margen = $('#margen').val();
	var descuento = $('#descuento').val();
	var descuento2 = $('#descuento2').val();
	var margen = $('#margen').val();
	var precio_venta_iva = $('#precio_venta_iva').val();

	$.ajax({
    type:"POST",
    url:"<?php echo base_url()?>index.php/articulos/calculatePrices",
    data:{
			precio_costo:precio_costo,
			iva:iva,
			margen:margen,
			impuesto:impuesto,
			descuento:descuento,
			descuento2:descuento2,
		},
    success:function(datos){
				var precios = JSON.parse(datos);
				$('#precio_venta_iva').val(precios.precio_venta_iva);
     },
	 })
}

</script>
<style>
.hrLine{
	margin-top: 10px;
	margin-bottom: 10px;
}
