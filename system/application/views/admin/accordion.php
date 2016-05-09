<?php $hash = 'accordion-' . uniqid(time()); ?>

<div id="<?php echo $hash; ?>"><!-- #<?php echo $hash; ?> -->

	<?php 
	
		$accortion_content_html = '';
		
		foreach($accordion as $k => $accordion_content)
		{
			
			$accortion_content_html.= '
				<div>
					<h3>
						<a href="#" title="' . $accordion_content['title'] .'">' . $accordion_content['title'] .'</a>
					</h3>
					<div>' . $accordion_content['content'] . '</div> 
				</div>';
			
		}
		
		echo $accortion_content_html;
		
	?>
										
</div><!-- /#<?php echo $hash; ?> -->

<script type="text/javascript">$(function(){$("#<?php echo $hash; ?>").accordion({header:"h3",collapsible:true});})</script>