$(document).ready(function() {

  if ($('body.toys').length) {

    console.log("Toy page...");

    $(".toy-exchange").on("change", "select", function(e){
      console.log("changed");
      console.log($(this).val());
      $this = $(this);

      $.ajax({
        url: '/brinquedos/'+$(this).val(),
        dataType: 'json'
        // data: {param1: 'value1'},
      })
      .success(function(e) {

        console.log("success");
        console.log(e);
        console.log($(this));
        $this.parent().find(".toy").html(e.title);
      });

    });

    $(".toy-filter").change(function(event) {
      event.preventDefault();

      console.log("form alterado");

      $(this).submit();
    });

  }

});

