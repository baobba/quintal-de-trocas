<!DOCTYPE html>

<html>

	<head>
		
		<title><?php echo BACKEND_TITLE; ?> Login</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link type="text/css" href="<?php echo $base_url; ?>css/admin/login.css" rel="stylesheet" />	
		<link type="text/css" href="<?php echo $base_url; ?>css/admin/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
		
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/easyTooltip.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>js/admin/jquery-ui-1.7.2.custom.min.js"></script>
			
	</head>
	
	<body>
	
		<div id="container">
		
			<div class="logo">
				<a href=""><img src="<?php echo $base_url; ?>img/admin/logo.png" alt="" /></a>
			</div>
			
			<div id="box">
			
				<form action="" method="POST">
				
					<p class="main">
						<label>User: </label>
						<input type="text" name="login" value="<?php if(isset($_POST['login'])) { echo $_POST['login']; }?>"/> 
						<label>Pass: </label>
						<input type="password" name="pass" value="<?php if(isset($_POST['pass'])) { echo $_POST['pass']; }?>">	
					</p>
	
					<p class="space">
						<input type="submit" value="Login" class="login" />
					</p>
					
				</form>
				
			</div>
			
		</div>	

	</body>
	
</html>