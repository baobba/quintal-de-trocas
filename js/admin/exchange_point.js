$(function(){

	new dgCidadesEstados({
		estado: $('.state').get(0),
		cidade: $('.city').get(0),
		change: true
	});
	
	$('.zipcode').keyup(function(){
		atualizacep($(this).val());
	});
});

function correiocontrolcep(valor){
    if (valor.erro) {
		limpaResultadosAnteriores();
		$('.zipcode').focus();
		return;
    };
    
    $('.address').focus();
    $('.address').val(valor.logradouro);
    $('.neighborhood').val(valor.bairro);
    $('.state').val(valor.uf);
    $('.state').trigger('change');
    $('.city').val(valor.localidade);
}

function limpaResultadosAnteriores(){
    $('.address').val('');
    $('.neighborhood').val('');
    $('.city').val('');
    $('.state').val('');
}