angular.module('theme.inicio', ['theme.core.services'])
  .controller('inicioController', function($scope, $theme, $filter
    ,inicioServices
    ,sedeServices
    ,usuarioServices ){
      'use strict';
      shortcut.remove("F2"); 
      $scope.modulo = 'inicio'; 
      $scope.arrays = {};
      $scope.fDataFiltro = {};
      $scope.fBusqueda = {};
      $scope.arrays.listaAvisos = [];
      $scope.arrays.listaCumpleaneros = [];
      $scope.arrays.listaTelefonica = [];
      $scope.arrays.listaDocumentosInterno = [];

      $scope.listaMeses = [
        { 'id': 1, 'mes': 'Enero' },
        { 'id': 2, 'mes': 'Febrero' },
        { 'id': 3, 'mes': 'Marzo' },
        { 'id': 4, 'mes': 'Abril' },
        { 'id': 5, 'mes': 'Mayo' },
        { 'id': 6, 'mes': 'Junio' },
        { 'id': 7, 'mes': 'Julio' },
        { 'id': 8, 'mes': 'Agosto' },
        { 'id': 9, 'mes': 'Septiembre' },
        { 'id': 10, 'mes': 'Octubre' },
        { 'id': 11, 'mes': 'Noviembre' },
        { 'id': 12, 'mes': 'Diciembre' }
      ];
      var mes_actual = $filter('date')(new Date(),'M');

      usuarioServices.sRecargarUsuarioSession($scope.fSessionCI).then(function(rpta){
        if(rpta.flag == 1){
          $scope.fSessionCI = rpta.datos;            
        } 
      });
      
      $scope.goToPerfil = function(){
        $scope.goToUrl('/mi-perfil');
      }

      $scope.goToHistorial = function(){
        $scope.goToUrl('/historial-citas');
      }

      $scope.goToResultados = function(){
        $scope.goToUrl('/resultado-laboratorio');
      } 

      $scope.goToSelCita = function(){
        $scope.goToUrl('/seleccionar-cita');
      } 
  })
  .service("inicioServices",function($http, $q) {
    return({
    });
  });