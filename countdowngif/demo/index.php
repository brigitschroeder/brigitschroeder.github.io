<?php

	date_default_timezone_set('America/Denver');
	include 'GIFEncoder.class.php';
	include 'php52-fix.php';
	$future_date = new DateTime(date('r',strtotime($_GET['time'])));
	$now = new DateTime(date('r', time()));
	$frames = array();
	$delays = array();
	$image = imagecreatefrompng('images/countdownbg.png');
	$delay = 100;// milliseconds

	$font = array(
		'size' => 49, // Font size, in pts usually.
		'angle' => 0, // Angle of the text
		'x-offset' => 4, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 58, // The vertical alignment, trial and error between 20 and 60.
		'file' => __DIR__ . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf', // Font path
		'color' => imagecolorallocate($image, 255, 255, 255), // RGB Colour of the text
	);
	for($i = 0; $i <= 60; $i++){
		
		$interval = date_diff($future_date, $now);
		
		if($future_date < $now){
			// Open the first source image and add the text.
			$image = imagecreatefrompng('images/countdownbg.png');
			;
			$text = $interval->format('000 00 00 00');
			imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$delays[]=$delay;
			$loops = 1;
			ob_end_clean();
			break;
		} else {
			// Open the first source image and add the text.
			$image = imagecreatefrompng('images/countdownbg.png');
            $numDays = $interval->format('%a');
            if ($numDays > 99){
                $text = $interval->format('%a %H %I %S');
            } elseif ($numDays > 9){
                $text = $interval->format('0%a %H %I %S');
            } else {
                $text = $interval->format('00%a %H %I %S');
            }
			imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$delays[]=$delay;
			$loops = 0;
			ob_end_clean();
		}
		$now->modify('+1 second');
	}

	//expire this image instantly
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
	$gif = new AnimatedGif($frames,$delays,$loops);
	$gif->display();
