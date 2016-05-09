
	var es_config = {
			
		isTesting : false,
	
		test : function()
		{
		
			if (!es_config.isTesting)
			{
		
				es_config.isTesting = true;
				
				var img = '<img src="' + baseUrl + 'img/admin/loading.gif" style="float:left;margin:2px 3px 0 0;" />';
				
				$('.es_config_test').html(img + 'Aguarde...').show(1, function(){
					$('#' + $('.es_config_test').parent().parent().parent().attr('id')).accordion('resize');
				});
				
				$.getJSON(baseUrl + 'email_sender/config/ajaxTestConnection/', function(data){
					
					es_config.isTesting = false; 
					
					msg = (typeof(data.txt) == 'undefined') ? 'Erro ao enviar e-mail, aguarde alguns segundos e tente novamente' : data.txt;  
					
					$('.es_config_test').html(msg);
					
				});
				
			}
		
		}
	
	} 