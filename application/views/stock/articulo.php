<?php
$config = [
	'1' => [
		'color' => 'bg-teal',
		'icon' => 'icon icon-user',
	],

	'2' => [
		'color' => 'bg-teal',
		'icon' => 'icon icon-shopping-cart',
	],

	'3' => [
		'color' => 'bg-olive',
		'icon' => 'icon icon-thumbs-down',
	],

	'4' => [
		'color' => 'bg-olive',
		'icon' => 'icon icon-trash',
	],
];
if ($articulo) {
	if($articulo[0]->stock >= $articulo[0]->stock_deseado){
		$stockColor = [
			'color' => 'bg-green',
			'icon' => 'icon icon-check',
			'msj' => 'Ok: mayor o igual al deseado',
		];
	} else if($articulo[0]->stock >= $articulo[0]->stock_minimo){
		$stockColor = [
			'color' => 'bg-orange',
			'icon' => 'icon icon-check-minus',
			'msj' => 'Advertencia: menor al deseado',
		];
	} else if($articulo[0]->stock < 0){
		$stockColor = [
			'color' => 'bg-red',
			'icon' => 'icon icon-warning-sign',
			'msj' => 'Error: menor a 0',
		];
	} else {
		$stockColor = [
			'color' => 'bg-red',
			'icon' => 'icon icon-eye-open',
			'msj' => 'Advertencia: menor al minimo',
		];
	}
}
?>
<div class="container">
<div class="col-md-12">
	<div class="panel panel-primary">
		<div class="panel-heading">Stock Articulo</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label class="col-sm-2 control-label">Codigo:</label>
						<input class="form-control" disabled value="<?php echo ($articulo) ? $articulo[0]->cod_proveedor : ''?>"/>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-2 control-label">Descripcion:</label>
						<input class="form-control" type='text' placeholder="Articulo" name='articulo' value="<?php echo ($articulo) ? $articulo[0]->descripcion : ''?>" id='articulo'/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="col-sm-2 control-label">Stock:</label>
						<input class="form-control" disabled value="<?php echo ($articulo) ? ($articulo[0]->llevar_stock) ? 'Si lleva stock' : 'No lleva stock' : '' ?>"/>
					</div>
				</div>
			</div>
			<?php if ($articulo) { ?>
			<div class="row">
				<div class="col-xs-12 col-sm-6 emphasis">
					<div class="small-box <?php echo $stockColor['color']?>">
					<!-- <div class="small-box <?php echo $articulo[0]->stock?>"> -->
						<div class="inner">
							<h3><?php echo $articulo[0]->stock?></h3>
							<p style="color:#fff;">STOCK</p>
							<div class="icon">
								<i class="<?php echo $stockColor['icon']?>"></i>
							</div>
						</div>
						<a href="" class="small-box-footer"><?php echo $stockColor['msj']?></a>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 emphasis">
					<div class="small-box bg-light-blue">
						<div class="inner">
							<h3><?php echo $articulo[0]->stock_deseado?></h3>
							<p style="color:#fff;">Stock Deseado</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 emphasis">
					<div class="small-box bg-light-blue">
						<div class="inner">
							<h3><?php echo $articulo[0]->stock_minimo?></h3>
							<p style="color:#fff;">Stock minimo</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php if ($stock) { ?>
					<?php foreach ($stock as $stockRow) { ?>
						<div class="col-xs-12 col-sm-3 emphasis">
							<div class="small-box <?php echo $config[$stockRow->id_comprobante]['color'] ?>">
								<div class="inner">
									<h3><?php echo $stockRow->cantidad ?></h3>
									<p style="color:#fff;"><?php echo $stockRow->comprobante ?></p>
									<div class="icon">
										<i class="<?php echo $config[$stockRow->id_comprobante]['icon'] ?>"></i>
									</div>
								</div>
								<a href="<?php echo base_url().'/index.php/stock/stockArticulo/'.$articulo[0]->id_articulo.'/'.$stockRow->id_comprobante?>" class="small-box-footer">
									Ver más <i class="fa fa-arrow-circle-right"></i>
								</a>
							</div>
						</div>
					<?php } ?>
				<?php } else{
					echo setMensaje('No hay movimientos');
				} ?>
			</div>
			<?php if($detail || $filter['filter']){
				switch ($id_comprobante) {
				  case COMPROBANTES::MANUAL:
						$type = 'Manual';
						$additional = 'Comentario';
						$href = base_url().'index.php/stock/crud/';
				    break;
				  case COMPROBANTES::PRESUPUESTO:
						$type = 'Presupuesto';
						$additional = 'Cliente';
						$href = base_url().'index.php/presupuestos/update/';
				    break;
				  case COMPROBANTES::DEVOLUCION:
						$type = 'Devolucion';
						$additional = 'Comentario';
						$href = base_url().'index.php/devoluciones/crud/read/';
				    break;
				  case COMPROBANTES::ANULACION:
						$type = 'Presupuesto Anulado';
						$additional = 'Cliente';
						$href = base_url().'index.php/presupuestos/update/';
						break;
				}
			?>
			<hr>
			<div class="row">
				<form method="post">
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control" id="filterDesde" autocomplete="off"  name="filterDesde" value="<?php echo $filter['desde'];?>" placeholder="Desde">
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input type="text" class="form-control" id="filterHasta" autocomplete="off" name="filterHasta" value="<?php echo $filter['hasta'];?>" placeholder="Hasta">
						</div>
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" id="filterNro" name="filterNro" value="<?php echo $filter['nro'];?>" placeholder="<?php echo $type?> nro">
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" id="filterAdditional" name="filterAdditional" value="<?php echo $filter['additional'];?>" placeholder="<?php echo $additional?>">
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary" name="filter" value="1">
							Filtrar
						</button>
						<a class="btn btn-default" href="<?php echo base_url().'index.php/stock/stockArticulo/'.$articulo[0]->id_articulo.'/'.$id_comprobante ?>">
							Limpiar filtros
						</a>
						<button class="btn btn-default" type="button" onclick="printDiv('printableArea')"/>
							<i class="fa fa-print"></i> Imprimir
						</button>
					</div>
				</form>
			</div>
  		<div  id="printableArea">
				<table id="table_detail" class="table" cellpadding="5" cellspacing="5" width="100%" style="border-collapse:collapse;">
					<thead>
					<tr>
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Nro</th>
						<th><?php echo $additional?></th>
						<th>Cantida</th>
					</tr>
					</thead>
					<tbody>
						<?php
						$total = 0;
						if ($detail) {
							foreach ($detail as $rowDetail) {
								$total += $rowDetail->cantidad;

								echo '<tr>';
								echo '<td class="td-center">'.date("d-m-Y", strtotime($rowDetail->fecha)).'</td>';
								echo '<td class="td-center">'.$type.'</td>';
								echo '<td class="td-center"><a class="btn btn-default btn-xs" href="'.$href.$rowDetail->nro.'">'.$rowDetail->nro.'</a></td>';
								echo '<td>'.$rowDetail->additional.'</td>';
								echo '<td class="td-center">'.$rowDetail->cantidad.'</td>';
								echo '</tr>';
							}

							echo '<tr>';
							echo '<th colspan="4">Total</th>';
							echo '<th class="td-center">'.$total.'</th>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
			</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>

<script>
$(function() {
	$("#articulo").autocomplete({
	  source: "<?php echo base_url()?>index.php/articulos/searchArticulo",
	  minLength: 2,//search after two characters
	  select: function(event,ui) {
			id_articulo = ui.item.id;
			window.location.replace("<?php echo base_url()?>index.php/stock/stockArticulo/"+id_articulo);
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

$(function() {
	$( "#filterHasta" ).datepicker({
		maxDate: '0',
		changeMonth: true,
				changeYear: true,
		dateFormat: 'dd-mm-yy',
		onClose: function( selectedDate ) {
			$( "#filterDesde" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});

$(function() {
	$( "#filterDesde" ).datepicker({
		maxDate: '0',
		changeMonth: true,
				changeYear: true,
		dateFormat: 'dd-mm-yy',
		onClose: function( selectedDate ) {
			$( "#filterHasta" ).datepicker( "option", "minDate", selectedDate );
		}
	});
});
$(document).ready(function() {
	//$('#table_facturas').DataTable();
});
</script>
