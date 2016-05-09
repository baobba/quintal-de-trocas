<div class="subscribe-form">
	<form action="" method="post">
        <input type="hidden" name="<?php echo $formx->get_form_name(); ?>" value="<?php echo $formx->use_field($formx->get_form_name())->get_value(); ?>" />
        <?php 
			if (count($formx->get_form_errors())) {
		    	foreach($formx->get_form_errors() as $error) {
			        echo '<p style="color:#990000">' . $error . '</p>';
		    	}
			} elseif ($formx->is_sucess()) {
			    echo '<p style="color:#A2D246"><b>Newsletter cadastrada!</b></p>';
			}
        ?>
        	       
		<fieldset>
			<h3>Newsletter</h3>
			<p>Receba nossas novidades por e-mail:</p>
			<p>Nome</p>
			<div class="text">
				<input type="text" value="<?php echo $formx->use_field('name')->get_posted(); ?>" name="name" />
			</div>
			<br />
			<p>E-mail</p>
			<div class="text">
				<input type="text" value="<?php echo $formx->use_field('email')->get_posted(); ?>" name="email" />
			</div>
			<input class="btn-submit" type="submit" value="Cadastrar" />
		</fieldset>
	</form>
</div>