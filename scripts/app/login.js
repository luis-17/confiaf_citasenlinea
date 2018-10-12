angular.module('theme.login', ['theme.core.services'])
  .controller('loginController', function($scope, $theme, $controller, $uibModal, blockUI, loginServices, rootServices ){
    //'use strict';
    $theme.set('fullscreen', true);

    $scope.$on('$destroy', function() {
      $theme.set('fullscreen', false);
    });
    $scope.modulo='login';    

    $scope.initLoginRecaptcha = function() {      
      var datos = {
        tipo: 'captcha'
      }
      rootServices.sGetConfig(datos).then(function(rpta){
        $scope.keyRecaptcha =  rpta.datos.KEY_RECAPTCHA;
        grecaptcha.render('recaptcha-login', {
          'sitekey' : $scope.keyRecaptcha,
          'callback' : recaptchaResponse,
        });
      });            
    };

    $scope.fLogin = {};
    
    $scope.logOut();
    $scope.btnLoginToSystem = function () {
      if($scope.fLogin.usuario == null || $scope.fLogin.clave == null){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= 'Debe completar los campos usuario y clave.';
        $scope.fAlert.strStrong = 'Error.';
        return;
      }

      if(!$scope.captchaValido){
        $scope.fAlert = {};
        $scope.fAlert.type= 'danger';
        $scope.fAlert.msg= 'Debe completar reCaptcha';
        $scope.fAlert.strStrong = 'Error.';
        return;
      }

      loginServices.sLoginToSystem($scope.fLogin).then(function (response) { 
        $scope.fAlert = {};
        if( response.flag == 1 ){ // SE LOGEO CORRECTAMENTE 
          $scope.fAlert.type= 'success';
          $scope.fAlert.msg= response.message;
          $scope.fAlert.strStrong = 'OK.';
          $scope.getValidateSession();
          $scope.logIn();
          // $scope.getNotificaciones();
        }else if( response.flag == 0 ){ // NO PUDO INICIAR SESION 
          $scope.fAlert.type= 'danger';
          $scope.fAlert.msg= response.message;
          $scope.fAlert.strStrong = 'Error.';
        }else if( response.flag == 2 ){  // CUENTA INACTIVA
          $scope.fAlert.type= 'warning';
          $scope.fAlert.msg= response.message;
          $scope.fAlert.strStrong = 'Información.';
          $scope.listaSedes = response.datos;
        }
        $scope.fAlert.flag = response.flag;
        //$scope.fLogin = {};
      });
    }

    $scope.btnResendPass = function (){
      $scope.fRecuperaDatos = {};
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Acceso/ver_popup_formulario_password',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = 'Generar nueva contraseña';   

          $scope.btnCancel = function(){
            $scope.fAlertPass = null;
            $modalInstance.dismiss('btnCancel');            
          }

          $scope.generaNewPassword = function(){
            blockUI.start('Enviando nueva contraseña...'); 
            loginServices.sGeneraNewPassword($scope.fRecuperaDatos).then(function(response){
              $scope.fAlertPass = {};
              if( response.flag == 1 ){ // SE GENERO CORRECTAMENTE 
                $scope.fAlertPass.type= 'success';
                $scope.fAlertPass.msg= response.message;
                $scope.fAlertPass.strStrong = 'Genial! ';                
              }else if( response.flag == 0 ){ // NO PUDO GENERAR
                $scope.fAlertPass.type= 'danger';
                $scope.fAlertPass.msg= response.message;
                $scope.fAlertPass.strStrong = 'Error. ';
              }else if( response.flag == 2 ){  // OTRA COSA
                $scope.fAlertPass.type= 'warning';
                $scope.fAlertPass.msg= response.message;
                $scope.fAlertPass.strStrong = 'Información. ';
              }else{
                alert('Error Inesperado.');
              }
              $scope.fRecuperaDatos = {};
              blockUI.stop(); 
            });
          }
        }
      });
    }
  })
  .service("loginServices",function($http, $q) {
    return({
        sLoginToSystem: sLoginToSystem,
        sGeneraNewPassword: sGeneraNewPassword
    });

    function sLoginToSystem(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sGeneraNewPassword(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/genera_new_password", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });