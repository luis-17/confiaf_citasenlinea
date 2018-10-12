<div class="msj modal-close " >
	<a href ng-click="btnCancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="msj modal-header reprogramacion" style="padding-top: 50px;">
	<div class="filtros btn-group-btn pl-n ml-n">
		<button type="button" class="btn btn-info btn-sm toggle-filtros" data-toggle="collapse" data-target="#filtros-modal">
	      	<i class="ti ti-more-alt" style="font-size: 15px;margin-right: 0px; margin-left: 0px;" ></i>  
	    </button>
	    <ul class="demo-btns collapse in" id="filtros-modal" >			
			<!-- <li class="" >	<label class="control-label">Cita para:</label>		</li> -->
			<li class="item-filtro" >
				<label class="control-label">Cita para:</label>
				<select class="form-control" disabled ng-model="fBusquedaRep.itemFamiliar"
					ng-change="" 
					ng-options="item.descripcion for item in listaFamiliares">
				</select>
			</li>
			<!-- <li class="" >	<label class="control-label">en:</label>		</li> -->
			<li class="item-filtro" >
				<label class="control-label">en:</label>
				<select class="form-control " disabled ng-model="fBusquedaRep.itemSede"
					ng-change="listarEspecialidad();" 
					ng-options="item.descripcion for item in listaSedes">
				</select>
			</li>
			<li class="item-filtro" >
				<select class="form-control " disabled ng-model="fBusquedaRep.itemEspecialidad"
					ng-change="" 
					ng-options="item.descripcion for item in listaEspecialidadRep">
				</select>
			</li>
			<!-- <li class="" >	    		 
				<label class="control-label">Fecha:</label>	        
			</li> -->
			<li class="item-filtro item-fecha grid-fecha" >	    		 
				<label class="control-label">Fecha:</label>	
				<input type="text" placeholder="Fecha"  class="form-control datepicker " ng-change="cargarPlanning();"
                                uib-datepicker-popup="{{format}}" popup-placement="auto right-top"
                                ng-model="fBusquedaPlanning.desde" is-open="opened" style="width:50px !important;margin-right: 26px;"
                                datepicker-options="datePikerOptions" ng-required="true" date-disabled="disabled(date, mode)"
                                close-text="Cerrar" alt-input-formats="altInputFormats"
                                 />

                <span class="" style="position: relative;
                                float: right;
                                white-space: nowrap;
                                top: -25px;    
                                ">
	            	<button type="button" class="btn btn-page btn-sm" ng-click="openDP($event)">
	            		<i class="ti ti-calendar" style="font-size: 10px;"></i>
	            	</button>
	          	</span>
				<!-- <input type="text" class="form-control mask" ng-model="fBusquedaPlanning.desde" placeholder="Desde" data-inputmask="'alias': 'dd-mm-yyyy'" style="width:50px !important;" />  -->
			</li>
			<li class="item-filtro" >
				<label class="control-label">Médico:</label>
				<input type="text" ng-model="fBusquedaPlanning.medico" class="form-control " autocomplete="off"
					placeholder="Digite el Médico..." 
					typeahead-loading="loadingLocations" 
					uib-typeahead="item as item.medico for item in getMedicoAutocomplete($viewValue)" 
					typeahead-min-length="2" 
					typeahead-on-select="getSelectedMedico($item, $model, $label)"
					ng-change="fBusquedaPlanning.itemMedico = null;" />
			</li>
			<li class="item-filtro">
				<button class="btn btn-page btn-sm" ng-click="cargarPlanning();" ><i class="ti ti-search"></i></button>
			</li>
		</ul>
	</div>
</div>
<div class="msj modal-body row" style="font-size: initial;">
	<form class="row">				
		<div class="col-xs-12 col-sm-12 col-md-12">
          	<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1" ng-if="!fPlanning.mostrar && fPlanning.mostraralerta" >
	          <div class="alert alert-warning" >
	            No hay turnos diponibles con las opciones seleccionadas, intenta con otros parámetros... 
	          </div>
          	</div>
	        <div class="planning large" ng-class="{visible : fPlanning.mostrar}" ng-if="fPlanning.mostrar">            
	            <div class="block-visible-planning" scroller>
	              <div class="header">
	              <div class="desc-header fecha-header" style="width: 95px; ">
	                H./FECHAS
	              </div>                
	              <div ng-repeat="fecha in fPlanning.fechas" class="{{fecha.class}}">
	                <div class="cell-fecha">{{fecha.dato}}</div>
	              </div>                    
	            </div>
	            <div class="sidebar">              
	                <div ng-repeat="hora in fPlanning.horas" class="{{hora.class}}">
	                  <div class="cell-hora">{{hora.dato}}</div>
	                </div>                 
	              </div>

	              <div class="body"  >                              
	                <div ng-repeat="column in fPlanning.grid" class="column">
	                  <div ng-repeat="item in column" class="{{item.class}}" ng-if="!item.unset && fPlanning.grid[index][24].total != 0" style="height:{{30*item.rowspan}}px;" >
	                    <div class="content-cell-column" >
	                      <span class="favorito animation" uib-tooltip="{{fBusqueda.medico.medico}}" tooltip-placement="top" ng-if="item.medico_favorito">
	                      	<i class="fa fa-star" style="font-size: 15px;margin-right: 0px; margin-left: 0px;"></i>
	                      </span>
	                      <a href="" class="label label-info" ng-click="viewTurnos(item); $event.stopPropagation();">{{item.dato}} </a>
	                    </div>
	                  </div>
	                </div>       
	              </div> 
	            </div>
	            <div class="clearfix"></div>
	        </div>  

	        <div class="planning movil">
	            <div class="boton-toggle" data-toggle="collapse" data-target="#planning">Disponibilidad
	              <button type="button" class="btn btn-default btn-sm toggle-filtros">
	                <i class="ti ti-more-alt" style="font-size: 15px;margin-right: 0px; margin-left: 0px;"></i>  
	              </button>
	            </div>
	            <div class="block-visible-planning-movil collapse in" id="planning">
	              <div ng-repeat="(index, fecha) in fPlanning.fechas" class="bloque-planning" >
	                <div class="fecha">{{fecha.dato}}</div>
	                <div ng-repeat="item in fPlanning.grid[index]" class="bloque-item p-sm" ng-if="!item.unset && fPlanning.grid[index][24].total != 0" >
	                  <div class="content-cell-column" >
	                    <span class="favorito animation" uib-tooltip="{{fBusqueda.medico.medico}}" tooltip-placement="top" ng-if="item.medico_favorito">
	                    	<i class="fa fa-star" style="font-size: 15px;margin-right: 0px; margin-left: 15px;"></i>
	                    </span>
	                    <a href="" class="label label-info" ng-click="viewTurnos(item); $event.stopPropagation();">{{item.dato}} </a>
	                    <a href="" ng-if="item.dato != '' ">{{item.inicio_bloque.dato}} - {{item.fin_bloque.dato}}</a>
	                  </div>
	                </div>
	                <div class="bloque-item p-sm" ng-if="fPlanning.grid[index][24].total == 0">
	                  <div class="alert alert-warning">
	                    No hay turnos diponibles para esta fecha... 
	                  </div>
	                </div>
	              </div>

	              <div class="col-xs-12">
	                <div class="alert alert-warning" ng-if="!fPlanning.mostrar">
	                  no hay turnos diponibles con las opciones seleccionadas, intenta con otros parámetros... 
	                </div>
	              </div>
	              
	            </div>
	            <div class="clearfix"></div>
	        </div>        
        </div>
	</form>
</div>


