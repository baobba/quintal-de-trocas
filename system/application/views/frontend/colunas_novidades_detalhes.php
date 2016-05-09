<div class="page-title">
	<div class="in">
		<h1>Colunas e Novidades</h1>
		<ul class="breadcrumbs">
			<li><a href="<?php echo $base_url; ?>">Home</a></li>
			<li>Colunas e Novidades</li>
		</ul>
	</div>
</div>
<!-- /page-title -->
<article class="main">
	<div class="content">
		<div class="post">
			<div class="heading">
				<div class="in">
					<a href="<?php echo $base_url . URL_COLUNAS_E_NOVIDADES?>" class="btn-back ie-fix">Voltar para a lista</a>
					<h2><?php echo $news->name; ?></h2>
					<ul class="meta">
						<li><?php echo date_create_from_format('Y-m-d H:i:s', $news->publicated_at)->format('d.m.Y'); ?></li>
						<li><?php echo $news->category_name; ?></li>
						<li>Por <?php echo $news->author_name; ?></li>
					</ul>
					<div class="fb-like" data-href="<?php echo $base_url . trim($_SERVER['REQUEST_URI'], '/'); ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
				</div>
			</div>
			<div class="entry">
				<?php echo $news->content; ?>
			</div>
			<div class="comments-area ie-fix">
				<div class="fb-comments" data-href="<?php echo $base_url . trim($_SERVER['REQUEST_URI'], '/'); ?>" data-width="810" data-numposts="10" data-colorscheme="light"></div>
			</div>
		</div>
	</div>
	<!-- /content -->
	<aside>
		<div class="subscribe-form">
			<form action="#">
				<fieldset>
					<h3>Newsletter</h3>
					<p>Receba nossas novidades por e-mail:</p>
					<div class="text">
						<input type="text" value="Digite seu nome" />
					</div>
					<div class="text">
						<input type="text" value="Digite seu e-mail" />
					</div>
					<input class="btn-submit" type="submit" value="Cadastrar" />
				</fieldset>
			</form>
		</div>
		<!--<div class="ad-box">
			<a href="#"><img src="<?php echo $base_url; ?>img/img-18.gif" alt="image description"></a>
		</div>
		<div class="ad-box">
			<a href="#"><img src="<?php echo $base_url; ?>img/img-18.gif" alt="image description"></a>
		</div>
		<div class="ad-box">
			<a href="#"><img src="<?php echo $base_url; ?>img/img-18.gif" alt="image description"></a>
		</div>-->
	</aside>
</article>
<!-- /main -->