<div class="page-title">
	<div class="in">
		<h1>Login</h1>
		<ul class="breadcrumbs">
			<li><a href="<?php echo $base_url; ?>">Home</a></li>
			<li>Login</li>
		</ul>
	</div>
</div>
<!-- /page-title -->
<div class="login-section">
	<div class="block ie-fix">
		<div class="cols">
			<div class="cols-in">
				<div class="col">
					<div class="in">
						<h2>Faça seu login</h2>
						<p>Digite seus dados abaixo para entrar</p>
						<?php echo $message; ?>
						<div class="login-form">
							<form action="<?php echo $base_url . URL_USUARIO_LOGIN; ?>" method="post">
								<fieldset>
									<div class="text email">
										<input type="text" value="<?php echo $email; ?>" name="email" />
									</div>
									<div class="text passwd">
										<input type="password" value="Digite sua senha" name="password" />
									</div>
									<div class="btn-holder">
										<input class="btn-submit" type="submit" value="Entrar" />
										<a href="<?php echo $base_url . URL_USUARIO_RECUPERAR_SENHA; ?>">Esqueci minha senha &nbsp;&raquo;</a>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="in">
						<h2>Bem-vindo</h2>
						<br />
						<a href="https://www.facebook.com/dialog/oauth?client_id=631147513594252&redirect_uri=<?php echo $base_url; ?>usuario/facebook/&scope=email,user_birthday"><img src="http://www.quintaldetrocas.com.br/img/btn-fb.png" alt="Entrar com Facebook"></a>
					</div>
				</div>
				<div class="col">
					<div class="in">
						<h2>Primeira troca?</h2>
						<div class="hold">
							<p>Crie sua conta para desfrutar de todas as vantagens do Quintal
								de Trocas</p>
						</div>
						<a href="<?php echo $base_url . URL_USUARIO_CRIAR_CONTA; ?>" class="btn-reg">Criar nova conta</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /login-section -->