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
      <div class="row mt-md mb-md">
        <div class="col-md-2 col-xs-12 col-sm-3 text-center pr-n">
          <img id="img-lab" ng-src="{{ dirImages + 'dinamic/empresa/gm-laboratorio5.png'}}" width="100" height="100"/>       
        </div>
        <div class="col-md-10 col-xs-12 col-sm-9 pl-n pt-md pb-n" style="border-bottom: 2px solid #95A5A7;">
          <h3 class="resultado pb-n"> Resultados de Laboratorio</h3> 
          <h4 class="datos pull-right pt-sm"><span class="ti ti-user"></span> Paciente : <strong>{{ fSessionCI.nombres }} {{ fSessionCI.apellido_paterno}} {{ fSessionCI.apellido_materno }} </strong></h4>
        </div>
      </div>
      <div class="row"><!-- grid de ordenes -->
        <div class="row">


      <div class="form-group col-md-4">
        <label class="control-label">Atenciones</label>
        <div class="m-n">
          <div class="col-sm-12 p-n">
            <ui-select ng-model="ctrl.atenc.selected" theme="selectize" title="Choose a person">
              <ui-select-match placeholder="Seleccione ...">{{$select.selected.name}}</ui-select-match>
              <ui-select-choices repeat="item in ctrl.atenciones | filter: $select.search">
                <div ng-bind-html="item.name | highlight: $select.search"></div>
                <!--<small ng-bind-html="item.email | highlight: $select.search"></small>-->
              </ui-select-choices>
            </ui-select>
          </div>          
        </div>
      </div>

      <div class="form-group col-md-6">
        <label class="control-label">Examenes</label>
        <div>
          <div class="col-sm-12 p-n">
            <ui-select ng-model="ctrl.examen.selected" theme="selectize">
              <ui-select-match placeholder="Seleccione el Examen ...">{{$select.selected.descripcion}}</ui-select-match>
              <ui-select-choices group-by="'country'" repeat="item in ctrl.resultados | filter: $select.search">
                <span ng-bind-html="item.descripcion | highlight: $select.search"></span>

                <small class="badge badge-primary pull-right" ng-bind-html="item.contador | highlight: $select.search"></small>
                <small class="text text-primary pull-right"><strong>&nbsp; CANTIDAD &nbsp;&nbsp;</strong></small>
                <!--<small class="label label-primary pull-right" ng-bind-html="item.fecha | highlight: $select.search"></small>-->
              </ui-select-choices>
            </ui-select>

          </div>

        </div>
      </div>
      <div class="form-group col-md-2  pt-md mt-md">
        <button type="button" ng-click="ListarResultados();" class="btn btn-primary">
          <span class="glyphicon glyphicon-search"></span> Consultar
        </button>
      </div>

          

        </div>
        <div class="table-responsive">
        <table id="table-two-axis" class="two-axis">
          <thead>
            <tr>
              <th>Name</th>
              <th>Age</th>
              <th>Gender</th>
              <th>Height</th>
              <th>Province</th>
              <th>Sport</th>
              <th>Name</th>
              <th>Age</th>
              <th>Gender</th>
              <th>Height</th>
              <th>Province</th>
              <th>Sport</th>              
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Jill Smith</td>
              <td>25</td>
              <td>Female</td>
              <td>5'4</td>
              <td>British Columbia</td>
              <td>Volleyball</td>
              <td>Jill Smith</td>
              <td>25</td>
              <td>Female</td>
              <td>5'4</td>
              <td>British Columbia</td>
              <td>Volleyball</td>              
            </tr>
            <tr>
              <td>John Stone</td>
              <td>30</td>
              <td>Male</td>
              <td>5'9</td>
              <td>Ontario</td>
              <td>Badminton</td>
              <td>John Stone</td>
              <td>30</td>
              <td>Male</td>
              <td>5'9</td>
              <td>Ontario</td>
              <td>Badminton</td>              
            </tr>
            <tr>
              <td>Jane Strip</td>
              <td>29</td>
              <td>Female</td>
              <td>5'6</td>
              <td>Manitoba</td>
              <td>Hockey</td>
              <td>Jane Strip</td>
              <td>29</td>
              <td>Female</td>
              <td>5'6</td>
              <td>Manitoba</td>
              <td>Hockey</td>              
            </tr>
            <tr>
              <td>Gary Mountain</td>
              <td>21</td>
              <td>Mail</td>
              <td>5'8</td>
              <td>Alberta</td>
              <td>Curling</td>
              <td>Gary Mountain</td>
              <td>21</td>
              <td>Mail</td>
              <td>5'8</td>
              <td>Alberta</td>
              <td>Curling</td>              
            </tr>
            <tr>
              <td>James Camera</td>
              <td>31</td>
              <td>Male</td>
              <td>6'1</td>
              <td>British Columbia</td>
              <td>Hiking</td>
              <td>James Camera</td>
              <td>31</td>
              <td>Male</td>
              <td>6'1</td>
              <td>British Columbia</td>
              <td>Hiking</td>              
            </tr>
          </tbody>
        </table>
         </div>            
      </div>
    </div>  
</div>
