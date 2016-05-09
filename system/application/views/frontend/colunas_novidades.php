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
		<div class="filter-form">
			<div class="in">
				<form action="<?php echo $base_url . URL_COLUNAS_E_NOVIDADES ; ?>" method="post">
					<fieldset>
						<label for="lbl-01">Filtrar a lista:</label>
						<div class="sep">
							<select id="lbl-01" class="cs-2" title="Selecione uma Categoria" name="cat">
							    <option value="">Selecione uma Categoria</option>
                                <?php 
                                    foreach ($categories as $catId => $catName) {
                                        $selected = $_catId == $catId ? 'selected="selected"' : ''; 
                                        echo sprintf('<option value="%s" %s>%s</option>', $catId, $selected, $catName);
                                    }
                                ?>
							</select>
						</div>
						<div class="sep">
							<select class="cs-2" title="Selecione um Autor" name="author">
                                <option value="">Selecione um Autor</option>
								<?php 
                                    foreach ($authors as $authorId => $authorName) {
                                        $selected = $_catId == $authorId ? 'selected="selected"' : '';
                                        echo sprintf('<option value="%s" %s>%s</option>', $authorId, $selected, $authorName);
                                    }
                                ?>
							</select>
						</div>
						<input type="submit" value="Filtrar" />
					</fieldset>
				</form>
			</div>
		</div>
		<?php 
            foreach ($news->result() as $_news) {
        ?>
                <div class="post-preview">
                    <div class="img">
                        <a href="<?php echo $base_url . URL_COLUNAS_E_NOVIDADES_DETALHE . $_news->friendly_url; ?>">
                        <?php echo sprintf('<img class="ie-fix" src="%s" alt="image description"><span class="mask">&nbsp;</span>', $base_url . URL_UPLOAD_IMAGE . $_news->cover_image); ?></a>
                    </div>
                    <div class="descr">
				        <ul class="meta">
					       <li><?php echo date_create_from_format('Y-m-d H:i:s', $_news->publicated_at)->format('d.m.Y'); ?></li>
					       <li><?php echo $_news->category_name; ?></li>
					       <li>Por <?php echo $_news->author_name; ?></li>
				        </ul>
				<h2>
					<a href="<?php echo $base_url . URL_COLUNAS_E_NOVIDADES_DETALHE . $_news->friendly_url; ?>"><?php echo $_news->name; ?></a>
				</h2>
				<p><?php echo truncate($_news->content, 350); ?></p>
				<a href="<?php echo $base_url . URL_COLUNAS_E_NOVIDADES_DETALHE . $_news->friendly_url; ?>" class="more">+ Leia a matéria</a>
			</div>
			</div>
        <?php
            }
		?>

		<ul class="paging">
            <?php 
                $pages = $pagination->generate();
               
                foreach ($pages as $k => $item) {
                    if ($k == 'current' | $item == '') {
       					continue;
       				}

       				if ($k == 'pages') {
       					$only = count($item) == 1 ? 'only' : '';

       					foreach ($item as $page) {
 
       						$active = $page == $pages['current'] ? 'active' : '';
       						
       						echo sprintf('<li class="%s"><a href="%s" class="link ie-fix">%s</a></li>',$active,  $pagination->get_url($page), $page);
       					}

       				} elseif ($k == 'prev') {
                        echo sprintf('<li class="prev"><a href="%s">Anterior</a></li>', $pagination->get_url($item));

       				} elseif ($k == 'next') {
                        echo sprintf('<li class="next"><a href="%s">Pr&oacute;ximo</a></li>', $pagination->get_url($item));
                    }
                }
            ?>
		</ul>
	</div>
	<!-- /content -->
	<aside>
		<?php echo $newsletter; ?>
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