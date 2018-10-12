<div class=" " ng-controller="inicioController">       
	<div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
		<div class="row dashboard ">
			<div class="col-md-12 col-xs-12 col-sm-12 ">
				<div ng-if="fSessionCI.sexo=='M'" class="saludo"> ¡BIENVENIDO {{fSessionCI.nombres}} {{fSessionCI.apellido_paterno}}! </div>
				<div ng-if="fSessionCI.sexo=='F'"  class="saludo"> ¡BIENVENIDA {{fSessionCI.nombres}} {{fSessionCI.apellido_paterno}}! </div>
			</div>
			<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12 col-sm-12">
				<div class="valores panel">
					<div class="contenedor-items-valores">
						<div class="item">
							<div class="box-item">
								<div class="imagen">
									<img src="{{ dirImages + 'dashboard/icon-peso.png' }}"  />
								</div>
								<div class="value">
									<div class="title">Peso</div>
									<div class="data">{{fSessionCI.peso}} <span class="medida" >Kg.</span></div>
								</div>
							</div>
						</div>
						<div class="item">
						<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-estatura.png' }}"  />
							</div>
							<div class="value">
								<div class="title">Estatura</div>
								<div class="data">{{fSessionCI.estatura}} <span class="medida" >Mts.</span></div>
							</div>
						</div>
						</div>
						<div class="item imc" uib-tooltip="Índice de Masa Corporal (IMC): {{fSessionCI.imc.tipo}}" tooltip-placement="top">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-imc.png' }}"  />
							</div>
							<div class="value" >
								<div class="title">IMC</div>
								<span class="alerta" style="background:{{fSessionCI.imc.color}};" ng-class="{animation: fSessionCI.imc.dato < 18 || fSessionCI.imc.dato > 24.9}" ></span>
								<div class="data" style="color:{{fSessionCI.imc.color}};" >
									{{fSessionCI.imc.dato}} <!-- <span class="medida" ></span> -->
								</div>
							</div>
							</div>
						</div>
						<div class="item">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-tipo-sangre.png' }}"  />
							</div>
							<div class="value">
								<div class="title">Tipo de Sangre</div>
								<div class="data">{{fSessionCI.tipo_sangre.descripcion}}</div>
							</div>
							</div>
						</div>
						<div class="item">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-sexo-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
							</div>
							<div class="value">
								<div class="title">Sexo</div>
								<div class="data">{{fSessionCI.sexo}}</div>
							</div>
							</div>
						</div>
						<div class="item">
							<div class="box-item">
							<div class="imagen">
								<img src="{{ dirImages + 'dashboard/icon-edad-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
							</div>
							<div class="value">
								<div class="title">Edad</div>
								<div class="data">{{fSessionCI.edad}} <span class="medida" >años</span></div>
							</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="separador-valores"></div> -->
			</div>
			<div class="col-md-6 col-xs-12 col-sm-6 mb-lg">
				<div class="div-btn div-btn-1">					
					<div class="btn btn-default btn-go-citas" style="width: 200px;" ng-click="goToSelCita();">PROGRAMAR CITA <i class="fa fa-angle-right"></i></div>
				</div>
			</div>			
			<div class="col-md-6 col-xs-12 col-sm-6 mb-lg pl-n">
				<div class="div-btn div-btn-2">					
					<div class="btn-go-perfil" ng-click="goToPerfil();">ACTUALIZAR MI PERFIL <i class="fa fa-angle-right"></i></div>
				</div>
			</div>
			<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12 col-sm-12 mb-lg"				
				ng-include="'application/views/template-tips-femenino.php'" ng-if="fSessionCI.sexo.toLowerCase() == 'f'">
			</div>
			<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12 col-sm-12 mb-lg"	
				ng-include="'application/views/template-tips-masculino.php'" ng-if="fSessionCI.sexo.toLowerCase() == 'm'"></div>

			<div class="col-md-12 col-xs-12 col-sm-12 ">
				<div class="col-md-offset-1 col-md-5 col-xs-12 col-sm-12 ">
					<div class="caja caja-1 panel" style="">
						<div class="titulo-caja">PROGRAMA TUS CITAS</div>
						<div class="divisiones">
							<div class="division division-1">	
								<div class="texto">
									<p class="pasos"><strong>¡Es muy sencillo!</strong></br>Selecciona la Sede, la Especialidad, tu Médico de confianza, la fecha y hora de tu Cita Médica.</p>
								</div>
								<div class="btn btn-default btn-go-citas" ng-click="goToSelCita();">PROGRAMAR CITA <i class="fa fa-angle-right"></i></div>
							</div>
							<div class="division division-2">	              				
								<div class="citas pendientes" ng-click="goToHistorial();">
									<spam class="count-citas">{{fSessionCI.citas_pendientes}}</spam>
									<spam class="tipo">Citas </br>Pendientes</spam>
								</div>
								<div class="citas realizadas" ng-click="goToHistorial();">
									<spam class="count-citas">{{fSessionCI.citas_realizadas}}</spam>
									<spam class="tipo">Citas</br>Realizadas</spam>
								</div>
							</div>
						</div>						
					</div>
				</div>
				<div class="col-md-5 col-md-offset-right-1 col-xs-12 col-sm-12 ">
					<div class="caja caja-2 panel" >	              			
						<div class="titulo-caja">REVISA AQUÍ</div>
						<div class="divisiones">
							<div class="division division-1">	              				
								<div class="imagen">
									<img class src="{{ dirImages + 'dashboard/historial-citas.png' }}" />
								</div>	              				
								<div class="texto" ng-click="goToHistorial();"> 
									<div class="historial">
										TU HISTORIAL DE CITAS
										<span>
											<i class="fa fa-angle-right"></i>
											<i class="fa fa-angle-right"></i>
										</span>
									</div>									
								</div>
							</div>

							<div class="division  division-2">	
								<div class="imagen">
									<img class src="{{ dirImages + 'dashboard/historial-laboratorio.png' }}" />
								</div>
								<div class="texto" ng-click="goToResultados();">									
									<div class="resultados">										
										RESULTADOS DE LABORATORIO
										<span>
											<i class="fa fa-angle-right"></i>
											<i class="fa fa-angle-right"></i>
										</span>
									</div>									 
								</div>									              				              				              				
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>