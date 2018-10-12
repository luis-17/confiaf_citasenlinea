angular.patchURL = dirWebRoot;
angular.patchURLCI = dirWebRoot+'ci.php/';
angular.dirViews = angular.patchURL+'/application/views/';
function handleError( response ) {
    if ( ! angular.isObject( response.data ) || ! response.data.message ) {
        return( $q.reject( "An unknown error occurred." ) );
    }
    return( $q.reject( response.data.message ) );
}
function handleSuccess( response ) {
    return( response.data );
}
function redondear(num, decimal){
  var decimal = decimal || 2;
  if (isNaN(num) || num === 0){
    return parseFloat(0);
  }
  var factor = Math.pow(10,decimal);
  return Math.round(num * factor ) / factor;
}

function newNotificacion(body,icon,title,tag) {
  var options = {
      body: body,
      icon: icon,
      tag: tag
  }

  var n = new Notification(title,options); 
  //console.log('se creo', n); 
}

appRoot = angular.module('theme.core.main_controller', ['theme.core.services', 'blockUI'])
  .controller('MainController', ['$scope', '$route', '$uibModal', '$document', '$theme', '$timeout', '$interval', 'progressLoader', 'wijetsService', '$routeParams', '$location','$controller'
    , 'blockUI', 'uiGridConstants', 'pinesNotifications',
    'rootServices',
    'usuarioServices',
    'ModalReporteFactory',
    'programarCitaServices',
    function($scope, $route, $uibModal, $document, $theme, $timeout, $interval, progressLoader, wijetsService, $routeParams, $location, $controller
      , blockUI, uiGridConstants, pinesNotifications,
      rootServices,
      usuarioServices,
      ModalReporteFactory,
      programarCitaServices) {
    //'use strict';
    $scope.fAlert = {};
    $scope.arrMain = {};
    $scope.fSessionCI = {};
    $scope.fSessionCI.listaEspecialidadesSession = [];
    $scope.fSessionCI.listaNotificaciones = {};

    
    $scope.arrMain.sea = {};
    $scope.localLang = {
      selectAll       : "Seleccione todo",
      selectNone      : "Quitar todo",
      reset           : "Resetear todo",
      search          : "Escriba aquí para buscar...",
      nothingSelected : "No hay items seleccionados"
    };
    $scope.layoutFixedHeader = $theme.get('fixedHeader');
    $scope.layoutPageTransitionStyle = $theme.get('pageTransitionStyle');
    $scope.layoutDropdownTransitionStyle = $theme.get('dropdownTransitionStyle');
    $scope.layoutPageTransitionStyleList = ['bounce',
      'flash',
      'pulse',
      'bounceIn',
      'bounceInDown',
      'bounceInLeft',
      'bounceInRight',
      'bounceInUp',
      'fadeIn',
      'fadeInDown',
      'fadeInDownBig',
      'fadeInLeft',
      'fadeInLeftBig',
      'fadeInRight',
      'fadeInRightBig',
      'fadeInUp',
      'fadeInUpBig',
      'flipInX',
      'flipInY',
      'lightSpeedIn',
      'rotateIn',
      'rotateInDownLeft',
      'rotateInDownRight',
      'rotateInUpLeft',
      'rotateInUpRight',
      'rollIn',
      'zoomIn',
      'zoomInDown',
      'zoomInLeft',
      'zoomInRight',
      'zoomInUp'
    ];
    $scope.dirImages = angular.patchURL+'/assets/img/';
    $scope.layoutLoading = true;
    $scope.blockUI = blockUI;

    $scope.$on('$routeChangeStart', function() {
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          if ($location.path() === '') {            
            return $location.path('/');
          }

          if($location.path() !== '/login'){
            $scope.logIn();
          }          
        }else{
          $scope.goToUrl('/login');
          //window.location = "https://citasenlinea.villasalud.pe/#/login";
        }
      });
      progressLoader.start();
      progressLoader.set(50);
    });

    $scope.$on('$routeChangeSuccess', function() {
      progressLoader.end();
      if ($scope.layoutLoading) {
        $scope.layoutLoading = false;
      }
      wijetsService.make();
    });
    
    $scope.captchaValido = false;
    window.recaptchaResponse = function(key) {
      $scope.captchaValido = true;
    };
    
    window.recaptchaResponseReg = function(key) {
      $scope.captchaValidoReg = true;
    }

    $scope.keyRecaptcha='';
    window.onloadCallback = function(){      
      var datos = {
        tipo: 'captcha'
      }
      if( $location.path() == '/login' ){
        rootServices.sGetConfig(datos).then(function(rpta){
          $scope.keyRecaptcha =  rpta.datos.KEY_RECAPTCHA;
          grecaptcha.render('recaptcha-login', {
            'sitekey' : $scope.keyRecaptcha,
            'callback' : recaptchaResponse,
          }); 
        });
      }          
    }
 
    $scope.getLayoutOption = function(key) {
      return $theme.get(key);
    };

    $scope.getUrlActual = function(){
      return $location.path();
    }

    $scope.isLoggedIn = false;
    $scope.logOut = function() {
      $scope.isLoggedIn = false;
      $scope.captchaValido = false;
    };
    $scope.logIn = function() {
      $scope.isLoggedIn = true;
    };

    $scope.goToUrl = function ( path ) {      
      $location.path( path );
    };
    
    $scope.cargarItemFamiliar = function(item){
      $scope.familiarSeleccionado = item;
    }
      
    $scope.btnLogoutToSystem = function () {
      rootServices.sLogoutSessionCI().then(function () {
        $scope.fSessionCI = {};
        $scope.listaUnidadesNegocio = {};
        $scope.listaModulos = {};
        $scope.logOut();
        $scope.goToUrl('/login');
      });
    }

    $scope.getValidateSession = function () {
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
          $scope.getNotificacionesEventos();
          $scope.logIn();
          if( $location.path() == '/login' ){
            $scope.goToUrl('/');
          }
        }else{
          $scope.fSessionCI = {};
          $scope.logOut();
          $scope.goToUrl('/login');
        }
      });
    }   
    
    $scope.btnCambiarMiClave = function (size){
      $uibModal.open({
        templateUrl: angular.patchURLCI+'usuario/ver_popup_password',
        size: size || 'sm',
        controller: function ($scope, $modalInstance) {
          $scope.titleForm = 'Cambiar Contraseña';
          $scope.aceptar = function (){            
            $scope.fDataUsuario.miclave = 'si';
            usuarioServices.sActualizarPasswordUsuario($scope.fDataUsuario).then(function (rpta){
              if(rpta.flag == 1){
                $scope.fAlert = {};
                $scope.fAlert.type= 'success';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Genial.';
                setTimeout(function() {
                  $scope.cancel();
                }, 1000);
              }else if(rpta.flag == 2){
                $scope.fDataUsuario.clave = null;
                $scope.fAlert = {};
                $scope.fAlert.type= 'warning';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Advertencia.';
              }else if(rpta.flag == 0){
                $scope.fDataUsuario.claveNueva = null;
                $scope.fDataUsuario.claveConfirmar = null;
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error. ';
                setTimeout(function() {
                  $('#nuevoPass').focus();
                }, 500);
              }else{
                alert('Error inesperado');
              }               
            });            
          }

          $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
            $scope.fDataUsuario = {};
          }
        }
      });
    }

    $scope.viewRegister = false;
    $scope.btnViewRegister = function (){
      $controller('usuarioController', { 
        $scope : $scope
      }); 
      $scope.initRegistrarUsuario();
      $scope.viewRegister = true;
      $scope.initRecaptchaReg();
      $scope.fAlert = null;
      $scope.fDataUser = null;
      $scope.fDataUser = {};
      $scope.fDataUser.sexo = '-';
    }

    $scope.btnViewLogin = function (){
      $scope.viewRegister = false;
      $scope.fAlert = null;
      $scope.fDataUser = null;
      $scope.fLogin = null;
    }

    $scope.getNotificacionesEventos = function (firtsTime) {
      rootServices.sListarNotificacionesEventos().then(function (rpta) {
        $scope.fSessionCI.listaNotificacionesEventos = {};
        $scope.fSessionCI.listaNotificacionesEventos.datos = rpta.datos;
        $scope.fSessionCI.listaNotificacionesEventos.noLeidas = rpta.noLeidas;
        $scope.fSessionCI.listaNotificacionesEventos.contador = rpta.contador;
        /*
        if(firtsTime && $location.path() == '/'){ 
          console.log('window.Notification',window.Notification);
          console.log('window.mozNotification',window.mozNotification);
          console.log('window.webkitNotifications',window.webkitNotifications);
          console.log('window.notifications', window.notifications);

          var Notificacion = window.Notification || window.mozNotification || window.webkitNotification;
          if(Notificacion){
            if(Notification.permission != 'granted'){
              Notification.requestPermission();
            }

            //notificación por cada no leida
            var title = "Notificación Programación Asistencial";
            var icon = $scope.dirImages +'dinamic/empresa/' + $scope.fSessionCI.nombre_logo;
            angular.forEach( $scope.fSessionCI.listaNotificacionesEventos.noLeidas, function(value, key) {              
                newNotificacion(value.notificacion,icon,title, value.idcontroleventousuario);
            });
          }
        }   */             
      });
    }

    $scope.viewDetalleNotificacionEvento = function(fila){
      blockUI.start('Cargando notificación...');
      console.log(fila);
      rootServices.sUpdateLeidoNotificacion(fila).then(function (rpta) {
        $scope.fData = fila;
        if(rpta.flag == 1){
          $scope.getNotificacionesEventos(false);         
          $uibModal.open({
            templateUrl: angular.patchURLCI+'ControlEventoWeb/ver_popup_notificacion_evento',
            size: '',
            backdrop: 'static',
            keyboard:false,
            scope: $scope,
            controller: function ($scope, $modalInstance) { 
              $scope.titleForm = 'DETALLE DE NOTIFICACIÓN';               
              //console.log('$scope.fData.cita',$scope.fData.cita);
              $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
              }

              $scope.viewComprobante = function(){
                rootServices.sCargaObjetoNotificacion(fila).then(function(rpta){
                  $scope.fData.cita = rpta.cita;
                  $scope.cancel();
                  $scope.descargaComprobanteCita($scope.fData.cita);
                });
              }  
              blockUI.stop();            
            }
          });
        }else if(rpta.flag == 0){
          var pTitle = 'Advertencia!';
          var pType = 'warning';
          pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 1000 });
        }else{
          alert('Error inesperado');
        }
      });
    }

    $scope.descargaComprobanteCita = function(cita){
      blockUI.start('Cargando comprobante...');
      var arrParams = {
        titulo: 'COMPROBANTE DE CITA',
        datos: cita,
        metodo: 'js'
      }
      arrParams.url = angular.patchURLCI+'ProgramarCita/report_comprobante_cita'; 
      ModalReporteFactory.getPopupReporte(arrParams); 
      blockUI.stop();
    }

    $scope.datoTip = null;
    $scope.btnSolicitarCita = function(idsede, idespecialidad){
      return;
      $scope.datoTip = {
        idsede : idsede,
        idespecialidad, idespecialidad
      }
      console.log($scope.datoTip);
      $scope.goToUrl('/seleccionar-cita');
    }

    $scope.closeTimer = function(liberar){
      if($scope.fSessionCI.timer.activeCount) {
        $interval.cancel($scope.runTimer);
        $scope.timer.activeCount = false;
        $scope.timer.viewTimerExpired = true;
        //liberar cupos
        if(liberar){ 
          rootServices.sGetSessionCI().then(function (response) {
            programarCitaServices.sLiberarCuposSession(response.datos).then(function (rpta){
              console.log(rpta);
            });
          });      
        }
      }
    }

    $scope.exitTimer = function(){
      if($scope.fSessionCI.timer.activeCount) {
        $interval.cancel($scope.runTimer);
        $scope.timer.activeCount = false;
        $scope.timer.viewTimerExpired = false;
        rootServices.sRegistraTimerSession($scope.timer).then(function(rpta){          
          console.log('salio del timer');
        });
      }
    }

    $scope.starTimer = function(){
      /*console.log('$scope.fSessionCI',$scope.fSessionCI);
      console.log('$scope.timer',$scope.timer);*/
      if(!$scope.fSessionCI.timer.activeCount ||  ($scope.timer && $scope.timer.countDownTime=='00:00')) {
        $scope.timer = {};
        $scope.timer.start = moment("2017-07-01 00:00:00", "YYYY-MM-DD HH:mm:ss").add(5, 'minute');  
        $scope.timer.count = moment("2017-07-01 00:00:00", "YYYY-MM-DD HH:mm:ss").add(5, 'minute');  
        $scope.timer.activeCount =true;  
        $scope.timer.viewTimerExpired =false;  
        $scope.timer.countDownTime = $scope.timer.count.format("mm:ss");                
        rootServices.sRegistraTimerSession($scope.timer).then(function(rpta){
           $scope.initRunTimer(true);               
        });
      }
    }

    $scope.initRunTimer = function (interna){
      //if($scope.isLoggedIn){        
        console.log('$scope.initRunTimer');
        rootServices.sGetSessionCI().then(function (response){
          console.log('RECARGO PAGINA...');
          if(response.datos.idusuario){            
            $scope.fSessionCI = response.datos;
            if(!interna){
              $scope.timer = $scope.fSessionCI.timer;
              $scope.timer.start = moment($scope.timer.start);
              $scope.timer.count = moment($scope.timer.count);
              console.log('paso por aqui...');
            }
            
            $scope.runTimer = $interval(
              function(){
                  if($scope.fSessionCI.timer && $scope.fSessionCI.timer.activeCount){ 
                    $scope.timer.count.subtract(1, 'seconds');
                    $scope.timer.countDownTime = $scope.timer.count.format("mm:ss");
                    //console.log('$scope.timer.countDownTime ',$scope.timer.countDownTime );
                    var diff = $scope.timer.start.unix() - $scope.timer.count.unix();
                    $scope.timer.seconds = moment.duration(diff).asSeconds() * 1000
                    //console.log('$scope.timer.seconds',$scope.timer.seconds);
                    if($scope.timer.countDownTime == '00:00'){                                        
                      $scope.closeTimer(true);
                    } 
                    rootServices.sRegistraTimerSession($scope.timer);
                  }
              },
              1000
            ); 
          }
        });
      //}
    }      
    $scope.initRunTimer();
    /* END */
  }])
  .service("rootServices", function($http, $q) {
    return({
        sGetSessionCI: sGetSessionCI,
        sLogoutSessionCI: sLogoutSessionCI,
        sGetConfig:sGetConfig,
        sListarNotificacionesEventos: sListarNotificacionesEventos,
        sUpdateLeidoNotificacion: sUpdateLeidoNotificacion,
        sCargaObjetoNotificacion: sCargaObjetoNotificacion,
        sRegistraTimerSession:sRegistraTimerSession,
    });
    function sGetSessionCI() {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/getSessionCI"
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sLogoutSessionCI() {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/logoutSessionCI"
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sGetConfig(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/get_config",
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }   
    function sListarNotificacionesEventos (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/lista_notificaciones_eventos",
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sUpdateLeidoNotificacion (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ControlEventoWeb/update_leido_notificacion",
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    } 
    function sCargaObjetoNotificacion (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ControlEventoWeb/carga_objeto_notificacion",
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sRegistraTimerSession(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"acceso/registra_timer_session",
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
/* DIRECTIVAS */
appRoot.
  directive('ngEnter', function() {
    return function(scope, element, attrs) {
      element.bind("keydown", function(event) {
          if(event.which === 13) {
            scope.$apply(function(){
              scope.$eval(attrs.ngEnter);
            });
          }
      });
    };
  })
  .directive('scroller', function() {
    return {
      restrict: 'A',
      link: function(scope,elem,attrs){
          $(elem).on('scroll', function(evt){ 
            // PLANNING 
            $('.planning .header').css('top', $(this).scrollTop());     

            //TABLAS
            $('.body-grid .header').css('top', $(this).scrollTop());            
          });
      }
    }
  })
  .directive('resetscroller', function() {
    return {
      restrict: 'A',
      link: function(scope,elem,attrs){
          $(elem).on('click', function(evt){ 
            // PROGRAMACION DE AMBIENTES 
            $('.planning .sidebar .table').css('margin-top', 0);
            $('.planning .header .table').css('margin-left', 0);
            $('.planning .body').scrollLeft(0);
            $('.planning .body').scrollTop(0);
            // PROGRAMACION DE MEDICOS 
            $('.planning-medicos .fixed-row').css('margin-left', -$(this).scrollLeft());
            $('.planning-medicos .fixed-column').css('margin-top', -$(this).scrollTop()); 

            $('.planning-medicos .fixed-row .cell-planing.ambiente').css('left', $(this).scrollLeft()); 
            
          });
      }
    }
  })
 .directive("scroll", function ($window) {
      return function(scope, element, attrs) {
          angular.element($window).bind("scroll", function() {            
            /*var top_filtro = $('.filtros').position().top;
            var height_header = $('.navbar').height();
            var pos_scroll = $(this).scrollTop();
            var top = top_filtro - pos_scroll;

            //console.log($(this).scrollTop(), (top_filtro - pos_scroll), height_header);
            
            if($(window).scrollTop() == 0){
              $('.filtros').css('top','initial');
            }else if($(window).scrollTop() >= height_header){
              $('.filtros').css('top',0);            
            }else{
              $('.filtros').css('top', top );
            }*/
            var height_header = $('.navbar').height();
            if($(window).scrollTop() >= height_header){
              $('.filtros').addClass("sticky");  
            }else{
              $('.filtros').removeClass("sticky"); 
            }
          });
      };
  })
  .directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
          var model = $parse(attrs.fileModel);
          var modelSetter = model.assign;
          element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
          });
        }
    };
  }])
  .directive('focusMe', function($timeout, $parse) {
    return {
      link: function(scope, element, attrs) {
        var model = $parse(attrs.focusMe);

        scope.$watch(model, function(pValue) {
            value = pValue || 0;
            $timeout(function() {
              element[value].focus();
              // console.log(element[value]);
            });
        });
      }
    };
  })
  .directive('stringToNumber', function() {
    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ngModel) {
        // console.log(scope);
        ngModel.$parsers.push(function(value) {
          // console.log('p '+value);
          return '' + value;
        });
        ngModel.$formatters.push(function(value) {
          // console.log('f '+value);
          return parseFloat(value, 10);
        });
      }
    };
  })
  .directive('enterAsTab', function () {
    return function (scope, element, attrs) {
      element.bind("keydown keypress", function (event) {
        if(event.which === 13 || event.which === 40) {
          event.preventDefault();
          var fields=$(this).parents('form:eq(0),body').find('input, textarea, select');
          var index=fields.index(this);
          if(index > -1 &&(index+1) < (fields.length - 1))
            fields.eq(index+1).focus();
        }
        if(event.which === 38) {
          event.preventDefault();
          var fields=$(this).parents('form:eq(0),body').find('input, textarea, select');
          var index=fields.index(this);
          if((index-1) > -1 && index < fields.length)
            fields.eq(index-1).focus();
        }
      });
    };
  })
  .directive('hcChart', function () {
      return {
          restrict: 'E',
          template: '<div></div>',
          scope: {
              options: '='
          },
          link: function (scope, element) {
            // scope.$watch(function () {
            //   return attrs.chart;
            // }, function () {
            //     if (!attrs.chart) return;
            //     var charts = JSON.parse(attrs.chart);
            //     $(element[0]).highcharts(charts);                
                Highcharts.chart(element[0], scope.options);
            // });

          }
      };
  })
  .directive('smartFloat', function() {
    var FLOAT_REGEXP = /^\-?\d+((\.|\,)\d+)?$/;
    return {
      require: 'ngModel',
      link: function(scope, elm, attrs, ctrl) {
        ctrl.$parsers.unshift(function(viewValue) {
          if (FLOAT_REGEXP.test(viewValue)) {
            ctrl.$setValidity('float', true);
            if(typeof viewValue === "number")
              return viewValue;
            else
              return parseFloat(viewValue.replace(',', '.'));
          } else {
            ctrl.$setValidity('float', false);
            return undefined;
          }
        });
      }
    };
  })
  .config(function(blockUIConfig) {
    blockUIConfig.message = 'Cargando datos...';
    blockUIConfig.delay = 0;
    blockUIConfig.autoBlock = false;
    //i18nService.setCurrentLang('es');
  })
  .filter('getRowSelect', function() {
    return function(arraySelect, item) {
      var fSelected = {};
      angular.forEach(arraySelect,function(val,index) {
        if( val.id == item ){
          fSelected = val;
        }
      })
      return fSelected;
    }
  })
  .filter('numberFixedLen', function () {
    return function (n, len) {
      var num = parseInt(n, 10);
      len = parseInt(len, 10);
      if (isNaN(num) || isNaN(len)) {
        return n;
      }
      num = ''+num;
      while (num.length < len) {
        num = '0'+num;
      }
      return num;
    };
  })
  .factory("ModalReporteFactory", function($modal,$http,blockUI,rootServices){
    var interfazReporte = {
      getPopupReporte: function(arrParams){ //console.log(arrParams.datos.salida,' as');
        if( arrParams.datos.salida == 'pdf' || angular.isUndefined(arrParams.datos.salida) ){
          $modal.open({
            templateUrl: angular.patchURLCI+'CentralReportes/ver_popup_reporte',
            size: 'xlg',
            controller: function ($scope,$modalInstance,arrParams) {
              $scope.titleModalReporte = arrParams.titulo;
              $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
              }
              blockUI.start('Preparando reporte');
              $http.post(arrParams.url, arrParams.datos)
                .success(function(data, status) {
                  blockUI.stop();
                  if( arrParams.metodo == 'php' ){
                    $('#frameReporte').attr("src", data.urlTempPDF);
                  //}else if( arrParams.metodo == 'js' ){
                  }else{
                    var docDefinition = data.dataPDF
                    pdfMake.createPdf(docDefinition).getBuffer(function(buffer) {
                      var blob = new Blob([buffer]);
                      var reader = new FileReader();
                      reader.onload = function(event) {
                        var fd = new FormData();
                        fd.append('fname', 'temp.pdf');
                        fd.append('data', event.target.result);
                        $.ajax({
                          type: 'POST',
                          url: angular.patchURLCI+'CentralReportes/guardar_pdf_en_temporal', // Change to PHP filename
                          data: fd,
                          processData: false,
                          contentType: false
                        }).done(function(data) {
                          $('#frameReporte').attr("src", data.urlTempPDF);
                        });
                      };
                      reader.readAsDataURL(blob);
                    });
                  }
                })
                .error(function(data, status){
                  blockUI.stop();
                });
            },
            resolve: {
              arrParams: function() {
                return arrParams;
              }
            }
          });
        }else if( arrParams.datos.salida == 'excel' ){
          blockUI.start('Preparando reporte');
          $http.post(arrParams.url, arrParams.datos)
            .success(function(data, status) {
              blockUI.stop();
              if(data.flag == 1){
                //window.open = arrParams.urlTempEXCEL;
                window.location = data.urlTempEXCEL;
              }
          });
        }
      },
      getPopupGraph: function(arrParams) {
        if( arrParams.datos.tipoCuadro == 'grafico' || arrParams.datos.tiposalida == 'grafico' || angular.isUndefined(arrParams.datos.tipoCuadro) ){
          $uibModal.open({
            templateUrl: angular.patchURLCI+'CentralReportes/ver_popup_grafico',
            size: 'xlg',
            controller: function ($scope,$modalInstance,arrParams) {
              $scope.metodos = {};
              $scope.titleModalGrafico = arrParams.datos.titulo;
              $scope.metodos.listaColumns = false;
              $scope.metodos.listaData = false;

              $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
              }

              rootServices.sGraphicData(arrParams.url, arrParams.datos).then(function (data) {
                $scope.metodos.chartOptions = arrParams.structureGraphic;
                //console.log(data.series[0]);
                $scope.metodos.chartOptions.chart.events = {
                    load: function () {
                      var thes = this;
                      setTimeout(function () {
                          thes.setSize($("#chartOptions").parent().width(), $("#chartOptions").parent().height());
                      }, 10);
                    }
                  };
                if( data.tipoGraphic == 'line' || data.tipoGraphic == 'bar'){
                  $scope.metodos.chartOptions.xAxis.categories = data.xAxis;
                  $scope.metodos.chartOptions.series = data.series;
                }
                //TRANSICIÓN DE LAS GRÁFICAS DE REPORTES DE ENCUESTA QUE SE ENCUENTRA EN LA INTRANET
                if( data.tipoGraphic == 'pie' ){//
                  var arrData = [];
                  var tamanio = 300;
                  //SE RECORRE data.series PARA OBTENER TODAS LAS PREGUNTAS CON SUS RESPECTIVOS DATOS
                  angular.forEach(data.series, function(value, key) {
                    arrData.push({name: value.descripcion_pr, colorByPoint: true, size: 200, center: [tamanio, null], showInLegend: true, data: data.series[key].respuestas,
                      total: data.series[key].totalPorPie});
                    tamanio = tamanio + 300;                    
                  });
                  //console.log(arrData);
                  $scope.metodos.chartOptions.series = arrData;
                }
                if (data.tipoGraphic == 'line_encuesta'){//EL TIPO DE GRÁFICA PARA ESTE CASO ES ESPECIAL PORQUE
                  var arrData = [];
                  $scope.metodos.chartOptions.xAxis.categories = data.xAxis;
                  $scope.metodos.chartOptions.title.text = (data.series[0].descripcion).toUpperCase();
                  angular.forEach(data.series[0].respuestas , function(value, key) {
                    arrData.push({name: data.series[0].respuestas[key].name, data: data.series[0].respuestas[key].data});
                  });
                  $scope.metodos.chartOptions.series = arrData;
                  //console.log(arrData);
                }                
                if( data.tieneTabla == true ){
                  $scope.metodos.listaColumns = data.columns;
                  $scope.metodos.listaData = data.tablaDatos;
                  $scope.metodos.contTablaDatos = false;
                  $scope.metodos.linkText = 'VER TABLA DE DATOS';
                  $scope.linkVerTablaDatos = function () {
                    if( $scope.metodos.contTablaDatos === true ){
                      $scope.metodos.contTablaDatos = false;
                      $scope.metodos.linkText = 'VER TABLA DE DATOS';
                    }else{
                      $scope.metodos.contTablaDatos = true;
                      $scope.metodos.linkText = 'OCULTAR TABLA DE DATOS';
                    }

                  }
                }
              });
            },
            resolve: {
              arrParams: function() {
                return arrParams;
              }
            }
          });
        }
      }
    }
    return interfazReporte;
  })  
  .filter('griddropdown', function() {
    return function (input, context) {
      var map = context.col.colDef.editDropdownOptionsArray;
      var idField = context.col.colDef.editDropdownIdLabel;
      var valueField = context.col.colDef.editDropdownValueLabel;
      var initial = context.row.entity[context.col.field];
      if (typeof map !== "undefined") {
        for (var i = 0; i < map.length; i++) {
          if (map[i][idField] == input) {
            return map[i][valueField];
          }
        }
      } else if (initial) {
        return initial;
      }
      return input;
    };
  });

  // Prevent the backspace key from navigating back.
$(document).unbind('keydown').bind('keydown', function (event) {
  var doPrevent = false;
  if (event.keyCode === 8) {
    var d = event.srcElement || event.target;
    if((d.tagName.toUpperCase() === 'INPUT' &&
         (
             d.type.toUpperCase() === 'TEXT' ||
             d.type.toUpperCase() === 'PASSWORD' ||
             d.type.toUpperCase() === 'FILE' ||
             d.type.toUpperCase() === 'SEARCH' ||
             d.type.toUpperCase() === 'EMAIL' ||
             d.type.toUpperCase() === 'NUMBER' ||
             d.type.toUpperCase() === 'TEL' ||
             d.type.toUpperCase() === 'DATE' )
        ) ||
        d.tagName.toUpperCase() === 'TEXTAREA'
    ){
      doPrevent = d.readOnly || d.disabled;
    }
    else {
        doPrevent = true;
    }
  }

  if (doPrevent) {
      event.preventDefault();
  }
});
   