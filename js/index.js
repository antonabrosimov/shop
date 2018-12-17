// kada se zavrsi ucitavanje stranice...
$(document).ready(function() {

  // Bootstrap funcionalnost za popover
  $(function () {
    $('[data-toggle="popover"]').popover();
  });
  
  // prikazivanje add comment forme na klik
  $('.add-category-button').click(function() {
    $('.add-category-button').slideUp();
    $('.add-category-form').slideDown();
  });

  // sakrivanje alert poruka
  setTimeout(function() {
    $('.alert-success').slideUp();
  }, 5000);
  
});
