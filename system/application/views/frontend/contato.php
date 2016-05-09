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
			<h2>Envie sua mensagem</h2>
		</div>
	</div>
	<div class="feedback-form ie-fix">
        <?php 
			if (count($formx->get_form_errors())) {
		    	foreach($formx->get_form_errors() as $error) {
			        echo '<p style="color:#990000">' . $error . '</p>';
		    	}
			} elseif ($formx->is_sucess()) {
			    echo '<p style="color:#A2D246"><b>Mensagem enviada!</b></p>';
			}
        ?>
		<form action="<?php echo $base_url . URL_CONTATO; ?>" method="post">
            <input type="hidden" name="<?php echo $formx->get_form_name(); ?>" value="<?php echo $formx->use_field($formx->get_form_name())->get_value(); ?>" />
			<fieldset>
				<div class="col-l">
					<div class="row">
						<label for="lbl-01">Nome:</label>
						<div class="text">
							<input type="text" id="lbl-01" name="name" value="<?php echo $formx->use_field('name')->get_posted(); ?>" />
						</div>
					</div>
					<div class="row">
						<label for="lbl-02">E-mail:</label>
						<div class="text">
							<input type="text" id="lbl-02" name="email" value="<?php echo $formx->use_field('email')->get_posted(); ?>"/>
						</div>
					</div>
					<div class="row">
						<label for="lbl-03">Telefone:</label>
						<div class="text">
							<input type="text" id="lbl-03" name="phone" value="<?php echo $formx->use_field('phone')->get_posted(); ?>"/>
						</div>
					</div>
					<div class="row">
						<label for="lbl-04">Cidade:</label>
						<div class="text">
							<input type="text" id="lbl-04" name="city" value="<?php echo $formx->use_field('city')->get_posted(); ?>" />
						</div>
					</div>
					<div class="row">
						<label for="lbl-05">Estado:</label>
                            <?php
                                $_state = trim($_state) == '' ? 'Selecione...' : strtoupper($_state);
                                
						        foreach ($states as $state) {
                                    if (strpos($state, $_state) !== false) {
                                        $_state = $state;
                                        break;
                                    }
                                }
                            ?>
						      
						  <select id="lbl-05" class="cs-3" title="<?php echo $_state; ?>" name="state">
							<?php 
                                foreach ($states as $state) {
                                    $selected = $state == $_state ? 'selected="selected"' : ''; 
                                    echo sprintf('<option value="%s" %s>%s</option>', $state, $selected, $state);
                                }
							?>
						</select>
					</div>
				</div>
				<div class="col-r">
					<div class="row">
						<label for="lbl-06">Assunto:</label>
						<div class="sel-hold">
							<select id="lbl-06" class="cs-3" title="Selecione..." name="subject">
								<option value="">Selecione</option>
								<option value="Quero">Quero ser um ponto de troca</option>
                                <option value="Dúvidas">Dúvidas e Sugestões</option>
								<option value="Imprensa">Imprensa</option>
                                <option value="Publicidade">Publicidade</option>
                                <option value="Outros">Outros</option>
							</select>
						</div>
					</div>
					<div class="row">
						<label for="lbl-07">Mensagem:</label>
						<div class="text">
							<textarea id="lbl-07" cols="30" rows="10" style="resize: none;" name="message"><?php echo $formx->use_field('message')->get_posted(); ?></textarea>
						</div>
					</div>
					<input class="btn-submit" type="submit" value="Enviar mensagem" />
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- /contact-area -->