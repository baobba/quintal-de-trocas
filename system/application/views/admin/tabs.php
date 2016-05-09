<?php $hash = 'tabs-' . uniqid(time()); ?>

<div id="<?php echo $hash; ?>"><!-- #<?php echo $hash; ?> -->

	<?php 
	
		$tabContentHTML = '';
		$tabHeaderHTML 	= '<ul>';
	
		$sum = 0;
	
		$selected = 0;
		
		foreach($tabs as $tabKey => $tabContent)
		{
			
			if ($tabContent['selected'])
			{
				
				$selected = $sum;
				
			}
			
			$tabHeaderHTML.= '<li><a href="#' . $hash . '-' . $sum . '" title="'. $tabContent['title'] . '">'. $tabContent['title'] . '</a></li>';
			
			$tabContentHTML.= '<div id="' . $hash . '-' . $sum . '">'. $tabContent['content'] . '</div>';
			
			$sum++;
			
		}
		
		$tabHeaderHTML.= '</ul>';
	
		echo $tabHeaderHTML;
		echo $tabContentHTML;
		
	?>
										
</div><!-- /#<?php echo $hash; ?> -->

<script type="text/javascript">$(function(){$('#<?php echo $hash; ?>').tabs({selected:<?php echo $selected; ?>});})</script>