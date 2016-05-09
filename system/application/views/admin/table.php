<?php 

	if (isset($table))
	{
		
		if (count($table) > 0)
		{
			if (isset($delete_url) && $check_all)
			{
				$table_html = '<form action="'. $base_url . 'admin/' . $delete_url . '" method="post">';
			}else{
				$table_html = '';
			}
		
			$table_html.= '<table class="normal" cellpadding="0" cellspacing="0" border="0">';
			$check_all  = isset($check_all) ? $check_all : false;
			
				$header_html = '<thead><tr>';
			
				$hash_check_all = 'checkall-' . time();
				
				$header_html.= $check_all ? '<td><input type="checkbox" class="' . $hash_check_all . '" /></td>' : '';
				
				$headers = reset($table);
			
				if (isset($update_url) | isset($delete_url) && !isset($headers['a&ccedil;&otilde;es']))
				{
					$headers['a&ccedil;&otilde;es'] = '';
				}
				
				foreach ($headers as $header => $content)
				{
					$header_html.= '<td>' . $header . '</td>';
				}
				
				$header_html.= '</tr></thead>';
				
				$table_html.= $header_html;
				
				$table_html.= '<tbody>';
				
				$erro = false;
					
				foreach ($table as $line => $table_content)
				{
				
					if (!isset($table_content['id']))
					{
						$erro = true;
						break;
					}else{
						$id = $table_content['id'];
					}
					
					$class = (($line & 1) == 1) ? 'odd' : 'even';
					
					$table_html.= '<tr class="' . $class . '">';
					
						$table_html.= $check_all ? '<td><input type="checkbox" name="item_id[]" value="'. $id . '" /></td>' : '';
						
						foreach ($table_content as $body => $content)
						{
							$table_html.= '<td>' . $content . '</td>';
						}
						
						if (isset($headers['a&ccedil;&otilde;es']) && (isset($delete_url) | isset($update_url)) && @$update_url != '')
						{
						
							$table_html.= '<td>';
							
								if (isset($delete_url))
								{
									$table_html.= '
										<a title="" style="padding-right:0" onclick="return confirm(\'Confirma?\');" class="button tooltip" href="'. $base_url . 'admin/' . $delete_url . '/' . $id . '">
											<span class="ui-icon ui-icon-trash"></span>
										</a>
									';
								}
									
								if (isset($update_url))
								{
								
									$table_html.= '
										<a title="" style="padding-right:0" class="button tooltip" href="'. $base_url . 'admin/' . $update_url . '/' . $id . '">
											<span class="ui-icon ui-icon ui-icon-pencil"></span>
										</a>
									';
								}
							
							$table_html.= '</td>';
							
						}
						
					$table_html.= '</tr>';
					
				}
				
				$table_html.= '
						</tbody>
						
					</table>
				';
			
			if (isset($delete_url) | $check_all)
			{
			
				$table_html.= '<input class="button" type="submit" value="Apagar selecionados" onclick="return confirm(\'Confirma?\'); " />';
			
			}
				
			if (isset($delete_url) | $check_all)
			{
			
				$table_html.='</form>';
			
			}
			
			$table_html.= '<script type="text/javascript">$(function(){$(\'.' . $hash_check_all . '\').click(function(){$(this).parent().parent().parent().parent().find("input[type=\'checkbox\']").attr(\'checked\', $(this).is(\':checked\'));});})</script>';
			
			echo ($erro ? 'Erro na renderiza&ccedil;&atilde;o' : $table_html);
			
		}else{
			echo '<p>Nada encontrado</p>';
		}
		
	}else{
		echo '<p>Nada encontrado</p>';
	}

?>