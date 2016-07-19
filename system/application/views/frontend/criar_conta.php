<div class="page-title">
	<div class="in">
		<h1>Criar conta</h1>
		<ul class="breadcrumbs">
			<li><a href="index.html">Home</a></li>
			<li>Criar conta</li>
		</ul>
	</div>
</div>

<script>
$(function(){
	$('input[name=zip_code]').keyup(function(){
		atualizacep($(this).val());
	});
});
function correiocontrolcep(valor){
    if (valor.erro) {
		limpaResultadosAnteriores();
		$('input[name=zip_code]').focus();
		return;
    };

    $('input[name=address]').focus();
    $('input[name=address]').val(valor.logradouro);
    $('input[name=neighborhood]').val(valor.bairro);
    $('select[name=state]').val(valor.uf);
    $('input[name=city]').val(valor.localidade);
}

function limpaResultadosAnteriores(){
	$('input[name=address]').val('');
	$('input[name=neighborhood]').val('');
	$('input[name=city]').val('');
    $('select[name=state]').val('');
}
</script>
<!-- /page-title -->
<div class="reg-area">
	<div class="block ie-fix">
		<?php 
			if (count($formx->get_form_errors())) {
		    	foreach($formx->get_form_errors() as $error) {
			        echo '<p style="color:#990000">' . $error . '</p>';
		    	}
			}
        ?>
		<form action="<?php echo $base_url . URL_USUARIO_CRIAR_CONTA; ?>" method="post">
            <input type="hidden" name="<?php echo $formx->get_form_name(); ?>" value="<?php echo $formx->use_field($formx->get_form_name())->get_value(); ?>" />
			<fieldset>
				<div class="cols">
					<div class="cols-in">
						<div class="col">
							<div class="in">
								<h2>Dados de acesso</h2>
								<div class="text email">
									<input type="text" value="<?php echo trim($formx->use_field('email')->get_posted()); ?>" name="email" />
								</div>
								<div class="text email">
									<input type="text" value="<?php echo trim($formx->use_field('email_check')->get_posted()); ?>" name="email_check" />
								</div>
								<div class="text passwd">
									<input type="password" name="password" value="Digite a sua senha" />
								</div>
								<div class="text passwd">
									<input type="password" name="password_check" value="Confirme a sua senha" />
								</div>
							</div>
						</div>
						<div class="col">
							<div class="in">
								<h2>Dados pessoais</h2>
								<div class="text">
									<input type="text" value="<?php echo trim($formx->use_field('name')->get_posted()); ?>" name="name" />
								</div>
								<div class="holder">
									<div class="frame-1">
										<div class="text">
											<input type="text" value="<?php echo trim($formx->use_field('birth_date')->get_posted());; ?>" name="birth_date" placeholder="dd/mm/yyyy" onkeyup="var v = this.value;if (v.match(/^\d{2}$/) !== null) { this.value = v + '/'; } else if (v.match(/^\d{2}\/\d{2}$/) !== null) { this.value = v + '/'; }" maxlength="10" />
										</div>
									</div>
									<div class="sel-hold">
										<select class="cs-3" title="Sexo *" name="gender">
                                            <?php 
        								        $value = trim($formx->use_field('gender')->get_posted());
        								    ?>
											<option value="m" <?php echo $value == 'm' ? 'selected="selected"' : ''; ?>>Masculino</option>
											<option value="f" <?php echo $value == 'f' ? 'selected="selected"' : ''; ?>>Feminino</option>
										</select>
									</div>
								</div>
								<div class="holder">
								    <!-- 
									<div class="frame-1">
										<div class="text">
											<input type="text" name="cpf" value="<?php echo '';#trim($formx->use_field('cpf')->get_posted()); ?>" />
										</div>
									</div>
									 -->
									<div class="text">
										<input type="text" name="phone" value="<?php echo trim($formx->use_field('phone')->get_posted()); ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="in">
								<h2>Endereço</h2>
								<div class="holder">
									<div class="frame-1">
										<div class="text">
											<input type="text" value="<?php echo trim($formx->use_field('zip_code')->get_posted()); ?>" name="zip_code" />
										</div>
									</div>
									<span class="forget"><a href="http://www.buscacep.correios.com.br/sistemas/buscacep/" target="_blank">não sabe seu CEP?</a></span>
								</div>
								<div class="holder">
									<div class="frame-2">
										<div class="text">
											<input type="text" value="<?php echo trim($formx->use_field('address')->get_posted()); ?>" name="address" />
										</div>
									</div>
									<div class="text">
										<input type="text" value="<?php echo trim($formx->use_field('address_no')->get_posted());?>" name="address_no" />
									</div>
								</div>
								<div class="holder">
									<div class="frame-1">
										<div class="text">
											<input type="text" value="<?php echo trim($formx->use_field('complement')->get_posted()); ?>" name="complement" />
										</div>
									</div>
									<div class="text">
										<input type="text" value="<?php echo trim($formx->use_field('neighborhood')->get_posted()); ?>" name="neighborhood" />
									</div>
								</div>
								<div class="holder">
									<div class="frame-1">
										<div class="sel-hold">
											<select class="cs-3" title="Estado *" name="state">
												<?php 
                                                    foreach ($states as $k => $state) {
                                                        $selected = $k == $_state ? 'selected="selected"' : ''; 
                                                        echo sprintf('<option value="%s" %s>%s</option>', $k, $selected, $state);
                                                    }
    											?>
											</select>
										</div>
									</div>
									<div class="text">
										<input type="text" value="<?php echo trim($formx->use_field('city')->get_posted()); ?>" name="city"  />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /cols -->
				<div class="separator">
					<p>
                        <input type="checkbox" name="accept" value="1" <?php echo array_shift($formx->use_field('accept')->get_posted()) == 1 ? 'checked="checked"' : ''; ?>  />
						Estou de acordo com os <a href="<?php echo $base_url ?>home/termos" class="iframe-content">termos e condições de uso</a>
						do Quintal de Trocas e desejo criar minha conta.
					    <br />
                        <input type="checkbox" name="newsletter" value="1" <?php echo array_shift($formx->use_field('newsletter')->get_posted()) == 1 ? 'checked="checked"' : ''; ?>  />
                        Quero receber novidades.
					</p>
					<input class="btn-submit" type="submit" value="Criar nova conta" />
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- /reg-area -->