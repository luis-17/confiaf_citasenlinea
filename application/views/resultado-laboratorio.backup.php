<style type="text/css">
  .glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -webkit-animation: spin2 .7s infinite linear;
  }

  .resultado {
    border-bottom:1px solid #95A5A7;
    color:#23A2B7 !important;
    font-size:26px;
    padding:0;
    padding-bottom: 5px;
    font-family: 'Oswald', sans-serif;
  }  
  .datos {
    border-bottom:0;
    color: #95A5A7 !important;
    margin-top :0;
    margin-bottom: 7px;
    font-size:16px;
    padding:0;
    font-family: 'Oswald', sans-serif;
  }
 
</style>
<div ng-controller="resultadolaboratorioController">
    <div class="container">
      <div class="row mt-md mb-md">
        <div class="col-md-2 col-xs-12 col-sm-3">
          <img id="img-lab" ng-src="{{ dirImages + 'dinamic/empresa/gm-laboratorio4.png'}}" width="150" height="150"/>       
        </div>
        <div class="col-md-10 col-xs-12 col-sm-9">
          <h3 class="resultado"> Resultados de Laboratorio</h3> 
          <h4 class="datos"><span class="ti ti-user"></span> Paciente : <strong>{{ fSessionCI.nombres }} {{ fSessionCI.apellido_paterno}} {{ fSessionCI.apellido_materno }} </strong></h4>
          <h4 class="datos"><span class="ti ti-calendar"></span> Fecha de Nacimiento : <strong>{{ fSessionCI.fecha_nacimiento }}</strong></h4>
          <h4 class="datos"><span class="ti ti-time"></span> Edad : <strong>{{ fSessionCI.edad }}</strong></h4>

        </div>
      </div>
      <div class="row"><!-- grid de ordenes -->
          <div class="panel" data-widget='{"draggable": "false"}' ng-show="vistabla1"> <!-- grid de ordenes -->
            <div class="panel-body" style="height: 200px">
              <div class="col-md-12 col-sm-12">
                <table id="grid" class="mb-md"></table> 
              </div>
              <div class="row text-center" ng-show="vistabla2">
                  <label class="label label-primary p-sm"><i class="fa fa-refresh fa-spin" style="font-size:18px"></i> Cargando Resultados ...</label>
              </div>                                                                             
            </div>
          </div>
          <div class="panel" data-widget='{"draggable": "false"}' ng-show="vistabla3"> <!-- grid de ordenes -->
            <div class="panel-body" style="height: 400px">
              <div class="col-md-12 col-sm-12">
                <button class="btn btn-danger mb-md" ng-click="volver();"><i class="fa fa-arrow-left"></i> Volver </button>
                <table id="gridDetail"></table> 
              </div>                                                                             
            </div>
          </div>                      
      </div>
    </div>  
</div>
