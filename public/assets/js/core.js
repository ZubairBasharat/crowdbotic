$(document).on('click', function(event) {
  if (!$(event.target).closest('#profile-toggle').length) {
    $('#profile-menu').removeClass('active');
  }
});
$(document).ready(function(){
  $('#profile-toggle').click(function(){
    $('#profile-menu').toggleClass('active');
  });
  $('.aside-toggle').click( () => {
    $('.theme-aside').toggleClass('minimized');
    $('.theme-aside .logo').toggleClass('d-none');
    $('.main').toggleClass('active');
  });
});