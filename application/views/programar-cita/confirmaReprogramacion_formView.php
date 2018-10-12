<div class="msj modal-close" >
	<a href ng-click="btnClose(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="modal-header formulario-pariente-heading">
	{{fDataModal.mensaje}}
</div>
<div class="modal-body row" style="font-size:14px;">	
	<div class="col-md-12 ">
		<div class="mb-n col-md-12" >
			<div class="row" >
		        <div class="col-md-12">
		          <strong class="control-label mb-n">CITA PARA: </strong>
		          <p class="help-block m-n truncate"> {{ fDataModal.oldCita.itemFamiliar.paciente }} </p>
		        </div>
			</div>
		</div>
		<div class="mb-n col-md-12" >
			<div class="row" >
		        <div class="col-md-12">
		          <strong class="control-label mb-n">CITA ANTERIOR: </strong>
		        </div>
			</div>		
			
			<div class="mb-n col-md-12" >
				<div class="row" >
			        <div class="col-md-4">
			          <strong class="control-label mb-n">SEDE: </strong>
			          <p class="help-block m-n truncate"> {{ fDataModal.oldCita.itemSede.sede }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">ESPECIALIDAD: </strong>
			          <p class="help-block m-n truncate"> {{ fDataModal.oldCita.itemEspecialidad.especialidad }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">MÉDICO: </strong>
			          <p class="help-block m-n truncate"> {{ fDataModal.oldCita.itemMedico.medico }} </p>
			        </div>
				</div>
			</div>
			<div class="mb-n col-md-12" >
				<div class="row">
					<div class="col-md-4">
			          <strong class="control-label mb-n">FECHA: </strong>
			          <p class="help-block m-n"> {{ fDataModal.oldCita.fecha_formato }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">HORA DE ATENCIÓN: </strong>
			          <p class="help-block m-n"> {{ fDataModal.oldCita.hora_inicio_formato }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">CONSULTORIO: </strong>
			          <p class="help-block m-n"> {{ fDataModal.oldCita.itemAmbiente.numero_ambiente }} </p>
			        </div>
				</div>
			</div>
		</div>
		<div class="mb-n col-md-12" >
			<div class="row" >
		        <div class="col-md-12">
		          <strong class="control-label mb-n">NUEVA CITA SELECCIONADA: </strong>
		        </div>
			</div>		

			<div class="mb-n col-md-12" >
				<div class="row" >
			        <div class="col-md-4">
			          <strong class="control-label mb-n">SEDE: </strong>
			          <p class="help-block m-n truncate"> {{ fDataModal.oldCita.itemSede.sede }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">ESPECIALIDAD: </strong>
			          <p class="help-block m-n truncate"> {{ fDataModal.oldCita.itemEspecialidad.especialidad }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">MÉDICO: </strong>
			          <p class="help-block m-n truncate"> {{ fDataModal.seleccion.medico }} </p>
			        </div>			        			        
				</div>
			</div>

			<div class="mb-n col-md-12" >
				<div class="row">
					<div class="col-md-4">
			          <strong class="control-label mb-n">FECHA: </strong>
			          <p class="help-block m-n"> {{ fDataModal.seleccion.fecha_programada }} </p>
			        </div>
			    	<div class="col-md-4">
			          <strong class="control-label mb-n">HORA DE ATENCIÓN: </strong>
			          <p class="help-block m-n"> {{  fDataModal.seleccion.hora_formato }} </p>
			        </div>
			        <div class="col-md-4">
			          <strong class="control-label mb-n">CONSULTORIO: </strong>
			          <p class="help-block m-n"> {{ fDataModal.seleccion.numero_ambiente }} </p>
			        </div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<div class="modal-footer formulario-pariente-btn-registro">	
	<a ng-click="btnOk();">SI,DESEO CAMBIAR LA CITA</a>
</div>