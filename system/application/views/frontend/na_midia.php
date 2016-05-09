<div class="page-title">
	<div class="in">
		<h1>Na mídia</h1>
		<ul class="breadcrumbs">
			<li><a href="<?php echo $base_url; ?>">Home</a></li>
			<li>Na mídia</li>
		</ul>
	</div>
</div>
<!-- /page-title -->
<article class="media-area">
	<div class="filter-bar">
		<div class="in">
			<div class="form">
				<form action="<?php echo $base_url . URL_NA_MIDIA; ?>" method="post">
					<fieldset>
						<label for="lbl-01">Selecione a categoria:</label>
						  <select id="lbl-01" class="cs-2" name="catId">
							<option value="">Selecione uma Categoria</option>
                                <?php 
                                    foreach ($categories as $catId => $catName) {
                                        $selected = $_catId == $catId ? 'selected="selected"' : ''; 
                                        echo sprintf('<option value="%s" %s>%s</option>', $catId, $selected, $catName);
                                    }
                                ?>
						</select>
						<label for="lbl-02">Filtre por ano:</label>
                        <select id="lbl-02" class="cs-2 sel-2" name="year">
                            <?php 
                                foreach ($years as $year) {
                                    $selected = $_year == $year ? 'selected="selected"' : ''; 
                                    echo sprintf('<option value="%s" %s>%s</option>', $year, $selected, $year);
                                }
                            ?>
						</select>
						<input type="submit" value="Filtrar" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<ul class="media-list">
        <?php 
	       foreach ($press->result() as $_press) {
        ?>
            <li>        
                <?php echo sprintf('<div class="img"><img width="171" height="227" src="%s" alt="%s"></div>', $base_url . URL_UPLOAD_IMAGE . $_press->cover_image, $_press->name); ?>
                <div class="descr">
    				<ul class="meta">
    					<li><?php echo date_create_from_format('Y-m-d H:i:s', $_press->publicated_at)->format('d.m.Y'); ?></li>
    					<li><?php echo $_press->category_name; ?></li>
    				</ul>
    				<strong class="name">
    				    <?php 
    				        if (trim($_press->url) !== '') {
                        ?>
    				        <a href="<?php echo $_press->url;?>" target="_blank"><?php echo $_press->name; ?></a>
				        <?php 
                            } else {
				                echo $_press->name;
				            }
				        ?>
			        </strong>
    				
                    <a href="<?php echo $base_url . URL_UPLOAD_IMAGE . $_press->cover_image; ?>" title="<?php echo $_press->name; ?>" class="more">Ampliar</a>
                </div>
            <li>
        <?php } ?>
	</ul>
</article>
<!-- /media-area -->