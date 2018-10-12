angular.module('theme.venta', ['theme.core.services'])
  .controller('ventaController', ['$scope', '$controller', '$filter', '$sce', '$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys','blockUI', 
    'ventaServices',
    function($scope, $controller, $filter, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
      ventaServices
      ){
    'use strict';
    shortcut.remove("F2"); 
    $scope.modulo = 'venta';

  }])
  .service("ventaServices",function($http, $q) {
    return({
      sGenerarVentaCitas:sGenerarVentaCitas,
      sValidarCitas: sValidarCitas,
    });    
    function sGenerarVentaCitas(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Venta/generar_venta_citas", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sValidarCitas(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Venta/validar_citas", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
