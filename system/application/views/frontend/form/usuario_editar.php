<form action="<?php echo $base_url . URL_USUARIO_MEUS_DADOS; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="<?php echo $formxUserData->get_form_name(); ?>" value="<?php echo $formxUserData->use_field($formxUserData->get_form_name())->get_value(); ?>" />
<fieldset>
	<div class="cols">
		<div class="col">
			<div class="col-in">
				<h3>Dados pessoais</h3>
				<div class="text">
					<input type="text" placeholder="Nome" value="<?php echo trim($formxUserData->use_field('name')->get_posted()); ?>" name="name" />
				</div>
				<div class="holder">
					<div class="frame-1">
						<div class="text">
							<input type="text" value="<?php echo trim($formxUserData->use_field('birth_date')->get_posted());; ?>" name="birth_date" placeholder="dd/mm/yyyy" onkeyup="var v = this.value;if (v.match(/^\d{2}$/) !== null) { this.value = v + '/'; } else if (v.match(/^\d{2}\/\d{2}$/) !== null) { this.value = v + '/'; }" maxlength="10" />
						</div>
					</div>
					<div class="sel-hold">
                        <?php 
					        $value = trim($formxUserData->use_field('gender')->get_posted());
					    ?>
						<select class="cs-3" title="<?php echo $value == 'm' ? 'Masculino' : 'Feminino'; ?>" name="gender">
							<option value="m" <?php echo $value == 'm' ? 'selected="selected"' : ''; ?>>Masculino</option>
							<option value="f" <?php echo $value == 'f' ? 'selected="selected"' : ''; ?>>Feminino</option>
						</select>
					</div>
				</div>
				<div class="holder">
				    <!-- 
					<div class="frame-1">
						<div class="text">
							<input type="text" name="cpf" value="<?php echo '';#trim($formxUserData->use_field('cpf')->get_posted()); ?>" />
						</div>
					</div>
					 -->
					<div class="text">
						<input type="text" name="phone" placeholder="Telefone" value="<?php echo trim($formxUserData->use_field('phone')->get_posted()); ?>" />
					</div>
				</div>
			</div>
                    <div class="col-in">
                        <div class="frame-1">
                            <div class="">
                                <div class="file-block">
                                    <input type="file" name="avatar" id="avatar" style="display:none">
                                    <button  id="browse" class="btn-submit">Trocar foto</button>
                                </div>
                            </div>
                        </div>
                    </div>
		</div>
		<div class="col">
			<div class="col-in">
				<h3>Endereço</h3>
				<div class="holder">
					<div class="frame-1">
						<div class="text">
							<input type="text" placeholder="CEP" value="<?php echo trim($formxUserData->use_field('zip_code')->get_posted()); ?>" name="zip_code" />
						</div>
					</div>
					<span class="forget"><a href="http://www.correios.com.br/servicos/cep/cep_loc_log.cfm" target="_blank">não sabe seu CEP?</a></span>
				</div>
				<div class="holder">
					<div class="frame-2">
						<div class="text">
							<input type="text" placeholder="Endereço" value="<?php echo trim($formxUserData->use_field('address')->get_posted()); ?>" name="address" />
						</div>
					</div>
					<div class="text">
						<input type="text" placeholder="N°" value="<?php echo trim($formxUserData->use_field('address_no')->get_posted());?>" name="address_no" />
					</div>
				</div>
				<div class="holder">
					<div class="frame-1">
						<div class="text">
							<input type="text" placeholder="Complemento" value="<?php echo trim($formxUserData->use_field('complement')->get_posted()); ?>" name="complement" />
						</div>
					</div>
					<div class="text">
						<input type="text" placeholder="Bairro" value="<?php echo trim($formxUserData->use_field('neighborhood')->get_posted()); ?>" name="neighborhood" />
					</div>
				</div>
				<div class="holder">
					<div class="frame-1">
						<div class="sel-hold">
							<select class="cs-3" title="<?php echo isset($states[$_state]) ? $states[$_state] : ''; ?>" name="state">
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
						<input type="text" placeholder="Cidade" value="<?php echo trim($formxUserData->use_field('city')->get_posted()); ?>" name="city"  />
					</div>
				</div>
			</div>
		</div>
		</div>
		<input class="btn-submit" type="submit" value="Salvar alterações" />
	</fieldset>
</form>