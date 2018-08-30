<?php  
 if(!defined("IN_USER")) die;  
 include "config.php";

    header ("Content-type: image/png");  
//    $im = ImageCreate (592, 513)  
//            or die ("Ошибка при создании изображения");          
    
		$np = "img/blankcbr.png";
		$image = @imagecreatefrompng($np);
		if(!$image){
			die ("Ошибка при создании изображения");
		}

		$date = date('d.m.Y');
		$textcolor = imagecolorallocate($image, 0, 0, 0);

    $proarrid = $_GET['paid'];
    $summa = $_GET['s'];

    $gst2 = mysql_query("SELECT * FROM projectbank WHERE proarrid='".$proarrid."'");
    if (!$gst2) puterror("Ошибка при обращении к базе данных");
    $projectbank = mysql_fetch_array($gst2);
    if (!empty($projectbank)) {
		 imagettftext($image,11,0,300,25,$textcolor,'img/times.ttf',$projectbank['name']);
		 imagettftext($image,11,0,175,56,$textcolor,'img/times.ttf',$projectbank['inn']."/".$projectbank['kpp']);
		 imagettftext($image,11,0,350,56,$textcolor,'img/times.ttf',$projectbank['account']);
		 imagettftext($image,11,0,175,86,$textcolor,'img/times.ttf',$projectbank['okato']);
		 imagettftext($image,11,0,325,86,$textcolor,'img/times.ttf',$projectbank['bankname']);
		 imagettftext($image,11,0,210,115,$textcolor,'img/times.ttf',$projectbank['bik']);
		 imagettftext($image,11,0,348,115,$textcolor,'img/times.ttf',$projectbank['corraccount']);
		 
		 imagettftext($image,11,0,290,155,$textcolor,'img/times.ttf',"Оплата счета №0000".$proarrid."/0000".USER_ID." от ".date('d/m/Y'));
		 imagettftext($image,11,0,178,172,$textcolor,'img/times.ttf',"по договору №DR000".USER_ID);
		 imagettftext($image,11,0,450,200,$textcolor,'img/times.ttf',$summa);

		 imagettftext($image,11,0,300,280,$textcolor,'img/times.ttf',$projectbank['name']);
		 imagettftext($image,11,0,175,309,$textcolor,'img/times.ttf',$projectbank['inn']."/".$projectbank['kpp']);
		 imagettftext($image,11,0,350,309,$textcolor,'img/times.ttf',$projectbank['account']);
		 imagettftext($image,11,0,175,338,$textcolor,'img/times.ttf',$projectbank['okato']);
		 imagettftext($image,11,0,325,338,$textcolor,'img/times.ttf',$projectbank['bankname']);
		 imagettftext($image,11,0,210,366,$textcolor,'img/times.ttf',$projectbank['bik']);
		 imagettftext($image,11,0,348,366,$textcolor,'img/times.ttf',$projectbank['corraccount']);
		 
		 imagettftext($image,11,0,290,411,$textcolor,'img/times.ttf',"Оплата счета №0000".$proarrid."/0000".USER_ID." от ".date('d/m/Y'));
		 imagettftext($image,11,0,178,428,$textcolor,'img/times.ttf',"по договору №DR000".USER_ID);
		 imagettftext($image,11,0,450,453,$textcolor,'img/times.ttf',$summa);
    }
    ImagePng ($image);  
?>