<div class="col-md-6">
	<div class="form-group">
		<label class="col-sm-2 control-label">Articulo:</label>
		<input class="form-control" type='text' placeholder="Cod o Detalle" name='articulo' value='' id='articulo'/>
	</div>
</div>
<div class="col-md-2">
<div class="form-group">
		<label class="col-sm-2 control-label">Cantidad:</label>
		<input class="form-control" type='number' name='cantidad' value='<?php echo $cantidad_inicial?>' id='cantidad'/>
		<p><input onclick="carga(item_elegido)" type='button' id="carga_articulo" hidden="hidden"/></p>
	</div>
</div>

<div id="reglon_factura" class="row">
		<span class="titulo_item_reglon col-sm-6"><b>DETALLE</b></span>
		<span class="titulo_cant_item_reglon col-sm-2"><b>CANT</b></span>
		<span class="titulo_px_item_reglon col-sm-2"><b>P.U </b></span>
		<span class="col-sm-2"></span>
</div>
<script>
var items_reglon=Array();
var nuevo	= true;

$(function() {
	$("#articulo").autocomplete({
	  source: "search_articulo",
	  minLength: 2,//search after two characters
	  select: function(event,ui){
			item_elegido = ui.item;
	    este = ui.item.id;
			pos = items_reglon.indexOf(este);
			$("#cantidad").removeAttr('disabled');
			$('#cantidad').focus();
			$('#cantidad').select();

			if(pos!=-1){
	  		nuevo=false;
	  		//console.log("modifica:",este);
	      cant_cargada=$('#cant_'+este).val();
	      //console.log(cant_cargada);
	      $('#cantidad').val(cant_cargada);
				$('#cantidad').select();
			}
		},

		close: function( event, ui ) {
			$("#articulo").val('');
		}
	});

	$("#cantidad").keypress(function( event ) {
		if ( event.which == 13 ) {
			$("#carga_articulo").click();
			$("#cantidad").attr('disabled','disabled');
		}
	});
});

function carga(elem) {
	cantidad = $('#cantidad').val();
	if($("#articulo").val().length >= 1) {
		este = elem.id;
		if(nuevo){
			items_reglon.push(este);
		}
		agrega_a_reglon(este,elem.value,cantidad,nuevo);
		reset_item();
	} else {
		reset_item();
	}
}

function agrega_a_reglon(este,texto,cantidad,bandera) {
	if(bandera){
		largo	= $('#reglon_factura').height();

		largo	= largo + 30;
		//console.log("largo despúes:"+largo);

		$('#reglon_factura').height(largo);
		$('#reglon_factura').append('<div id="cont_borra'+este+'" class="cont_reglon_item_presup row" style="padding-left: 15px"></div>');
		$('#cont_borra'+este).append('<span class="item_reglon col-md-6" id='+este+' >'+texto+'</span>');
		$('#cont_borra'+este).append('<span class="cant_item_reglon col-md-2" id=cant_'+este+' >'+cantidad+'</span>');
		$('#cont_borra'+este).append('<div class="col-md-2" id=cont_botones'+este+'></div>');
		$('#cont_botones'+este).append('<button title="Borrar linea" class="ico_borra btn btn-danger btn-xs pull-left" onclick="borra_reglon('+este+')" id="ico_borra'+este+'"></button>');
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
    $("#articulo").focus();
    $('#cantidad').val(1);
}


</script>
