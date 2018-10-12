angular.module('theme.usuario', ['theme.core.services'])
  .controller('usuarioController', ['$scope', '$controller','$sce', '$filter','$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI', 
    'usuarioServices',
    'rootServices',
    function($scope, $controller, $sce, $filter, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI, 
      usuarioServices,
      rootServices
    ){ 
    'use strict'; 
    $scope.modulo = 'usuario';
    $scope.titleForm = 'Registro en Citas en Linea';
    //$scope.fDataUser = {}; 
    $scope.listaSexos = [
      {id:'-', descripcion:'SELECCIONE SEXO'},
      {id:'F', descripcion:'FEMENINO'},
      {id:'M', descripcion:'MASCULINO'}
    ];

    $scope.fDataUsuario = {};
    $scope.init = function(){
      blockUI.start('Cargando perfil...');
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fDataUser = response.datos;
          $scope.fSessionCI = response.datos;
          if(!$scope.fSessionCI.nombre_imagen || $scope.fSessionCI.nombre_imagen === ''){
            $scope.fSessionCI.nombre_imagen = 'noimage.jpg';
          }
        }
      });
      $scope.selectedTab = '0';
      $controller('parienteController', { 
        $scope : $scope
      });
      $scope.initPariente();
      blockUI.stop();
    }

    $scope.initPerfil = function(){
      $scope.listaTiposSangre = [
        {id:0, descripcion: 'SELECCIONE TIPO SANGRE'},
        {id:1, descripcion: 'A+'},
        {id:2, descripcion: 'A-'},
        {id:3, descripcion: 'B+'},
        {id:4, descripcion: 'B-'},
        {id:5, descripcion: 'O+'},
        {id:6, descripcion: 'O-'},
        {id:7, descripcion: 'AB+'},
        {id:8, descripcion: 'AB-'},
      ];
      $scope.fDataDashboard={};
      var ind = 0;
      angular.forEach($scope.listaTiposSangre, function(value, key) {
        if(value.id == $scope.fSessionCI.tipo_sangre.id){
          ind = key;
        }
      });
      $scope.fDataDashboard.tipo_sangre = $scope.listaTiposSangre[ind];
      $scope.fDataDashboard.peso = $scope.fSessionCI.peso;
      $scope.fDataDashboard.estatura = $scope.fSessionCI.estatura;

      $scope.fAlertPerfilCli = null;
      
      /*console.log($scope.fDataDashboard.tipo_sangre);
      console.log($scope.listaTiposSangre);*/
    }

    $scope.initRecaptchaReg = function () {
      var datos = {
        tipo: 'captcha'
      }
      rootServices.sGetConfig(datos).then(function(rpta){
        $scope.keyRecaptcha =  rpta.datos.KEY_RECAPTCHA;
          grecaptcha.render('recaptcha-registro', {
          'sitekey' : $scope.keyRecaptcha,
          'callback' : recaptchaResponseReg,
        });
      });
    }
    
    $scope.initRegistrarUsuario = function(){ 
      $scope.fDataUser = {}; 
      $scope.fDataUser.sexo = '-'; 
      $scope.captchaValidoReg = false; 
      $scope.acepta = false;  
    }

    $scope.verificarDoc = function(){
      // if(!$scope.fDataUser.num_documento || $scope.fDataUser.num_documento == null || $scope.fDataUser.num_documento == ''){
      //   $scope.fAlert = {};
      //   $scope.fAlert.type= 'danger';
      //   $scope.fAlert.msg='Debe ingresar un Número de documento.';
      //   $scope.fAlert.strStrong = 'Error';
      //   $scope.fAlert.icon = 'fa fa-exclamation';
      //   return;
      // }
      // usuarioServices.sVerificarUsuarioPorDocumento($scope.fDataUser).then(function (rpta) {              
      //   $scope.fAlert = {};
      //   if( rpta.flag == 2 ){ //Cliente registrado en Sistema Hospitalario
      //     $scope.fDataUser = rpta.usuario;
      //     $scope.fAlert.type= 'info';
      //     $scope.fAlert.msg= rpta.message;
      //     $scope.fAlert.icon= 'fa fa-smile-o';
      //     $scope.fAlert.strStrong = 'Genial! ';
      //   }else if( rpta.flag == 1 ){ // Usuario ya registrado en web
      //     //$scope.fDataUser = rpta.usuario;
      //     $scope.fAlert.type= 'danger';
      //     $scope.fAlert.msg= rpta.message;
      //     $scope.fAlert.strStrong = 'Aviso! ';
      //     $scope.fAlert.icon = 'fa  fa-exclamation-circle';
      //   }else if(rpta.flag == 0){
      //     var num_documento = $scope.fDataUser.num_documento;                
      //     $scope.fAlert.type= 'warning';
      //     $scope.fAlert.msg= rpta.message;
      //     $scope.fAlert.strStrong = 'Aviso! ';
      //     $scope.fAlert.icon = 'fa fa-frown-o';
      //     $scope.fDataUser = {};
      //     $scope.fDataUser.num_documento = num_documento;
      //     $scope.fDataUser.sexo = '-';
      //   }
      //   $scope.fAlert.flag = rpta.flag;
      // });
    }

    $scope.btnCancel = function(){
      $modalInstance.dismiss('btnCancel');
    }

    $scope.registrarUsuario = function (){ 
      $scope.crearAlerta = function(msg){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= msg;
        $scope.fAlert.strStrong = 'Error';
        $scope.fAlert.icon = 'fa fa-exclamation';
        return;
      }
      console.log($scope.fDataUser,'$scope.fDataUser');
      if(!$scope.fDataUser.num_documento || $scope.fDataUser.num_documento == null || $scope.fDataUser.num_documento == ''){          
        $scope.crearAlerta('Debe ingresar un Número de documento.');
        return;
      }

      if(!$scope.fDataUser.nombres || $scope.fDataUser.nombres == null || $scope.fDataUser.nombres == ''){
        $scope.crearAlerta('Debe ingresar Nombres.');
        return;
      }

      if(!$scope.fDataUser.apellido_paterno || $scope.fDataUser.apellido_paterno == null || $scope.fDataUser.apellido_paterno == ''){
        $scope.crearAlerta('Debe ingresar Apellido paterno.');
        return;
      }

      if(!$scope.fDataUser.apellido_materno || $scope.fDataUser.apellido_materno == null || $scope.fDataUser.apellido_materno == ''){
        $scope.crearAlerta('Debe ingresar Apellido materno.');
        return;
      } 

      if(!$scope.fDataUser.email || $scope.fDataUser.email == null || $scope.fDataUser.email == ''){
        $scope.crearAlerta('Debe ingresar E-mail.');
        return;
      }         

      if(!$scope.fDataUser.fecha_nacimiento || $scope.fDataUser.fecha_nacimiento == null || $scope.fDataUser.fecha_nacimiento == ''){
        $scope.crearAlerta('Debe ingresar Fecha Nacimiento.');
        return;
      }

      if(!$scope.fDataUser.celular || $scope.fDataUser.celular == null || $scope.fDataUser.celular == ''){
        $scope.crearAlerta('Debe ingresar Celular.');
        return;
      }         

      if($scope.fDataUser.sexo =='-'){
        $scope.crearAlerta('Seleccione sexo.');
        return;
      }

      if(!$scope.fDataUser.clave || $scope.fDataUser.clave == null || $scope.fDataUser.clave == ''
         || !$scope.fDataUser.repeat_clave || $scope.fDataUser.repeat_clave == null || $scope.fDataUser.repeat_clave == ''){
        $scope.crearAlerta('Debe ingresar Claves.');
        return;
      }

      if($scope.fDataUser.clave !== $scope.fDataUser.repeat_clave){
        $scope.crearAlerta('Las claves ingresadas no coinciden');
        return;
      }

      if(!$scope.captchaValidoReg){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= 'Debe completar reCaptcha';
        $scope.fAlert.strStrong = 'Error';
        return;
      }

      if(!$scope.acepta){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= 'Debe aceptar los Términos y Condiciones.';
        $scope.fAlert.strStrong = 'Error';
        return;
      }
      
      blockUI.start('Registrando usuario...');
      usuarioServices.sRegistrarUsuario($scope.fDataUser).then(function (rpta) {       
        if(rpta.flag == 0){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg= rpta.message;
          $scope.fAlert.strStrong = 'Error';
          $scope.fAlert.icon = 'fa fa-exclamation';
        }else if(rpta.flag == 1){
          $scope.fDataUser = {};
          $scope.fDataUser.sexo = '-';
          $scope.fAlert = {};
          $scope.btnViewLogin();
          $uibModal.open({ 
            templateUrl: angular.patchURLCI+'Usuario/ver_popup_registro_exitoso',
            size: 'lg',
            //backdrop: 'static',
            //keyboard:false,
            scope: $scope,
            controller: function ($scope, $modalInstance) {                 
              $scope.titleForm = 'Genial! '; 
              $scope.msj = rpta.message;

              $scope.btnCancel = function(){
                $modalInstance.dismiss('btnCancel');
              }
            }
          });
        }
        blockUI.stop(); 
      });
    } 

    $scope.closeAlert = function() {
        $scope.fAlert = null;
    }

    $scope.btnActualizarDatosCliente = function(){
      blockUI.start('Actualizando datos...');
      usuarioServices.sActualizarDatosCliente($scope.fDataUser).then(function (rpta) {       
        if(rpta.flag == 0){
          $scope.fAlert = {};
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg= rpta.message;
          $scope.fAlert.strStrong = 'Error';
          $scope.fAlert.icon = 'fa fa-exclamation'; 
          setTimeout(function() {
                $scope.closeAlert();
              }, 15000);        
        }else if(rpta.flag == 1){
          var msg =  rpta.message;
          usuarioServices.sRecargarUsuarioSession($scope.fDataUser).then(function (rpta) {
            if(rpta.flag == 1){
              $scope.init();
              $scope.fAlert = {};
              $scope.fAlert.type= 'success';
              $scope.fAlert.msg= msg;
              $scope.fAlert.icon= 'fa fa-smile-o';
              $scope.fAlert.strStrong = 'Genial! ';
              setTimeout(function() {
                $scope.closeAlert();
              }, 15000);
            } else{
              alert('Error inesperado');
            }           
          });
        }
        blockUI.stop();                
      });
    }

    $scope.btnActualizarClave = function (){
      $scope.closeAlertClave = function() {
        $scope.fAlertClave = null;
      }

      $scope.fDataUsuario.miclave = 'si';
      blockUI.start('Actualizando datos...');
      usuarioServices.sActualizarPasswordUsuario($scope.fDataUsuario).then(function (rpta){
        if(rpta.flag == 1){
          $scope.fAlertClave = {};
          $scope.fAlertClave.type= 'success';
          $scope.fAlertClave.msg= rpta.message;
          $scope.fAlertClave.strStrong = 'Genial.';   
          $scope.fDataUsuario = {};       
        }else if(rpta.flag == 2){
          $scope.fDataUsuario.clave = null;
          $scope.fAlertClave = {};
          $scope.fAlertClave.type= 'warning';
          $scope.fAlertClave.msg= rpta.message;
          $scope.fAlertClave.strStrong = 'Advertencia.';
        }else if(rpta.flag == 0){
          $scope.fDataUsuario.claveNueva = null;
          $scope.fDataUsuario.claveConfirmar = null;
          $scope.fAlertClave = {};
          $scope.fAlertClave.type= 'danger';
          $scope.fAlertClave.msg= rpta.message;
          $scope.fAlertClave.strStrong = 'Error. ';
          setTimeout(function() {
            $('#nuevoPass').focus();
          }, 500);
        }else{
          alert('Error inesperado');
        } 
        blockUI.stop();
        setTimeout(function() {
            $scope.closeAlertClave();
          }, 1000);              
      });            
    }

    $scope.btnCambiarMiFotoPerfil = function (usuario, session){          
      blockUI.start('Abriendo formulario...');
      $uibModal.open({
        templateUrl: angular.patchURLCI+'usuario/ver_popup_foto_perfil',
        controller: function ($scope, $modalInstance) {
          $scope.titleForm = 'Cambiar Foto de perfil';
          $scope.dataUsuario = usuario; 
          $scope.session = session;
          $scope.closeAlertSubida = function() {
            $scope.fAlertSubida = null;
          }

          $scope.aceptarSubida = function (){
            blockUI.start('Subiendo Archivo...');
            var formData = new FormData();                  
            angular.forEach($scope.fDataSubida,function (val,index) {              
              formData.append(index,val);
            });
            
            usuarioServices.sSubirFotoPerfil(formData).then(function (rpta) { 
              var nuevoArchivo = rpta.nuevoNombre;
              if(rpta.flag == 1){                
                $scope.cancelSubida();                
                usuarioServices.sRecargarUsuarioSession($scope.dataUsuario).then(function (rpta) {
                  if(rpta.flag == 1){
                    $scope.session.nombre_imagen = nuevoArchivo;
                    $window.location.reload();
                  } else{
                    alert('Error inesperado');
                  }           
                });                
              }else if(rpta.flag == 0){
                $scope.fAlertSubida = {};
                $scope.fAlertSubida.type= 'warning';
                $scope.fAlertSubida.msg= rpta.message;
                $scope.fAlertSubida.strStrong = 'Advertencia.';
              }else{
                alert('Error inesperado');
              }

              blockUI.stop();
              setTimeout(function() {
                $scope.closeAlertSubida();
              }, 1000); 
            });
          }

          $scope.cancelSubida = function () {
            $modalInstance.dismiss('cancelSubida');
            $scope.fDataSubida = {};
          }
          blockUI.stop();
        }
      });      
    } 

    $scope.btnActualizarPerfilClinico = function (){
      blockUI.start('Actualizando datos...');
      $scope.fAlertPerfilCli = null;
      usuarioServices.sActualizarPerfilClinico($scope.fDataDashboard).then(function(rpta){
        var msg = rpta.message;
        if(rpta.flag == 1){
          usuarioServices.sRecargarUsuarioSession($scope.fSessionCI).then(function(rpta){
            if(rpta.flag == 1){
              $scope.fSessionCI = rpta.datos;
              $scope.initPerfil();
              $scope.fAlertPerfilCli = {};
              $scope.fAlertPerfilCli.type= 'success';
              $scope.fAlertPerfilCli.msg= msg;
              $scope.fAlertPerfilCli.strStrong = 'Genial!';              
            } 
          });
        }else{
          $scope.fAlertPerfilCli = {};
          $scope.fAlertPerfilCli.type= 'warning';
          $scope.fAlertPerfilCli.msg= rpta.message;
          $scope.fAlertPerfilCli.strStrong = 'Advertencia.';
        }
        blockUI.stop();
      });
    } 

  }])
  .service("usuarioServices",function($http, $q) {
    return({
      sVerificarUsuarioPorDocumento:sVerificarUsuarioPorDocumento,
      sRegistrarUsuario: sRegistrarUsuario,
      sActualizarDatosCliente:sActualizarDatosCliente,
      sRecargarUsuarioSession: sRecargarUsuarioSession,
      sActualizarPasswordUsuario: sActualizarPasswordUsuario,
      sSubirFotoPerfil:sSubirFotoPerfil,
      sActualizarPerfilClinico: sActualizarPerfilClinico,
    });
    function sVerificarUsuarioPorDocumento(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/verificar_usuario_por_documento", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sRegistrarUsuario(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/registrar_usuario", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sActualizarDatosCliente(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/actualizar_datos_cliente", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sRecargarUsuarioSession(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/recargar_usuario_session", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }     
    function sActualizarPasswordUsuario(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/actualizar_password_usuario", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sSubirFotoPerfil(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/subir_foto_perfil", 
            data : datos,
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sActualizarPerfilClinico(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Usuario/actualizar_perfil_clinico", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
  }); 