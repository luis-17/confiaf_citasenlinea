<div class="content" ng-controller="historialCitasController">       
  <div class="filtros btn-group-btn pl-n ml-n historial">
    <button type="button" class="btn btn-info btn-sm toggle-filtros" data-toggle="collapse" data-target="#filtros">
      <i class="ti ti-more-alt"></i>  
    </button>
    <ul class="demo-btns collapse in" id="filtros">
      <li class="item-filtro" >
        <label class="control-label">Cita para:</label>
        <select class="form-control " ng-model="fBusqueda.familiar"
            ng-change="listarHistorial();" 
            ng-options="item.descripcion for item in listaFamiliares">
          </select>
      </li>
      <li class="item-filtro" >
        <label class="control-label">en:</label>
        <select class="form-control " ng-model="fBusqueda.sede"
          ng-change="listarEspecialidad();listarHistorial();" 
          ng-options="item.descripcion for item in listaSedes">
        </select>
      </li>
      <li class="item-filtro" >
        <select class="form-control " ng-model="fBusqueda.especialidad"
          ng-change="listarHistorial();" 
          ng-options="item.descripcion for item in listaEspecialidad">
        </select>
      </li>

      <li class="item-filtro">
        <label class="control-label">Estado:</label>
        <select class="form-control " ng-model="fBusqueda.tipoCita"
          ng-change="listarHistorial();" 
          ng-options="item.descripcion for item in listaTipoCita">
        </select>
      </li>
    </ul>
  </div>
  <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">    
    <div class="col-sm-12 col-md-12 col-xs-12" style="margin-top: 20px;">
      <div class="row">
        <div class="col-sm-12 col-md-10 pl-n" >
          <div class="col-sm-6 col-md-8 col-xs-12">            
            <div class="text-guia historial">
              <p class="saludo mb-xs">¡Hola {{fSessionCI.nombres}}!</p>
              <p class="instruccion">Mira tu historial de citas... Próximas y Pasadas. Utiliza los filtros para ubicarlas más rápido!</p>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-xs-12 leyenda pull-right mb-sm">            
            Identifica y descarga tus comprobantes.</br>
            De cita: <span class="favorito animation" style="color: #4caf50;"><i class="fa fa-download"></i></span></br>
            De pago: <span class="favorito animation" style="color: #4caf50;"><i class="ti ti-money"></i></span>
          </div>

          <div class="col-sm-12 col-md-12 col-xs-12">  
            <div class="alert alert-warning" ng-show="listaDeCitas.length == 0">
              No hay citas registradas con las opciones seleccionadas, intenta con otros parámetros... 
            </div>
          </div>
          <div class="historial-citas" >
            <div class="mi-grid grid-citas mb-md col-md-12 col-xs-12 col-sm-12">
              <div class="body-grid" style="min-height: 100px;" ng-show="listaDeCitas.length > 0" scroller>
                <div class="header row-grid row-cita"  >
                  <div class="cell-grid cell-cita" style="width:17%;">
                    CITA PARA
                  </div>
                  <div class="cell-grid cell-cita" style="width:13%;">
                    SEDE
                  </div>
                  <div class="cell-grid cell-cita" style="width:12%;">
                    ESPECIALIDAD
                  </div>
                  <div class="cell-grid cell-cita" style="width:15%;">
                    MÉDICO
                  </div>
                  <div class="cell-grid cell-cita" style="width:9%;">
                    FECHA
                  </div>
                  <div class="cell-grid cell-cita" style="width:9%;">
                    HORA
                  </div>
                  <div class="cell-grid cell-cita" style="width:10%;">
                    CONSULTORIO
                  </div>
                  <div class="cell-grid cell-cita" style="width:5%;">
                    <i class="fa fa-download" style="color: #4caf50;"></i>
                  </div>
                  <div class="cell-grid cell-cita" style="width:5%;">
                    <i class="ti ti-money" style="color: #4caf50;"></i>
                  </div>
                  <div class="cell-grid cell-cita" style="width:5%;">
                    <i class="fa "></i>
                  </div>
                </div>
                <div ng-repeat="cita in listaDeCitas" class="row-grid row-cita">
                    <div class="cell-grid cell-cita" style="width:17%;text-transform: uppercase;">
                      <span class="icono-cita"><i class="fa fa-stethoscope" style="color: #36c0d1;" ></i> Cita para:</span>
                      {{cita.itemFamiliar.paciente}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:13%;">
                      <span class="icono-cita"><i class="fa fa-hospital-o" style="color: #ce1d19;" ></i> Sede:</span>
                      {{cita.itemSede.sede}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:12%;">
                      <span class="icono-cita"><i class="ti ti-slice" style="color: #ffc107;" ></i> Especialidad:</span>
                      {{cita.itemEspecialidad.especialidad}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:15%;">
                      <span class="icono-cita"><i class="fa fa-user-md" style="color: #191970;" ></i> Médico:</span>
                      {{cita.itemMedico.medico}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
                      <span class="icono-cita"><i class="ti ti-calendar" style="color: #929191;"></i> Fecha:</span>
                      {{cita.fecha_formato}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:9%; text-align:center;">
                      <span class="icono-cita"><i class="fa fa-clock-o" style="color: #929191;" ></i> Hora:</span>
                      {{cita.hora_inicio_formato}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:10%; text-align:center;">
                      <span class="icono-cita"><i class="ti ti-location-pin" style="color: #03a9f4;"></i> Consultorio:</span>
                      {{cita.itemAmbiente.numero_ambiente}}
                    </div>
                    <div class="cell-grid cell-cita" style="width:5%; text-align:center;cursor: pointer;">
                      <span class="icono-cita"><i class="fa fa-download" style="color: #4caf50;"></i> Comprobante de Cita:</span>
                      <span class="cita" ng-click="descargaComprobanteCita(cita);" style="font-size: 15px;">
                        <i class="fa fa-file-pdf-o"></i>
                      </span>
                      
                    </div>
                    <div class="cell-grid cell-cita" style="width:5%; text-align:center;cursor: pointer;">
                      <span class="icono-cita"><i class="ti ti-money" style="color: #4caf50;"></i> Comprobante de Pago:</span>
                      <span class="nro-doc" ng-click="descargaComprobantePago(cita);" 
                        style="padding-left: 5px;font-size: 15px;color:{{cita.color_comprobante}}"
                        uib-tooltip="Tu comprobante está en proceso de ser emitido.Recibirás un mail cuando esté listo!" 
                        tooltip-placement="top" tooltip-enable="cita.estado_comprobante == 2" tooltip-trigger="mouseenter">
                        <i class="{{cita.icon_comprobante}}" style="font-size:{{cita.font_size}}"></i>
                        <a href="{{dirComprobantes}}{{cita.nombre_archivo}}" target="_blank"> {{cita.numero_comprobante}} </a>
                      </span>                      
                    </div>
                    <div class="cell-grid cell-cita" style="width:5%; text-align:left;cursor: pointer;">
                      <!-- <span class="reprog" ng-click="reprogramarCita(cita);" >
                        <i class="{{cita.icon_cita}}" style="color:{{cita.color_cita}};"></i>                      
                      </span> -->
                      <button class="btn btn-sm {{cita.color_cita}}"  ng-click="reprogramarCita(cita);" >
                        <i class="{{cita.icon_cita}}"  style=""></i>
                      </button>
                    </div>
                </div>                     
              </div>
            </div>
            <div class="clearfix"></div>
          </div>           
        </div>
        <div class="col-sm-12 col-md-2 pl-n">
          <div class="previo">
            <div ng-show="fSessionCI.compra.listaCitas.length > 0">            
              <div class="text-guia noanimate">              
                <p class="instruccion">{{fSessionCI.nombres}}, finaliza tu compra!... Haz click en "FINALIZAR"</p>
                <p class="saludo">Vitacloud, Te Cuida!</p>
              </div>
            </div>
          </div>
          <div class="citas scroll-pane citas-large" style="">
            <p class="title">RESUMEN DE RESERVA</p>
            <ul class="list-citas">
                <li ng-repeat="(index,fila) in fSessionCI.compra.listaCitas" class="item-list-citas notification-{{fila.clase}}" >
                    <div class="cita" ng-click="" style="">
                      <span class="eliminar" ng-click="quitarDeLista(index,fila);"><i class="fa fa-times" style="color: #ce1d19;"></i></span>  
                      <div><i class="fa fa-stethoscope" style="color: #36c0d1;"></i>  Cita para:    <span class="cita-familiar">{{fila.busqueda.itemFamiliar.descripcion}}</span></div>
                      <div><i class="fa fa-hospital-o"  style="color: #ce1d19;"></i>  Sede:         <span class="cita-sede">{{fila.busqueda.itemSede.descripcion}}</span></div>
                      <div><i class="fa fa-edit"       style="color: #ffc107;"></i>  Especialidad: <span class="cita-esp">{{fila.busqueda.itemEspecialidad.descripcion}}</span></div>
                      <div><i class="fa fa-user-md"     style="color: #191970;"></i>  Médico:       <span class="cita-medico">{{fila.seleccion.medico}}</span></div>
                      <div><i class="fa fa-clock-o"     style="color: #929191;"></i>  Turno:        <span class="cita-turno">{{fila.seleccion.fecha_programada}} {{fila.seleccion.hora_formato}}</span></div>
                    </div>                            
                </li>
                <li class="media" ng-show="fSessionCI.compra.listaCitas.length < 1">
                    <div class="sin-citas"> Comienza a registrar tus citas... </div>
                </li>
            </ul>
            <div class="boton-finalizar" ng-show="fSessionCI.compra.listaCitas.length > 0">
              <a class="" ng-click="resumenReserva();" style="animation: pulse 2s ease infinite;">CONTINUAR  <i class="fa fa-angle-right"></i>
              </a>
            </div>            
          </div>
          <div class="citas-movil">
            <div class="boton-toggle" data-toggle="collapse" data-target="#citas">RESUMEN DE RESERVA
              <button type="button" class="btn btn-default btn-sm toggle-filtros">
                <i class="ti ti-more-alt"></i>  
              </button>
            </div>
            <div class="citas scroll-pane collapse in" id="citas" style="">            
              <ul class="list-citas">
                  <li ng-repeat="(index,fila) in fSessionCI.compra.listaCitas" class="item-list-citas notification-{{fila.clase}}" >
                      <div class="cita" ng-click="" style="">
                        <span class="eliminar" ng-click="quitarDeLista(index,fila);"><i class="fa fa-times" style="color: #ce1d19;"></i></span>  
                        <div><i class="fa fa-stethoscope" style="color: #36c0d1;"></i>  Cita para:    <span class="cita-familiar">{{fila.busqueda.itemFamiliar.descripcion}}</span></div>
                        <div><i class="fa fa-hospital-o"  style="color: #ce1d19;"></i>  Sede:         <span class="cita-sede">{{fila.busqueda.itemSede.descripcion}}</span></div>
                        <div><i class="fa fa-edit"       style="color: #ffc107;"></i>  Especialidad: <span class="cita-esp">{{fila.busqueda.itemEspecialidad.descripcion}}</span></div>
                        <div><i class="fa fa-user-md"     style="color: #191970;"></i>  Médico:       <span class="cita-medico">{{fila.seleccion.medico}}</span></div>
                        <div><i class="fa fa-clock-o"     style="color: #929191;"></i>  Turno:        <span class="cita-turno">{{fila.seleccion.fecha_programada}} {{fila.seleccion.hora_formato}}</span></div>
                      </div>                            
                  </li>
                  <li class="media" ng-show="fSessionCI.compra.listaCitas.length < 1">
                      <div class="sin-citas"> Comienza a registrar tus citas... </div>
                  </li>
              </ul>
              <div class="boton-finalizar" ng-show="fSessionCI.compra.listaCitas.length > 0">
                <a class="" ng-click="resumenReserva();" style="animation: pulse 2s ease infinite;">CONTINUAR  <i class="fa fa-angle-right"></i>
                </a>
              </div>          
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!--   <div class="burbble" style="background: url('assets/img/dinamic/empresa/alerta.png') no-repeat left top; position: relative;
    width: 200px;
    height: 200px;
    background-size: contain;"></div>  -->
</div>