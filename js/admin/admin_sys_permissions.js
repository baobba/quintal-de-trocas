	
	$(function(){
		$('.permission-checkbox').click(function(){
			var selected = $(this).attr('value'), checked  = $(this).attr('checked')
			if (selected == 4 && checked)
			{
				$('input[name=' + $(this).attr('name') + ']').not($(this)).each(function(){
					$(this).attr('checked', false).attr('disabled', true);
				})
			}else{
				$('input[name=' + $(this).attr('name') + ']').each(function(){
					$(this).attr('disabled', false);
					($(this).attr('checked') && $(this).attr('value') !== 4) ? $('input[name=' + $(this).attr('name') + ']').eq(0).attr('checked', true) : '';
				})
			}
			((selected == 1 | selected == 2 | selected == 3) && checked) ? $('input[name=' + $(this).attr('name') + ']').eq(0).attr('checked', true) : '';
		}).each(function(){
			var selected = $(this).attr('value'), checked  = $(this).attr('checked')
			if (selected == 4 && checked)
			{
				$('input[name=' + $(this).attr('name') + ']').not($(this)).each(function(){
					$(this).attr('checked', false).attr('disabled', true);
				})
			}else{
				((selected == 1 | selected == 2 | selected == 3) && checked) ? $('input[name=' + $(this).attr('name') + ']').eq(0).attr('checked', true) : '';
			}
		});
	});