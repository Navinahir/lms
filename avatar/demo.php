<?php

public function create_avatar(){
   $user_avatar = $this->make_avatar(strtoupper('J'));
}

function make_avatar($character)
{
    $avatar_name = uniqid();
    $path = "avatar/". $avatar_name . ".png";
    $image = imagecreate(200, 200);

    // Image background color.
    // $red = rand(0, 255);
    // $green = rand(0, 255);
    // $blue = rand(0, 255);
    // imagecolorallocate($image, $red, $green, $blue);  
    // $textcolor = imagecolorallocate($image, 255,255,255);  
    
    // Plain background with specific font color. 
    imagecolorallocate($image, 255, 255, 255);  
    $textcolor = imagecolorallocate($image, 51,169,245);  
    if($character == "i" || $character == "I")
    {
        imagettftext($image, 110, 0, 72, 150, $textcolor, 'avatar/arial.ttf', $character);  
    } else if($character == "c" || $character == "C" || $character == "g" || $character == "G" || $character == "h" || $character == "H" || $character == "m" || $character == "M" || $character == "n" || $character == "N" || $character == "o" || $character == "o" || $character == "u" || $character == "U"){
        imagettftext($image, 110, 0, 45, 150, $textcolor, 'avatar/arial.ttf', $character);  
    } else if($character == "o" || $character == "O" || $character == "q" || $character == "Q" || $character == "w" || $character == "W"){
        imagettftext($image, 110, 0, 38, 150, $textcolor, 'avatar/arial.ttf', $character);  
    } else {
        imagettftext($image, 110, 0, 55, 150, $textcolor, 'avatar/arial.ttf', $character);  
    }
    //header("Content-type: image/png");  
    imagepng($image, $path);
    imagedestroy($image);
    return $path;
}