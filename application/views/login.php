<div class="container page-login"  ng-controller="loginController">
    <style type="text/css">
        .static-content-wrapper {
            padding: 0;
            margin-bottom: 0;
            background: url("{{ dirImages + 'dinamic/empresa/banner.jpg'}}") no-repeat left top;
            background-size: cover;
            background-position-x: -100px;
        }

        @media only screen and (max-width : 991px) {
          .static-content-wrapper{
            background: none;
          }  

          .contenedor-formularios{
            padding-left: 0; 
          }
        }

        @media only screen and (min-width : 1200px) {
          .static-content-wrapper{
            background-position-x: 0px;
          }  
        }
    </style>
    <div class="row">                   
        <div class="" style="margin: 0 auto;"> 
            <div class="contenedor-formularios" style="" > 
                <div class="formulario formulario-login" ng-show="!viewRegister" style="width: 335px; margin: 0 auto;">                
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Bienvenido
                        </div>
                        <div class="panel-body">                        
                            <form action="" class="form-horizontal" id="validate-form" novalidate>
                                <div class="form-group mb-md">
                                    <div class="col-xs-12">
                                        <input ng-model="fLogin.usuario" type="text" class="form-control" placeholder="N° documento" data-parsley-minlength="6" focus-me enter-as-tab/>
                                        
                                    </div>
                                </div>
                                <div class="form-group mb-md">
                                    <div class="col-xs-12">
                                        <input ng-model="fLogin.clave" type="password" class="form-control" id="exampleInputPassword1" placeholder="Clave" ng-enter="btnLoginToSystem(); $event.preventDefault();"/>
                                        
                                    </div>
                                </div>                       

                                <div class="form-group mb-n">
                                    <div class="col-xs-12">
                                        <div id="recaptcha-login" class="g-recaptcha" data-ng-controller="loginController" data-ng-init="initLoginRecaptcha()"
                                            data-sitekey="{{keyRecaptcha}}" data-callback="recaptchaResponse"></div> 
                                    </div>
                                </div>  

                                <uib-alert type="{{fAlert.type}}" close="fAlert = null;" ng-show='fAlert.type' class="p-sm">
                                    <strong> {{ fAlert.strStrong }} </strong> <span ng-bind-html="fAlert.msg"></span>
                                </uib-alert>               
                                                      
                            </form>
                        </div>
                        
                        <div class="panel-footer">
                            <div class="clearfix">
                                <button class="btn btn-page" style="width:100%;"  ng-click="btnLoginToSystem(); $event.preventDefault();" > Iniciar sesión </button> 
                            </div>

                            <div class="col-xs-12 set-password">
                                <a ng-click="btnResendPass();" class="link-password">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                        <div class="col-xs-12 btn-registro">
                            <a href="" ng-click="fLogin = null; fAlert=null; captchaValido=false; btnViewRegister(); $event.preventDefault();">
                                ¿No tienes cuenta? Regístrate Aquí <i class="fa fa-angle-right"></i>
                            </a>
                        </div> 
                    </div>            
                </div>
                <div class="capa-info" ng-show="!viewRegister">            
                    <div class="info-heading">
                        Gestiona tus citas y las de tus familiares, desde la comodidad de tu hogar.
                        <!-- <a href="" class="btn btn-page" ng-click="btnRegistroEnSistema(); $event.preventDefault();">Registrarse</a>  -->                                  
                    </div>
                    <div class="info-subheading">
                        Disfruta los beneficios de ser un paciente de Vitacloud... 
                    </div>
                    <div class="info-lema">
                        Vitacloud, Te Cuida!
                    </div>                
                </div>

                <div class="formulario formulario-registro" ng-show="viewRegister" ng-controller="usuarioController">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a style="" ng-click="fAlert = null; fDataUser=null; btnViewLogin();" class="ir-atras">
                                <i class="fa fa-angle-left"></i>
                            </a>{{ titleForm }} 
                        </div>
                        <div class="panel-body" style="padding-bottom: 0px;padding-top: 0px;">
                            <form class="" name="formUsuario" novalidate>
                                <div class="row">
                                    <div class="col-md-12">
                                        <uib-alert type="{{fAlert.type}}" close="fAlert = null" ng-show='fAlert.type' class="p-sm mb-n" style="margin-right: 12px; margin-bottom: 10px !important;">
                                            <strong> {{ fAlert.strStrong }} <i class='{{fAlert.icon}}'></i></strong> 
                                            <span ng-bind-html="fAlert.msg"> </span>
                                        </uib-alert>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs"> Doc. de Identidad </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control input-sm" ng-model="fDataUser.num_documento" placeholder="Ingresa tu DNI ó Documento de Identidad" tabindex="1" 
                                                ng-enter="verificarDoc(); $event.preventDefault();" focus-me /> 
                                            <div class="input-group-btn ">
                                                <button type="button" class="btn btn-default btn-sm" ng-click="verificarDoc(); $event.preventDefault();" ><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <!-- <input ng-init="verificaDNI();" type="text" class="form-control input-sm" ng-model="fData.num_documento" placeholder="Registre su dni" tabindex="1" focus-me ng-minlength="8" ng-pattern="/^[0-9]*$/" ng-change="verificaDNI();" />  -->
                                    </div>                                 
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs">Nombres <small class="text-danger">(*)</small> </label>
                                        <input type="text" class="form-control input-sm" ng-model="fDataUser.nombres" placeholder="Ingresa tus nombre" 
                                            ng-focus="verificarDoc(); $event.preventDefault();"  
                                            ng-click="verificarDoc(); $event.preventDefault();"
                                            required tabindex="2" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs">Apellido Paterno <small class="text-danger">(*)</small> </label>
                                        <input type="text" class="form-control input-sm" ng-model="fDataUser.apellido_paterno" placeholder="Ingresa tu apellido paterno" required tabindex="3" /> 
                                    </div>
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs">Apellido Materno <small class="text-danger">(*)</small> </label>
                                        <input type="text" class="form-control input-sm" ng-model="fDataUser.apellido_materno" placeholder="Ingresa tu apellido materno" required tabindex="4" /> 
                                    </div>          
                                </div>      

                                <div class="row">
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12" >
                                        <label class="control-label mb-xs">E-mail <small class="text-danger">(*)</small></label>
                                        <input type="email" class="form-control input-sm" ng-model="fDataUser.email" placeholder="Ingresa tu e-mail" required tabindex="5" />
                                    </div>                                   

                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12" >
                                        <label class="control-label mb-xs">Fecha Nacimiento <small class="text-danger">(*)</small> </label>  
                                        <input type="text" class="form-control input-sm mask" data-inputmask="'alias': 'dd-mm-yyyy'" placeholder="Ingresa tu fecha de nacimiento" ng-model="fDataUser.fecha_nacimiento" required tabindex="6"/> 
                                    </div>
                                </div>

                                <div class="row">                                   

                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs">Teléfono Móvil <small class="text-danger">(*)</small> </label>
                                        <input type="tel" class="form-control input-sm" ng-model="fDataUser.celular" placeholder="Ingresa tu celular" required tabindex="7" />
                                    </div>
                                    <!-- <div class="form-group mb-md col-md-6">
                                        <label class="control-label mb-xs">Teléfono Casa  </label>
                                        <input type="tel" class="form-control input-sm" ng-model="fDataUser.telefono" placeholder="Ingresa tu teléfono" ng-minlength="6" tabindex="9" />
                                    </div> -->
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12" >
                                        <label class="block" style="margin-bottom: 4px;"> Sexo <small class="text-danger">(*)</small> </label>
                                        <select class="form-control input-sm" ng-model="fDataUser.sexo" ng-options="item.id as item.descripcion for item in listaSexos" tabindex="8" required > </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs">Contraseña <small class="text-danger">(*)</small> </label>
                                        <input type="password" class="form-control input-sm" ng-model="fDataUser.clave" placeholder="Contraseña" 
                                               required tabindex="9" tooltip-placement="top-left" 
                                               uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/> 
                                    </div>

                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label mb-xs">Repita Contraseña <small class="text-danger">(*)</small> </label>
                                        <input type="password" class="form-control input-sm" ng-model="fDataUser.repeat_clave" placeholder="Repita Contraseña" 
                                            required tabindex="10" tooltip-placement="top-left" 
                                               uib-tooltip="Por seguridad, te recomendamos que tu contraseña sea de 8 caracteres y contenga al menos 1 mayúscula, 1 minúscula y 1 número"/>  
                                    </div>                  
                                </div>

                                <div class="row">
                                    <div class="form-group mb-md col-md-6 col-sm-6 col-xs-12">
                                        <div id="recaptcha-registro" data-ng-controller="usuarioController" data-ng-init="initRecaptchaReg();"
                                                class="g-recaptcha" data-sitekey="{{keyRecaptcha}}" data-callback="recaptchaResponse"></div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="form-group mb-md col-md-12 col-sm-12 col-xs-12">
                                        <input type="checkbox" ng-model="acepta"></input> Acepto los <a style="font-size: 17px;color: #36c5df;font-weight:600;text-decoration: underline;" target="_blank" href="#">Términos y Condiciones</a> establecidos.
                                    </div>
                                </div>                             


                            </form>
                        </div>

                        <div class="col-xs-12 btn-registro">
                            <a href="" ng-click="registrarUsuario(); $event.preventDefault();" ng-disabled="formUsuario.$invalid" tabindex="14">
                                REGISTRARME 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>     
    </div>
</div>