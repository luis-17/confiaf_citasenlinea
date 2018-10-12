<div class="msj modal-header">
	{{titleForm}}
</div>
<div class="msj modal-body row">
	<div class="col-md-12 col-sm-12">
		{{msj}}
	</div>
</div>
<div class="msj modal-footer" >
	<a href ng-click="btnOk(); $event.preventDefault();"><i class="fa fa-check-circle-o" style="color:#14A21D;"></i></a>
	<a href ng-click="btnCancel(); $event.preventDefault();"><i class="fa fa-times-circle-o" style="color:#CE1E19;"></i></a>
</div>
