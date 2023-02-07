<!-- imprimir -->
<?php
include_once('config/conexion.php');
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
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $tbody = '';
          if($result_presupuestos) {
            while ($row_presupuesto = $result_presupuestos->fetch_array(MYSQLI_ASSOC)) {
              $disabledMail = ($row_presupuesto['mail'] == '') ? 'disabled' : '';
              $textMail = ($row_presupuesto['mail'] == '') ? 'Completar mail' : 'Enviar mail a :'.$row_presupuesto['mail'];
              $tbody .= "<tr>";
              $tbody .= "<td>".$row_presupuesto['id_presupuesto']."</td>";
              $tbody .= "<td>". date("d-m-Y H:i", strtotime($row_presupuesto['fecha']))."</td>";
              $tbody .= "<td>$ ".round($row_presupuesto['monto'], 3)."</td>";
              $tbody .= "<td>$ ".round($row_presupuesto['descuento'], 3)."</td>";
              $tbody .= "<td>".$row_presupuesto['alias']." - ".$row_presupuesto['apellido'].", ".$row_presupuesto['nombre']."</td>";
              $tbody .= "<td>
                <button title='Imprimir' type='button' class='btn btn-default' onclick='imprimir(".$row_presupuesto['id_presupuesto'].")'><span class='glyphicon glyphicon-print' aria-hidden='true'></span></button>
                <button title='".$textMail."' type='button' class='btn btn-default' onclick='mail(".$row_presupuesto['id_presupuesto'].")' ".$disabledMail."><span class='glyphicon glyphicon-envelope' aria-hidden='true'></span></button>
              </td>";
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
  var url = 'http://bulonessarmiento.com/gestion/index.php/presupuestos/setPDF/'+id_presupuesto;
  window.open(url, '_blank');
}


function mail(id_presupuesto){
  $.ajax({
    type:"POST",
    url:'http://bulonessarmiento.com/gestion/index.php/mails/enviar/'+id_presupuesto,
    data:{},
    success:function(datos){
      alert("Correo enviado");
     },
  })
}
</script>
<style>
#imprimirTable td{
  font-size: 12px;
  cursor: pointer;
}
</style>
