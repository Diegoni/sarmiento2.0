<!-- cabecera -->
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
               <label for="cuit_cliente" class="col-sm-2 control-label">Cuil/Cuit</label>
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
