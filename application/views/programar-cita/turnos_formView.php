<div class="msj modal-close" >
	<a href ng-click="btnCancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="modal-header formulario-pariente-heading">
	<!-- <a style="position: relative;left: -20%; color:#36c0d1;" ng-click="btnCancel();"> <i class="fa fa-angle-left"></i></a>-->
	{{ titleForm }} 
</div>
<div class="modal-body pb-n" style="padding-top: 5px;">
	<div class="row">
		<form>
			<p style="font-size: 15px;font-weight: 400;color: #616161;font-size: 15px;text-align: center;">Selecciona la hora que deseas</p>		
			<p class="turnos-detalle">
				Especialidad: {{fPlanning.detalle.especialidad}} - Fecha: {{fPlanning.detalle.fecha_programada}}
			</p>		
			<div class="turnos-list">
				<div ng-repeat="turno in fPlanning.turnos" class="cupos">			
					<div class="turnos-disponibles col-sm-12 col-md-12 col-xs-12 pr-n">
						<p class="medico mb-n">
							<span class="fa fa-user-md"></span>{{turno.medico}} 
							<span class="favorito animation" ng-if="turno.medico_favorito" ><i class="fa fa-star"></i></span> - 
							<span class="consultorio">Consultorio: {{turno.ambiente.numero_ambiente}}</span>
						</p>
						<div ng-repeat="cupo in turno.cupos" class="cupo" ng-click="checkedCupo(cupo);" >
							<input type="radio" id="radio{{cupo.iddetalleprogmedico}}" name="radio[]" ng-checked="cupo.checked" />
			    			<label 	for="radio{{cupo.iddetalleprogmedico}}" ng-click="checkedCupo(cupo);"></label>
			    			<span class="box" ng-click="checkedCupo(cupo);"
			    					uib-tooltip="Este es un cupo adicional. Será atendido entre los últimos pacientes." 
			    					tooltip-placement="bottom" tooltip-enable="cupo.adicional">
			    				{{cupo.hora_formato}}
			    			</span>
			    			<span class="box-adicional" ng-if="cupo.adicional">
			    				<i class="fa fa-plus-circle"></i>
			    			</span>	
						</div>
					</div>
				</div>				
			</div>
		</form>
	</div>
</div>
<div class="modal-footer formulario-pariente-btn-registro">
	<a href ng-click="btnReservarTurno(); $event.preventDefault();" ng-if="!boolExterno" > SELECCIONAR </a>    
	<a href ng-click="btnCambiarTurno(); $event.preventDefault();" ng-if="boolExterno"> REPROGRAMAR </a>    
</div>
