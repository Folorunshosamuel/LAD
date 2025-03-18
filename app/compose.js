$('#btnCompose').on('click', function(e){
    e.preventDefault();
    $('.az-mail-compose').show();
  });

  $('.az-mail-compose-header a:first-child').on('click', function(e){
    e.preventDefault();
    $('.az-mail-compose').toggleClass('az-mail-compose-minimize');
  })

  $('.az-mail-compose-header a:nth-child(2)').on('click', function(e){
    e.preventDefault();
    $(this).find('.fas').toggleClass('fa-compress');
    $(this).find('.fas').toggleClass('fa-expand');
    $('.az-mail-compose').toggleClass('az-mail-compose-compress');
    $('.az-mail-compose').removeClass('az-mail-compose-minimize');
  });

  $('.az-mail-compose-header a:last-child').on('click', function(e){
    e.preventDefault();
    $('.az-mail-compose').hide(100);
    $('.az-mail-compose').removeClass('az-mail-compose-minimize');
  });