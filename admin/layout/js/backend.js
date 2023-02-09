$(function () {
  "use strict";

  $("select").selectBoxIt({
      autoWidth:false
  }
  );

  $(".toggle-info").click(function () {
    $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
    if ($(this).hasClass('selected')) {
      $(this).html('<i class="fa fa-minus fa-lg"></i>');
    }else{
      $(this).html('<i class="fa fa-plus fa-lg"></i>');}
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

  var passField = $(".passwordd");
  $(".show-pass").hover(
    function () {
      passField.attr("type", "text");
    },
    function () {
      passField.attr("type", "password");
    }
  );


  $('.confirm').click(function(){
    return confirm('are you sure you want to delete this?');

    });

    $('.cat h3').click(function() {
      $(this).next('.full-view').fadeToggle();
    });

    $('.option span').click(function() {
      $(this).addClass('active').siblings('span').removeClass('active');

      
      
      
      if ($(this).data('view')=='full') {
        $('.cat .full-view').fadeIn(200);
      }else{
        $('.cat .full-view').fadeOut(200);
      }
    });

    $('.child-link').hover(function () {

      $(this).find('.show-delete').fadeIn(400);
  
    }, function () {
  
      $(this).find('.show-delete').fadeOut(400);
  
    });


});
