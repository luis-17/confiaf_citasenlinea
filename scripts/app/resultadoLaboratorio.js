angular.module('theme.resultadolaboratorio', ['theme.core.services'])
/*  .filter('propsFilter', function() {
    return function(items, props) {
      var out = [];

      if (angular.isArray(items)) {
        var keys = Object.keys(props);

        items.forEach(function(item) {
          var itemMatches = false;

          for (var i = 0; i < keys.length; i++) {
            var prop = keys[i];
            var text = props[prop].toLowerCase();
            if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
              itemMatches = true;
              break;
            }
          }
          if (itemMatches) {
            out.push(item);
          }
        });
      } else {
        // Let the output be the input untouched
        out = items;
      }
      return out;
    };
  })
*/
  .controller('resultadolaboratorioController', ['$scope', '$sce','$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI', 
    'resultadolaboratorioServices', 'analisisServices', 'ModalReporteFactory' ,
    'rootServices',
    function($scope, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI, 
      resultadolaboratorioServices, analisisServices , ModalReporteFactory ,
      rootServices
    ){ 
    'use strict'; 

    $('#table-two-axis').basictable();

    var vm = this;

    vm.listaParams = {};
    vm.examen = {};

    vm.disabled = undefined;
    vm.searchEnabled = undefined;

    vm.setInputFocus = function (){
      $scope.$broadcast('UiSelectDemo1');
    };

    vm.enable = function() {
      vm.disabled = false;
    };

    vm.disable = function() {
      vm.disabled = true;
    };

    vm.enableSearch = function() {
      vm.searchEnabled = true;
    };

    vm.disableSearch = function() {
      vm.searchEnabled = false;
    };

    vm.clear = function() {
      vm.examen.selected = null;
    };

    vm.someGroupFn = function (item){
      if (item.name[0] >= 'A' && item.name[0] <= 'M')
          return 'From A - M';
      if (item.name[0] >= 'N' && item.name[0] <= 'Z')
          return 'From N - Z';
    };

    vm.firstLetterGroupFn = function (item){
        return item.name[0];
    };

    vm.reverseOrderFilterFn = function(groups) {
      return groups.reverse();
    };

    vm.counter = 0;
    vm.onSelectCallback = function (item, model){
      vm.counter++;
      vm.eventResult = {item: item, model: model};
    };

    vm.removed = function (item, model) {
      vm.lastRemoved = {
          item: item,
          model: model
      };
    };

    vm.tagTransform = function (newTag) {
      var item = {
          name: newTag,
          email: newTag.toLowerCase()+'@email.com',
          age: 'unknown',
          country: 'unknown'
      };

      return item;
    };

    vm.atenciones = {};

    $scope.datosGridAte = {
      idcliente : $scope.fSessionCI.idcliente
    };

    resultadolaboratorioServices.sCargaResultadoUsuario($scope.datosGridAte).then(function (rpta) {
      vm.verRes = false ;
      vm.verlist = false ;
      vm.verEx = false;

      vm.atenciones = rpta.datos;
      if(vm.atenciones.length>0){
        vm.atenc.selected =  rpta.datos[0];
        vm.ListarAnalisis();
      }else{
        vm.verRes = true ;
        vm.verEx = false;
        vm.verlist = false;
      }
    });

    vm.atenc = {};
    vm.atenc.selected = {};

    vm.ListarAnalisis = function(){
      vm.verlist = false;
      vm.verEx = true;
      vm.verRes = false;
      if(vm.listaParams.length > 0){
        vm.listaParams.length = 0; 
      }

      $scope.fData = {};
      $scope.orden = {};

      $scope.orden['orden_lab'] = vm.atenc.selected.orden_lab ;
      $scope.orden['idsedeempresaadmin'] = vm.atenc.selected.idsedeempresaadmin ;

      resultadolaboratorioServices.sListarPacienteConResultados($scope.orden).then(function (rpta) {
        vm.resultados = rpta.arrAnalisis;
        $scope.fData = rpta.datos;
        $scope.fDataArrPrincipal = rpta.arrSecciones;
        vm.verlist = true ;
        vm.verEx = false;
        vm.verRes = false;
        vm.clear(); 
      });  

    };

    vm.ListarResultados = function(){
      angular.forEach($scope.fDataArrPrincipal,function(valueAP, keyAP){ 
        angular.forEach(valueAP.analisis,function(valueAnal, keyAnal){ 
          $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].seleccionado = false;
          $scope.fDataArrPrincipal[keyAP].seleccionado = false;
        });
      });

      angular.forEach($scope.fDataArrPrincipal,function(valueAP, keyAP){ 
        angular.forEach(valueAP.analisis,function(valueAnal, keyAnal){ 
          if( valueAP.idseccion == vm.examen.selected.idseccion && valueAnal.idanalisis == vm.examen.selected.idanalisis ){
            $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].seleccionado = true;
            $scope.fDataArrPrincipal[keyAP].seleccionado = true;
          }
        });
      });            

      angular.forEach($scope.fDataArrPrincipal,function(valueAP, keyAP){ 
        if($scope.fDataArrPrincipal[keyAP].seleccionado == true){
          angular.forEach(valueAP.analisis,function(valueAnal, keyAnal){ 
              if($scope.fDataArrPrincipal[keyAP].analisis[keyAnal].seleccionado == true){
                angular.forEach(valueAnal.parametros,function(valuePar, keyPar){
                  if($scope.fDataArrPrincipal[keyAP].analisis[keyAnal].parametros[keyPar].iddetalleresultado == null && $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].parametros[keyPar].idparametro != 456){
                    $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].parametros[keyPar].resultado = 'SIN RESULTADOS';
                    $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].parametros[keyPar].valor_normal = '-';
                  }
                });
                vm.listaParams = $scope.fDataArrPrincipal[keyAP].analisis[keyAnal].parametros;
                return;
              }              
          });
        }
      });
    }    
  }])
  .service("resultadolaboratorioServices",function($http, $q) {
    return({
      sCargaResultadoUsuario :sCargaResultadoUsuario ,
      sListarPacienteConResultados : sListarPacienteConResultados
    });
    function sCargaResultadoUsuario(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Resultadolaboratorio/carga_resultados_usuario", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sListarPacienteConResultados(pDatos) { 
      var datos = pDatos || {};
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Resultadolaboratorio/listarPacientesConResultados", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    

  }); 