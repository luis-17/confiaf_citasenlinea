angular.module('theme.especialidad', ['theme.core.services'])
  .controller('especialidadController', ['$scope', '$sce', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 
    'especialidadServices', 
    function($scope, $sce, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, 
      especialidadServices  ){
    'use strict';
    shortcut.remove("F2"); 
    $scope.modulo = 'especialidad';
    

  }])
  .service("especialidadServices",function($http, $q) {
    return({
        sListarEspecialidadesProgAsistencial: sListarEspecialidadesProgAsistencial,  
    });

    function sListarEspecialidadesProgAsistencial (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"especialidad/lista_especialidades_prog_asistencial", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }  
  });
