<div class="page-title page-title2">
	<div class="in">
		<h1>Contato</h1>
		<ul class="breadcrumbs">
			<li><a href="#">Home</a></li>
			<li>Contato</li>
		</ul>
	</div>
</div>
<!-- /page-title -->
<div class="contact-area">
	<div class="heading">
		<div class="col-l">
			<h2>Recuperar senha</h2>
		</div>
	</div>
	<div class="feedback-form ie-fix">
        <?php
			if (count($formx->get_form_errors())) {
		    	foreach($formx->get_form_errors() as $error) {
			        echo '<p style="color:#990000">' . $error . '</p>';
		    	}
			}
        ?>
		<form action="<?php echo $base_url . URL_USUARIO_RECUPERAR_SENHA_CODIGO . $code; ?>" method="post">
            <input type="hidden" name="<?php echo $formx->get_form_name(); ?>" value="<?php echo $formx->use_field($formx->get_form_name())->get_value(); ?>" />
			<fieldset>
				<div class="col-l">
					<div class="row">
						<label for="lbl-01">Nova Senha:</label>
						<div class="text">
							<input type="password" id="lbl-01" name="password" />
						</div>
					</div>
					<div class="row">
						<label for="lbl-02">Nova Senha (Confirma&ccedil;&atilde;o):</label>
						<div class="text">
							<input type="text" id="lbl-02" name="password_check" />
						</div>
					</div>
					<input class="btn-submit" type="submit" value="Alterar senha" />
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- /contact-area -->