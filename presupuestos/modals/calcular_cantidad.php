<!-- calcular cantidad -->
<div class="modal fade" id="calcularCantidadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Calcular Cantidad</h4>
          </div>
          <div class="modal-body">
            <div class="row" id="cont_datos_cliente">
              <div class="form-group col-md-6">
                  <label for="calcular-cantidad" class="col-sm-6 control-label">Sub total</label>
                  <input class="form-control" type="number" id="input-calcular-cantidad" value=""/>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_comentario">Cancelar</button>
            <button type="button" class="btn btn-primary"  id="btnCalcularPrecioAccion">Calcular</button>
          </div>
      </div>
    </div>
</div>
