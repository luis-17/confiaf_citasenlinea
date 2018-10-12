angular.module('theme.programarCita', ['theme.core.services'])
  .controller('programarCitaController', ['$scope', '$controller', '$filter', '$sce', '$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys','blockUI', 
    'programarCitaServices',
    'sedeServices',
    'especialidadServices',
    'parienteServices',
    'rootServices',
    'ventaServices',
    function($scope, $controller, $filter, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
      programarCitaServices,
      sedeServices,
      especialidadServices,
      parienteServices,
      rootServices,
      ventaServices
      ){
    'use strict';
    shortcut.remove("F2"); 
    $scope.modulo = 'programarCita';
    

    $scope.bloquearSelector = function(value){
      $scope.bloqueaSelector = value;
    }

    $scope.initSeleccionarCita=function(){      
      console.log('$scope.familiarSeleccionado', $scope.familiarSeleccionado);      
      console.log('$scope.datoTip', $scope.datoTip);      
      $scope.fBusqueda = {};
      var fechaHasta = moment().add(6,'days');
      $scope.fBusqueda.desde =  $filter('date')(moment().toDate(),'dd-MM-yyyy'); 
      $scope.fBusqueda.hasta =  $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy');
      $scope.fSeleccion = {};
      $scope.fPlanning = null; 
      $scope.fBusqueda.itemFamiliar = null; 
      $scope.listaEspecialidad = [
        { id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '}
      ];
      $scope.fBusqueda.itemEspecialidad = $scope.listaEspecialidad[0];
      var datos = {
        search:1,
        nameColumn:'tiene_prog_cita'
      };

      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fDataUser = response.datos;
          $scope.fSessionCI = response.datos;
          $scope.fSessionCI.compraFinalizada = false;
          if($scope.fSessionCI.compra.listaCitas.length > 0){
            $scope.bloquearSelector(true);
          }else{
            $scope.bloquearSelector(false); 
            if($scope.timer)
              $scope.timer.viewTimerExpired = false;
          }
        }

        sedeServices.sListarSedesCbo(datos).then(function (rpta) {
          $scope.listaSedes = rpta.datos;
          $scope.listaSedes.splice(0,0,{ id : 0, idsede:0, descripcion:'SEDE'});
          $scope.fBusqueda.itemSede = $scope.listaSedes[0];

          if($scope.bloqueaSelector){
            angular.forEach($scope.listaSedes, function(value, key) {
              if(value.id == $scope.fSessionCI.compra.itemSede.id){
                $scope.fBusqueda.itemSede = $scope.listaSedes[key];
              }                
            });

            var datos = {
              idsede : $scope.fBusqueda.itemSede.id,
            }
            especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
              $scope.listaEspecialidad = rpta.datos;
              $scope.listaEspecialidad.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
              angular.forEach($scope.listaEspecialidad, function(value, key) {
                if(value.id == $scope.fSessionCI.compra.itemEspecialidad.id){
                  $scope.fBusqueda.itemEspecialidad = $scope.listaEspecialidad[key];
                }                
              });
            });
          }

          if($scope.datoTip){
            angular.forEach($scope.listaSedes, function(value, key) {
              if(value.id == $scope.datoTip.idsede){
                $scope.fBusqueda.itemSede = $scope.listaSedes[key];
              }                
            });

            var datos = {
              idsede : $scope.fBusqueda.itemSede.id,
            }
            especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
              $scope.listaEspecialidad = rpta.datos;
              $scope.listaEspecialidad.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
              var ind = 0;
              angular.forEach($scope.listaEspecialidad, function(value, key) {
                if(value.id == $scope.datoTip.idespecialidad){
                  ind = key;
                }                
              });
              $scope.fBusqueda.itemEspecialidad = $scope.listaEspecialidad[ind];
            });
          }
        });
      });      

      $scope.listarParientes = function(externo){
        parienteServices.sListarParientesCbo().then(function (rpta) {
          $scope.listaFamiliares = rpta.datos;
          $scope.listaFamiliares.splice(0,0,{ idusuariowebpariente:0, 
                                              descripcion: $scope.fSessionCI.nombres + ' [TITULAR]',
                                              paciente: $scope.fSessionCI.paciente,
                                              edad:$scope.fSessionCI.edad,
                                            });
          if(externo){          
            $scope.fBusqueda.itemFamiliar = $scope.listaFamiliares[$scope.listaFamiliares.length-1]; 
          }else{
            $scope.fBusqueda.itemFamiliar = $scope.listaFamiliares[0];
          }

          if($scope.familiarSeleccionado){
            angular.forEach($scope.listaFamiliares, function(value, key) {
              if(value.idusuariowebpariente == $scope.familiarSeleccionado.idusuariowebpariente){
                $scope.fBusqueda.itemFamiliar = $scope.listaFamiliares[key];
              }                
            });
          }
        });
      }
      $scope.listarParientes();

      $scope.listarEspecialidad = function(){
        var datos = {
          idsede : $scope.fBusqueda.itemSede.id,
        }

        especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
          $scope.listaEspecialidad = rpta.datos;
          $scope.listaEspecialidad.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
          $scope.fBusqueda.itemEspecialidad = $scope.listaEspecialidad[0];
        });
      }

      $scope.formats = ['dd-MM-yyyy','dd-MMMM-yyyy','yyyy/MM/dd','dd.MM.yyyy','shortDate'];
      $scope.format = $scope.formats[0]; // formato por defecto
      $scope.datePikerOptions = {
        formatYear: 'yy',        
        'show-weeks': false,
        //startingDay: 1,
      };

      $scope.disabled = function(date, mode) { 
        var fecha = new Date(date).toLocaleDateString('zh-Hans-CN', { 
                    day : 'numeric',
                    month : 'numeric',
                    year : 'numeric'
                }); 
        return (mode === 'day' && (date.getDay() === 0 || moment(fecha).isBefore( moment().toDate().toLocaleDateString('zh-Hans-CN', { 
                day : 'numeric',
                month : 'numeric',
                year : 'numeric'
            }) )  ));
      };

      $scope.openDP = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      }
    }

    $scope.getMedicoAutocomplete = function (value) {
      var params = $scope.fBusqueda;
      params.search= value;
      params.sensor= false;
        
      return programarCitaServices.sListarMedicosAutocomplete(params).then(function(rpta) { 
        $scope.noResultsLM = false;
        if( rpta.flag === 0 ){
          $scope.noResultsLM = true;
        }
        return rpta.datos; 
      });
    }

    $scope.getSelectedMedico = function($item, $model, $label){
      $scope.fBusqueda.itemMedico = $item;
    }

    $scope.cargarPlanning = function(){
      if($scope.fBusqueda.desde){
        console.log('$scope.fBusqueda',$scope.fBusqueda);
        var esAdulto = false;
        if($scope.fBusqueda.itemEspecialidad.id == 31 && $scope.fBusqueda.itemFamiliar.edad >17){
          esAdulto = true;
        }

        if(esAdulto){
          $scope.mostrarMsj(0,'Aviso', 'No puede solicitar citas de PEDIATRIA para personas mayores a 17 años. Verifica el paciente de tu cita e intenta nuevamente.');
          return;
        }

        blockUI.start('Cargando programación...');
        programarCitaServices.sCargarPlanning($scope.fBusqueda).then(function(rpta){
          $scope.fPlanning = rpta.planning;
          blockUI.stop();
        });
      }      
    }

    $scope.goToHistorial = function(){
      $scope.goToUrl('/historial-citas');
    }

    $scope.goToSelCita = function(){     
      $scope.goToUrl('/seleccionar-cita');
    } 

    $scope.btnAgregarNuevoPariente = function(){
      var callback = function(){
        $scope.listarParientes(true);
      }

      $controller('parienteController', { 
        $scope : $scope
      });
      $scope.btnNuevoPariente(callback);
    }

    $scope.verTurnosDisponibles = function(item, boolExterno, callback){
      blockUI.start('Cargando turnos disponibles...');
      if(boolExterno){
        $scope.boolExterno = true;
      } else {
        $scope.boolExterno = false;
      }
      
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_turnos',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                          
          $scope.titleForm = 'Turnos Disponibles'; 
          var datos = item;
          datos.medico = $scope.fBusqueda.itemMedico;
          $scope.fPlanning.detalle = item;
          $scope.fSeleccion = {};

          $scope.cargarTurnos = function(){
            blockUI.start('Cargando turnos disponibles...');
            programarCitaServices.sCargarTurnosDisponibles($scope.fPlanning.detalle).then(function(rpta){
              $scope.fPlanning.turnos=rpta.datos;  
              blockUI.stop();          
            }); 
          } 
          $scope.cargarTurnos();            

          $scope.btnCancel = function(){
            $scope.fSeleccion = {};
            $modalInstance.dismiss('btnCancel');
          }

          $scope.checkedCupo = function(cupo){
            $scope.fSeleccion = cupo; 
            cupo.checked=true;
            angular.forEach($scope.fPlanning.turnos, function(value, key){   
              angular.forEach(value.cupos, function(objCupo, indCupo){
                if(objCupo.iddetalleprogmedico != cupo.iddetalleprogmedico){
                  $scope.fPlanning.turnos[key].cupos[indCupo].checked = false;
                }
              });           
            });
          }

          $scope.btnReservarTurno = function(){ 
            var selecciono = true;
            if(!($scope.fSeleccion.iddetalleprogmedico && $scope.fSeleccion.iddetalleprogmedico != '' && $scope.fSeleccion.checked)){
              var selecciono = false;
            }

            if(!selecciono){
              $uibModal.open({ 
                templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
                size: 'sm',
                //backdrop: 'static',
                //keyboard:false,
                scope: $scope,
                controller: function ($scope, $modalInstance) {                 
                  $scope.titleForm = 'Aviso'; 
                  $scope.msj = 'Debe seleccionar un cupo';

                  $scope.btnCancel = function(){
                    $modalInstance.dismiss('btnCancel');
                  }
                }
              });
              return;
            }

            var encontro = false;
            angular.forEach($scope.fSessionCI.compra.listaCitas, function(value, key){              
              if(value.seleccion.iddetalleprogmedico == $scope.fSeleccion.iddetalleprogmedico){
                encontro = true;
              }

            });   

            if(encontro){
              $uibModal.open({ 
                templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
                size: 'sm',
                //backdrop: 'static',
                //keyboard:false,
                scope: $scope,
                controller: function ($scope, $modalInstance) {                 
                  $scope.titleForm = 'Aviso'; 
                  $scope.msj = 'El turno seleccionado ya ha sido escogido para otra cita de su sesión';

                  $scope.btnCancel = function(){
                    $modalInstance.dismiss('btnCancel');
                  }
                }
              });
            }else{
              var datos = {
                busqueda:angular.copy($scope.fBusqueda),
                seleccion:angular.copy($scope.fSeleccion)
              }

              $scope.fSessionCI.compra.listaCitas.push(datos);
              programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
                if($scope.fSessionCI.compra.listaCitas.length > 0){
                  $scope.bloquearSelector(true);
                }else{
                  $scope.bloquearSelector(false); 
                }
              });
              $scope.btnCancel();
            }
          }

          $scope.btnCambiarTurno = function(){
            if(!$scope.fSeleccion){
              return;
            }

            $scope.fPlanning.citas.seleccion = $scope.fSeleccion;
            $scope.fDataModal= $scope.fPlanning.citas;
            $scope.fDataModal.oldCita.itemFamiliar.paciente = $scope.fDataModal.oldCita.itemFamiliar.paciente.toUpperCase(); 
            $scope.fDataModal.mensaje = '¿Estas seguro de realizar el cambio?';
            console.log($scope.fDataModal);
            $uibModal.open({ 
              templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_confirmacion',
              size: '',
              //backdrop: 'static',
              //keyboard:false,
              scope: $scope,
              controller: function ($scope, $modalInstance) {                 
                $scope.titleForm = 'Aviso'; 
                $scope.msj = 'El turno seleccionado ya ha sido escogido para otra cita de su sesión';

                $scope.btnClose = function(){
                  $modalInstance.dismiss('btnClose');
                }

                $scope.btnOk = function(){
                  blockUI.start('Reprogramando cita...');
                  programarCitaServices.sCambiarCita($scope.fPlanning.citas).then(function(rpta){
                    var modal = false;
                    var titulo = '';
                    blockUI.stop();
                    if(rpta.flag==1){
                      $scope.btnClose();
                      $scope.btnCancel();                  
                      modal = true;
                      titulo = 'Genial!';              
                    }else if(rpta.flag == 0){
                      modal = true;
                      titulo = 'Aviso'; 
                      $scope.cargarTurnos();  
                    }else{
                      alert('Erros inesperado');
                    }

                    if(modal){
                       $scope.mostrarMsj(rpta.flag,titulo,rpta.message, callback);
                    }
                  });
                }
              }
            });            
          }
        
          blockUI.stop();
        }
      });
    }

    $scope.mostrarMsj = function(tipo,titulo, msg, callback){      
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
        size: 'sm',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = titulo; 
          $scope.msj = msg;

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
            if(tipo==1){              
              callback();
            }
          }

          if(tipo==2){
            setTimeout(function() {            
              $scope.btnCancel();
            }, 10000);
          }
        }
      });
    }

    $scope.quitarDeLista = function(index, fila){
      blockUI.start('Actualizando...');
      //console.log(index, fila);
      programarCitaServices.sLiberaCupo($scope.fSessionCI.compra.listaCitas[index]).then(function(rpta){
        console.log(rpta);
        $scope.fSessionCI.compra.listaCitas.splice( index, 1 );
        if($scope.fSessionCI.compra.listaCitas.length > 0){
          $scope.bloquearSelector(true);
        }else{
          $scope.bloquearSelector(false); 
        }

        programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
          //console.log(rpta);
          blockUI.stop();
        });
      });     
    }

    $scope.resumenReserva = function(){
      blockUI.start('Verificando reserva...');
      ventaServices.sValidarCitas($scope.fSessionCI).then(function(rpta){
        //console.log(rpta);
        if(rpta.flag != 1){
          $scope.fSessionCI.compra.listaCitas = angular.copy(rpta.listaDefinitiva);         
          $scope.mostrarMsj(2,'Aviso', rpta.message + '. Selecciona nuevas citas.'); 
        }

        if($scope.fSessionCI.compra.listaCitas.length > 0){
          programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(rpta){
            $scope.goToUrl('/resumen-cita'); 
            blockUI.stop();
          });
        }else{
          $scope.mostrarMsj(0,'Aviso', rpta.msg + '. Selecciona nuevas citas.');
          setTimeout(function() {            
              $scope.goToUrl('/seleccionar-cita');
          }, 5000);
          blockUI.stop();
        }               
      });               
    }

    $scope.changeAcepta = function(){
      if($scope.acepta ){
        $scope.acepta = false;
      }else{
        $scope.acepta = true;
      }
    }
    $scope.initResumenReserva = function(){
      blockUI.start('Verificando reserva...');
      $scope.acepta = false;      
      $scope.viewResumenCita = true;
      $scope.viewResumenCompra = false;      

      /*  
      $scope.viewResumenCita = false;
      $scope.viewResumenCompra = true; 
      */

      $scope.generarCargo = function(token){
        blockUI.start('Procesando pago... Espere y NO recargue la página');
        var datos = {
          usuario:$scope.fSessionCI,
          token: token
        }

        ventaServices.sGenerarVentaCitas(datos).then(function(rpta){          
          var titulo = '';
          var url = '';
          var size = '';
          var modal = true;
          if(rpta.flag == 1){
            titulo = 'Genial!';
            url = angular.patchURLCI+'ProgramarCita/ver_popup_compra_exitosa';
            size = 'lg';
            $scope.exitTimer();
          }else if(rpta.flag == 0 || rpta.flag == 2){
            titulo = 'Aviso!';
            url = angular.patchURLCI+'ProgramarCita/ver_popup_aviso';
            size = 'sm';
          }else{
            alert('Error inesperado');
            modal = false;
          }
          
          if(modal){            
            $uibModal.open({ 
              templateUrl: url,
              size: size,
              //backdrop: 'static',
              //keyboard:false,
              scope: $scope,
              controller: function ($scope, $modalInstance) {                 
                $scope.titleForm = titulo; 
                $scope.msj = rpta.message;

                $scope.btnCancel = function(){
                  $modalInstance.dismiss('btnCancel');
                }

                if(rpta.flag == 1){
                  setTimeout(function() {
                    var callback = function(){
                      $scope.btnCancel();
                    }
                    $scope.goToResumenCompra(callback);
                  }, 1000);
                }
                blockUI.stop();
              }
            });
          }
        });
      }

      window.initCulqi = function(value, key) {        
        Culqi.publicKey = key; //'pk_test_5waw7MlH2GomYjCx';
        Culqi.settings({
            title: 'Vitacloud',
            currency: 'PEN',
            description: 'Pago de Citas en linea',
            amount: value,            
        });
        
        window.culqi = function(){
          if(Culqi.token) { // ¡Token creado exitosamente!
            // Get the token ID:
            var token = Culqi.token;
            $scope.generarCargo(token);
            //console.log(token);
          }else{ 
            console.log('Culqi.error',Culqi.error);
            if($scope.isLoggedIn){ 
              $uibModal.open({ 
                templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
                size: 'sm',
                //backdrop: 'static',
                //keyboard:false,
                scope: $scope,
                controller: function ($scope, $modalInstance) {                 
                  $scope.titleForm = 'Aviso'; 
                  $scope.msj = Culqi.error.user_message;
                  $scope.btnCancel = function(){
                    $modalInstance.dismiss('btnCancel');
                  }
                }
              });
            }
          }
        }        
      }

      $scope.pagar = function(){
        if(!$scope.acepta){
          $scope.mostrarMsj(0,'Aviso', 'Debe aceptar los Términos y Condiciones');
          return;
        }
        console.log('$scope.fSessionCI',$scope.fSessionCI);
        Culqi.open();
      }

      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
          programarCitaServices.sActualizarListaCitasSession($scope.fSessionCI).then(function(response){

            $scope.totales = {};
            $scope.totales.total_productos = response.datos.compra.totales.total_productos;
            $scope.totales.total_servicio = response.datos.compra.totales.total_servicio;
            $scope.totales.total_pago = response.datos.compra.totales.total_pago;
            $scope.totales.total_pago_culqi = response.datos.compra.totales.total_pago_culqi;

            var datos = {
              tipo: 'pago',
              idsedeempresaadmin: $scope.fSessionCI.compra.itemSede.idsedeempresaadmin,
            }
            rootServices.sGetConfig(datos).then(function(rpta){
              window.initCulqi($scope.totales.total_pago_culqi,rpta.datos.CULQI_PUBLIC_KEY); 
            });
              
            if($scope.fSessionCI.compra.listaCitas.length < 1){
              $scope.goToUrl('/seleccionar-cita');
            }       

            $scope.listaCitas = $scope.fSessionCI.compra.listaCitas;            
            if($scope.fSessionCI.compra.listaCitas.length>0){
              console.log('$scope.fSessionCI',$scope.fSessionCI);
              $scope.starTimer();              
            }
            blockUI.stop();            
          });          
        }           
      });    
    }

    $scope.goToResumenCompra = function(callback){
      blockUI.start('Cargando resumen de compra...');
      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos; 
          $scope.getNotificacionesEventos();         
        } 

        $scope.viewResumenCita = false;
        $scope.viewResumenCompra = true; 
        blockUI.stop();
        //callback();           
      });
    }

    /* ============================ */
    /* ATAJOS DE TECLADO NAVEGACION */
    /* ============================ */
    hotkeys.bindTo($scope)
      .add({
        combo: 'alt+n',
        description: 'Nueva especialidad',
        callback: function() {
          $scope.btnNuevo();
        }
      })
      .add ({ 
        combo: 'e',
        description: 'Editar especialidad',
        callback: function() {
          if( $scope.mySelectionGrid.length == 1 ){
            $scope.btnEditar();
          }
        }
      })
      .add ({ 
        combo: 'del',
        description: 'Anular especialidad',
        callback: function() {
          if( $scope.mySelectionGrid.length > 0 ){
            $scope.btnAnular();
          }
        }
      })
      .add ({ 
        combo: 'b',
        description: 'Buscar especialidad',
        callback: function() {
          $scope.btnToggleFiltering();
        }
      })
      .add ({ 
        combo: 's',
        description: 'Selección y Navegación',
        callback: function() {
          $scope.navegateToCell(0,0);
        }
      });
  }])
  .service("programarCitaServices",function($http, $q) {
    return({
      sCargarPlanning:sCargarPlanning,
      sCargarTurnosDisponibles:sCargarTurnosDisponibles, 
      sListarMedicosAutocomplete:sListarMedicosAutocomplete,  
      sActualizarListaCitasSession:sActualizarListaCitasSession,
      sGenerarVenta:sGenerarVenta,
      sVerificaEstadoCita:sVerificaEstadoCita,
      sCambiarCita:sCambiarCita,      
      sLiberaCupo:sLiberaCupo,  
      sLiberarCuposSession:sLiberarCuposSession,    
    });
    function sCargarPlanning(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/cargar_planning", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sCargarTurnosDisponibles(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/cargar_turnos_disponibles", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sListarMedicosAutocomplete(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/lista_medicos_autocomplete", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sActualizarListaCitasSession(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/actualizar_lista_citas_session", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sGenerarVenta(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/generar_venta", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sVerificaEstadoCita(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/verifica_estado_cita", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sCambiarCita(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/cambiar_cita", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sLiberaCupo(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/libera_cupo_quitar_lista", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sLiberarCuposSession(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"ProgramarCita/libera_lista_citas_session", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
