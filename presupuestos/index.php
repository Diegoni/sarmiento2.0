<?php
include_once('conexion.php');

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

<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type:application/json; charset=UTF-8" />
    <title>Bulones Sarmiento</title>
    <link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap.css" type="text/css" />
</head>
<body onload="inicializa()">

<!-- Primer Bloque -->

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            BULONES SARMIENTO
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#cabeceraModal" id="btnModalCabecera">
                Cabecera
            </button>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#comentarioModal">
                Comentario
            </button>
        </div>

<!-- Modal -->

	<div class="modal fade" id="comentarioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  		<div class="modal-dialog" role="document">
    		<div class="modal-content">
				<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        			<h4 class="modal-title" id="myModalLabel">Comentario</h4>
      			</div>
		      	<div class="modal-body">
		        	<textarea class="form-control" rows="3" id="comentario" name="comentario"></textarea>
		        	<div class="checkbox">
						<label>
							<input type="checkbox" value="" id="com_publico" name="com_publico">
					    	Publico
						</label>
					</div>
		      	</div>
      			<div class="modal-footer">
        			<button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_comentario">Cancelar</button>
        			<button type="button" class="btn btn-primary" data-dismiss="modal">Guardar</button>
      			</div>
    		</div>
  		</div>
	</div>

<!-- Labels -->

<div class="modal fade" id="cabeceraModal" tabindex="-1" role="dialog" aria-labelledby="myModalComentario">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalComentario">Cabecera</h4>
      </div>
      <div class="modal-body">
        <div class="row" id="cont_datos_buscador">
           <div class="form-group col-md-6 ">
             <label for="carga_cliente" class="control-label">Buscar</label>
             <div class="input-group">
               <input class="data_cliente form-control" type="text" id="carga_cliente" placeholder="Alias o Cuil/Cuit"/>
               <div class="input-group-addon" onclick="limpia_cli()" id="search" name="search">  <i class="glyphicon glyphicon-remove"></i></div>
             </div>
           </div>

           <div class="cont_rotulo_presupuesto form-group col-md-2">
             <label for="fecha_presupuesto" class="control-label">Fecha</label>
             <input class="data_presupuesto form-control" type="text" id="fecha_presupuesto" value=""/>
           </div>

           <div class="cont_rotulo_presupuesto form-group col-md-2">
             <label for="forma_pago" class="control-label">Forma de pago</label>
             <select class="form-control"  id="forma_pago" name="tipo">
               <option value="1">Contado</option>
               <option value="3">Tarjeta</option>
               <option value="2">Cta Cte</option>
             </select>
           </div>
           <div class="form-group col-sm-2">
             <label for="tipo_comprobante" class="control-label">Tipo</label>
             <select class="form-control"  id="tipo_comprobante" name="test">
               <option value="1">Presupuesto</option>
               <option value="2">Cae</option>
             </select>
           </div>
           <div class="row" id="cont_datos_presupuesto"></div><!-- Este me quedo vacio no lo borre por las dudas de que lo uses, revisa si no lo usas volalo -->
         </div>


     <!-- Datos del cliente -->
         <div class="row" id="cont_datos_cliente">
           <div class="form-group cont_rotulo_cliente col-md-3">
               <label for="email" class="col-sm-2 control-label">Nombre</label>
               <input class="data_cliente form-control" disabled type="text" id="nombre_cliente" value=""/>
           </div>
           <div class="form-group cont_rotulo_cliente col-md-3">
               <label for="email" class="col-sm-2 control-label">Apellido</label>
               <input class="data_cliente form-control" disabled type="text" id="apellido_cliente" value=""/>
           </div>
           <div class="form-group cont_rotulo_cliente col-md-3">
               <label for="email" class="col-sm-2 control-label">Domicilio</label>
               <input class="data_cliente form-control" disabled type="text" id="domicilio_cliente" value=""/>
           </div>
           <div class="form-group cont_rotulo_cliente col-md-3">
               <label for="email" class="col-sm-2 control-label">Cuil/Cuit</label>
               <input class="data_cliente form-control" type="text" disabled id="cuit_cliente" value=""/>
           </div>
           <input type="hidden" type="text"  id="pertmite_cta_cte" value="0"/>
           <input type="hidden" type="text"  id="monto_max_presupuesto" value="0"/>
           <input type="hidden" type="text"  id="id_cliente" value="0"/>
         </div>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_cabecera">Cancelar</button>
         <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardar_cabecera">Guardar</button>
       </div>
     </div>
   </div>
  </div>

