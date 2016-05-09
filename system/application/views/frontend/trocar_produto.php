<?php 
    if ($isLogged === false) { 
        return;
    }
    if ( isset($errorMessage) && strlen($errorMessage) != 0 ) {
        echo "<script>$(function() { $('.trocar').show(); });</script>";
    }
?>
<div class="trocar">
    	<div class="head">
    		<h3>Qual dos seus brinquedos você deseja trocar?</h3>
    		
    		<?php echo $errorMessage; ?>
    	</div>
    	<form action="<?php echo $base_url . URL_PRODUTOS_DETALHE . $toyId; ?>" method="post" id="exchange-form">
            <input type="hidden" value="1" name="exchange" />
        	<div class="carousel" id="carousel-1">
        		<div class="wrap">
                    <?php 
                        if (count($toys) == 0) {
                            echo '<h2>Nenhum brinquedo cadastrado.</h2>';
                    
                        } else {
                            echo '<ul class="list">';
                            
                            foreach ($toys as $toy) {
                                echo '<li>';
                                
                                    echo sprintf('<a href="#" onclick="$(\'.img-border\').removeClass(\'bordered\');$(\'#toy%s\').attr(\'checked\', true);$(this).find(\'.img-border\').addClass(\'bordered\');return false;">', $toy->id);
                                        
                                        echo sprintf('
                                            <span class="img">
                                                <span class="tbl-cell">
                                                    <img src="%s" alt="%s" width="114" class="img-border">
                                                </span>
                                            </span>', $base_url . URL_UPLOAD_IMAGE . $toy->image, truncate($toy->name, 20)); 
                                    
                                        echo sprintf('<span class="name">%s</span>', $toy->name);
                                    echo sprintf('<input type="radio" id="toy%s" name="product" value="%s" %s/>', $toy->id, $toy->id, $toy->id == $selectedProduct ? 'checked="checked"' : '');
                                    echo '</a>';
                                
                                echo '</li>';
                            }
                            
                            echo '</ul>';
                            
                            echo '<a href="#" class="next">next</a>';
        		            echo '<a href="#" class="prev">prev</a>';
                        }
                    ?>
        		</div>
        		
        	</div>
    		<fieldset>
    			<div class="head">
    				<h3>Como gostaria de efetuar a troca?</h3>
    			</div>
    			<ul class="radio-list">
    				<li>
    					<input id="lbl-01" class="radio" type="radio" name="exchange_type" value="0" <?php echo $exchangeType == CmsExchange::TYPE_PONTO_TROCA ? 'checked="checked"' : ''; ?>/>
    					<label for="lbl-01">Pontos de Troca</label>
    				</li>
    				<li>
    					<input id="lbl-02" class="radio" type="radio" name="exchange_type" value="1" <?php echo $exchangeType == CmsExchange::TYPE_PONTO_CORREIOS ? 'checked="checked"' : ''; ?>/>
    					<label for="lbl-02">Quero trocar pelo Correio</label>
    				</li>
    			</ul>
    			<div class="head">
    				<h3>Enviar mensagem ao dono do brinquedo</h3>
    			</div>
    			<div class="textarea">
                    <textarea cols="30" rows="10" style="resize:none;" name="message" placeholder="<?php echo $message; ?>" required></textarea>
                </div>
    				<input class="btn-submit" type="submit" value="Trocar!" />
                    <a href="#" class="cancelar">cancelar</a>
    			</fieldset>
    		</form>
</div>