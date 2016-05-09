<div id="navbar">
	<ul class="nav">
		<?php 
			foreach($menu as $key => $item_menu)
			{
				echo '<li><a href="'. $item_menu['link'] . '">' . $item_menu['menu'] . '</a>';
				if (isset($item_menu['submenu']))
				{
					echo '<ul>';
						foreach($item_menu['submenu'] as $key2 => $item_submenu)
						{
							echo '<li><a href="'. $item_submenu['link'] . '">' . $item_submenu['menu'] . '</a>';
							echo '</li>';
						}
					echo '</ul>';
				}
				echo '</li>';
			}
		?>
	</ul>
</div>