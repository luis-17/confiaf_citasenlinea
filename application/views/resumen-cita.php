<style type="text/css">
  .page-content{
    margin-bottom: 150px;
  }
</style>
<div class="container page-pagos" ng-controller="programarCitaController" ng-init="initResumenReserva();" >       
  	<div class="row" ng-if="viewResumenCita">
      	<div class="col-md-12 col-xs-12 col-sm-12 ">	              	
          	<div class="tab-heading ">                
                <div>                    
                    <h2 class="title">
                      <span class="icon"><i class="ti ti-layout-list-thumb"></i></span> Resumen de Citas
                    </h2> 
                    <p class="descripcion">Revisa detenidamente tu selección y realiza el pago en dos clicks.</p>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xs-12 col-sm-12 float-right">
          <div class="container-clock clock-timer" style="padding-left: 15px;">
            <div class="text-remaining">Tiempo restante:</div>
            <div class="" id="countdown">{{timer.countDownTime}}</div>
          </div>
          <div class="container-clock clock-loading"  >
            <div class="loading" ng-class="{stop: timer.viewTimerExpired}">
                <span></span>
                <span></span>
                <span></span>
            </div>
          </div>
          <p class="text-warning pt-sm" style="font-weight:bold;clear: right;text-align: right;" ng-hide="timer.viewTimerExpired">
            <small>Por favor, completa el proceso de pago antes de que termine el tiempo.</small>
          </p>
        </div>

      	<div class="mi-grid grid-citas col-md-12 col-xs-12 col-sm-12 pb-md" ng-hide="timer.viewTimerExpired">
      		<div class="body-grid" scroller >
            <div class="header row-grid row-cita">
              <div class="cell-grid cell-cita" style="width:17%;">
                CITA PARA
              </div>
              <div class="cell-grid cell-cita" style="width:14%;">
                SEDE
              </div>
              <div class="cell-grid cell-cita" style="width:12%;">
                ESPECIALIDAD
              </div>
              <div class="cell-grid cell-cita" style="width:17%;">
                MÉDICO
              </div>
              <div class="cell-grid cell-cita" style="width:10%;">
                FECHA
              </div>
              <div class="cell-grid cell-cita" style="width:10%;">
                TURNO
              </div>
              <div class="cell-grid cell-cita" style="width:10%;">
                CONSULTORIO
              </div>
              <div class="cell-grid cell-cita" style="width:10%;">
                PRECIO (S/.)
              </div>
            </div>
        	  <div ng-repeat="cita in listaCitas" class="row-grid row-cita">
              <div class="cell-grid cell-cita" style="width:17%;">
                <span class="icono-cita"><i class="fa fa-stethoscope" style="color: #36c0d1;" ></i> Cita para:</span>
                {{cita.busqueda.itemFamiliar.descripcion}}
              </div>
              <div class="cell-grid cell-cita" style="width:14%;">
                <span class="icono-cita"><i class="fa fa-hospital-o" style="color: #ce1d19;" ></i> Sede:</span>
                {{cita.busqueda.itemSede.descripcion}}
              </div>
              <div class="cell-grid cell-cita" style="width:12%;">
                <span class="icono-cita"><i class="ti ti-slice" style="color: #ffc107;" ></i> Especialidad:</span>
                {{cita.busqueda.itemEspecialidad.descripcion}}
              </div>
              <div class="cell-grid cell-cita" style="width:17%;">
                <span class="icono-cita"><i class="fa fa-user-md" style="color: #191970;" ></i> Médico:</span>
                {{cita.seleccion.medico}}
              </div>
              <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
                <span class="icono-cita"><i class="ti ti-calendar" style="color: #929191;"></i> Fecha:</span>
                {{cita.seleccion.fecha_programada}}
              </div>
              <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
                <span class="icono-cita"><i class="fa fa-clock-o" style="color: #929191;" ></i> Hora:</span>
                {{cita.seleccion.hora_formato}}
              </div>
              <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
                <span class="icono-cita"><i class="ti ti-location-pin" style="color: #03a9f4;"></i> Consultorio:</span>
                {{cita.seleccion.numero_ambiente}}
              </div>
              <div class="cell-grid cell-cita" style="width:10%; text-align:right;">
                <span class="icono-cita"><i class="fa fa-money" style="color: #4caf50;"></i> Precio (S/.):</span>
                {{cita.producto.precio_sede}}
              </div>
            </div>        		
      		</div>
      		
          <div class="totales">
      			<div class="divisor-movil"></div>
            <div class="total total-productos ">
      				<div class="descripcion">
      					TOTAL PRODUCTOS: S/. 
      				</div> 
      				<div class="monto">
      					{{totales.total_productos}}
      				</div> 
      			</div>
      			<div class="total total-servicio ">
      				<div class="descripcion">      					
                <i class="fa fa-info-circle" uib-tooltip="Cargo por transacción realizada vía web." 
                    tooltip-placement="top"
                   style="color:#00bcd4;font-size: 18px;">
                </i>
               CARGOS POR SERVICIO WEB: S/. 
      				</div> 
      				<div class="monto">
      					{{totales.total_servicio}}
      				</div> 
      			</div>
      			<div class="total total-pago ">
      				<div class="descripcion">
      					TOTAL A PAGAR: S/. 
      				</div> 
      				<div class="monto">
      					{{totales.total_pago}}
      				</div>
      			</div>
      		</div>
      	</div>
        
      	<div class="col-md-12 col-xs-12 col-sm-12" ng-hide="timer.viewTimerExpired">
      		<div class="botones" style="text-align: right;">
            <button class="btn btn-default btn-go-citas" style="width: 120px;" ng-click="pagar();" ><i class="fa fa-credit-card" style="padding: 0 5px 0 0;"></i>PAGAR</button>
          </div>
          <div class="terminos-condiciones">
        		<div class="titulo" style="font-size: 17px;color: #36c5df;font-weight:600;">
        			<input type="checkbox" ng-model="acepta" ng-click="changeAcepta();" ng-checked="acepta"></input> Acepto los <a style="font-size: 17px;color: #36c5df;font-weight:600;text-decoration: underline;" target="_blank" href="#">Términos y Condiciones</a> establecidos (Lea detenidamente esta sección)             
        		</div>
        		<ul>
        			<li>
        				Luego de efectuar el pago, el dinero no será reembolsado. 
        			</li>
        			<li>
        				En caso de no poder asistir a su cita en la fecha pautada, podrá realizar una reprogramación de la misma (antes de la fecha programada). 
        			</li>
        		</ul>
          </div>        	
      	</div>  

        <!-- <div class="col-md-12 reserva-expirada" >  -->
        <div class="col-md-12 reserva-expirada" ng-show="timer.viewTimerExpired"> 
          <div class="msj pt-md">Tu reserva ha expirado. Por favor intentalo nuevamente.
            </br>Si necesitas ayuda, contacta nuestro equipo de soporte a 
            <a href="mailto:citasenlinea@vitacloud.pe?Subject=Necesito soporte con citas en linea" target="_top">
              <span class="text-info" style="font-weight:bold;">citasenlinea@vitacloud.pe</span>
            </a> 
          </div>
          <div class="col-md-12 col-xs-12 col-sm-12 pt-md pb-md">
            <div class="btn btn-default btn-go-citas" ng-click="goToSelCita();">
              INTENTAR DE NUEVO                             
            </div>
            <div class="pt-lg mt-md">
              <img src="assets/img/icons/logo-slogan.png" style="max-width:250px;">
            </div>
          </div>
          <!-- <div class="name pt-lg">Vitacloud </div> 
          <div class="lema">Te cuida!</div> -->
        </div>

        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="call-actions mt-md">
              <div class="col-md-4 col-xs-12 col-sm-4">
                <div class="btn btn-default btn-go-citas" ng-click="goToSelCita();">
                  <i class="fa fa-angle-left"></i> PROGRAMAR OTRA CITA                             
                </div>
              </div>

              <div class="col-md-4 col-xs-12 col-sm-4">
                <a href="http://www.villasalud.pe" target="_blank"> 
                  <span class="lema" >
                    Ir a - 
                  </span>
                </a>                            
              </div>

              <div class="col-md-4 col-xs-12 col-sm-4">
                <a class="btn-go-historial" ng-click="goToHistorial();">MIRA TU 
                  <span class="historial">HISTORIAL DE CITAS</span>
                  <i class="fa fa-angle-right"></i>
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
          </div>
        </div>

  	</div>

  	<div class="row" ng-if="viewResumenCompra">
      <div class="col-md-12 col-xs-12 col-sm-12 ">                  
        <div class="tab-heading ">               
          <div>
              <h2 class="title">
                <span class="icon"><i class="ti ti-layout-list-thumb"></i></span>Resumen de Compra
              </h2> 
              <p class="descripcion">Descarga e imprime tus tickets para el día de tu cita.</p>
          </div>
        </div>
      </div>

      <div class="mi-grid grid-citas col-md-12 col-xs-12 col-sm-12">
        <div class="body-grid" style="min-height: 100px;" scroller >
          <div class="header row-grid row-cita">
          <div class="cell-grid cell-cita" style="width:17%;">
            CITA PARA
          </div>
          <div class="cell-grid cell-cita" style="width:14%;">
            SEDE
          </div>
          <div class="cell-grid cell-cita" style="width:12%;">
            ESPECIALIDAD
          </div>
          <div class="cell-grid cell-cita" style="width:17%;">
            MÉDICO
          </div>
          <div class="cell-grid cell-cita" style="width:9%;">
            FECHA
          </div>
          <div class="cell-grid cell-cita" style="width:9%;">
            TURNO
          </div>
          <div class="cell-grid cell-cita" style="width:10%;">
            CONSULTORIO
          </div>
          <div class="cell-grid cell-cita" style="width:12%;">
            <i class="fa fa-download"></i>
          </div>
        </div>
        <div ng-repeat="cita in fSessionCI.compra.listaCitasGeneradas" class="row-grid row-cita">
            <div class="cell-grid cell-cita" style="width:17%;">
              <span class="icono-cita"><i class="fa fa-stethoscope" style="color: #36c0d1;" ></i> Cita para:</span>
              {{cita.busqueda.itemFamiliar.descripcion}}
            </div>
            <div class="cell-grid cell-cita" style="width:14%;">
              <span class="icono-cita"><i class="fa fa-hospital-o" style="color: #ce1d19;" ></i> Sede:</span>
              {{cita.busqueda.itemSede.descripcion}}
            </div>
            <div class="cell-grid cell-cita" style="width:12%;">
              <span class="icono-cita"><i class="ti ti-slice" style="color: #ffc107;" ></i> Especialidad:</span>
              {{cita.busqueda.itemEspecialidad.descripcion}}
            </div>
            <div class="cell-grid cell-cita" style="width:17%;">
              <span class="icono-cita"><i class="fa fa-user-md" style="color: #191970;" ></i> Médico:</span>
              {{cita.seleccion.medico}}
            </div>
            <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
              <span class="icono-cita"><i class="ti ti-calendar" style="color: #929191;"></i> Fecha:</span>
              {{cita.seleccion.fecha_programada}}
            </div>
            <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
              <span class="icono-cita"><i class="fa fa-clock-o" style="color: #929191;" ></i> Hora:</span>
              {{cita.seleccion.hora_formato}}
            </div>
            <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
              <span class="icono-cita"><i class="ti ti-location-pin" style="color: #03a9f4;"></i> Consultorio:</span>
              {{cita.seleccion.numero_ambiente}}
            </div>
            <div class="cell-grid cell-cita" style="width:12%; text-align:center;">
              <span class="icono-cita"><i class="fa fa-download" style="color: #4caf50;"></i> Comprobante:</span>
              <span class="cita" ng-click="descargaComprobanteCita(cita);">
                <i class="fa fa-file-pdf-o"></i>
              </span>
            </div>
          </div>    
        </div>
      </div>

      <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="call-actions mt-md">
            <div class="col-md-4 col-xs-12 col-sm-4">
              <div class="btn btn-default btn-go-citas" ng-click="goToSelCita();">
                <i class="fa fa-angle-left"></i> PROGRAMAR OTRA CITA                             
              </div>
            </div>

            <div class="col-md-4 col-xs-12 col-sm-4">
              <a href="http://www.villasalud.pe" target="_blank"> 
                <span class="lema" >
                  Ir a www.villasalud.pe
                </span>
              </a>                            
            </div>

            <div class="col-md-4 col-xs-12 col-sm-4">
              <a class="btn-go-historial" ng-click="goToHistorial();">MIRA TU 
                <span class="historial">HISTORIAL DE CITAS</span>
                <i class="fa fa-angle-right"></i>
                <i class="fa fa-angle-right"></i>
              </a>
            </div>
        </div>
      </div>
    </div>
</div>