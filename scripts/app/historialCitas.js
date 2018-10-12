angular.module('theme.historialCitas', ['theme.core.services'])
  .controller('historialCitasController', function($scope, $controller, $filter, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
    historialCitasServices,
    sedeServices,
    especialidadServices,
    parienteServices,
    rootServices,
    programarCitaServices,
    ventaServices
     ){
      'use strict';
      shortcut.remove("F2"); 
      $scope.modulo = 'historialCitas'; 
      $scope.dirComprobantes = 'https://citasenlinea.villasalud.pe/comprobantesWeb/';
      blockUI.start('Cargando historial de citas...');

      rootServices.sGetSessionCI().then(function (response) {
        if(response.flag == 1){
          $scope.fSessionCI = response.datos;
        }
      });

      $scope.fBusqueda = {};
      $scope.listaTipoCita = [
        {id:'P', descripcion:'CITAS PENDIENTES'},
        {id:'R', descripcion:'CITAS REALIZADAS'}
      ]
      $scope.fBusqueda.tipoCita = $scope.listaTipoCita[0];
      var fechaHasta = moment().add(6,'days');
      $scope.fBusqueda.desde =  $filter('date')(moment().toDate(),'dd-MM-yyyy'); 
      $scope.fBusqueda.hasta =  $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy');

      var datos = {
        search:1,
        nameColumn:'tiene_prog_cita'
      };
      sedeServices.sListarSedesCbo(datos).then(function (rpta) {
        $scope.listaSedes = rpta.datos;
        $scope.listaSedes.splice(0,0,{ id : 0, idsede:0, descripcion:'SEDE'});
        $scope.fBusqueda.sede = $scope.listaSedes[0];
      });

      $scope.listarParientes = function(externo){
        parienteServices.sListarParientesCbo().then(function (rpta) {
          $scope.listaFamiliares = rpta.datos;
          $scope.listaFamiliares.splice(0,0,{ idusuariowebpariente:0, descripcion: $scope.fSessionCI.nombres + ' [TITULAR]'});
          if(externo){          
            $scope.fBusqueda.familiar = $scope.listaFamiliares[$scope.listaFamiliares.length-1]; 
          }else{
            $scope.fBusqueda.familiar = $scope.listaFamiliares[0];
          }
        });
      }
      $scope.listarParientes();

      $scope.listaEspecialidad = [
        { id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '}
      ];
      $scope.fBusqueda.especialidad = $scope.listaEspecialidad[0];

      $scope.listarEspecialidad = function(){
        var datos = {
          idsede : $scope.fBusqueda.sede.id,
        }

        especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
          $scope.listaEspecialidad = rpta.datos;
          $scope.listaEspecialidad.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
          $scope.fBusqueda.especialidad = $scope.listaEspecialidad[0];
        });
      }

      $scope.listarHistorial = function(){
        blockUI.start('Cargando historial de citas...');
        historialCitasServices.sCargarHistorialCitas($scope.fBusqueda).then(function(rpta){          
          $scope.listaDeCitas = rpta.datos; 
          blockUI.stop();       
        });
      }
      $scope.listarHistorial();

      $scope.reprogramarCita = function(cita){        
        programarCitaServices.sVerificaEstadoCita(cita).then(function(rpta){
          if(rpta.flag == 1){
            $scope.viewPlanning(cita);
          }else if(rpta.flag == 0){
            $uibModal.open({ 
              templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_aviso',
              size: 'sm',
              //backdrop: 'static',
              //keyboard:false,
              scope: $scope,
              controller: function ($scope, $modalInstance) {                 
                $scope.titleForm = 'Aviso'; 
                $scope.msj = rpta.message;

                $scope.btnCancel = function(){
                  $modalInstance.dismiss('btnCancel');
                }
              }
            });
          }else{
            alert('Error inesperado');
          }
        });

        $scope.viewPlanning = function(cita){
          $uibModal.open({ 
            templateUrl: angular.patchURLCI+'ProgramarCita/ver_popup_planning',
            size: 'xlg',
            //backdrop: 'static',
            //keyboard:false,
            scope: $scope,
            controller: function ($scope, $modalInstance) {
              $scope.formats = ['dd-MM-yyyy','dd-MMMM-yyyy','yyyy/MM/dd','dd.MM.yyyy','shortDate'];
              $scope.format = $scope.formats[0]; // formato por defecto
              $scope.datePikerOptions = {
                formatYear: 'yy',
                // startingDay: 1,
                'show-weeks': false,
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
              
              $scope.fBusquedaRep = {};
              $scope.fBusquedaPlanning = {};
              $scope.fBusquedaPlanning = angular.copy(cita);

              angular.forEach($scope.listaFamiliares, function(value, key) {
                if(value.idusuariowebpariente == $scope.fBusquedaPlanning.itemFamiliar.idusuariowebpariente){
                  $scope.fBusquedaRep.itemFamiliar = $scope.listaFamiliares[key];
                }                
              });

              angular.forEach($scope.listaSedes, function(value, key) {
                if(value.id == $scope.fBusquedaPlanning.itemSede.id){
                  $scope.fBusquedaRep.itemSede = $scope.listaSedes[key];
                }                
              });

              var datos = {
                idsede : $scope.fBusquedaRep.itemSede.id,
              }
              especialidadServices.sListarEspecialidadesProgAsistencial(datos).then(function (rpta) {
                $scope.listaEspecialidadRep = rpta.datos;
                $scope.listaEspecialidadRep.splice(0,0,{ id : 0, idespecialidad:0, descripcion:'ESPECIALIDAD '});
                angular.forEach($scope.listaEspecialidadRep, function(value, key) {
                  if(value.id == $scope.fBusquedaPlanning.itemEspecialidad.id){
                    $scope.fBusquedaRep.itemEspecialidad = $scope.listaEspecialidadRep[key];
                  }                
                });
              });

              var fechaHasta = moment().add(6,'days');
              $scope.fBusquedaPlanning.desde =  $filter('date')(moment().toDate(),'dd-MM-yyyy'); 
              $scope.fBusquedaPlanning.hasta =  $filter('date')(fechaHasta.toDate(),'dd-MM-yyyy');              

              $scope.btnCancel = function(){
                $modalInstance.dismiss('btnCancel');
              }

              $scope.cargarPlanning = function(){
                console.log($scope.fBusquedaPlanning);
                programarCitaServices.sCargarPlanning($scope.fBusquedaPlanning).then(function(rpta){
                  $scope.fPlanning = rpta.planning;
                });
              }
              $scope.cargarPlanning();

              $scope.viewTurnos = function(item){
                $scope.fPlanning.citas = {};
                $scope.fPlanning.citas.oldCita = cita;
                var callback = function (){
                  $scope.btnCancel();
                  $scope.listarHistorial();
                  $scope.getNotificacionesEventos();
                }
                $controller('programarCitaController', { 
                  $scope : $scope
                });
                $scope.verTurnosDisponibles(item, true, callback);
              }

              $scope.getMedicoAutocomplete = function (value) {
                var params = $scope.fBusquedaPlanning;
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
                $scope.fBusquedaPlanning.itemMedico = $item;
              }
            }
          });
        }
      }

      $scope.cambiarVista = function(){
        $scope.listarHistorial();
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

      $scope.quitarDeLista = function(index, fila){
        blockUI.start('Actualizando...');
        //console.log(index, fila);
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
      }
      blockUI.stop();
  })
  .service("historialCitasServices",function($http, $q) {
    return({
      sCargarHistorialCitas:sCargarHistorialCitas,
    });
    function sCargarHistorialCitas(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"HistorialCitas/lista_historial_citas", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }  
  });