<?php
	$frd = '';
	if(isset($_GET['act']) && $_GET['act'] != ''){
		$frd = $_GET['act'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="cache-control" content="no-cache" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<script>
		document.write('<meta name="viewport" content="width=' + (1.5755 / (document.documentElement.clientHeight / document.documentElement.clientWidth) * 640) + ', user-scalable=no, target-densitydpi=device-dpi">');
	</script>
	<script src="multiple/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="multiple/js/swiper-3.4.2.jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="multiple/css/swiper-3.4.2.min.css">
	<link rel="stylesheet" type="text/css" href="multiple/css/index.css">
	<title>怡宝国际-梦开始的地方</title>
	<style>
	.open{margin-top:35px;}
	.open h4{ font-size:34px; margin-bottom:30px;color:#fc622a;letter-spacing: 0.15em; font-weight: normal;}
	.open a{ display:inline-block; width:57%; max-width:362px; height:60px; line-height:60px; text-decoration:none; font-size:24px;  border-radius:60px; background:#fc632a; color:#FFF;}
	.wrap{ /*background:#dfe2e5;*/
/*	
background:#e8dfdb; 
background: -webkit-linear-gradient(to top, #f6f6f6,#d5c2b8);  
background: linear-gradient(to top, #f6f6f6,#d5c2b8); 
*/	
	}
	</style>
</head>
<body class="main">
	<div id="mask" style="display: none;">
		<img src="multiple/images/hc_mask.png" width="98%">
	</div>
		<div class="wrap">
			<div class="title">
				<img src="multiple/images/background_title/yblogo.png" alt="">
				<p style="letter-spacing:0.9em; text-indent:0.9em;">梦开始的地方</p>
			</div>
			<div class="shell">
				<div class="mobile">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<img src="multiple/images/platform/CFF/CFF_01.jpg" alt="">
							</div>
		
							<div class="swiper-slide">
								<img src="multiple/images/platform/CFF/CFF_02.jpg" alt="">
							</div>
		
							<div class="swiper-slide">
								<img src="multiple/images/platform/CFF/CFF_03.jpg" alt="">
							</div>
							<div class="swiper-slide">
								<img src="multiple/images/platform/CFF/CFF_04.jpg" alt="">
							</div>
							<div class="swiper-slide">
								<img src="multiple/images/platform/CFF/CFF_05.jpg" alt="">
							</div>
		
							<div class="swiper-slide">
								<img src="multiple/images/platform/CFF/CFF_06.jpg" alt="">
							</div>
						</div>
					<!-- 如果需要导航按钮 -->
					<!-- <div class="swiper-button-prev"></div> -->
					<!-- <div class="swiper-button-next"></div> -->
					</div>
				</div>
			</div>
		</div>
	
	<div class="destail">
		<div class="downBtn">
			    <img src="multiple/images/ty_02.png" alt="" class="ty_02">
				<div class="open">
				    <h4>欢迎光临访问</h4>
					<a href="https://m.yuebet100.com/agent.php?act=<?php echo $frd ?>">马上进入</a>
					<!--<img src="multiple/images/ty_03.png" alt="访问wap版" class="towap">-->
				</div>
		</div>
	</div>
	<script src="multiple/js/pub.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>