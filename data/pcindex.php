<?php
header("Access-Control-Allow-Origin: *");
$data = (Object) $_POST;

if(isset($data->username_email)){
	echo 
	'
	{
		"status": 1,
	    "info": {
			"banner":[
	                  {
					"imgUrl":"https://999j9azx.u2d8899.com/j9pwa/images/DESKTOP_BANNERS/01.webp",
					"imgClickUrl":"111"
				},
	                    {
					"imgUrl":"https://999j9azx.u2d8899.com/j9pwa/images/DESKTOP_BANNERS/02.webp",
					"imgClickUrl":"113"
				},
	           
	            {
					"imgUrl":"https://999j9azx.u2d8899.com/j9pwa/images/DESKTOP_BANNERS/03.webp",
					"imgClickUrl":"114"
				},
	           
	            {
					"imgUrl":"https://999j9azx.u2d8899.com/j9pwa/images/DESKTOP_BANNERS/04.webp",
					"imgClickUrl":"124"
				}
			]
		
		}
	}';
}