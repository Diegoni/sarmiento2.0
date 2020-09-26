<?php
include_once('config/conexion.php');
include_once('config/values.php');
?>

<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type:application/json; charset=UTF-8" />
    <title>Bulones Sarmiento</title>
    <link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="css/main.css" type="text/css" />
</head>
<body onload="inicializa()">


<div class="container">
  <!-- Primer Bloque -->
  <div class="panel panel-default">
    <div class="panel-heading">
      BULONES SARMIENTO
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#cabeceraModal" id="btnModalCabecera">Cabecera</button>
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#comentarioModal">Comentario</button>
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#imprimirModal">Imprimir</button>
    </div>
  </div>

<!-- Modals -->
<?php include_once('modals/comentario.php'); ?>
<?php include_once('modals/cabecera.php') ?>
<?php include_once('modals/imprimir.php') ?>

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

</body>
</html>
