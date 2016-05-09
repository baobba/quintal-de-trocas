
	var es_send = {
			
		loading 		: '<img src="' + baseUrl + 'img/admin/loading.gif" class="fl" style="margin:2px 3px 0 0;" />',
		currentStep 	: 0,
		toSend			: 0,
		
		begin : function()
		{
		
			toSend = $('select[name=to-send]').val();

			if (toSend !== '')
			{
				
				es_send.toSend = toSend;
				
				$('#send-fieldset').hide();
				
				es_send.setStep('Buscando informa&ccedil;&otilde;es', true)

				$('#send-status').show();
				
				$.getJSON(baseUrl + 'email_sender/send/ajax_send_step_0/' + toSend, function(data){
					
					if (data.error)
					{
						
						es_send.setStep(data.txt, false);
						
					}else{
					
						es_send.setStep(data.txt, true);
						
						es_send.send();
						
					}
					
				});
				
			}else{
			
				$('#send-fieldset > p > span.validate_error').html('Selecione um e-mail marketing');
				
			}
		
		},
		
		send : function()
		{
			
			$('.send-steps-' + es_send.currentStep).find('img').remove();
			$('.send-steps-' + es_send.currentStep).find('span').append(' OK');
			
			es_send.currentStep++;
			
			$('#send-content').append('<br /><p class="send-steps-' + es_send.currentStep + '"><span class="fl" style="margin-right:5px"><b>' + es_send.currentStep + '</b> - Enviando <span class="progress">0%</span></span>' + es_send.loading + '<div style="width:150px;margin:-17px 3px 0 0;" class="fl progress-bar"></div><div class="cb"></div></p>');
			$('.progress-bar').progressbar({value:0});
			
			$.get(baseUrl + 'email_sender/send/ajax_send_step_1/' + es_send.toSend);
			
			es_send.verify();
			
		},
		
		verify : function()
		{
		
			$.getJSON(baseUrl + 'email_sender/send/ajax_send_verify/' + es_send.toSend, function(data){
				
				if (data.error)
				{
					
					es_send.setStep(data.txt, false)
					
				}else{
				
					if (data.perc >= 100)
					{
						
						es_send.setStep('Conclu&iacute;do', false);
						
					}else{
					
						setTimeout(function(){
							es_send.verify();
						}, 1500);
					
					}
					
					$('.progress').html(data.perc + '%');
					$('.progress-bar').progressbar('option', 'value', data.perc);
					
				}
								
			});
			
		},
		
		setStep : function (msg, showLoading)
		{
			
			$('.send-steps-' + es_send.currentStep).find('img').remove();
			$('.send-steps-' + es_send.currentStep).find('span').append(' OK');
			
			es_send.currentStep++;

			var img = '';
			
			if (showLoading == true)
			{
				img = es_send.loading;
			}
			
			$('#send-content').append('<br /><p class="send-steps-' + es_send.currentStep + '"><span class="fl" style="margin-right:5px"><b>' + es_send.currentStep + '</b> - ' + msg + '.</span>' + img + '<div class="cb"></div></p>');
			
		}
			
	}