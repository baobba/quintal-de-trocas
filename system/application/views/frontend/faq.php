<div class="page-title">
	<div class="in">
		<h1>Perguntas frequentes</h1>
		<ul class="breadcrumbs">
			<li><a href="<?php echo $base_url; ?>">Home</a></li>
			<li>Perguntas frequentes</li>
		</ul>
	</div>
</div>
<!-- /page-title -->
<article class="faq-area">
	<div class="block ie-fix">
		<div class="column">
			<h2>Clique na dúvida para ler a explicação</h2>
			<div id="accordion-1">
                <?php 
                    foreach ($faqs as $faq) {
                        echo '<div class="faq-item ie-fix">';
                            echo sprintf('<strong class="ttl">%s</strong>', $faq->question);
                            echo sprintf('<div class="entity">%s</div>', $faq->answer);
                        echo '</div>';
                    }
                ?>
			</div>
		</div>
		<aside>
			<h3>Ainda possui dúvidas?</h3>
			<p>Preencha nosso formulário e envie sua mensagem!</p>
			<a href="<?php echo $base_url . URL_CONTATO; ?>" class="btn-msg">Enviar mensagem!</a>
		</aside>
	</div>
</article>
<!-- /faq-area -->