jQuery(document).ready(
    function ()
    {
      //lets's watch window scrolling
      $(document).scroll(function() {
        resizeHeader();
      });
    });
    
    var HAPPY_TEXT = "HEY YEAHHHHH!!!";
    var SAD_TEXT = "Scroll down me plz :/";
    
    function resizeHeader() {
      var _posScroll = window.scrollY;
      var _maxScroll = 50;
      
      //Decrease header size
      if (_posScroll > _maxScroll)
      {
        if($('.small-header').size()<=0)
        {
          //do stuffs here
          $('.fixed-header').addClass('small-header');
          $(".msg").text(HAPPY_TEXT);
        }
      }
      //back things to normal
      else if (_posScroll < _maxScroll)
      {
        $('.fixed-header').removeClass('small-header');
        $(".msg").text(SAD_TEXT);
      }
    }