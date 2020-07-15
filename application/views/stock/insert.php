<div class="container">
<div class="col-md-12">
	<div class="panel panel-primary">
		<div class="panel-heading">Movimientos Stock</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-2 control-label">Articulo:</label>
						<input class="form-control" type='text' placeholder="Cod o Detalle" name='articulo' value='' id='articulo'/>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipo:</label>
						<select class="form-control" name='tipo' id='tipo'/>
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
						<label class="col-sm-2 control-label">Cantidad:</label>
						<input class="form-control" type='number' name='cantidad' id='cantidad' disabled/>
						<input onclick="carga(item_elegido)" type='button' id="carga_articulo" hidden="hidden"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-2 control-label">Comentario:</label>
						<textarea class="form-control" rows="1" id="comentario" name="comentario"></textarea>
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
						<button id="cont_boton" onclick="carga_presupuesto()" hidden="true" class="btn btn-primary form-control">CARGAR STOCK</button>
					</div>
				</div>
			</div>
			<hr>
			<div id="reglon_factura" class="row">
				<div class="col-md-7">
					<div class="form-group">
						<label class="col-sm-2 control-label">Detalle</label>
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
		</div>
	</div>
</div>
<script>
var items_reglon= [];
var nuevo				= true;
var cantidad_r	= [];
var codigo_r		= [];


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
				$("#cantidad").removeAttr('disabled');
				$('#cantidad').focus();
				$('#cantidad').select();
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

	$("#cantidad").keypress(function( event ) {
		if ( event.which == 13 ) {
			cantidad = parseInt($("#cantidad").val());
			if(Number.isInteger(cantidad)){
				$("#carga_articulo").click();
				$("#cantidad").attr('disabled','disabled');
			} else {
				$("#cantidad").val('');
				alert('por favor ingrese cantidad');
			}
		}
	});
});

function carga(elem) {
	cantidad = parseFloat($('#cantidad').val());
	tipo = parseFloat($('#tipo').val());
	stock = parseFloat($('#stock').val());
	if($("#articulo").val().length >= 1) {
		este = elem.id;
		if(nuevo){
			items_reglon.push(este);
		}
		agrega_a_reglon(este, elem.value, cantidad, tipo, stock, nuevo);
		reset_item();
	} else {
		reset_item();
	}
}

function agrega_a_reglon(este, texto, cantidad, tipo, stock, bandera) {
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
		$('#cont_borra'+este).append('<span class="item_reglon col-md-7" id='+este+' >'+texto+'</span>');
		$('#cont_borra'+este).append('<div class="col-md-1">'+stock+'</div>');
		$('#cont_borra'+este).append('<input class="cant_item_reglon" id=cant_'+este+' value='+cantidad+' type="hidden">');
		$('#cont_borra'+este).append('<div class="col-md-1">'+cantidad+'</div>');
		$('#cont_borra'+este).append('<div class="col-md-1">'+newStock+'</div>');
		$('#cont_borra'+este).append('<div class="col-md-2" id=cont_botones'+este+'></div>');
		$('#cont_botones'+este).append('<button title="Borrar linea" class="ico_borra btn btn-danger btn-xs pull-left" onclick="borra_reglon('+este+')" id="ico_borra'+este+'"></button>');
		$('#reglon_factura').append('</div><hr class="hrLine">');
		$('#ico_borra'+este).append('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>');

		reset_item();
  } else {
		$('#cant_'+este).val(cantidad);
		nuevo = true;
		reset_item();
	}
}

function reset_item() {
    $("#articulo").val('');
		$("#cantidad").val('');
		$("#stock").val('');
		$("#stock_minimo").val('');
		$("#stock_deseado").val('');
    $("#articulo").focus();
}



/*---------------------------------------------------------------------------------
-----------------------------------------------------------------------------------

		Realiza el control del presupuesto

-----------------------------------------------------------------------------------
---------------------------------------------------------------------------------*/


function carga_presupuesto() {
	if(items_reglon.length > 0){
		guarda_detalle();
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



function fin_presupuesto() {
	comentario	= $("#comentario").val();
	$.ajax({
		url : 'insertDetail',
		type: 'POST',
		data : {
			codigos_art:codigo_r,
			cantidades:cantidad_r,
			comentario:comentario
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
	var nuevo_largo = $('#reglon_factura').height();
	nuevo_largo = nuevo_largo - 30;
	$('#reglon_factura').height(nuevo_largo);
}


</script>
<style>
.hrLine{
	margin-top: 10px;
	margin-bottom: 10px;
}
