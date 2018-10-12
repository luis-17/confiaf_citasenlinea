<div class="content container "  ng-controller="usuarioController" ng-init="init();" >
    <div class="perfil row">
        <div class="col-md-2 col-sm-12 col-xs-12 pl-n">
            <div class="panel-profile" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);background-color:transparent;border:none;">
              <div class="panel-body">
                <img  masked-image="" class="img-circle" ng-src="{{ dirImages + 'dinamic/usuario/' + fSessionCI.nombre_imagen }}" alt=" {{ fSessionCI.username }} " /> 
                <div class="col-sm-12">
                    <a href style="color:#ce1d19;" ng-click="btnCambiarMiFotoPerfil(fDataUser,fSessionCI);">
                        <i class="ti ti-camera"></i> Subir foto
                    </a>               
                </div>                      
              </div>                      
            </div>
            <div class="list-group list-group-alternate mb-n nav nav-tabs">
                <a href="" ng-click="selectedTab='0'" ng-class="{active: selectedTab=='0'}" class="list-group-item "><i class="ti ti-pencil"></i> EDITAR</a>
                <a href="" ng-click="selectedTab='1'; refreshListaParientes();" ng-class="{active: selectedTab=='1'}" class="list-group-item "><i class="fa fa-users"></i> FAMILIARES</a>
                <a href="" ng-click="selectedTab='2'; initPerfil();" ng-class="{active: selectedTab=='2'}" class="list-group-item"><i class="ti ti-check-box"></i> PERFIL CLÍNICO</a>
            </div>
            <div class="panel-profile score-puntos" >                        
            </div>
        </div><!-- col-sm-3 -->
        <div class="col-md-8 col-sm-12 col-xs-12" style="padding-bottom:20px;">
            <div class="tab-content">
                <div class="tab-pane " ng-class="{active: selectedTab=='0'}">
                    <div class="panel panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);background-color:transparent;">
                        <div class="panel-body" style="background-color:transparent;">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row"> 
                                    <div class="tab-heading col-sm-7">                                         
                                        <div>
                                            <h2 class="title">
                                                <span class="icon"><i class="ti ti-pencil"></i></span>Editar
                                            </h2> 
                                            <p class="descripcion">Queremos saber más de ti.</p>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">                                                                                           
                                        <div class="row"> 
                                            <alert type="{{fAlert.type}}" close="fAlert = null;" ng-show='fAlert.type' class="p-sm">
                                                <strong> {{ fAlert.strStrong }} </strong> <span ng-bind-html="fAlert.msg"></span>
                                            </alert>                                                   
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label mb-xs"> DNI ó Documento de Identidad </label>
                                                <input type="text" class="form-control " ng-model="fDataUser.num_documento" required disabled tabindex="1" />
                                            </div>                                                       

                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
                                                <input type="text" class="form-control " ng-model="fDataUser.nombres" placeholder="Registre su nombre" required tabindex="2" />
                                            </div>
                                      
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
                                                <input type="text" class="form-control " ng-model="fDataUser.apellido_paterno" placeholder="Registre su apellido paterno" required tabindex="3" /> 
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
                                                <input type="text" class="form-control " ng-model="fDataUser.apellido_materno" placeholder="Registre su apellido materno" required tabindex="4" /> 
                                            </div>          
                                        
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12" >
                                                <label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label>
                                                <input type="email" class="form-control " ng-model="fDataUser.email" required disabled tabindex="5" />
                                            </div>

                                            <div class="form-group col-md-3 col-sm-6 col-xs-12" >
                                                <label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
                                                <select class="form-control " ng-model="fDataUser.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="6" required > </select>
                                            </div>

                                            <div class="form-group col-md-3 col-sm-6 col-xs-12" >
                                                <label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
                                                <input type="text" class="form-control  mask" data-inputmask="'alias': 'dd-mm-yyyy'" ng-model="fDataUser.fecha_nacimiento" required tabindex="7"/> 
                                            </div>
                                        
                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label>
                                                <input type="tel" class="form-control " ng-model="fDataUser.celular" placeholder="Registre su celular" ng-minlength="9" required tabindex="8" />
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label mb-xs">Teléfono Casa  </label>
                                                <input type="tel" class="form-control " ng-model="fDataUser.telefono" placeholder="Registre su teléfono" ng-minlength="6" tabindex="9" />
                                            </div> 
                                            <div class="col-sm-12 ">
                                                <button class="btn btn-blue  pull-left" 
                                                        ng-click="btnActualizarDatosCliente(); $event.preventDefault();">
                                                        <i class="fa fa-refresh"></i> Actualizar Datos
                                                </button>
                                            </div>                 
                                        </div>                                                                                               
                                    </div>                                            
                                </div>
                                <div class="row">
                                    <div class="col-sm-12"> 
                                        <div class="row"> 
                                            <h4>Información de cuenta</h4>
                                            <div class="col-sm-12">
                                                <alert type="{{fAlertClave.type}}" close="fAlertClave = null" ng-show='fAlertClave.type' class="p-sm">
                                                    <strong> {{ fAlertClave.strStrong }} <i class='{{fAlertClave.icon}}'></i></strong> 
                                                    <span ng-bind-html="fAlertClave.msg"> </span>
                                                </alert>
                                                <div class="row">
                                                    <div class="form-group col-sm-12">
                                                        <div class="row">
                                                            <div class="form-group mb-n col-md-4 col-sm-12 col-xs-12">
                                                                <label class="control-label mb-xs">Contraseña Actual <small class="text-danger">(*)</small> </label> 
                                                                <input id="clave" required type="password" class="form-control " ng-model="fDataUsuario.clave" placeholder="Ingresa su contraseña actual" />
                                                            </div>
                                                    
                                                            <div class="form-group mb-n col-md-4 col-sm-12 col-xs-12">
                                                            <label class="control-label mb-xs">Nueva Contraseña <small class="text-danger">(*)</small> </label> 
                                                            <input id="nuevoPass" required ng-minlength="8" type="password" class="form-control " ng-model="fDataUsuario.claveNueva" 
                                                                placeholder="Nueva contraseña (Min 8 caracteres)" tooltip-placement="top-left" 
                                                                uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>
                                                            </div>
                                                    
                                                            <div class="form-group mb-n col-md-4 col-sm-12 col-xs-12">
                                                                <label class="control-label mb-xs">Confirmar Nueva Contraseña <small class="text-danger">(*)</small> </label> 
                                                                <input required ng-minlength="8" type="password" class="form-control " ng-model="fDataUsuario.claveConfirmar" placeholder="Confirme su nueva contraseña" />
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                                                                   
                                            </div>

                                            <div class="col-sm-12 ">
                                                <button class="btn btn-blue pull-left" 
                                                        ng-click="btnActualizarClave(); $event.preventDefault();">
                                                        <i class="fa fa-refresh"></i> Actualizar Clave
                                                </button>
                                            </div>                                                                                        
                                        </div>                                                                                        
                                    </div>                                                                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane " ng-class="{active: selectedTab=='1'}">
                    <div class="panel panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                        <div class="panel-body">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="tab-heading col-sm-7">                                        
                                        <div>
                                            <h2 class="title">
                                                <span class="icon"><i class="fa fa-users"></i></span> Gestionar Familiares
                                            </h2> 
                                            <p class="descripcion">Puedes agregar a tus familiares y agendar citas para ellos.</p>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                          
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="header-parientes btn-group-btn ">
                                            <button type="button" class="btn btn-page" ng-click="btnNuevoPariente();"><i class="fa fa-plus"></i> NUEVO</button>
                                        </div>
                                        <alert type="{{fAlertFam.type}}" close="fAlertFam = null;" ng-show='fAlertFam.type' class="p-sm">
                                            <strong> {{ fAlertFam.strStrong }} </strong> <span ng-bind-html="fAlertFam.msg"></span>
                                        </alert> 
                                        <div class="mi-grid grid-citas " scroller >
                                          <div class="body-grid" style="min-height: 100px;">
                                            <div class="header row-grid row-cita">
                                                <div class="cell-grid cell-cita" style="width:8%;">
                                                  ID
                                                </div>
                                                <div class="cell-grid cell-cita" style="width:23%;">
                                                  Nombres
                                                </div>
                                                <div class="cell-grid cell-cita" style="width:23%;">
                                                  Apellidos
                                                </div>
                                                <div class="cell-grid cell-cita" style="width:15%;">
                                                  Parentesco
                                                </div>
                                                <div class="cell-grid cell-cita" style="width:15%;">
                                                  sexo
                                                </div>                                                
                                                <div class="cell-grid cell-cita" style="width:16%;">                                              
                                                </div>
                                            </div>
                                            <div ng-repeat="row in listaParientes" class="row-grid row-cita">
                                              <div class="cell-grid cell-cita" style="width:8%;">
                                                {{row.idusuariowebpariente}}
                                              </div>
                                              <div class="cell-grid cell-cita" style="width:23%;">
                                                {{row.nombres}}
                                              </div>
                                              <div class="cell-grid cell-cita" style="width:23%;">
                                                {{row.apellido_paterno}} {{row.apellido_materno}}
                                              </div>
                                              <div class="cell-grid cell-cita" style="width:15%;">
                                                {{row.parentesco}}
                                              </div>
                                              <div class="cell-grid cell-cita" style="width:15%;">
                                                <span><i class="{{row.icon}}" style="color:{{row.color_sexo}};margin-right:3px;"></i></span>{{row.desc_sexo}}
                                              </div>                                              
                                              <div class="cell-grid cell-cita" style="width:16%; text-align:center;">
                                                <button class="btn btn-warning btn-sm"  ng-click="btnGenerarCita(row)" ><i class="fa fa-calendar"></i></button>
                                                <button class="btn btn-info btn-sm"     ng-click="btnEditarPariente(row);"><i class="ti ti-pencil"></i></button>
                                                <button class="btn btn-danger btn-sm"   ng-click="btnEliminarPariente(row);"><i class="ti ti-close"></i></button>
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
            </div>
            <div class="tab-content">
                <div class="tab-pane " ng-class="{active: selectedTab=='2'}" ng-controller="usuarioController">
                    <div class="panel panel-default" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                        <div class="panel-body">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="tab-heading col-sm-7">                                        
                                        <div>
                                            <h2 class="title">
                                                <span class="icon"><i class="ti ti-check-box"></i></span> Perfil Clínico
                                            </h2> 
                                            <p class="descripcion">Mantén tu perfil clínico actualizado</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                              
                                    <div class="col-md-12 col-xs-12 col-sm-12 dashboard">                                              
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12 col-sm-12">
                                                <div class="valores">
                                                    <div class="item">
                                                        <div class="imagen">
                                                            <img src="{{ dirImages + 'dashboard/icon-peso.png' }}"  />
                                                        </div>
                                                        <div class="value">
                                                            <span class="title">Peso</span>
                                                            <div class="data">{{fSessionCI.peso}} <span class="medida" >Kg.</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="item">
                                                        <div class="imagen">
                                                            <img src="{{ dirImages + 'dashboard/icon-estatura.png' }}"  />
                                                        </div>
                                                        <div class="value">
                                                            <span class="title">Estatura</span>
                                                            <div class="data">{{fSessionCI.estatura}} <span class="medida" >Mts.</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="item imc" uib-tooltip="Índice de Masa Corporal (IMC): {{fSessionCI.imc.tipo}}" tooltip-placement="top">
                                                        <div class="imagen">
                                                            <img src="{{ dirImages + 'dashboard/icon-imc.png' }}"  />
                                                        </div>
                                                        <div class="value" >
                                                            <span class="title">IMC</span>
                                                            <span class="alerta" style="background:{{fSessionCI.imc.color}};" ng-class="{animation: fSessionCI.imc.dato < 18 || fSessionCI.imc.dato > 24.9}" ></span>
                                                            <div class="data" style="color:{{fSessionCI.imc.color}};" >
                                                                {{fSessionCI.imc.dato}} <!-- <span class="medida" ></span> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="item">
                                                        <div class="imagen">
                                                            <img src="{{ dirImages + 'dashboard/icon-tipo-sangre.png' }}"  />
                                                        </div>
                                                        <div class="value">
                                                            <span class="title">Tipo de Sangre</span>
                                                            <div class="data">{{fSessionCI.tipo_sangre.descripcion}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="item">
                                                        <div class="imagen">
                                                            <img src="{{ dirImages + 'dashboard/icon-sexo-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
                                                        </div>
                                                        <div class="value">
                                                            <span class="title">Sexo</span>
                                                            <div class="data">{{fSessionCI.sexo}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="item">
                                                        <div class="imagen">
                                                            <img src="{{ dirImages + 'dashboard/icon-edad-' +  fSessionCI.sexo.toLowerCase() + '.png' }}"  />
                                                        </div>
                                                        <div class="value">
                                                            <span class="title">Edad</span>
                                                            <div class="data">{{fSessionCI.edad}} <span class="medida" >años</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-9 col-xs-12 col-sm-12 edit-dashboard"> 
                                        <alert type="{{fAlertPerfilCli.type}}" close="fAlertPerfilCli = null;" ng-show='fAlertPerfilCli.type' class="p-sm">
                                            <strong> {{ fAlertPerfilCli.strStrong }} </strong> <span ng-bind-html="fAlertPerfilCli.msg"></span>
                                        </alert>
                                        <div class="row">
                                            <div class="form-group col-md-4 col-sm-12">
                                                <label class="control-label mb-xs">Peso (Kg) <small class="text-danger">(*)</small> </label>
                                                <input type="text" class="form-control " ng-model="fDataDashboard.peso" placeholder="Ingresa tu peso" required tabindex="1" /> 
                                            </div>          
                                        
                                            <div class="form-group col-md-4 col-sm-12" >
                                                <label class="control-label mb-xs">Estatura (Mts.) <small class="text-danger">(*)</small> </label>
                                                <input type="text" class="form-control " ng-model="fDataDashboard.estatura" placeholder="Ingresa tu estatura" required tabindex="2" />
                                            </div>

                                            <div class="form-group col-md-4 col-sm-12" >
                                                <label class="block" style="margin-bottom: 4px;"> Tipo de sangre <small class="text-danger">(*)</small> </label>
                                                <select class="form-control " ng-model="fDataDashboard.tipo_sangre" ng-options="item.descripcion for item in listaTiposSangre" tabindex="3" required > </select>
                                            </div>

                                            <div class="col-md-12 col-sm-12 col-xs-12 ">
                                                <button class="btn btn-blue  pull-left" 
                                                        ng-click="btnActualizarPerfilClinico(); $event.preventDefault();">
                                                        <i class="fa fa-refresh"></i> Actualizar 
                                                </button>
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
    </div>
</div>
