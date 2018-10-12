<style>
  .selectize-control {
    /* Align Selectize with input-group-btn */
    top: 2px;
  }
  .selectize-control > .selectize-dropdown {
    top: 34px;
  }
  /* Reset right rounded corners, see Bootstrap input-groups.less */
  .input-group > .selectize-control > .selectize-input {
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
  }
</style>

<div ng-controller="resultadolaboratorioController as ctrl">
    <div class="container">
      <!-- <div class="form-group mt-md mb-md col-md-12 pl-n">
        <div class="col-md-2 col-xs-12 col-sm-3 pr-n pl-n">
          <img id="img-lab" class="pull-left" ng-src="{{ dirImages + 'dinamic/empresa/gm-laboratorio5.png'}}" width="100" height="100"/>       
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 p-n" style="border-bottom: 2px solid #95A5A7;">
          <div class="col-md-6 col-xs-12 col-sm-6 pl-n pt-md pb-n">
            <h3 class="resultado pb-n"> Resultados de Laboratorio</h3> 
          </div>
          <div class="col-md-6 col-xs-12 col-sm-6 pl-n pt-md pb-n" >
            <h4 class="datos pt-sm"><span class="ti ti-user"></span> Paciente : <strong>{{ fSessionCI.nombres }} {{ fSessionCI.apellido_paterno}} {{ fSessionCI.apellido_materno }} </strong></h4>
          </div>
        </div>
      </div> -->
      <div class="row">
          <div class="tab-heading col-sm-7">                                        
              <div>
                  <h2 class="title">
                      <span class="icon"><i class="fa fa-flask"></i></span> Resultados de Laboratorio
                  </h2> 
                  <p class="descripcion" style="font-size: 18px;font-weight: 400;position: relative;top: 20px;">
                    <span class="ti ti-user"></span> 
                    Paciente: {{ fSessionCI.nombres }} {{ fSessionCI.apellido_paterno}} {{ fSessionCI.apellido_materno }} 
                  </p>
              </div>
          </div>
      </div>
      <!--<div class="clearfix"></div>-->
      <div class="form-group"><!-- grid de ordenes -->
        <div class="form-group">
          <div class="form-group col-md-4 pl-n">
            <label class="control-label">Num.Orden - Fecha Muestra</label>
            <div class="m-n">
              <div class="col-sm-12 p-n">
                <ui-select ng-model="ctrl.atenc.selected" theme="selectize" on-select="ctrl.ListarAnalisis()">
                  <ui-select-match placeholder="Seleccione ...">{{$select.selected.orden_venta}} <strong>[ {{$select.selected.fecha_recepcion}} ]</strong></ui-select-match>
                  <ui-select-choices repeat="item in ctrl.atenciones | filter: $select.search">
                    <span ng-bind-html="item.orden_venta | highlight: $select.search"></span>
                    <small class="pull-right" ng-bind-html="item.fecha_recepcion | highlight: $select.search"></small>
                  </ui-select-choices>
                </ui-select>
              </div>          
            </div>
          </div>

          <div class="form-group col-md-6 pl-n">
            <label class="control-label">Examenes</label>
            <div>
              <div class="col-sm-12 p-n">
                <ui-select ng-model="ctrl.examen.selected" theme="selectize" ng-if="ctrl.verlist">
                  <ui-select-match id="datExamen" placeholder="Seleccione el Examen ...">{{$select.selected.descripcion_anal}}</ui-select-match>
                  <ui-select-choices repeat="item in ctrl.resultados | filter: $select.search">
                    <span ng-bind-html="item.descripcion_anal | highlight: $select.search"></span>
                  </ui-select-choices>
                </ui-select>
                <div class="row ml-sm pt-sm" ng-if="ctrl.verEx" >
                  <span ><i class="fa fa-circle-o-notch fa-spin" style="font-size:18px"></i> Cargando Examenes ...</span>
                </div>
                <div class="row ml-sm pt-sm" ng-if="ctrl.verRes" >
                  <span > El usuario no tiene examenes.</span>
                </div>                

              </div>

            </div>
          </div>
          <div class="form-group col-md-2 pt-md mt-md pl-n">
            <button type="button" ng-click="ctrl.ListarResultados()" class="btn btn-page" ng-disabled="ctrl.examen.selected == undefined" >
              <span class="glyphicon glyphicon-search"></span> Consultar
            </button>
          </div>
        </div>

        <table id="table-two-axis" class="two-axis">
          <thead>
            <tr>
              <th class="col-sm-4">Analisis</th>
              <th class="col-sm-4">Resultado</th>
              <th class="col-sm-4">Referencia</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="grid in ctrl.listaParams">
              <td data-th="Analisis">
                {{ grid.parametro }}
              </td>
              <td data-th="Resultado">
                {{ grid.resultado }}
              </td>
              <td data-th="Referencia">
                {{ grid.valor_normal }}
              </td>

            </tr>
          </tbody>
        </table>
      </div>
    </div>  
</div>
