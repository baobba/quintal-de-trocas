<form action="<?php echo $base_url . URL_USUARIO_IMAGENS_BRINQUEDO . $toyId ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="<?php echo $formxUpdateToyImages->get_form_name(); ?>" value="<?php echo $formxUpdateToyImages->use_field($formxUpdateToyImages->get_form_name())->get_value(); ?>" />
<fieldset>
	<div class="form-in">
		<h3 class="ttl">Imagem Principal</h3>
		<div class="file-block">
			<input type="file" name="image1" title="Selecione a foto" />
		</div>
		
		<h3 class="ttl">Imagem Extra 1</h3>
		<div class="file-block">
			<input type="file" name="image2" title="Selecione a foto" />
		</div>
		
		
		<h3 class="ttl">Imagem Extra 2</h3>
		<div class="file-block">
			<input type="file" name="image3" title="Selecione a foto" />
		</div>
		
		<div class="btn-holder">
			<input class="btn-submit" type="submit" value="Alterar" />
		</div>
	</fieldset>
</form>