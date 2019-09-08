(function ($) {
  // thanks: https://github.com/pisi/Metronome

  var sound = document.getElementById("audio");
  var counter = 0;
  var timesignature = 3;
  var subdivisions = 2;
  var $keyhole = $('.keyhole');
  var rowOffset = 0;
  var currentRowWidth = $('.music').width();
  var measures = 17;
  var highlightEnd = measures * subdivisions * timesignature;

  $('#btn-start').on('click', function () {
    var sound = new Audio("https://www.soundjay.com/switch/switch-1.wav");
    sound.load();
    var bpm = $('input[name="bpm"]').val() * subdivisions;
    var bps = bpm / 60;
    $.metronome.start(bps);
  });

  $('#btn-stop').on('click', function () {
    $.metronome.stop();
  });

  $('#btn-reset').on('click', function () {
    $.metronome.stop();
    resetPosition();
  });

  function resetPosition() {
    counter = 0;
    rowOffset = 0;
    $keyhole.css({
      'top': 0,
      'left': -24,
      'backgroundPositionX': 0,
      'backgroundPositionY': 0,
    });
  }

  $(document).bind('tick', function(){
    if (counter % subdivisions == 0){
      sound.play();
    }
    counter++;
    if (counter <= highlightEnd){ highlightNotes(); }
    if (counter > highlightEnd) {
      counter = 1;
      rowOffset = 0;
      $keyhole.css({
        'top': 0,
        'left': 0,
        'backgroundPositionX': 0,
        'backgroundPositionY': 0,
      });
    }
  });

  function highlightNotes() {
    var left = parseInt($keyhole.css('left')) + 24;
    if (currentRowWidth > left) {
      $keyhole.css({
        'left': left,
        'backgroundPositionX': -left,
      });
    }
    else { // move to the next row
      rowOffset++;
      $keyhole.css({
        'top': rowOffset*96,
        'left': 0,
        'backgroundPositionX': 0,
        'backgroundPositionY': -rowOffset*96,
      });
    }
  }


})(jQuery);