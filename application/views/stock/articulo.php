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

if($articulo[0]->stock >= $articulo[0]->stock_deseado){
	$stockColor = [
		'color' => 'bg-green',
		'icon' => 'icon icon-check',
		'msj' => 'Ok: mayor al deseado',
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
						<input class="form-control" disabled value="<?php echo $articulo[0]->cod_proveedor?>"/>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-2 control-label">Descripcion:</label>
						<input class="form-control" type='text' placeholder="Articulo" name='articulo' value="<?php echo $articulo[0]->descripcion?>" id='articulo'/>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="col-sm-2 control-label">Stock:</label>
						<input class="form-control" disabled value="<?php echo ($articulo[0]->llevar_stock) ? 'Si lleva stock' : 'No lleva stock' ?>"/>
					</div>
				</div>
			</div>
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
						<a href="" class="small-box-footer">Debajo de 0</a>
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
</script>
