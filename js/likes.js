$(document).ready(function() {

  // uzimanje informacija o lajkovima
  // kontaktiraj api stranicu koristeci AJAX
  $.ajax({
    // u adresi se salje product_id
    url: './api.php?product_id=' + product_id,
    // ukoliko uspesno kontaktiras server
    success: function(res) {
      // u "res" promenljivoj se nalaze informacije sa kojima je
      // server "odgovorio"
      console.log(res);
      // pronadji span sa klasom .num-of-likes i upisi broj lajkova
      $('.num-of-likes').text(res.likes);

      // ako je proizvod lajkovan
      if(res.liked) {
        // zameni ikonicu sa punom
        $('.like-icon').html('<i class="fas fa-thumbs-up"></i>');
        // zameni tekst sa "liked"
        $('.like-text').text('Liked');
      // ako proizvod nije lajkovan
      } else {
        // zameni ikonicu sa prazonm
        $('.like-icon').html('<i class="far fa-thumbs-up"></i>');
        // zameni tekst sa "like"
        $('.like-text').text('Like');
      }
    },
    // ako dodje do greske
    error: function(err) {
      // prikazi alert
      alert('An error has occured. Check console for more info.');
      // ispisi razlog u konzoli (F12 -> console tab)
      console.log(err);
    }
  });

  // ceka se klik na lajk
  // cekaj klik na div sa klasom liked
  $('.likes').click(function() {
    // sve kontaktiraj API isto kao gore samo sa komandom "like"
    $.ajax({
      url: './api.php?command=like&product_id=' + product_id,
      success: function(res) {
        console.log(res);
        $('.num-of-likes').text(res.likes);

        if(res.liked) {
          $('.like-icon').html('<i class="fas fa-thumbs-up"></i>');
          $('.like-text').text('Liked');
        } else {
          $('.like-icon').html('<i class="far fa-thumbs-up"></i>');
          $('.like-text').text('Like');
        }
      },
      error: function(err) {
        alert('An error has occured. Check console for more info.');
        console.log(err);
      }
    });
  });

});