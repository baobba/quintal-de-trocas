<?php 

	$hash_dialog = 'dialog-'.uniqid(time());
	
	if ($config['auto_open'] == false)
	{
		$hash_dialog_opener = 'dialog-'.uniqid(time());
		
		echo '
			<p>
				<a href="#" class="button" title="' . $opener['title'] . '" id="' . $hash_dialog_opener . '">
					<span class="ui-icon ui-icon-newwin"></span>
					' . $opener['content'] . '
				</a>
			</p>';
		
	}
	
	echo '
		<div id="' . $hash_dialog . '" title="' . $content['title'] . '">
			' . $content['content'] . '
		</div>
		';
	
	echo '
		<script>
		
			$(function(){
		';
	
			echo '$(\'#' . $hash_dialog . '\').dialog({';	
	
					$total_config = count($config);
					$config_count = 1;
			
					foreach($config as $key => $config_item)
					{
						
						switch (true)
						{
							case is_int($config_item): 
								break;
							case is_bool($config_item):
								$config_item = $config_item == true ? 'true' : 'false';
								break;
							default:
								$config_item = '\''.$config_item.'\'';
								break;
						}
						
						if ($config_count == $total_config)
						{
							echo $key . ':' .  $config_item;
						}else{
							echo $key . ':' .  $config_item . ',';
						}
						
						$config_count++;
					}
			
			echo '})';
			
			if ($config['auto_open'] == false)
			{
				echo '
					$(\'#' . $hash_dialog_opener . '\').click(function(){
						$(\'#' . $hash_dialog . '\').dialog(\'open\');
						return false;
					});
					';
			}
			
	echo '	
			});
		</script>
		';
			
	
?>