<html>
    <body>
    	<p>Olá <?php echo $name; ?>,</p>
    	
    	<p>O usuário <?php echo $interested_name; ?> possui interesse em um dos seus briquedos!</p>

    	<p>Cidade: <?php echo $interested_city; ?></p>
    	
    	<p>Estado: <?php echo strtoupper($interested_state); ?></p>
    	
    	<p>Acesse o <a href="http://www.quintaldetrocas.com.br/usuario/meus_dados/">site</a> e verifique sua conta.</p>
		<?php //Mensagem: echo $message; ?>
    	<br />

    	<p>Um abraço e boa troca,
    	<br>
    	Equipe Quintal de Trocas.</p>
    </body>
</html>q