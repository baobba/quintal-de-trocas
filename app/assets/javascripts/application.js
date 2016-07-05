// This is a manifest file that'll be compiled into application.js, which will include all the files
// listed below.
//
// Any JavaScript/Coffee file within this directory, lib/assets/javascripts, vendor/assets/javascripts,
// or any plugin's vendor/assets/javascripts directory can be referenced here using a relative path.
//
// It's not advisable to add code directly here, but if you do, it'll appear at the bottom of the
// compiled file.
//
// Read Sprockets README (https://github.com/rails/sprockets#sprockets-directives) for details
// about supported directives.
//
//= require jquery
//= require jquery_ujs
//= require bootstrap.min
//= require jquery.mask.min
//= require_tree .


$(document).ready(function() {
  
  $('#user_zipcode').mask('00000-000');

  var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  },
  spOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
      }
  };

  $('#user_phone').mask(SPMaskBehavior, spOptions);

  $("#user_zipcode").on('keyup change', function() {
    var cep = $(this).val();
    if(cep.length >= 8) {
      $(".cep-feedback").html("<i class='icon-spinner icon-spin'></i> Aguarde...");
      $.getJSON("/busca-por-cep?cep=" + cep,
        function(data) {
          if(data.cep) {
            $(".cep-feedback").html("<span class='glyphicon glyphicon-ok' style='color:green'></span>");
            $("#user_street").val(data.tipo_logradouro + ' ' + data.logradouro);
            $("#user_city").val(data.cidade);
            $("#user_state").val(data.uf);
          } else {
            $(".cep-feedback").html("<span class='glyphicon glyphicon-warning-sign' style='color:red'></span> CEP não encontrado, por favor preencha-o manualmente.");
            // $(".endereco").find('input:text').val('');
          }
        }
      );
    } else {
      $(".cep-feedback").html("");
    }
  });

  
  $(document).on("change", "#user_zipcode", function(){
    console.log("kkk");
    $(".add_fields").show();
  });


  $(".endereco").on("change", "input", function(){
    console.log("formulario alterado....");
    codeAddress();
  });

});

