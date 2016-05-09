<div class="page-title page-title2">
	<div class="in">
		<h1>Quero trocar</h1>
		<ul class="breadcrumbs">
			<li><a href="<?php echo $base_url; ?>">Home</a></li>
			<li>Quero trocar</li>
		</ul>
	</div>
</div><!-- /page-title -->
<article class="product-area">
	<div class="product-info">
		<div class="col-r">
			<div class="col-in">
				<div class="heading">
					<div class="in">
						<h2 class="tbl">
							<span class="vert-center"><?php echo $toy->name; ?></span>
						</h2>
						<!-- <div class="count">
							<strong class="num ie-fix">1</strong>
							<span class="unit">QUANTIDADE</span>
						</div> -->
					</div>
				</div>
				<p>Descrição: <?php echo $toy->description; ?></p>
				<div class="details">
					<dl>
						<dt>Marca:</dt>
						<dd><?php echo $toy->brand; ?></dd>
					</dl>
					<dl>
						<dt>Categoria:</dt>
						<dd><?php echo $toy->category; ?></dd>
					</dl>
					<dl>
						<dt>Faixa Etária:</dt>
						<dd><?php echo $toy->age; ?></dd>
					</dl>
				</div>
				<div class="details">
					<dl>
						<dt>Estado:</dt>
						<dd><?php echo $toy->state; ?></dd>
					</dl>
					<dl>
						<dt>Cidade:</dt>
						<dd><?php echo $toy->city; ?></dd>
					</dl>
					<dl>
						<dt>Bairro:</dt>
						<dd><?php echo $toy->neighborhood; ?></dd>
					</dl>
				</div>
				<div class="details">
				    <dl>
						<dt>Classificação do Usuário:</dt>
						<dd><?php echo $reputation; ?></dd>
					</dl>
				</div>
				
				<?php 
    				$toyAgeInterest = trim($toy->age_interest);
    				$toyAgeInterest = strlen($toyAgeInterest) ? json_decode($toyAgeInterest, 1) : array();
    				 
    				$toyCategoryInterest = trim($toy->category_interest);
    				$toyCategoryInterest = strlen($toyCategoryInterest) ? json_decode($toyCategoryInterest, 1) : array();
    				 
    				$toyBrandInterest = trim($toy->brand_interest);
    				$toyBrandInterest = strlen($toyBrandInterest) ? json_decode($toyBrandInterest, 1) : array();
    				
    				if (count($toyAgeInterest) | count($toyCategoryInterest) | count($toyBrandInterest)) {
                        echo '
                            <div class="details">
    			                <dl>
                                    <dt>Interesses:</dt>
                                </dl>
						        <br />
						        <br />
						        ';
                        
                        if (count($toyBrandInterest)) {
                            echo '
                                <dl>
                                    <dt>Marca do Brinquedo:</dt>
                                    <dd>';
                            
                            echo implode(', ', $toyBrandInterest);
                                                        
                            echo '
                                    </dd>
				                </dl>
    				            <br />
    				        ';
                        }
                        
                        if (count($toyAgeInterest)) {
                            echo '
                                <dl>
                                    <dt>Faixa Etária:</dt>
                                    <dd>';
                        
                            echo implode(', ', $toyAgeInterest);
                        
                            echo '
                                    </dd>
				                </dl>
    				            <br />
    				        ';
                            
                        }
                        
                        if (count($toyCategoryInterest)) {
                            echo '
                                <dl>
                                    <dt>Tipo de Brinquedo:</dt>
                                    <dd>';
                        
                            echo implode(', ', $toyCategoryInterest);
                        
                            echo '
                                    </dd>
				                </dl>
    				            <br />
    				        ';
                        }
						        
					    echo '
				            </div>';
						        
                    }
				?>
				
				
				<?php 
				    if ($isLogged) {
                        if ($isFromUser === false) {
                            /*if ($isExchanging) {
                                echo '<script>$(function(){$(\'.open-popup\').click();} );</script>';
                            }*/
 
                            echo '<a href="#" class="btn open-exchange" data-target="trocar">Quero trocar!</a>';
                        }
                        
                    } else {
                        echo sprintf('<a href="%s" class="btn">Quero trocar!</a>', $base_url . URL_USUARIO_LOGIN);
                    }
				?>
				
			</div>

		</div>
		<div class="col-l">
			<div class="col-in">
				<div class="gallery" id="gallery-1">
					<div class="flexslider">
						<ul class="slides">
							<li>
								<div class="slide">
								    <img src="<?php echo $base_url . URL_UPLOAD_IMAGE . $toy->image; ?>" alt="<?php echo $toy->name; ?>">
								</div>
							</li>
							<?php 
                                foreach ($images as $image) {
                                    echo '
                                        <li>
                                    	   <div class="slide">
                                    	        <img src="' . $image . '" alt="'. $toy->name . '">
                                    	   </div>
                                        </li>';
                                }
							?>
						</ul>
					</div>
					<?php 
                        echo '<ul class="thumbs-list">';
                        echo '
                            <li>
                                <a class="ie-fix" href="#">
                                    <span class="tbl">
                                        <span class="tbl-cell">
                                            <img src="' . $base_url . URL_UPLOAD_IMAGE . $toy->image . '" width="96">
                                        </span>
                                    </span>
                                </a>
                            </li>';

                        if (count($images)) {
                            foreach ($images as $image) {
                                echo '
                                    <li>
                                        <a class="ie-fix" href="#">
                                            <span class="tbl">
                                                <span class="tbl-cell">
                                                    <img src="' . $image . '" width="96">
                                                </span>
                                            </span>
                                        </a>
                                    </li>';
                            }
                            
                        }
                        echo '</ul>';
					?>
				</div>
			</div>
		</div>
		
	</div>
	<?php echo $productExchangeView; ?>
</article><!-- /product-area -->