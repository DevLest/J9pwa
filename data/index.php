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
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_MEMBER/01.webp",
					"imgClickUrl":"111"
				},
	                    {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_MEMBER/02.webp",
					"imgClickUrl":"113"
				},
	           
	            {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_MEMBER/03.webp",
					"imgClickUrl":"114"
				},
	           
	            {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_MEMBER/04.webp",
					"imgClickUrl":"124"
				}
			]
		
		}
	}';
}
else {
	echo 
	'
	{
		"status": 1,
	    "info": {
			"banner":[
	                  {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_GUEST/01.webp",
					"imgClickUrl":"111"
				},
	                    {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_GUEST/02.webp",
					"imgClickUrl":"113"
				},
	           
	            {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_GUEST/03.webp",
					"imgClickUrl":"114"
				},
	           
	            {
					"imgUrl":"https://999j9azx.999game.online/j9pwa/images/MOBILE_BANNERS_GUEST/04.webp",
					"imgClickUrl":"124"
				}
			]
		
		}
	}';
}