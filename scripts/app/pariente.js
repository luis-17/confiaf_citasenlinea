angular.module('theme.pariente', ['theme.core.services'])
  .controller('parienteController', ['$scope', '$controller', '$sce', '$uibModal', '$bootbox', '$window', '$http', '$theme', '$log', '$timeout', 'uiGridConstants', 'pinesNotifications', 'hotkeys', 'blockUI',
    'parienteServices',
    'parentescoServices',
    function($scope, $controller, $sce, $uibModal, $bootbox, $window, $http, $theme, $log, $timeout, uiGridConstants, pinesNotifications, hotkeys, blockUI,
     parienteServices,
     parentescoServices
    ){
    'use strict';
    shortcut.remove("F2"); 
    $scope.modulo = 'pariente';
    $scope.cargarItemFamiliar(null);
    $scope.listaSexos = [
      {id:'-', descripcion:'SELECCIONE SEXO'},
      {id:'F', descripcion:'FEMENINO'},
      {id:'M', descripcion:'MASCULINO'}
    ];

    var paginationOptions = {
      pageNumber: 1,
      firstRow: 0,
      pageSize: 10,
      sort: uiGridConstants.ASC,
      sortName: null,
      search: null
    };

    $scope.mySelectionGrid = [];

    $scope.gridOptions = {
      paginationPageSizes: [10, 50, 100, 500, 1000],
      paginationPageSize: 10,
      useExternalPagination: true,
      useExternalSorting: true,
      enableGridMenu: true,
      enableRowSelection: true,
      enableSelectAll: true,
      enableFiltering: true,
      enableFullRowSelection: true,
      multiSelect: true,
      columnDefs: [
        { field: 'idusuariowebpariente', name: 'idusuariowebpariente', displayName: 'ID', width: '8%',  sort: { direction: uiGridConstants.ASC} },
        { field: 'pariente', name: 'pariente', displayName: 'Pariente', },        
        { field: 'parentesco', name: 'parentesco', displayName: 'Parentesco', width: '20%', }, 
        { field: 'sexo', name: 'sexo', displayName: 'Sexo', width: '12%', },       
      ],
      onRegisterApi: function(gridApi) {
        $scope.gridApi = gridApi;
        gridApi.selection.on.rowSelectionChanged($scope,function(row){
          $scope.mySelectionGrid = gridApi.selection.getSelectedRows();
        });
        gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
          $scope.mySelectionGrid = gridApi.selection.getSelectedRows();
        });

        $scope.gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
          if (sortColumns.length == 0) {
            paginationOptions.sort = null;
            paginationOptions.sortName = null;
          } else {
            paginationOptions.sort = sortColumns[0].sort.direction;
            paginationOptions.sortName = sortColumns[0].name;
          }
          $scope.refreshListaParientes();
        });
        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
          paginationOptions.pageNumber = newPage;
          paginationOptions.pageSize = pageSize;
          paginationOptions.firstRow = (paginationOptions.pageNumber - 1) * paginationOptions.pageSize;
          $scope.refreshListaParientes();
        });
        $scope.gridApi.core.on.filterChanged( $scope, function(grid, searchColumns) {
          var grid = this.grid;
          paginationOptions.search = true;
          // console.log(grid.columns);
          // console.log(grid.columns[1].filters[0].term);
          paginationOptions.searchColumn = {
            'idusuariowebpariente' : grid.columns[1].filters[0].term,
            "concat_ws(' ',  c.nombres, c.apellido_paterno, c.apellido_materno)" : grid.columns[2].filters[0].term,
            'cp.descripcion' : grid.columns[3].filters[0].term,
            'c.sexo' : grid.columns[4].filters[0].term,
          }
          $scope.refreshListaParientes();
        });
      }
    };

    paginationOptions.sortName = $scope.gridOptions.columnDefs[0].name;
    
    $scope.refreshListaParientes = function(){
      blockUI.start('Cargando familiares...');
      $scope.datosGrid = {
        paginate : paginationOptions
      };
      parienteServices.sListarParientes($scope.datosGrid).then(function (rpta) {
        $scope.gridOptions.totalItems = rpta.paginate.totalRows;
        $scope.gridOptions.data = rpta.datos;
        $scope.listaParientes = rpta.datos;
        blockUI.stop();
      });
      $scope.mySelectionGrid = [];
    };
    

    $scope.btnNuevoPariente = function(callback){
      blockUI.start('Abriendo formulario...');
      $scope.fData = {}; 
      $scope.fData.sexo = '-'; 
      $scope.accion ='reg';
      $scope.fAlert = {};
      $scope.fAlertFam = {};
      parentescoServices.sListarParentescoCbo().then(function(rpta){
        $scope.regListaParentescos = rpta.datos;
        $scope.regListaParentescos.splice(0,0,{ id : 0, idparentesco:0, descripcion:'SELECCIONE PARENTESCO'});
        $scope.fData.parentesco = $scope.regListaParentescos[0];
      });

      
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Pariente/ver_popup_formulario',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = 'Agregar Familiar';     

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
          }

          $scope.verificarDoc = function(){
            if(!$scope.fData.num_documento || $scope.fData.num_documento == null || $scope.fData.num_documento == ''){
              $scope.fAlert = {};
              $scope.fAlert.type= 'danger';
              $scope.fAlert.msg='Debe ingresar un Número de documento.';
              $scope.fAlert.strStrong = 'Error';
              $scope.fAlert.icon = 'fa fa-exclamation';
              return;
            }
            parienteServices.sVerificarParientePorDocumento($scope.fData).then(function (rpta) {              
              $scope.fAlert = {};
              if( rpta.flag == 2 ){ //Cliente registrado en Sistema Hospitalario
                $scope.fData = rpta.usuario;
                $scope.fData.parentesco = $scope.regListaParentescos[0];
                $scope.fAlert.type= 'info';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.icon= 'fa fa-smile-o';
                $scope.fAlert.strStrong = 'Genial! ';
              }else if( rpta.flag == 1 ){ // Usuario ya registrado en web
                //$scope.fData = rpta.usuario;
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Aviso! ';
                $scope.fAlert.icon = 'fa  fa-exclamation-circle';
              }else if(rpta.flag == 0){
                var num_documento = $scope.fData.num_documento;                
                $scope.fAlert.type= 'warning';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Aviso! ';
                $scope.fAlert.icon = 'fa fa-frown-o';
                $scope.fData = {};
                $scope.fData.num_documento = num_documento;
                $scope.fData.sexo = '-';
                $scope.fData.parentesco = $scope.regListaParentescos[0];
              }
              $scope.fAlert.flag = rpta.flag;
            });
          }

          $scope.btnRegistrarPariente = function (){
            blockUI.start('Registrando familiar...');
            parienteServices.sRegistrarPariente($scope.fData).then(function (rpta) {              
              $scope.fAlert = {};
              $scope.fAlertFam = {};
              if(rpta.flag == 0){
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error';
                $scope.fAlert.icon = 'fa fa-exclamation';
              }else if(rpta.flag == 1){
                $scope.fData = {};
                $scope.fData.sexo = '-';
                $scope.fAlertFam.type= 'success';
                $scope.fAlertFam.msg= rpta.message;
                $scope.fAlertFam.icon= 'fa fa-smile-o';
                $scope.fAlertFam.strStrong = 'Genial! ';
                $scope.fAlertFam.flag = rpta.flag;
                if(callback){
                  callback();                  
                }else{
                  $scope.refreshListaParientes();
                  $scope.btnCancel();
                }
                $scope.getNotificacionesEventos();
                $scope.btnCancel();                
              }
              $scope.fAlert.flag = rpta.flag;
              blockUI.stop();
            });
          }   
        
          blockUI.stop();
        }
      });
    }

    $scope.btnEditarPariente = function(row){
      blockUI.start('Abriendo formulario...');
      $scope.fData = angular.copy(row); 
      $scope.accion ='edit';
      $scope.regListaParentescos = angular.copy($scope.listaParentescos);
      $scope.regListaParentescos[0].descripcion = 'SELECCIONE PARENTESCO';

      angular.forEach($scope.regListaParentescos, function(value, key) {
        if(value.idparentesco == $scope.fData.idparentesco){
            $scope.fData.parentesco= $scope.regListaParentescos[key];
        }        
      });      

      
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Pariente/ver_popup_formulario',
        size: '',
        backdrop: 'static',
        keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = 'Editar Familiar';     

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
          }

          $scope.btnActualizarPariente = function (){
            blockUI.start('Editar familiar...');
            parienteServices.sActualizarPariente($scope.fData).then(function (rpta) {              
              $scope.fAlert = {};
              $scope.fAlertFam = {};
              if(rpta.flag == 0){
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error';
                $scope.fAlert.icon = 'fa fa-exclamation';                
              }else if(rpta.flag == 1){
                $scope.fData = {};
                $scope.fData.sexo = '-';
                $scope.fAlertFam.type= 'success';
                $scope.fAlertFam.msg= rpta.message;
                $scope.fAlertFam.icon= 'fa fa-smile-o';
                $scope.fAlertFam.strStrong = 'Genial! ';
                $scope.fAlertFam.flag = rpta.flag;
                $scope.refreshListaParientes();
                $scope.btnCancel();
              }
              $scope.fAlert.flag = rpta.flag;
              blockUI.stop();
            });
          }   
        
          blockUI.stop();
        }
      });
    }

    $scope.btnEliminarPariente = function(row){
      blockUI.start('');
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Pariente/ver_popup_aviso',
        size: 'sm',
        //backdrop: 'static',
        //keyboard:false,
        scope: $scope,
        controller: function ($scope, $modalInstance) {                 
          $scope.titleForm = 'Aviso'; 
          $scope.msj = '¿Estás seguro de realizar esta acción?';

          $scope.btnOk = function(){
            blockUI.start('Anulando familiar...');
            parienteServices.sEliminarPariente(row).then(function (rpta) {                         
              $scope.fAlert = {};
              if(rpta.flag == 0){
                $scope.fAlert = {};
                $scope.fAlert.type= 'danger';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.strStrong = 'Error';
                $scope.fAlert.icon = 'fa fa-exclamation';                
              }else if(rpta.flag == 1){
                $scope.fData = {};
                $scope.fData.sexo = '-';
                $scope.fAlert.type= 'success';
                $scope.fAlert.msg= rpta.message;
                $scope.fAlert.icon= 'fa fa-smile-o';
                $scope.fAlert.strStrong = 'Genial! ';
                $scope.refreshListaParientes();
              }
              $scope.fAlert.flag = rpta.flag;
              blockUI.stop();
            });
            $scope.btnCancel();
          }

          $scope.btnCancel = function(){
            $modalInstance.dismiss('btnCancel');
          }
          blockUI.stop();
        }
      });
    }

    $scope.btnGenerarCita = function(row){
      $scope.cargarItemFamiliar(row);
      $scope.goToUrl('/seleccionar-cita');
    }

    $scope.initPariente = function(){
      parentescoServices.sListarParentescoCbo().then(function(rpta){
        $scope.listaParentescos = rpta.datos;
        $scope.listaParentescos.splice(0,0,{ id : 0, idparentesco:0, descripcion:'--VER TODOS --'});
        $scope.fBusqueda.parentesco = $scope.listaParentescos[0];
      });
      $scope.fBusqueda = {};      
      $scope.refreshListaParientes();
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
  .service("parienteServices",function($http, $q) {
    return({
        sListarParientes: sListarParientes, 
        sListarParientesCbo:sListarParientesCbo, 
        sVerificarParientePorDocumento: sVerificarParientePorDocumento,
        sRegistrarPariente:sRegistrarPariente,
        sActualizarPariente: sActualizarPariente,  
        sEliminarPariente:sEliminarPariente,   
    });
    function sListarParientes(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/lista_parientes", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    
    function sListarParientesCbo(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/lista_parientes_cbo", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sVerificarParientePorDocumento(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/verificar_pariente_por_documento", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
    function sRegistrarPariente(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/registrar_pariente", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }     
    function sActualizarPariente(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/editar_pariente", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }    

    function sEliminarPariente(datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"pariente/eliminar_pariente", 
            data : datos
      });
      return (request.then( handleSuccess,handleError ));
    }
  });
