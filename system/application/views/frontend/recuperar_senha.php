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
			} elseif ($formx->is_sucess()) {
			    echo '<p style="color:#A2D246"><b>Um link foi enviado ao seu e-mail!</b></p>';
			}
        ?>
		<form action="<?php echo $base_url . URL_USUARIO_RECUPERAR_SENHA; ?>" method="post">
            <input type="hidden" name="<?php echo $formx->get_form_name(); ?>" value="<?php echo $formx->use_field($formx->get_form_name())->get_value(); ?>" />
			<fieldset>
				<div class="col-l">
					<div class="row">
						<label for="lbl-01">E-mail:</label>
						<div class="text">
							<input type="text" id="lbl-01" name="email" />
						</div>
					</div>
					<div class="row">
						<label for="lbl-02">E-mail (Confirma&ccedil;&atilde;o):</label>
						<div class="text">
							<input type="text" id="lbl-02" name="email_check" />
						</div>
					</div>
					<input class="btn-submit" type="submit" value="Recuperar senha" />
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- /contact-area -->