
	<form action="<?php echo $base_url . 'admin/' .  $url ?>/" method="post" id="form-retrieve">
	
		<p>
			<input type="text" name="query" value="<?php echo $query; ?>" />
			<select name="filter_by">
				<option value="">Filtar por</option>
				<?php 
					foreach ($filters as $filter_name => $val)
					{
						$selected = $val['selected'] ? 'selected="selected"' : '';
						echo '<option value="' . $val['dbField'] . '" ' . $selected . '>' . $filter_name . '</option>';
					}
				?>
			</select>
			<input type="submit" value="Filtrar" class="button" />
			<br />
			<?php echo $total_real . $total_current; ?>
			<br />
			<br />
			<?php 
				if ($total_pages > 1)
				{
			?>
			<select name="page" onchange="$('#form-retrieve').submit();">
				<?php 
					for ($i=0; $i<$total_pages; $i++)
					{
						$v = ($i + 1);
						$selected = $current_page == $v ? 'selected="selected"' : '';
						echo '<option value="' . $v . '" ' . $selected . '>P&aacute;gina ' . $v . '</option>';
					}
				?>
			</select>
			<?php 
				}
			?>
		</p>
			
	</form>