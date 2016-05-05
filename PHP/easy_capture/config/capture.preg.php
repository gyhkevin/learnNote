<?php
return array(
	'/<link(.*?)href=[\'|"]\s*\/(.*?)[\'|"](.*?)\/?>/i' =>	'<link$1href="_request_site$2" $3/>',
	
	'/<a(.*?)href=[\'|"]\s*\/(.*?)[\'|"](.*?)>/i' => '<a$1href="_request_site$2" $3>',
	
	'/<img(.*?)src=\s*\/(.*?)\s+(.*?)\/?>/' => '<img$1src="/$2" $3 />',
	
	'/<img(.*?)src=[\'|"]([^\/(?:http\:\/\/)].*?)[\'|"](.*?)\/?>/' => '<img$1src="/$2" $3 />',
	
	'/<img(.*?)src=[\'|"]\s*\/(.*?)[\'|"]\s+?(.*?)\/?>/i' => '<img$1src="_request_site$2" $4 />',
	
	'/<img(.*?)src=\s*\/(.*?)[^\s]\/?>/' => '<img$1src="_request_site$2"/>',
	
	'/<script(.*?)src=[\'|"]\s*([^\/(?:http\:\/\/)].*?)[\'|"](.*?)>/i' => '<script$1src="/$2" $3>',
	
	'/<script(.*?)src=[\'|"]\s*\/(.*?)[\'|"](.*?)>/i' => '<script$1src="_request_site$2" $3>',
	
	'/<form(.*?)action=[\'|"]\s*\/(.*?)[\'|"](.*?)>/i' => '<form$1action="_request_site$2" $3>',

	'/<embed(.*?)src=[\'|"]\s*([^\/].*?)[\'|"](.*?)\/?>/'	=> '<embed$1src="_request_site$2" $3>'
); 
?>