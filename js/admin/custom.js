$(function(){
				
	// topMenu
	$("ul.nav").superfish({
		animation :
		{
			height: "show",
			width: 	"show"
		},
		speed : 0
	});
	
	// Tooltips
	$(".tooltip").easyTooltip();
	
	// Close notifications
	$(".close").click(
		function () {
			$(this).fadeTo(400, 0, function () { // Links with the class "close" will close parent
			$(this).slideUp(400);
		});
		return false;
		}
	);

	// Datepicker
	$('.datepicker').datepicker({
		inline: true,
		duration: 0,
		dateFormat: 'dd/mm/yy',
		dayNames: [
			'Domingo','Segunda','Ter�a','Quarta','Quinta','Sexta','S�bado','Domingo'
		],
		dayNamesMin: [
			'D','S','T','Q','Q','S','S','D'
		],
		dayNamesShort: [
			'Dom','Seg','Ter','Qua','Qui','Sex','S�b','Dom'
		],
		monthNames: [
			'Janeiro','Fevereiro','Mar�o','Abril','Maio','Junho','Julho','Agosto','Setembro', 'Outubro','Novembro','Dezembro'
		],
		monthNamesShort: [
			'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set', 'Out','Nov','Dez'
		],
		nextText: 'Pr&oacute;ximo',
		prevText: 'Anterior',
		changeMonth: true,
		changeYear: true
		
	});
				
	// Sortable, portlets
	$(".column").sortable({
		connectWith: '.column'
	});
				
	$(".sort").sortable({
		connectWith: '.sort'
	});		

	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
			.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend('<span class="ui-icon ui-icon-circle-arrow-s"></span>')
			.end()
			.find(".portlet-content");

	$(".portlet-header .ui-icon").click(function() {
		$(this).toggleClass("ui-icon-minusthick");
		$(this).parents(".portlet:first").find(".portlet-content").toggle();
	});

	$(".column").disableSelection();
			
	// WYSIWYG Editor
	$('textarea.wysiwyg').wysiwyg({
  		controls: {
    		strikeThrough : { visible : true },
    		underline     : { visible : true },
      
   			separator00 : { visible : true },
      
     		justifyLeft   : { visible : true },
      		justifyCenter : { visible : true },
     		justifyRight  : { visible : true },
     		justifyFull   : { visible : true },
      
 			separator01 : { visible : true },
      
   			indent  : { visible : true },
			outdent : { visible : true },
      
     		separator02 : { visible : true },
      
    		subscript   : { visible : true },
    		superscript : { visible : true },
      
   			separator03 : { visible : true },
  	    
  			undo : { visible : true },
   			redo : { visible : true },
      
    		separator04 : { visible : true },
      
     		insertOrderedList    : { visible : true },
    		insertUnorderedList  : { visible : true },
    		insertHorizontalRule : { visible : true },
      
    		h4mozilla : { visible : true && $.browser.mozilla, className : 'h4', command : 'heading', arguments : ['h4'], tags : ['h4'], tooltip : "Header 4" },
     		h5mozilla : { visible : true && $.browser.mozilla, className : 'h5', command : 'heading', arguments : ['h5'], tags : ['h5'], tooltip : "Header 5" },
     		h6mozilla : { visible : true && $.browser.mozilla, className : 'h6', command : 'heading', arguments : ['h6'], tags : ['h6'], tooltip : "Header 6" },
      
     		h4 : { visible : true && !( $.browser.mozilla ), className : 'h4', command : 'formatBlock', arguments : ['<H4>'], tags : ['h4'], tooltip : "Header 4" },
     		h5 : { visible : true && !( $.browser.mozilla ), className : 'h5', command : 'formatBlock', arguments : ['<H5>'], tags : ['h5'], tooltip : "Header 5" },
   			h6 : { visible : true && !( $.browser.mozilla ), className : 'h6', command : 'formatBlock', arguments : ['<H6>'], tags : ['h6'], tooltip : "Header 6" },
      
   			separator07 : { visible : true },
      
     		cut   : { visible : true },
     		copy  : { visible : true },
     		paste : { visible : true }
   		}
  	});
	
	setTimeout(function(){resize()}, 100);
	
	$("#twitter").getTwitter({
		userName: "tazzoom",
		numTweets: 5,
		loaderText: "Carregando tweets.",
		slideIn: true,
		slideDuration: 750,
		showHeading: true,
		headingText: "&Uacute;ltimos Tweets",
		showProfileLink: true,
		showTimestamp: true
	});
	
	
	$('.fulltime').mask('99/99/9999 99:99:00',	{placeholder:' '});
	$('.fone').mask('(99) 9999-9999',	{placeholder:' '});
	$('.cpf').mask('999.999.999-99',	{placeholder:' '});
	$('.cep').mask('99999-999',	{placeholder:' '});
	$('.valor').maskMoney({
			symbol:		'R$',
			decimal:	',',
			thousands:	'.'
	});
	$('.porcentagem').maskMoney({
			symbol:		'%',
			decimal:	',',
			thousands:	'.'
	});
	
	/** **/
	
	jQuery.expr[':'].regex = function(elem, index, match) {
	    var matchParams = match[3].split(','),
	        validLabels = /^(data|css):/,
	        attr = {
	            method: matchParams[0].match(validLabels) ? 
	                        matchParams[0].split(':')[0] : 'attr',
	            property: matchParams.shift().replace(validLabels,'')
	        },
	        regexFlags = 'ig',
	        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
	    return regex.test(jQuery(elem)[attr.method](attr.property));
	};

	$('input[type=text]:regex(class, autocomplete.*)').each(function(){
		
		var hash = (/autocomplete+\[+.*\]/.exec(this.className) + '');
        hash = (/\[+.*\]/.exec(hash) + '').replace('[', '').replace(']', '').split(',');
        
        var ts 		= $(this);
        var url 	= hash[0];
        var hidden 	= hash[1];

        if ($('input[name=' + hidden + ']').length)
        {
	        $(this).autocomplete({
	    		url: url + '?',
	    		//useCache: false,
	    		onItemSelect: function(item) {
	    			if (item.data == 0)
	    			{
	    				ts.val('');
	    			}else{
	    				$('input[name=' + hidden + ']').val(item.data);
	    			}
	    		},
	    		maxItemsToShow: 5
	    	});
        }else{
        	alert('hidden [' + hidden + '] not found');
        }
	});

	$('input[type=text]:regex(class, maxlen.*)').each(function(){
		
		var n = 'max-' + $(this).attr('name');
		var t = (/maxlen-+\d{0,9}/.exec(this.className) + '').split('-')[1];
		var v = t + '';
		
		var input = '<input readonly="readonly" disable="disable" name="' + n + '" value="0/' + v + '" style="width:' + (((v.length * 2) + 1) * 8) + 'px" />';
		
		var cv = this.value.length;
		
		$(this).after(input);
		
		if (cv > 0)
		{
			$('input[name=' + n + ']').val(cv + '/' + t);
		}
		
		t = parseInt(t);
		
		$(this).keypress(function(event) {
			var cv = this.value.length;
			if (parseInt(cv + 1) <= t | event.which == 8 | event.which == 0 | event.which == 118 | event.which == 120 | event.which == 99 |  event.which == 13)
			{
				return true;
			}else{
				return false;
			}
		}).keydown(function(){
			cv = this.value.length;
			if (cv > t)
			{
				this.value = this.value.substring(0, t);
				cv = this.value.length;
			}
			$('input[name=' + n + ']').val(cv + '/' + t);
		}).keyup(function(){
			cv = this.value.length;
			if (cv > t)
			{
				this.value = this.value.substring(0, t);
				cv = this.value.length;
			}
			$('input[name=' + n + ']').val(cv + '/' + t);
		});
		
	});
	
	$('.noautocomplete').attr('autocomplete','off');
});

$(window).bind('resize', function(){
	resize();
});

function resize()
{
	var wh = $(window).height();
	var bh = $('#main').height();
	
	var diff = wh - 123;
	
	if (bh < diff)
	{
		$('#container').height(diff);
	}
}
