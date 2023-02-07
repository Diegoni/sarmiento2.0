
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" type="text/javascript" charset="utf-8"></script>

<script>
$(document).ready(function() {
	$('#table_presupuestos').DataTable({
        order: [[0, 'desc']],
		language: {
			"decimal": "",
			"emptyTable": "No hay información",
			"info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
			"infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
			"infoFiltered": "(Filtrado de _MAX_ total entradas)",
			"infoPostFix": "",
			"thousands": ",",
			"lengthMenu": "Mostrar _MENU_ Entradas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"search": "Buscar:",
			"zeroRecords": "Sin resultados encontrados",
			"paginate": {
				"first": "Primero",
				"last": "Ultimo",
				"next": "Siguiente",
				"previous": "Anterior"
			}
		},
		dom: 'Bfrtip',
        buttons: [
			{
				extend: 'print',
				text: 'Imprimir',
				autoPrint: true
        	},'excel', 'pdf'
        ]
    });
	$('#table_remitos').DataTable({
        order: [[0, 'desc']],
		language: {
			"decimal": "",
			"emptyTable": "No hay información",
			"info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
			"infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
			"infoFiltered": "(Filtrado de _MAX_ total entradas)",
			"infoPostFix": "",
			"thousands": ",",
			"lengthMenu": "Mostrar _MENU_ Entradas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"search": "Buscar:",
			"zeroRecords": "Sin resultados encontrados",
			"paginate": {
				"first": "Primero",
				"last": "Ultimo",
				"next": "Siguiente",
				"previous": "Anterior"
			}
		},
		dom: 'Bfrtip',
        buttons: [
			{
				extend: 'print',
				text: 'Imprimir',
				autoPrint: true
        	},'excel', 'pdf'
        ]
    });
	$('#table_devoluciones').DataTable({
        order: [[0, 'desc']],
		language: {
			"decimal": "",
			"emptyTable": "No hay información",
			"info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
			"infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
			"infoFiltered": "(Filtrado de _MAX_ total entradas)",
			"infoPostFix": "",
			"thousands": ",",
			"lengthMenu": "Mostrar _MENU_ Entradas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"search": "Buscar:",
			"zeroRecords": "Sin resultados encontrados",
			"paginate": {
				"first": "Primero",
				"last": "Ultimo",
				"next": "Siguiente",
				"previous": "Anterior"
			}
   	 	},
		dom: 'Bfrtip',
        buttons: [
			{
				extend: 'print',
				text: 'Imprimir',
				autoPrint: true
        	},'excel', 'pdf'
        ]
    });
	$('#table_resumen').DataTable({
        order: [[0, 'asc']],
		language: {
			"decimal": "",
			"emptyTable": "No hay información",
			"info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
			"infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
			"infoFiltered": "(Filtrado de _MAX_ total entradas)",
			"infoPostFix": "",
			"thousands": ",",
			"lengthMenu": "Mostrar _MENU_ Entradas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"search": "Buscar:",
			"zeroRecords": "Sin resultados encontrados",
			"paginate": {
				"first": "Primero",
				"last": "Ultimo",
				"next": "Siguiente",
				"previous": "Anterior"
			}
		},
		dom: 'Bfrtip',
        buttons: [
			{
				extend: 'print',
				text: 'Imprimir',
				autoPrint: true
        	},'excel', 'pdf'
        ]
    });
} );
</script>
<?php
$tableResumenFinal = [];
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
			<div class="panel-heading">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">Cliente</a></li>
	    			<li><a href="#tab2" data-toggle="tab">Presupuestos</a></li>
	    			<li><a href="#tab3" data-toggle="tab">Remitos</a></li>
	    			<li><a href="#tab4" data-toggle="tab">Devoluciones</a></li>
					<li><a href="#tab5" data-toggle="tab">Resumen</a></li>
					
				</ul>
			</div>
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane" id="tab2">
						<?php

						$total_p_contado = 0;
						$total_p_tarjeta = 0;
						$total_p_ctacte = 0;
						$total_p_cuenta = 0;
						$total_p_anulado = 0;

						if($presupuestos)
						{

							echo '<table class="table table-hover" id="table_presupuestos">';
							echo '<thead>';
							echo '<tr>';
								echo '<th>Nro</th>';
								echo '<th>Fecha</th>';
								echo '<th>Monto</th>';
								echo '<th>A Cuenta</th>';
								echo '<th>Tipo</th>';
								echo '<th>Estado</th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';

							foreach ($presupuestos as $row)
							{
								
								if($row->id_estado == 3){
									$total_p_anulado += $row->monto;
								}else if($row->id_tipo == 1 || $row->id_tipo == 3)
								{
									$row->a_cuenta = $row->monto;
									$total_p_contado = $total_p_contado + $row->monto;
								}
								else
								if($row->id_tipo == 2)
								{
									$total_p_ctacte = $total_p_ctacte + $row->monto;
									$total_p_cuenta = $total_p_cuenta + $row->a_cuenta;
								}
								else
								{
									$total_p_tarjeta = $total_p_tarjeta + $row->monto;
								}

								echo '<tr>';
								echo '<td>'.$row->id_presupuesto.'</td>';
								echo '<td>'.date('d-m-Y', strtotime($row->fecha)).'</td>';
								echo '<td class="success">$ '.round($row->monto, 2).'</td>';
								echo '<td>$ '.round($row->a_cuenta, 2).'</td>';
								echo '<td>'.$row->tipo.'</td>';
								echo '<td>'.$row->estado.'</td>';
								echo '</tr>';

								$tableResumenFinal[] = [
									'id_tipo' => 1,
									'tipo'	=> 'Presupuesto '.$row->tipo,
									'id'	=> $row->id_presupuesto,
									'fecha' => $row->fecha,
									'estado' => $row->estado,
									'monto'	=> $row->monto,
								];
							}

							echo '<tbody>';
							echo '</table>';

						}
						?>
					</div>
					<div class="tab-pane" id="tab3">
						<?php
						if($remitos)
						{
							$total_r_resta = 0;
							$total_r_monto = 0;
							$total_r_cuenta = 0;

							echo '<table class="table table-hover" id="table_remitos">';
							echo '<thead>';
							echo '<tr>';
								echo '<th>Nro</th>';
								echo '<th>Fecha</th>';
								echo '<th>Monto</th>';
								echo '<th>Devolución</th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';

							foreach ($remitos as $row)
							{
								echo '<tr>';
									echo '<td>'.$row->id_remito.'</td>';
									echo '<td>'.date('d-m-Y', strtotime($row->fecha)).'</td>';
									echo '<td class="success">$ '.round($row->monto, 2).'</td>';
									echo '<td>$ '.round($row->devolucion, 2).'</td>';
								echo '</tr>';

								$resta = $row->monto - $row->devolucion;

								$total_r_resta = $total_r_resta + $resta;
								$total_r_monto = $total_r_monto + $row->monto;
								$total_r_cuenta = $total_r_cuenta + $row->devolucion;

								$tableResumenFinal[] = [
									'id_tipo' => 2,
									'tipo'	=> 'Remito',
									'id'	=> $row->id_remito,
									'fecha' => $row->fecha,
									'monto'	=> $row->monto,
									'estado' => '',
								];
							}

							echo '<tbody>';
							echo '</table>';
						}
						?>
					</div>
					<div class="tab-pane" id="tab4">
						<?php
						if($devoluciones)
						{
							$total_d_resta = 0;
							$total_d_monto = 0;
							$total_d_cuenta = 0;

							echo '<table class="table table-hover" id="table_devoluciones">';
							echo '<thead>';
							echo '<tr>';
								echo '<th>Nro</th>';
								echo '<th>Pre.</th>';
								echo '<th>Fecha</th>';
								echo '<th>Monto</th>';
								echo '<th>A cuenta</th>';
								echo '<th>Nota</th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';

							foreach ($devoluciones as $row)
							{
								echo '<tr>';
									echo '<td>'.$row->id_devolucion.'</td>';
									echo '<td>'.$row->id_presupuesto.'</td>';
									echo '<td>'.date('d-m-Y', strtotime($row->fecha)).'</td>';
									echo '<td class="success">$ '.round($row->monto, 2).'</td>';
									echo '<td>$ '.round($row->a_cuenta, 2).'</td>';
									echo '<td>'.$row->nota.'</td>';
								echo '</tr>';

								$resta = $row->monto - $row->a_cuenta;

								$total_d_resta = $total_d_resta + $resta;
								$total_d_monto = $total_d_monto + $row->monto;
								$total_d_cuenta = $total_d_cuenta + $row->a_cuenta;

								$tableResumenFinal[] = [
									'id_tipo' => 3,
									'tipo'	=> 'Devolucion',
									'id'	=> $row->id_devolucion,
									'fecha' => $row->fecha,
									'monto'	=> $row->monto,
									'estado' => $row->nota,
								];
							}

							echo '<tbody>';
							echo '</table>';
						}
						?>
					</div>
					<div class="tab-pane active" id="tab1">
						<?php
						if($clientes)
						{
							$total_vendido = $total_p_contado + $total_p_tarjeta + $total_p_ctacte;
							$total_cobrado = $total_p_contado + $total_p_tarjeta + $total_p_cuenta;
							$deuda = $total_vendido - $total_cobrado - $total_d_monto;

							foreach ($clientes as $row)
							{

						?>
						<div class="col-md-12 well">
    	 					<div class="profile">
            					<div class="col-sm-12">
            					<h2><?php echo $row->nombre.' '.$row->apellido.' - '.$row->alias ?></h2>
                    			</div>
                				<div class="col-sm-6">
                    				<p><strong>Dirección: </strong><?php echo $row->direccion ?></p>
                    				<p><strong>Teléfono: </strong><?php echo $row->telefono ?></p>
				                    <p><strong>Celular: </strong><?php echo $row->celular ?></p>
				                	<p><strong>Mail: </strong><?php echo $row->mail ?></p>
                    			</div>
                				<div class="col-sm-6">
                    				<p><strong>Cuil: </strong><?php echo $row->cuil ?></p>
                    				<p><strong>Condición: </strong><?php echo $row->id_condicion_iva ?></p>
				                    <p><strong>Nota: </strong><?php echo $row->comentario ?></p>
				                </div>
            				</div>
						</div>

						<?php if($fechaDesde == '2015-01-01'){ ?>
							<div class="col-xs-12 divider text-center">
							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        $ <?php echo $total_vendido ?>
                                    </h3>
                                </div>
                                <a href="#" class="small-box-footer">
                                   VENDIDO
                                </a>
                            	</div>
							</div>

							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        $ <?php echo $total_cobrado ?>
                                    </h3>
                                </div>
                                <a href="#" class="small-box-footer">
                                   COBRADO
                                </a>
                            	</div>
							</div>

							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        $ <?php echo $deuda ?>
                                    </h3>
                                </div>
                                <a href="#" class="small-box-footer">
                                   DEUDA
                                </a>
                            	</div>
							</div>
            			</div>

            			<div class="col-xs-12 divider text-center">
                			<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-blue">
                                <div class="inner">
                                    <h4>
                                        $ <?php echo $total_p_contado ?>
                                    </h4>
                                </div>
                                <a href="#" class="small-box-footer">
                                   CONTADO
                                </a>
                            	</div>
							</div>

							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-maroon">
                                <div class="inner">
                                    <h4>
                                        $ <?php echo $total_p_tarjeta ?>
                                    </h4>
                                </div>
                                <a href="#" class="small-box-footer">
                                   TARJETA
                                </a>
                            	</div>
							</div>

							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-olive">
                                <div class="inner">
                                    <h4>
                                        $ <?php echo $total_p_ctacte ?>
                                    </h4>
                                </div>
                                <a href="#" class="small-box-footer">
                                   CUENTA CORRIENTE
                                </a>
                            	</div>
							</div>

							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-orange">
                                <div class="inner">
                                    <h4>
                                        $ <?php echo $total_p_anulado ?>
                                    </h4>
                                </div>
                                <a href="#" class="small-box-footer">
                                	ANULADO
                                </a>
                            	</div>
							</div>

							<div class="col-xs-12 col-sm-4 emphasis">
								<div class="small-box bg-danger">
                                <div class="inner">
                                    <h4>
                                        $ <?php echo $total_d_monto ?>
                                    </h4>
                                </div>
                                <a href="#" class="small-box-footer">
                                	DEVOLUCION
                                </a>
                            	</div>
							</div>
						<?php 	
						} 
						?>
            			</div>
    	 			</div>
					<div class="tab-pane" id="tab5">
					<form method="post">
						<div class="col-md-4">
							<label>Fecha Desde</label>
						</div>
						<div class="col-md-4">
							<input type="date" name="fechaDesde" id="fechaDesde" value="<?php echo $fechaDesde; ?>" max="<?php echo date("Y-m-d"); ?>" class="form-control" />
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-primary form-control"> Cambiar </button>
						</div>
					</form>	
					
					<?php
						if(count($tableResumenFinal))
						{
							echo '<table class="table table-hover" id="table_resumen">';
							echo '<thead>';
							echo '<tr>';
								echo '<th>Fecha</th>';
								echo '<th>Numero</th>';
								echo '<th>Tipo</th>';
								echo '<th>Estado</th>';
								echo '<th>Monto</th>';
								echo '<th>Saldo</th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';

							$saldo = 0;

							foreach ($tableResumenFinal as $row)
							{
								$monto = ($row['monto'] > 0) ? '$ '.round($row['monto'], 2) : '';
							
								if($row['tipo'] == 'Presupuesto Cta Cte' ){
									$saldo += $row['monto'];
								}

								if($row['tipo'] == 'Remito' || $row['tipo'] == 'Devolucion' ){
									$saldo -= $row['monto'];
								}

								echo '<tr>';
									echo '<td>'.date('Y-m-d', strtotime($row['fecha'])).'</td>';
									echo '<td>'.$row['id'].'</td>';
									echo '<td>'.$row['tipo'].'</td>';
									echo '<td>'.$row['estado'].'</td>';
									echo '<td>'.$monto.'</td>';
									echo '<td>$ '.round($saldo, 2).'</td>';
								echo '</tr>';
							}

							echo '<tbody>';
							echo '</table>';
						}
						?>
					</div>
				</div>
			</div>
			<?php
				}
			}
			?>
			</div>
		</div>
	</div>
</div>