<!-- Segundo bloque carga de articulos -->

    <div class="panel panel-default">
        <div class="panel-body">
            <div id="cont_busqueda_articulo">
                <div id="cont_busca">
                <form  action='' method='post'>
                    <div class="row">
                        <p>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">BUSCAR:</label>
                                    <input class="form-control" type='text' placeholder="Cod o Detalle" name='country' value='' id='quickfind'/>
                                    <!--<input class="form-control" type='text' placeholder="Busqueda x Codigo" name='country' value='' id='quickfind_cod'/>
                                --></div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Precio</label>
                                    <input class="form-control" id="px_unitario_rapido" readonly="true"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cantidad:</label>
                                    <input class="form-control" type='number' name='cantidad' value='<?php echo $cantidad_inicial?>' id='cantidad'/>
                                    <p><input onclick="carga(item_elegido)" type='button' id="carga_articulo" hidden="hidden"/></p>
                                </div>
                            </div>
                        </p>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div> <!-- panel panel-default-->

<!-- Segundo bloque carga de articulos -->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-body">
                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 control-label">TOTAL</label>
                        <label for="inputEmail3" class="col-sm-2 control-label">Total iva</label>
                        <label for="inputEmail3" class="col-sm-2 control-label">%Desc.</label>
                        <label for="inputEmail3" class="col-sm-2 control-label">Vendedor</label>
                    </div>
                    <div id="totales_de_factura" class="row">
                        <div id="cont_fac" class="col-sm-3">
                            <input type='number' class='form-control' disabled value='0' id='total_presupuesto'style="background-color: #5cb85c; color: #fff;"/>
                        </div>
                        <div class="col-sm-2">
                            <input type='number'  disabled value='0' id='total_iva' class='form-control'/>
                        </div>
                        <div class="col-sm-2">
                            <input onchange="descuento()" type='number' autocomplete="off" value='0' disabled="disabled" id='descuento' min="0" max="100" class='form-control'/>
                        </div>

                        <div class="col-sm-2">
                            <select name="vendedor" id="vendedor" class="form-control" autocomplete onchange="$('#quickfind').focus()">
                            <?php echo $optionVendedores;?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button id="cont_boton" onclick="carga_presupuesto()" hidden="true" class="btn btn-primary form-control">CARGAR PRESUPUESTO</button>
                        </div>
                    </div>
                    <hr>
                    <div id="reglon_factura" class="row">
                        <span class="titulo_item_reglon col-sm-5"><b>DETALLE</b></span>
                        <span class="titulo_cant_item_reglon col-sm-1"><b>CANT</b></span>
                        <span class="titulo_px_item_reglon col-sm-1"><b>P.U </b></span>
                        <span class="titulo_px_item_reglon col-sm-1"><b>IVA</b></span>
                        <span class="col-sm-1"><b>% IVA</b></span>
                        <span class="titulo_px_reglon col-sm-1"><b>SUBTOTAL</b></span>
                        <span class="col-sm-1"></span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- class="row" -->
</div> <!-- class="container" -->

<!-- Carga de librerias -->

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/buscador.js"></script>
<script type="text/javascript" src="librerias/bootstrap/js/bootstrap.js"></script>

<script>
$(document).ready(function(){
    $(".slidingDiv").hide();
    $(".show_hide").show();

    $('.show_hide').click(function(){
        $(".slidingDiv").slideToggle();
    });
});

$(document).ready(function(){
	 $("#cancel_comentario").click(function() {
  		 $('#comentario').val('');
	});
});
</script>
<style>
.ui-widget-content{
  z-index: 9000;
}
#search:hover{
  cursor: pointer;
  background-color: #d9534f;
  color: #fff;
}
.ico_borra{
  padding: 5px;
}

</style>


<!-- Carga devoluciones ? -->


    <div id="devoluciones" style="display:none">
        <div class="row cabecera">
            <div class="col-xs-12 cabecera">
                <span class="col-xs-1">Devolucion</span>
                <span class="col-xs-2">Fecha</span>
                <span class="col-xs-1">Monto</span>
                <span class="col-xs-1">A cuenta</span>
                <span class="col-xs-4">Nota</span>
                <span class="col-xs-3">Accion</span>
            </div>
            <div id="reglon_devoluciones" class="col-xs-12">
            </div>
        </div>
    </div>
</body>
</html>
