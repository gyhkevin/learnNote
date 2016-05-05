<?php
return array(
	/* The domain name of website */
	'domain_name'  		 => 'feelingzone.lofter.com',
	/**
	 * request_site: The root directory of website
	 * 		must end with '/'
	 */
	'request_site' 		 => 'http://feelingzone.lofter.com/',
	'request_url'  		 => 'http://feelingzone.lofter.com/',
	'accept_type' 		 => array( 'gif', 'bmp', 'png', 'ico',  'jpg', 'jpeg' ),
	'save_path' 		 => 'savefiles/',
	'partition_name'	 => 'Img_',
	'dir_file_limit'	 =>	100,
	'serialize_img_size' =>  30,
	/* serialize_img_size : serialize_url_size = 3 : 2 */
	'serialize_url_size' =>  10,
	/** 
	 * The approximate time(second) of saving an image, 
	 * 		avoiding to handle I/O constantly
	 */
	'save_img_interval' => 1,
	/** 
	 * The approximate time(second) of carrying out a page,
	 * 		this option is insignificant 
	 */
	'preform_page_time'	=> 0,
);
?>
