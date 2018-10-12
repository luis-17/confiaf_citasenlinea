<div class="msj modal-close" >
	<a href ng-click="btnCancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="msj modal-header">
	{{ titleForm }}
</div>
<div class="modal-body">
    <form class="row" name="formUsuario" novalidate >    	
		<div class="form-group mb-md col-md-12 col-sm-12 col-xs-12">
			Ingresa tu N° de documento y correo. Recibirás un mail con tu nueva clave de acceso.
		</div>
		<div class="form-group mb-md col-md-12 col-sm-12 col-xs-12">
			<label class="control-label mb-xs">Usuario<small class="text-danger">(*)</small> </label> 
			<input required type="text" class="form-control input-sm" ng-model="fRecuperaDatos.num_documento" placeholder="Ingresa tu documento" />
		</div>
		<div class="form-group mb-md col-md-12 col-sm-12 col-xs-12">
			<label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small> </label> 
			<input required type="mail" class="form-control input-sm" ng-model="fRecuperaDatos.email" placeholder="Ingresa tu email"/>
		</div>
		<div class="form-group mb-md col-md-12">
			<uib-alert type="{{fAlertPass.type}}" close="fAlertPass = null" ng-show='fAlertPass.type' class="p-sm mb-n" style="margin-right: 12px;">
                <strong> {{ fAlertPass.strStrong }} <i class='{{fAlertPass.icon}}'></i></strong> 
                <span ng-bind-html="fAlertPass.msg"> </span>
            </uib-alert>
		</div>
	</form>
</div>
<div class="modal-footer formulario-pariente-btn-registro">
    <a ng-click="generaNewPassword(); $event.preventDefault();" ng-disabled="formSubirFoto.$invalid">GENERAR</a>    
</div>