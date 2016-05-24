$(function () {
    $(".iframe-content").fancybox({
	'width': '75%',
	'height': '75%',
	'autoScale': false,
	'transitionIn': 'none',
	'transitionOut': 'none',
	'type': 'iframe'
    });

});


$(document).ready(function () {
    jQuery.fn.exists = function () {
	return this.length > 0;
    }

    $("#browse").click(function () {
	event.preventDefault();
	
	return $("#avatar").click();
    });

    $(".btn-done").click(function () {
	if (!$(".star-rating").hasClass("star-rating-on")) {
	    $(".erro-estrelas").remove();
	    $(".star-rating-control").append("<span class='erro-estrelas' style='color:red'>Avalie a troca antes de finalizar.</span>");
	    return false;
	}

    });
    if ($("#state_combo").exists()) {
	$("#state_combo").on('change', function () {
	    $.ajax({
		url: base_url + "autocomplete/state/" + $('#state_combo').val(),
		dataType: "json"
	    })
		    .done(function (data) {
			cities = '';
			for (state in data) {
			    cities = cities + "\n <option value=\"" + data[state]['id'] + "\">" + data[state]['value'] + "</option>";
			}
			$('#city_combo').html(cities);
			$('#city_combo').prop('disabled', false);
		    });
	});
    }

    if ($("#city_combo").exists()) {
	$("#city_combo").jCombo({
	    url: base_url + "autocomplete/state/",
	    initial_text: "Selecione uma cidade",
	    parent: "#state_combo",
	    dataType: "json",
	    timeout: 10000,
	    selected_value: selected_city
	});
    }

    if ($(".open-exchange").exists()) {
	$(".open-exchange").on('click', function (e) {
	    e.preventDefault();

	    $(".trocar").show();

	    var target = "." + $(this).data('target');
	    $('html, body').animate({
		scrollTop: $(target).offset().top
	    }, 1000);
	});

	$(".cancelar").on('click', function (e) {
	    e.preventDefault();
	    $(".trocar").toggle();
	});

	$('input').iCheck({
	    radioClass: 'iradio_square-green'
	});
	// variável que enviará o $formulário
	var request,
		type,
		success = '<p class="alert success">Pedido de troca enviado com sucesso.</p>',
		error = '<p class="alert warning">Ocorreu um erro, tente novamente.</p>';

	$("#exchange-form").on("submit", function (event) {

	    // abortar qualquer solicitação pendente
	    if (request) {
		request.abort();
	    }
	    if (!$("input[name='product']:checked").val()) {
		alert('Você não selecionou nenhum produto.');
		return false;
	    }

	    var $form = $(this),
		    // selecionar e salvar no cache todos os campos
		    $inputs = $form.find("input, select, button, textarea"),
		    // serialize os dados do $formulário
		    serializedData = $form.serialize();

	    // desativar os inputs durante a solicitação do ajax
	    $inputs.prop("disabled", true);
	    // enviar a solicitação
	    request = $.ajax({
		url: $form.attr('action'),
		type: $form.attr("method"),
		data: serializedData
	    });

	    // callback handler que será chamado no sucesso
	    request.done(function (response, textStatus, jqXHR) {
		console.log("Enviado!");
		type = true;
		$form.hide();
	    });

	    // callback handler que será chamado se houver falha
	    request.fail(function (jqXHR, textStatus, errorThrown) {
		type = false;
		console.error(
			"Ocorreu o seguinte erro: " +
			textStatus, errorThrown
			);
	    });

	    // callback handler que será chamado não importando
	    // se a solicação falhou ou sucedeu
	    request.always(function () {
		// reativar os $inputs
		$inputs.prop("disabled", false);

		// remover alerta se existir
		if ($(".alert").exists()) {
		    $(".alert").remove()
		}
		;

		$form.after(type ? success : error);

		setTimeout(function () {
		    $(".alert").fadeOut("slow").remove();
		}, 5000);
	    });

	    // prevenir a ação padrão do formulário
	    event.preventDefault();
	});
    }

    if (window.PIE) {
	$('.ie-fix').each(function () {
	    PIE.attach(this);
	});
    }

    if ($('form').exists()) {
	customForm.lib.domReady(function () {
	    customForm.customForms.replaceAll();
	});
    }

    if ($('#flexslider-1').exists()) {
	$('#flexslider-1').flexslider({
	    animation: "slide",
	    directionNav: false,
	    slideshow: true,
	    pauseOnHover: true,
	    video: true
	});
    }

    if ($('#gallery-1').exists()) {
	$('#gallery-1 .flexslider').flexslider({
	    animation: "slide",
	    directionNav: false,
	    slideshow: true,
	    manualControls: "#gallery-1 .thumbs-list a"
	});
    }

    if ($('#carousel-1').exists()) {
	$('#carousel-1').gallery({
	    duration: 500,
	    listOfSlides: '.list > li'
	});
    }

    $('header nav .open-menu').click(function () {
	$(this).next('ul').slideToggle();
	return false;
    });



    if ($('.open-popup').exists() && $('.popup').exists()) {
	$(".open-popup").fancybox({
	    'padding': 0,
	    'titlePosition': 'outside',
	    'overlayColor': '#6cad4a',
	    'overlayOpacity': 0.85,
	    showCloseButton: false
	});
    }

    if ($('.media-area .media-list .more').exists()) {
	$(".media-area .media-list .more").fancybox({
	    'padding': 10,
	    'titlePosition': 'over',
	    'overlayColor': '#6cad4a',
	    'overlayOpacity': 0.85
	});
    }

    if ($('#accordion-1').exists()) {
	$('#accordion-1').accordion({
	    collapsible: true,
	    header: '.ttl',
	    active: 0,
	    autoHeight: false
	});

	$('#accordion-1').find('.ui-state-active').closest('.faq-item').addClass('active');

	$('#accordion-1 .ttl').click(function (e) {
	    e.preventDefault();
	    $('#accordion-1').find('.active').not($(this).closest('.faq-item')).removeClass('active');
	    $(this).closest('.faq-item').toggleClass('active');
	});
    }

    if ($('#tabs-1').exists()) {
	$('#tabs-1').tabs({
	    selected: 0
	});
    }

    if ($('#accordion-2').exists()) {
	$('#accordion-2').accordion({
	    collapsible: true,
	    header: '.msg-head',
	    active: 0,
	    autoHeight: false
	});

	$('#accordion-2').find('.ui-state-active').closest('.item').addClass('active');

	$('#accordion-2 .msg-head').click(function (e) {
	    e.preventDefault();
	    $('#accordion-2').find('.active').not($(this).closest('.item')).removeClass('active');
	    $(this).closest('.item').toggleClass('active');
	});
    }

    if ($('.chosen-select').exists()) {
	$(".chosen-select").chosen();
    }

    if ($('.user-area').exists()) {
	$('.add-switcher a').click(function () {
	    var array = $(this).attr('href').split('#');

	    $('#tabs-1').tabs({
		selected: array[array.length - 1]
	    });
	})
    }

    if ($('input[name=toy_city_name]').exists()) {
	function findValue(li) {
	    if (li == null)
		return;

	    if (!!li.extra) {
		var sValue = li.extra[0];
	    } else {
		var sValue = li.selectValue;
	    }

	    $('input[name=toy_city]').val(sValue);
	}

	function selectItem(li) {
	    findValue(li);
	}

	function formatItem(row) {
	    return row[0];
	}

	$('input[name=toy_city_name]').autocomplete(base_url + 'autocomplete/city/', {
	    onItemSelect: selectItem,
	    onFindValue: findValue,
	    formatItem: formatItem,
	    delay: 10,
	    minChars: 2,
	    matchSubset: 1,
	    matchContains: 1,
	    cacheLength: 10
	});
    }
});


