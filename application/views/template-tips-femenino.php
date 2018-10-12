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
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 20 && fSessionCI.edad <= 35) || (fSessionCI.edad >= 35 && fSessionCI.edad <= 55)">
		<div class="tip mb-xs">
			<span>Papanicolau</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 20 && fSessionCI.edad <= 35) || (fSessionCI.edad >= 35 && fSessionCI.edad <= 55)">
		<div class="tip mb-xs">
			<span>Coloscopia</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="(fSessionCI.edad >= 20 && fSessionCI.edad <= 35) || (fSessionCI.edad >= 35 && fSessionCI.edad <= 55)">
		<div class="tip mb-xs">
			<span>Ecografía Transvaginal</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 20 && fSessionCI.edad <= 35">
		<div class="tip mb-xs">
			<span>Ecografía de mamas</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
		</div>
	</div>					
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 35 && fSessionCI.edad <= 55">
		<div class="tip mb-xs">
			<span>Mamografía</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,18);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 20 && fSessionCI.edad <= 35">
		<div class="tip mb-xs">
			<span>Examen Médico General</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,65);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad > 30">
		<div class="tip mb-xs">
			<span>Examen de lunares</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,10);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 35 && fSessionCI.edad <= 55">
		<div class="tip mb-xs">
			<span>Densimetria Osea</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,9);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad >= 35 && fSessionCI.edad <= 55">
		<div class="tip mb-xs">
			<span>Colonoscopia</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,16);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad > 40">
		<div class="tip mb-xs">
			<span>Examen Visual</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,29);"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6 pl-n" ng-if="fSessionCI.edad > 60">
		<div class="tip mb-xs">
			<span>Chequeo Cardiologico</span>
			<button type="button" class="btn btn-default btn-sm" ng-click="btnSolicitarCita(1,2);"><i class="fa fa-plus"></i></button>
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