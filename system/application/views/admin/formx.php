<form class="form-horizontal <?php echo $form_class; ?>" action="<?php echo $form_action; ?>" method="<?php echo $form_method; ?>" id="<?php echo $form_id; ?>" enctype="multipart/form-data">
	<fieldset>
	<?php
	
		if ($form_legend !== '') {
			
			echo '<legend>' . $form_legend . '</legend>';
		}
	
		$form_actions = '';
		
		foreach ($fields as $k => $field) {
			
			$field_type 			= $field->get_type();
			$field_name 			= $field->get_name();
			$field_help 			= $field->get_help();
			$field_label 			= $field->get_label();
			$field_tabindex 		= $field->get_tabindex();
			$field_value 			= $field->get_is_post() ? $field->get_post_value() : $field->get_value();
			$field_has_error 		= $field->get_has_error();
			$field_error_message 	= $field->get_error_message();
			$field_error_message 	= count($field_error_message) > 0 && $field_has_error ? '<span class="validate_error">' . implode('<br />', $field_error_message) . '</span>' : '';
			$field_html_class 		= $field->get_html_class();
			
			$field_placeholder  	= $field->get_placeholder();
			$field_placeholder 		= $field_placeholder !== '' ? 'placeholder="' . $field_placeholder . '"' : '';
			
			switch ($field_type) {
				
				case 'button':
					
					$form_actions.= '<input type="button" class="button ' . $field_html_class . '" value="' . $field_label . '" onclick="' . $field->get_onclick() . '">';
					
					break;
				/* ------------------------------------ */
					
				case 'checkbox':
					
				    echo '
				    <p>
    				    <label for="'. $field_name .'">' . $field_label . ':</label> ' . $field_error_message . '
    				    <div class="inpcol">
    				    ';
			            foreach ($field_value as $value => $option) {
                			echo '<input type="checkbox" name="' . $field_name . '[]" ' . ($option['selected'] ? 'checked="checked"' : '') . ' value="' . $option['field_value'] . '" class="' . $field_html_class . '">';
                			echo $option['field_label'];
                			echo '<br />';
                        }
    				    echo '
    				    </div>
				    </p>
				    ';
				    
					break;
				/* ------------------------------------ */
					
				case 'file':
    					
				    echo '
			             <p>
    			             <label for="'. $field_name .'">' . $field_label . ':</label>
				            <input type="file" class="'. $field_html_class .' input-1" name="'. $field_name .'" />
				        </p>
				    	' . $field_error_message . '
				        <br />
				    ';
				                		
					break;
				/* ------------------------------------ */
					
				case 'hidden':
   
					echo '<input type="hidden" name="' . $field_name . '" value="' . $field_value . '" id="' . $field_name . '" class="' . $field_html_class . '">';
					
					break;
				/* ------------------------------------ */
					
				case 'multiple':
					
					echo '
				    <p>
    				    <label for="'. $field_name .'">' . $field_label . ':</label>
    				    <br />
    				    ';
    				    
    				    echo '<select name="'. $field_name .'[]" class="'. $field_html_class .'" multiple="multiple" />';
    				    	
    				    echo '<option value="">Selecione</option>';
    				    	
    				    foreach ($field_value as $value => $option) {

    				        echo '<option value="' . $option['field_value'] . '" ' . ($option['selected'] ? 'selected="selected"' : '') . '>' . $option['field_label'] . '</option>';
    				    }
    				    	
    				    echo '
    				    </select>
                        ' . $field_error_message . '
				    </p>';
					
					break;
				/* ------------------------------------ */
					
				case 'password';

					echo '
    				    <p>
    				        <label for="'. $field_name .'">' . $field_label . ':</label>
    				        <input type="password" class="'. $field_html_class .'" name="'. $field_name .'" value="'.$field_value .'" />
    				        ' . $field_error_message . '
    				    </p>
				    ';
            		
					break;
				/* ------------------------------------ */
					
				case 'radio':
					
				    echo '
				    <p>
    				    <label for="'. $field_name .'">' . $field_label . ':</label> ' . $field_error_message . '
    				    <div class="inpcol">
    				    ';
    				    	
				        foreach ($field_value as $value => $option) {
    				        	
    				        echo '<input type="radio" name="' . $field_name . '" ' . ($option['selected'] ? 'checked="checked"' : '') . ' value="' . $option['field_value'] . '" class="' . $field_html_class . '">';
	                	    echo $option['field_label'];
    				        echo '<br />';
    				    }
    				    
    				    echo '
    				    </div>
				    </p>
				    ';
				    					
					break;
				/* ------------------------------------ */
					
				case 'reset_button':
					
					$form_actions.= '<button class="btn ' . $field_html_class . '" type="reset">' . $field_label . '</button>&nbsp;';
					
					break;
				/* ------------------------------------ */
					
				case 'select':
					
				    echo '
				    <p>
    				    <label for="'. $field_name .'">' . $field_label . ':</label>
    				    <br />
    				    ';
    				    
    				    echo '<select name="'. $field_name .'" class="'. $field_html_class .'" />';
    				    	
    				    echo '<option value="">Selecione</option>';
    				    	
    				    foreach ($field_value as $value => $option) {

    				        echo '<option value="' . $option['field_value'] . '" ' . ($option['selected'] ? 'selected="selected"' : '') . '>' . $option['field_label'] . '</option>';
    				    }
    				    	
    				    echo '
    				    </select>
                        ' . $field_error_message . '
				    </p>';
					
					break;
				/* ------------------------------------ */
					
				case 'submit_button':
					
				    $form_actions.= '<input type="submit" class="button ' . $field_html_class . '" value="' . $field_label . '" onclick="' . $field->get_onclick() . '">';
					
					
					break;
				/* ------------------------------------ */
					
				case 'text':
				    
				    echo '
    				    <p>
    				        <label for="'. $field_name .'">' . $field_label . ':</label>
    				        <input type="text" class="'. $field_html_class .'" name="'. $field_name .'" value="'.$field_value .'" />
    				        ' . $field_error_message . '
    				    </p>
				    ';
				    
					break;
				/* ------------------------------------ */
					
				case 'textarea':
					
				    echo '<p>' . $field_label . ':</p> ' . $field_error_message . '';
				    
				    $CI =& get_instance();
				    	
				    require_once(BASEPATH . 'plugins/fckeditor/fckeditor.php');
				    	
				    $fckeditor = new FCKeditor($field_name);
				    	
				    if ($fckeditor->IsCompatible()) {
				    
				        $fckeditor->Value 		= html_entity_decode(htmlentities($field_value));
				        $fckeditor->BasePath 	= $base_url . 'system/plugins/fckeditor/';
				        $fckeditor->ToolbarSet 	= 'Default';
				        $fckeditor->Width		= 800;
				        $fckeditor->Height		= 400;
				         
				        echo '<br />';
				         
				        echo $fckeditor->CreateHtml();
				         
				        echo '<br /><br />';
				         
				    } else {
				        echo '<textarea cols="80" rows="10" class="wysiwyg" name="'. $field_name .'">'. $field_value . '</textarea> ';
				    }
				    	
				    echo '<br />';
			
					break;
				
				default:
					
					break;
			}
		}
		
		echo '<div class="clear_fix"></div>';
		
		if ($form_actions !== ''){
		    echo $form_actions;
		}
	?>
	</fieldset>
</form>