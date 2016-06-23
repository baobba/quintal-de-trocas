<!-- script>
        $(document).ready(function(){
                $.colorbox({inline:true, width:"670px", href:"#inline_content"});
        });
</script -->
<!-- div style='display:none'>
        <div id='inline_content' style='background:#FF8C00;'>
        <table WIDTH=624 BORDER=0 CELLPADDING=0 CELLSPACING=0>
                <TR>
                        <TD COLSPAN=3>
                                <IMG SRC="< ?php echo $base_url; ?>css/imagens/pop-up_01.gif" WIDTH=624 HEIGHT=278></TD>
                </TR>
                <TR>
                        <TD ROWSPAN=2>
                                <IMG SRC="< ?php echo $base_url; ?>css/imagens/pop-up_02.gif" WIDTH=25 HEIGHT=62></TD>
                        <TD>
                                <A HREF="https://beta.benfeitoria.com/quintaldetrocas" TARGET="_blank">
                                        <IMG SRC="< ?php echo $base_url; ?>css/imagens/pop-up_03.gif" WIDTH=488 HEIGHT=32 BORDER=0></A></TD>
                        <TD ROWSPAN=2>
                                <IMG SRC="< ?php echo $base_url; ?>css/imagens/pop-up_04.gif" WIDTH=111 HEIGHT=62></TD>
                </TR>
                <TR>
                        <TD>
                                <IMG SRC="< ?php echo $base_url; ?>css/imagens/pop-up_05.gif" WIDTH=488 HEIGHT=30></TD>
                </TR>
        </table>
        </div>
</div -->
</div><!-- class 'w1' -->

<div id="home-destaque">
	<div class="w1">
		<h2>Por um mundo com mais trocas e conexões</h2>
		<p class="sub-title">Um brinquedo que circula é uma brincadeira que continua</p>
		<a href="<?php echo $base_url . URL_COMO_FUNCIONA;?>" class="btn-comoFunciona">Como funciona?</a>
	</div>
	<div class="w1">
		<div class="bar-busca">
			<p class="info">Encontre um brinquedo <span>perto de você!</span></p>
			<form action="<?php echo $base_url ?>" method="post">
				<input type="hidden" name="toy_name" value="<?php echo $toyName; ?>" />
				<fieldset>
					<select name="toy_state[]" onchange="this.form.submit()">
						<option value="">Escolha o seu estado</option>
						<?php 
							foreach ($toyStates as $toyStateId => $toyState) {
								$selected = in_array($toyStateId, $toyStateSelected) ? 'selected="selected"' : '';
								echo sprintf('<option value="%s" %s>%s</option>', $toyStateId , $selected, $toyState); 
							}
							?>
					</select>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<div class="clear"></div>
<div id="busca">
	<div class="area-input">
		<form name="form-busca" method="post" action="<?php echo $base_url?>">
			<input name="toy_name" type="text" placeholder="O que você quer trocar?" value="<?php echo isset($toyName) ? $toyName : 'O que você quer trocar?'; ?>">
			<select title="Selecione uma categoria" name="toy_category[]">
				<option value="">Selecione</option>
				<?php 
					foreach (CmsToyCategory::getCombo() as $id => $category) {
						echo sprintf('<option value="%s">%s</option>', $id, $category);        
					}    
				?>
			</select>
			<button type="submit" class="btn-submit">Buscar</button>
		</form>
		<div class="clear"></div>
	</div>
</div>

	<!--header>
		<div class="search-form">
			<form action="<'?php echo $base_url?>" method="post">
				<fieldset>
					<div class="text">
						<input type="text" value="<'?php echo isset($toyName) ? $toyName : 'O que você quer trocar?'; ?>" name="toy_name" placeholder="O que deseja trocar?"  />
					</div>
					<div>
						<select title="Selecione uma categoria" name="toy_category[]">
						    <option value="">Selecione</option>
		                    <'?php 
		                        foreach (CmsToyCategory::getCombo() as $id => $category) {
		                            echo sprintf('<option value="%s">%s</option>', $id, $category);        
		                        }    
		                    ?>
						</select>
						<input class="btn-submit" type="submit" value="Buscar" />
					</div>
				</fieldset>
			</form>
		</div>
	</header-->