function initPage()
{
    clearFormFields({
	clearInputs: true,
	clearTextareas: true,
	passwordFieldText: true,
	addClassFocus: "focus",
	filterClass: "default"
    });
}
function clearFormFields(o)
{
    if (o.clearInputs == null)
	o.clearInputs = true;
    if (o.clearTextareas == null)
	o.clearTextareas = true;
    if (o.passwordFieldText == null)
	o.passwordFieldText = false;
    if (o.addClassFocus == null)
	o.addClassFocus = false;
    if (!o.filter)
	o.filter = "default";
    if (o.clearInputs) {
	var inputs = document.getElementsByTagName("input");
	for (var i = 0; i < inputs.length; i++) {
	    if ((inputs[i].type == "text" || inputs[i].type == "password") && inputs[i].className.indexOf(o.filterClass)) {
		inputs[i].valueHtml = inputs[i].value;
		inputs[i].onfocus = function () {
		    if (this.valueHtml == this.value)
			this.value = "";
		    if (this.fake) {
			inputsSwap(this, this.previousSibling);
			this.previousSibling.focus();
		    }
		    if (o.addClassFocus && !this.fake) {
			this.className += " " + o.addClassFocus;
			this.parentNode.className += " parent-" + o.addClassFocus;
		    }
		}
		inputs[i].onblur = function () {
		    if (this.value == "") {
			this.value = this.valueHtml;
			if (o.passwordFieldText && this.type == "password")
			    inputsSwap(this, this.nextSibling);
		    }
		    if (o.addClassFocus) {
			this.className = this.className.replace(o.addClassFocus, "");
			this.parentNode.className = this.parentNode.className.replace("parent-" + o.addClassFocus, "");
		    }
		}
		if (o.passwordFieldText && inputs[i].type == "password") {
		    var fakeInput = document.createElement("input");
		    fakeInput.type = "text";
		    fakeInput.value = inputs[i].value;
		    fakeInput.className = inputs[i].className;
		    fakeInput.fake = true;
		    inputs[i].parentNode.insertBefore(fakeInput, inputs[i].nextSibling);
		    inputsSwap(inputs[i], null);
		}
	    }
	}
    }
    if (o.clearTextareas) {
	var textareas = document.getElementsByTagName("textarea");
	for (var i = 0; i < textareas.length; i++) {
	    if (textareas[i].className.indexOf(o.filterClass)) {
		textareas[i].valueHtml = textareas[i].value;
		textareas[i].onfocus = function () {
		    if (this.value == this.valueHtml)
			this.value = "";
		    if (o.addClassFocus) {
			this.className += " " + o.addClassFocus;
			this.parentNode.className += " parent-" + o.addClassFocus;
		    }
		}
		textareas[i].onblur = function () {
		    if (this.value == "")
			this.value = this.valueHtml;
		    if (o.addClassFocus) {
			this.className = this.className.replace(o.addClassFocus, "");
			this.parentNode.className = this.parentNode.className.replace("parent-" + o.addClassFocus, "");
		    }
		}
	    }
	}
    }
    function inputsSwap(el, el2) {
	if (el)
	    el.style.display = "none";
	if (el2)
	    el2.style.display = "inline";
    }
}
if (window.addEventListener)
    window.addEventListener("load", initPage, false);
else if (window.attachEvent)
    window.attachEvent("onload", initPage);
