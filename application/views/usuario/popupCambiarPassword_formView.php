<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div>
<div class="modal-body">
    <form class="row" name="formUsuario" novalidate >    	
		<div class="form-group mb-md col-md-12">
			<label class="control-label mb-xs">Contraseña Actual <small class="text-danger">(*)</small> </label> 
			<input id="clave" required type="password" class="form-control input-sm" ng-model="fDataUsuario.clave" placeholder="Ingresa su contraseña actual" />
		</div>
		<div class="form-group mb-md col-md-12">
			<label class="control-label mb-xs">Nueva Contraseña <small class="text-danger">(*)</small> </label> 
			<input id="nuevoPass" required ng-minlength="8" type="password" class="form-control input-sm" ng-model="fDataUsuario.claveNueva" 
				placeholder="Nueva contraseña (Min 8 caracteres)" tooltip-placement="top-left" 
				uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>
		</div>
		<div class="form-group mb-md col-md-12">
			<label class="control-label mb-xs">Confirmar Nueva Contraseña <small class="text-danger">(*)</small> </label> 
			<input required ng-minlength="8" type="password" class="form-control input-sm" ng-model="fDataUsuario.claveConfirmar" placeholder="Confirme su nueva contraseña" />
		</div>
		<div class="form-group mb-md col-md-12">
			<alert type="{{fAlert.type}}" close="fAlert = null" ng-show='fAlert.type' class="p-sm mb-n" style="margin-right: 12px;">
                <strong> {{ fAlert.strStrong }} <i class='{{fAlert.icon}}'></i></strong> 
                <span ng-bind-html="fAlert.msg"> </span>
            </alert>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-default" ng-click="cancel()">Salir</button>
    <button class="btn btn-page" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formUsuario.$invalid">Guardar</button>
    
</div>