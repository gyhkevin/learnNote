<?php
if( ! defined('__Os__') ) {
	define( '__Os__', DIRECTORY_SEPARATOR );
}
if( ! defined('__Home__') )  {
	define( '__Home__', dirname( dirname( __FILE__) ).__Os__ );
}
?>