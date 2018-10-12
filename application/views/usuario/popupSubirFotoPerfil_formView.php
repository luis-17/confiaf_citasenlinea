<div class="msj modal-close" >
	<a href ng-click="cancelSubida(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="msj modal-header" style="padding-top: 50px;     visibility: hidden;">
	{{titleForm}}	
</div>
<div class="modal-body pt-n" style="">
    <form class="row" name="formSubirFoto" novalidate  > 
		<div class="form-group mb-md col-sm-12" >
			<label class="control-label mb-xs"> Seleccione archivo a subir (Peso Máximo: 1MB. Tipo de archivo: jpg)</label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 150px;"></div>
				<div>
					<a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file btn-sm" ng-hide="fDataSubida.archivo">
						<span class="fileinput-new">Seleccionar archivo</span>
						<input type="file" name="file" file-model="fDataSubida.archivo" /> 
					</span>					
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<alert type="{{fAlertSubida.type}}" close="fAlertSubida = null" ng-show='fAlertSubida.type' class="p-sm m-n" style="font-size: 16px;">
		        <strong> {{ fAlertSubida.strStrong }} <i class='{{fAlertSubida.icon}}'></i></strong> 
		        <span ng-bind-html="fAlertSubida.msg"> </span>
		    </alert>
		</div>
	</form>		
</div>
<div class="modal-footer formulario-pariente-btn-registro">
    <a ng-click="aceptarSubida(); $event.preventDefault();" ng-disabled="formSubirFoto.$invalid">SUBIR</a>    
</div>