<div class="msj modal-close" >
	<a href ng-click="btnCancel(); $event.preventDefault();" class="btn-close"><i class="ti ti-close"></i></a>
</div>
<div class="msj modal-header" style="padding-top: 20px;">
	{{titleForm}}	
</div>
<div class="msj modal-body row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<img src="{{ dirImages + 'alertas/banner-compra.jpg'  }}" alt="Compra Exitosa"  class="img-alerta" style="max-height:500px;" />
		<div class="msj-text-alerta">
			<h4 style="color: #ce1d19;font-weight: bold;">¡Compra Exitosa!</h4>
			<p>Tu comprobante de pago está en proceso de ser emitido. Recibirás un correo cuando este listo</p>
		</div>
	</div>
</div>
<div class="msj modal-footer" >
</div>
