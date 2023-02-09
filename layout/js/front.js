$(function () {
  "use strict";

  $("select").selectBoxIt({
    autoWidth:false
}
);

  $(".login-page h1 span").click(function () {
    $(this).addClass("selected").siblings().removeClass("selected");
    $(".login-page form").hide();
    $('.'+ $(this).data("class")).fadeIn(100);
  });


  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));
      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });



  $('.confirm').click(function(){
    return confirm('are you sure you want to delete this?');

    });

  $('.live').keyup(function() {
    $($(this).data('class')).text($(this).val());
  });

});
