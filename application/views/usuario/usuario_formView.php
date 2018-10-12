<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div>
<div class="modal-body">
    <form class="" name="formUsuario" novalidate>
    	<div class="row">
    		<div class="form-group mb-md col-md-6">
				<label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
				<div class="input-group">
					<input type="text" class="form-control input-sm" ng-model="fDataUser.num_documento" placeholder="Registre su dni" tabindex="1" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/"/> 
					<div class="input-group-btn ">
						<button type="button" class="btn btn-page btn-sm" ng-click="verificarDoc(); $event.preventDefault();" >CONSULTAR</button>
					</div>
				</div>
				<!-- <input ng-init="verificaDNI();" type="text" class="form-control input-sm" ng-model="fData.num_documento" placeholder="Registre su dni" tabindex="1" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/" ng-change="verificaDNI();" />  -->
			</div>
			

    		<div class="form-group mb-md col-md-6">
				<label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
				<input type="text" class="form-control input-sm" ng-model="fData.nombres" placeholder="Registre su nombre" required tabindex="2" />
			</div>
    	</div>
    	<div class="row">
			<div class="form-group mb-md col-md-6">
				<label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
				<input type="text" class="form-control input-sm" ng-model="fData.apellido_paterno" placeholder="Registre su apellido paterno" required tabindex="3" /> 
			</div>
			<div class="form-group mb-md col-md-6">
				<label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
				<input type="text" class="form-control input-sm" ng-model="fData.apellido_materno" placeholder="Registre su apellido materno" required tabindex="4" /> 
			</div>			
		</div>		

		<div class="row">
			<div class="form-group mb-md col-md-6" >
				<label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label>
				<input type="email" class="form-control input-sm" ng-model="fData.email" placeholder="Registre su e-mail" required tabindex="5" />
			</div>

			<div class="form-group mb-md col-md-3" >
				<label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
				<select class="form-control input-sm" ng-model="fData.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="6" required > </select>
			</div>

			<div class="form-group mb-md col-md-3" >
				<label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
				<input type="text" class="form-control input-sm mask" data-inputmask="'alias': 'dd-mm-yyyy'" ng-model="fData.fecha_nacimiento" required tabindex="7"/> 
			</div>
		</div>

		<div class="row">
			<div class="form-group mb-md col-md-3">
				<label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label>
				<input type="tel" class="form-control input-sm" ng-model="fData.celular" placeholder="Registre su celular" ng-minlength="9" required tabindex="8" />
			</div>
			<div class="form-group mb-md col-md-3">
				<label class="control-label mb-xs">Teléfono Casa  </label>
				<input type="tel" class="form-control input-sm" ng-model="fData.telefono" placeholder="Registre su teléfono" ng-minlength="6" tabindex="9" />
			</div>

			<div class="form-group mb-md col-md-3">
				<label class="control-label mb-xs">Contraseña <small class="text-danger">(*)</small> </label>
				<input type="password" class="form-control input-sm" ng-model="fData.clave" placeholder="Contraseña" ng-minlength="8" 
					   required tabindex="10" tooltip-placement="top-left" 
					   uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/> 
			</div>

			<div class="form-group mb-md col-md-3">
				<label class="control-label mb-xs">Repita Contraseña <small class="text-danger">(*)</small> </label>
				<input type="password" class="form-control input-sm" ng-model="fData.repeat_clave" placeholder="Repita Contraseña" 
				       ng-minlength="8" required tabindex="11" tooltip-placement="top-left" 
					   uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>  
			</div>					
		</div>

		<div class="row">
            <div class="form-group mb-md col-md-6 col-md-offset-3">
                <div id="recaptcha-registro" data-ng-controller="usuarioController" data-ng-init="initRecaptcha()"
                		class="g-recaptcha" data-sitekey="{{keyRecaptcha}}" data-callback="recaptchaResponse"></div>
            </div>
        </div>

		<div class="row">
			<div class="col-md-12">
				<alert type="{{fAlert.type}}" close="fAlert = null" ng-show='fAlert.type' class="p-sm mb-n" style="margin-right: 12px;">
	                <strong> {{ fAlert.strStrong }} <i class='{{fAlert.icon}}'></i></strong> 
	                <span ng-bind-html="fAlert.msg"> </span>
	            </alert>
			</div>
		</div>


	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-default" ng-click="btnCancel()" tabindex="13"> SALIR </button>
    <button class="btn btn-page" ng-click="registrarUsuario(); $event.preventDefault();" ng-disabled="formUsuario.$invalid" tabindex="14" > GUARDAR </button>    
</div>
