<?php

include 'imgCatcher.class.php';
header('content-type:text/html;charset=utf-8');
$url = "http://shorthairgirls.lofter.com/?page=";
//$url = 'http://wanimal1983.tumblr.com/page/';
$url_arr = array();
$pattern = '/<img.*?src\=\"(.*\.(jpg)).*?>/';

for ($i=0; $i < 3; $i++) { 
	$url = $url.$i;
	$catcher = new imgCatcher($url,'image','jpg');
	$catcher->get_pic($pattern);
}


?>