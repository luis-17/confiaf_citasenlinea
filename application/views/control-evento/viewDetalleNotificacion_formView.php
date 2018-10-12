<div class="msj modal-close" >
  <a href ng-click="cancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="modal-header formulario-pariente-heading">
  <!-- <a style="position: relative;left: -20%; color:#36c0d1;" ng-click="btnCancel();"> <i class="fa fa-angle-left"></i></a>-->
  {{ titleForm }} 
</div>
<div class="modal-body" style="top: -10px;">  
  <form class="row" name="formDetalleNotificacionEvento">
    <div class="mb-n col-md-12 col-sm-12 col-xs-12">
      <strong class="control-label mb-n">TEXTO NOTIFICACIÓN: </strong>
      <p class="help-block m-n"> 
        <textarea class="form-control" rows="3" ng-model="fData.texto_notificacion" disabled ></textarea>
      </p>     
    </div> 
    <div class="mb-n col-md-6 col-sm-6 col-xs-12">
      <strong class="control-label mb-n">FECHA NOTIFICACIÓN: </strong>
      <p class="help-block m-n"><i class="fa fa-clock-o" style="margin-right: 4px;"></i>{{fData.fecha_evento_str}} </p>          
    </div>
    <div class="mb-n col-md-6 col-sm-6 col-xs-12">
      <strong class="control-label mb-n">TIPO NOTIFICACIÓN: </strong>
      <p class="help-block m-n">{{fData.descripcion_te}} </p>              
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:10px;" ng-if="fData.identificador != null">
      <div class="row">
        <div class="mb-n col-md-12 col-sm-12 col-xs-12" ng-if="fData.idtipoevento == 17">
          <div class="col-md-12 col-sm-12 col-xs-12 ">
            <a ng-click="viewComprobante(); $event.preventDefault();" style="color:#ce1d19;" class="pull-right"> 
              <i class="fa fa-file-pdf-o"></i> Comprobante de cita
            </a>   
          </div>
        </div>

        <div class="mb-n col-md-12 col-sm-12 col-xs-12" ng-if="fData.idtipoevento == 18">
          <div class="col-md-12 col-sm-12 col-xs-12  pull-right">
            <a ng-click="viewComprobante(); $event.preventDefault();" style="color:#ce1d19;" class="pull-right"> 
              <i class="fa fa-file-pdf-o"></i> Comprobante de cita
            </a>   
          </div>                             
        </div>

        <div class="mb-n col-md-12 col-sm-12 col-xs-12" ng-if="fData.idtipoevento == 19">
                                         
        </div>
      </div>       
    </div>
  </form>
</div>

