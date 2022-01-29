$(window).scroll(function(e){ 
    var $el = $('.right'); 
    var isPositionFixed = ($el.css('position') == 'fixed');

    if ($(this).scrollTop() > 100 && !isPositionFixed){ 
      $el.css({'position': 'fixed', 'top': '0px', 'right': '125px', 'width': '280px'}); 
    }
    if ($(this).scrollTop() < 100 && isPositionFixed){
      $el.css({'position': 'static', 'top': '0px'}); 
    } 
});