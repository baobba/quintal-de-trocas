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
//= require nested_form_fields
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

  $(".btn-footer").on("click", function(ev) {
    ev.preventDefault();
    console.log($(this).attr("href"));

    $.ajax({
      url: $(this).attr("href"),
      success: function(data){
        console.log(data);
        $("#footer_text .modal-content").html(data);
      }

    })
  })


  $(document).on("change", "#user_zipcode", function(){
    console.log("kkk");
    $(".add_fields").show();
  });


  $(".endereco").on("change", "input", function(){
    console.log("formulario alterado....");
    codeAddress();
  });



  var logged = false;

  $(document).on("click", ".login-button", function(e) {
    e.preventDefault();
    $('#login').modal('show');
  });


  $("form#login-box").on('ajax:success', function(e, data6, status, xhr){
    console.log("hiii");
    console.log(e);
    console.log(data6);
    console.log(status);
    console.log(xhr);

    $('#login').modal('hide');
    $('#myModal').modal('show');

    logged = true;
    if (logged) {
      $('.go-private').removeClass('login-button');
    }

  }).on('ajax:error',function(e, xhr, status, error){
    console.log("Failed");
    console.log(status);
    console.log(xhr);
    logged = false;
    $(".status").html(xhr.responseText);
  });

  $('#login_modal').submit(function(e) {
    e.preventDefault();
    var param = $(this).serialize();

    $('#login').find('input[type=submit]').val("Aguarde...").attr('disabled','disabled');

    $.ajax({
      url: $(this).attr('action'),
      data: param,
      dataType: "json"
    }).success(function(msg){

      console.log("Modal Type: "+modalType);

      logged = true;
      console.log("agora esta logado");
      $('.go-private').removeClass('login-button');

      if (modalType !== undefined && modalType === "modal") {

        $('#login').modal('hide');
        $(ref).click();

      } else if (modalType !== undefined && modalType !== "modal") {

        $('#login').modal('hide');

        $this.attr("data-toggle", "modal");
        $this.click();

      } else {
      }
      console.log("hi2");
    });
  });


  if ($('body.toys-index').length) {
    var $sidebar   = $("#map_wrapper"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        $footer    = $('footer').offset().top,
        topPadding = 0;

    $window.scroll(function() {
      if ($window.scrollTop() > offset.top) {
        $sidebar.css('position','fixed');
        $sidebar.css('top','0');

      } else if ($(window).scrollTop() <= 100) {
        $sidebar.css('position','');
        $sidebar.css('top','');
      }

      if ($sidebar.offset().top + $sidebar.height() > $("footer").offset().top) {
        $sidebar.css('top',-($sidebar.offset().top + $sidebar.height() - $("footer").offset().top));
      }
    });

  }

});

