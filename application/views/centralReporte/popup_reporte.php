<div class="msj modal-close" >
	<a href ng-click="cancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="modal-header">
	<h4 class="modal-title"> {{ titleModalReporte }}  </h4>
</div>
<div class="modal-body">
    <form class="row" name="formExamen"> 
		<div class="col-md-12"> 
			<iframe id="frameReporte" style="width: 100%; height: 500px;" type="application/pdf"></iframe> 
		</div>
	</form>
</div>
