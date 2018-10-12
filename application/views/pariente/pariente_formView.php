<div class="msj modal-close" >
	<a href ng-click="btnCancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="modal-header formulario-pariente-heading">
	<!-- <a style="position: relative;left: -20%; color:#36c0d1;" ng-click="btnCancel();"> <i class="fa fa-angle-left"></i></a>-->
	{{ titleForm }} 
</div>
<div class="modal-body">
    <form class="" name="formPariente" novalidate>
    	<div class="row">
    		<form class="" name="formPariente" novalidate>
				<div class="col-md-12 col-sm-12">
					<alert type="{{fAlert.type}}" close="fAlert = null" ng-show='fAlert.type' class="p-sm mb-n" style="margin-right: 12px;">
		                <strong> {{ fAlert.strStrong }} <i class='{{fAlert.icon}}'></i></strong> 
		                <span ng-bind-html="fAlert.msg"> </span>
		            </alert>
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12" ng-if="accion=='reg'">
					<label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
					<div class="input-group">
						<input type="text" class="form-control  " ng-model="fData.num_documento" placeholder="Registre su dni" tabindex="1" 
							ng-enter="verificarDoc(); $event.preventDefault();" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/"/> 
						<div class="input-group-btn ">
							<button type="button" class="btn btn-default" 
											ng-click="verificarDoc(); $event.preventDefault();" >
								<i class="fa fa-search"></i>
							</button>
						</div>
					</div>
				</div>	

				<div class="form-group mb-md col-md-6 col-sm-12" ng-if="accion=='edit'">
					<label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
					<input type="text" class="form-control  " ng-model="fData.num_documento" placeholder="Registre su dni" tabindex="1" disabled /> 
				</div>

				<div class="form-group mb-md col-md-6 col-sm-12" ng-if="accion=='reg'">
					<label class="control-label mb-xs" style="margin-bottom: 4px;"> Parentesco <small class="text-danger">(*)</small> </label>
					<select class="form-control  " ng-model="fData.parentesco" 
							ng-focus="verificarDoc(); $event.preventDefault();"
							ng-options="item.descripcion for item in regListaParentescos" tabindex="2" required > </select>
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12" ng-if="accion=='edit'">
					<label class="control-label mb-xs" style="margin-bottom: 4px;"> Parentesco <small class="text-danger">(*)</small> </label>
					<select class="form-control  " ng-model="fData.parentesco" ng-options="item.descripcion for item in regListaParentescos" tabindex="2" required > </select>
				</div>	
				<div class="form-group mb-md col-md-6 col-sm-12" >
					<label class="control-label mb-xs" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
					<select class="form-control  " ng-model="fData.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="3" required > </select>
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12">
					<label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
					<input type="text" class="form-control  " ng-model="fData.nombres" placeholder="Registre su nombre" required tabindex="4" />
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12">
					<label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
					<input type="text" class="form-control  " ng-model="fData.apellido_paterno" placeholder="Registre su apellido paterno" required tabindex="5" /> 
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12">
					<label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
					<input type="text" class="form-control  " ng-model="fData.apellido_materno" placeholder="Registre su apellido materno" required tabindex="6" /> 
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12" >
					<label class="control-label mb-xs">E-mail </label>
					<input type="email" class="form-control  " ng-model="fData.email" placeholder="Registre su e-mail" tabindex="7" />
				</div>
				<div class="form-group mb-md col-md-6 col-sm-12" >
					<label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
					<input type="text" class="form-control   mask" data-inputmask="'alias': 'dd-mm-yyyy'" ng-model="fData.fecha_nacimiento" required tabindex="8"/> 
				</div>
				<div class="col-md-12 col-sm-12">
					<alert type="info" class="p-sm mb-n" style="font-size: 16px;">
		                <i class='fa  fa-info-circle'></i>
		                <strong> Información: </strong> 
		                Sólo podrás registrar hijos menones de 18 años.
		            </alert>
				</div>
			</form>
    	</div>
	</form>
</div>
<div class="modal-footer formulario-pariente-btn-registro">
    <a href ng-if="accion=='reg'" ng-click="btnRegistrarPariente(); $event.preventDefault();" ng-disabled="formPariente.$invalid" tabindex="14" > REGISTRAR </a>    
    <a href ng-if="accion=='edit'" ng-click="btnActualizarPariente(); $event.preventDefault();" ng-disabled="formPariente.$invalid" tabindex="14" > ACTUALIZAR </a>    
</div>
