<?php $CI =& get_instance(); ?>

<!DOCTYPE html>

<html>

	<head>

		<title><?php echo BACKEND_TITLE; ?> <?php echo $subtitle; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" href="<?php echo $base_url; ?>css/admin/layout.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo $base_url; ?>css/admin/jquery.twitter.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo $base_url; ?>css/admin/jquery.autocomplete.css" rel="stylesheet" />
		
		<?php 
			$load_css = is_array($load_css) ? $load_css : array($load_css);
			foreach ($load_css as $key => $css_file)
			{
				echo '<link type="text/css" href="' . $base_url . 'css/admin/' . $css_file . '.css" rel="stylesheet" />';
			}	
		?>
		
		<script type="text/javascript">base_url = '<?php echo $base_url; ?>';</script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/easyTooltip.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery-ui-1.7.2.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery.wysiwyg.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/hoverIntent.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/superfish.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery.twitter.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery.maskedinput-1.2.2.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery.maskedmoney.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/custom.js"></script>
		
		<?php 
			$load_js = is_array($load_js) ? $load_js : array($load_js);
			foreach ($load_js as $key => $js_file)
			{
				echo '<script type="text/javascript" src="' . $base_url . 'js/admin/' . $js_file . '.js"></script>';
			}	
		?>
		
		<!--[if IE 6]>
			<link type="text/css" href="<?php echo $base_url; ?>css/admin/ie6_width_1600.css" rel="stylesheet" />
			<link type="text/css" href="<?php echo $base_url; ?>css/admin/ie6.css" rel="stylesheet" />
			<script src="<?php echo $base_url; ?>js/DD_belatedPNG.js"></script>
			<script>
				$(function(){
					DD_belatedPNG.fix('.logo img, #search, ul.dash li img, .message');
				});
			</script>	
		<![endif]-->
		
	</head>
	
	<body>
		
		<div id="container"><!-- #container -->
			
			<div id="header"><!-- #header -->
				
				<div id="top">
					<div class="logo"> 
						<a href="<?php echo $base_url; ?>admin/home" title="Home" class="tooltip"><img src="<?php echo $base_url; ?>img/admin/logo.png" style="margin-top:12px" /></a> 
					</div>
				
					<div class="meta">
						<?php 
							$h = date('H');
							$welcome = ($h < 12 && $h >= 6) ? 'Bom dia' : (($h >= 12 && $h <= 18) ? 'Boa tarde' : 'Boa noite');
							$CI->load->model('admin_user');
						?>
						<p><?php echo $welcome; ?> <?php echo @$CI->admin_user->getUser()->name; ?>!</p>
						<ul>
							<li><a href="<?php echo $base_url; ?>admin/log/out" title="" class="tooltip"><span class="ui-icon ui-icon-power"></span>Logout</a></li>
						</ul>	
					</div>
					
				</div>
				
				<?php 
					$CI->load->library('html_admin/menu_top');
					$CI->menu_top->render(false);
				?>
			</div><!-- /#header -->
			
			<div id="bgwrap">
				<div id="content">
					<div id="main">
						<h1><?php echo $subtitle; ?></h1>
						<hr />
						<div class="pad20"><?php echo $content; ?></div>
					</div>
				</div>
			</div>
			
		</div><!-- #container -->
		
		<div id="footer">
			<p class="mid">
				<a href="<?php echo $base_url; ?>admin/home" title="" class="tooltip">Home</a>
				&middot;
				<a href="<?php echo $base_url; ?>admin/log/out" title="" class="tooltip">Logout</a>
			</p>
			<p class="mid">
				&copy;<?php echo BACKEND_TITLE; ?> <?php echo date('Y'); ?>, Todos os direitos reservados
			</p>
		</div>
		
	</body>
	
</html>


