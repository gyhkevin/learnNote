<?php
set_time_limit(0);
require dirname(__FILE__).DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'Capture.const.php';
require __Home__.'include'.__Os__.'Capture.class.php';

$_cfg = array(
	'site' => __Home__.'config'.__Os__.'capture.site.php',
	'preg' => __Home__.'config'.__Os__.'capture.preg.php',
	'accompImg' => __Home__.'cache'.__Os__.'accompImg',
	'overURL'   => __Home__.'cache'.__Os__.'overURL'
);

$_parse = new Capture( $_cfg );
$_parse->parseQuestUrl();

?>
