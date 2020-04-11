<?php
$sql = "
SELECT
  *
FROM
  `presupuesto`
INNER JOIN
  cliente ON (presupuesto.id_cliente = cliente.id_cliente)
ORDER BY
  `id_presupuesto` DESC
LIMIT
  10";

$result_presupuestos = $conn->query($sql) ;
$version = explode("/", $_SERVER["REQUEST_URI"]);
?>


<div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Presupuestos</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover" id="imprimirTable">
          <thead>
            <tr>
              <th>Número</th>
              <th>Fecha</th>
              <th>Monto</th>
              <th>Descuento</th>
              <th>Cliente</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $tbody = '';
          if($result_presupuestos) {
            while ($row_presupuesto = $result_presupuestos->fetch_array(MYSQLI_ASSOC)) {
              $tbody .= "<tr onclick='imprimir(".$row_presupuesto['id_presupuesto'].")'>";
              $tbody .= "<td>".$row_presupuesto['id_presupuesto']."</td>";
              $tbody .= "<td>". date("d-m H:i", strtotime($row_presupuesto['fecha']))."</td>";
              $tbody .= "<td>".round($row_presupuesto['monto'], 3)."</td>";
              $tbody .= "<td>".round($row_presupuesto['descuento'], 3)."</td>";
              $tbody .= "<td>".$row_presupuesto['alias']." - ".$row_presupuesto['apellido'].", ".$row_presupuesto['nombre']."</td>";
              $tbody .= "</tr>";
            }
          }
          echo $tbody;
          ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_comentario">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
function imprimir(id_presupuesto){
  var host = '<?php echo $_SERVER["HTTP_HOST"] ?>';
  var version = '<?php echo $version[1];?>';
  var url = 'http://'+host+'/'+version+'/index.php/presupuestos/setPDF/'+id_presupuesto;
  window.open(url, '_blank');
}
</script>
<style>
#imprimirTable td{
  cursor: pointer;
}
</style>
