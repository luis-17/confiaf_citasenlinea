<div class="tips-villa-salud"> 
	<div class="col-md-12 col-xs-12 col-sm-12 mb-md"> <!-- style="text-align:center;font-size: 17px;color: #1f1c1c;" -->
		<div  class="milky">
			<span ng-if="fSessionCI.sexo=='M'"> Estimado <span style="color:#be0411;">{{fSessionCI.paciente}}</span></span>
			<span ng-if="fSessionCI.sexo=='F'"> Estimada <span style="color:#be0411;">{{fSessionCI.paciente}}</span></span>, tu salud nos importa, por ello te sugerimos realizarte los siguientes análisis y/o procedimientos que según tu perfil consideramos importantes para que puedas gozar de buena salud SIEMPRE!
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.imc.dato < 18 || fSessionCI.imc.dato > 24.9">
		<div class="tip mb-xs">
			<span>Chequea tu peso, Tu IMC no es normal</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,27);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 18 && fSessionCI.edad <= 39) || fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Medición de presión arterial</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 18 && fSessionCI.edad <= 39) || fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Examen de colesterol</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 18 && fSessionCI.edad <= 39) || fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Examen cardiológico</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 18 && fSessionCI.edad <= 39) || fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Examen oftalmológico</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Examen coprológico</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Colonoscopia</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Exploración urológica completa</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Ecografpía urológica/testicular</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Antígeno Prostático Especifico (PSA) </span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Descarte de osteoporosis</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>

	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 18 && fSessionCI.edad <= 39) || fSessionCI.edad >= 40">
		<div class="tip mb-xs">
			<span>Examen dental</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 18 && fSessionCI.edad <= 39)">
		<div class="tip mb-xs">
			<span>Examen médico general</span>
			<button type="button" class="btn btn-default btn-sm" ng-click=""><i class="fa fa-plus"></i></button>
		</div>
	</div>

	<div class="col-md-12 col-xs-12 col-sm-12 mt-md">
		<p style="font-weight: 900;color: navy;text-transform: uppercase;font-size: 16px;
				    font-weight: bold;
				    text-align: center;
				    position: relative;" class="m-n">
			Visita al especialista y solicita tu Orden Médica.
		</p>
	</div>				
</div>