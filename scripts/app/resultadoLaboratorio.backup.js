angular.module('theme.resultadolaboratorio', ['theme.core.services'])
  .filter('propsFilter', function() {
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

  .controller('resultadolaboratorioController', ['$scope', '$sce', '$filter','$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI', 
    'resultadolaboratorioServices', 'analisisServices', 'ModalReporteFactory' ,
    'rootServices',
    function($scope, $sce, $filter, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI, 
      resultadolaboratorioServices, analisisServices , ModalReporteFactory ,
      rootServices
    ){ 
    'use strict'; 

    $('#table-two-axis').basictable();
    console.log("sesion: ",$scope.fSessionCI);
    var vm = this;

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
      vm.person.selected = undefined;
      vm.address.selected = undefined;
      vm.country.selected = undefined;
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


    vm.atenciones = [
      { name: '5 Ultimas',      cantidad : 5  },
      { name: '10 Ultimas',     cantidad : 10 },
      { name: '20 Ultimas',     cantidad : 20 },
      { name: 'Todas',          cantidad : null }
    ];

    vm.atenciones.selected = {};
    vm.atenciones.selected.cantidad = null ;
    //vm.atenc.selectedValue = vm.atencionesObj[3];
    //vm.atenc.selectedSingle = 'Todas';
    //vm.atenc.selectedSingleKey = '4';

    // To run the demos with a preselected person object, uncomment the line below.
    //vm.person.selected = vm.person.selectedValue;

    $scope.datosGrid = {
      idcliente : $scope.fSessionCI.idcliente ,
      periodo : vm.atenciones.selected.cantidad
    }

    $scope.ListarAnalisis = function(){
      analisisServices.sListaranalisisPaciente($scope.datosGrid).then(function (rpta) {
        console.log("rpta :",rpta.datos);
        vm.resultados = rpta.datos;
      });      
    }

    $scope.ListarAnalisis();



    $scope.ListarResultados = function(){
      console.log(vm.examen.selected.id);
      analisisServices.sListaranalisisPacienteIdanalisis(vm.examen.selected.id).then(function (rpta){
        console.log("resultados :",rpta);
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