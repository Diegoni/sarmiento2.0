
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js" type="text/javascript" charset="utf-8"></script>

<script>
$(document).ready(function() {
	$('#table_resumen').DataTable({
        order: [[1, 'desc']],
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
<div class="col-md-12">
	<div class="panel panel-primary">
		<div class="panel-heading">Estado de cuenta</div>
		<div class="panel-body">
		<?php
			if(count($estado_cuentas))
			{
				echo '<table class="table table-hover" id="table_resumen">';
				echo '<thead>';
				echo '<tr>';
					echo '<th>Alias</th>';
					echo '<th>Deuda</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';

				foreach ($estado_cuentas as $cliente_id => $row)
				{
					echo '<tr>';
						echo '<td><a href="'.base_url().'index.php/clientes/resumen/'.$cliente_id.'" target="_blank">'.$row['alias'].'</a></td>';
						echo '<td>$ '.round($row['deuda'], 2).'</td>';
					echo '</tr>';
				}

				echo '<tbody>';
				echo '</table>';
			}
			?>
		</div>
	</div>
</div>