</div>

<div class="w1">
<article class="main main-inverse">
	<div class="content">
		<ul class="item-list">
            <?php
                echo $toyList;
            ?>
		</ul>
		
		<script>
		    $(function(){
		        $('.item-list').jscroll({nextSelector:'a.next'});
            })
		</script>
		<?php 
            if ($toys->num_rows == 0) {
                echo '<h4>Nada encontrado</h4>';
            }
        ?>
	</div><!-- /content -->
	<aside>
		<div class="filter-list">
			<form action="<?php echo $base_url ?>" method="post">
                <input type="hidden" name="toy_name" value="<?php echo $toyName; ?>" />
				<fieldset>
					<h3>Filtros:</h3>
					
					<h4>Por estado</h4>
					<select name="toy_state[]" onchange="this.form.submit()">
					   <option value="">Selecione</option>
                        <?php 
                            foreach ($toyStates as $toyStateId => $toyState) {
                                $selected = in_array($toyStateId, $toyStateSelected) ? 'selected="selected"' : '';
                                echo sprintf('<option value="%s" %s>%s</option>', $toyStateId , $selected, $toyState); 
                            }
                        ?>
					</select>
					
					<h4>Por cidade</h4>
					
				    <select name="toy_city[]" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php 
                            foreach ($toyCities as $toyCityId => $toyCity) {
                                $selected = in_array($toyCityId, $toyCitySelected) ? 'selected="selected"' : '';
                                echo sprintf('<option value="%s" %s>%s</option>', $toyCityId, $selected, $toyCity);
                            }
                        ?>
					</select>
					
					<h4>Por idade</h4>
					<ul class="list">
                        <?php 
                            foreach ($toyAges as $toyAgeId => $toyAge) {
                                $selected = in_array($toyAgeId, $toyAgeSelected) ? 'checked="checked"' : '';
                                echo sprintf('
                                    <li>
                                        <input id="lbl-2%d" name="toy_age[]" class="chk" type="checkbox" value="%d" %s onchange="this.form.submit()"/>
							            <label for="lbl-2%d">%s</label>
					                </li>', $toyAgeId, $toyAgeId, $selected, $toyAgeId, $toyAge);
                            }
                        ?>
					</ul>
					
					<h4>Por tipo de brinquedo</h4>
					<ul class="list">
                        <?php 
                            foreach ($toyCategories as $toyCategoryId => $toyCategory) {
                                $selected = in_array($toyCategoryId, $toyCategorySelected) ? 'checked="checked"' : '';
                                echo sprintf('
                                    <li>
                                        <input id="lbl-3%d" name="toy_category[]" class="chk" type="checkbox" value="%d" %s onchange="this.form.submit()"/>
							            <label for="lbl-3%d">%s</label>
					                </li>', $toyCategoryId, $toyCategoryId, $selected, $toyCategoryId, $toyCategory);
                            }
                        ?>
					</ul>

					<h4>Por marca</h4>
					<ul class="list">
                        <?php 
                            foreach ($toyBrands as $toyBrandId => $toyBrand) {
                                $selected = in_array($toyBrandId, $toyBrandSelected) ? 'checked="checked"' : '';
                                echo sprintf('
                                    <li>
                                        <input id="lbl-4%d" name="toy_brand[]" class="chk" type="checkbox" value="%d" %s onchange="this.form.submit()"/>
							            <label for="lbl-4%d">%s</label>
					                </li>', $toyBrandId, $toyBrandId, $selected, $toyBrandId, $toyBrand);
                            }
                        ?>
					</ul>
				</fieldset>
			</form>
		</div>
	</aside>
</article><!-- /main -->
</div>

<div class="w1">