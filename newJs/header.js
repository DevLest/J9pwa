var click_times=0;
$('.menu>a').on('click', function() {
	click_times++;
  $('.wrapper').toggleClass('active');
  winWidth = window.innerWidth;
  if(winWidth<=480 && click_times%2==1)
  {
  	$(".word1").css("display","none");
    $(".tab-nav a").css("font-size","12px");
    $(".word2").css("font-size","12px");
    $(".platform td i").css("display","none");
    $(".user_money_box").css("display","none");
    $(".menu").css("width","55%");
    $(".user_box").css("width","55%");
    $(".top_back").css("display","none");
    //$(".promotions_title_box img").css("width","36px");
    $(".word12px").css("display","none");
    $(".promotions_title ").css("margin-left","5px").css("margin-top","0px");
  }
  else
  {
  	$(".word1").css("display","inline-block");
    $(".tab-nav a").css("font-size","14px");
    $(".word2").css("font-size","14px");
    $(".platform td i").css("display","inline-block");
    $(".user_money_box").css("display","inline-block");
    $(".menu").css("width","100%");
    $(".user_box").css("width","100%");
    $(".top_back").css("display","inline-block");
    //$(".promotions_title_box img").css("width","60px");
    $(".word12px").css("display","inline-block");
    $(".promotions_title ").css("margin-left","10px").css("margin-top","5px");
  }

});

//个人中心下拉
$("#userTopTitle,#mask").on('click', function () {
	if ($("#downmenu").css('display') == "none") {
		$("#downmenu").slideDown(200);
		$("#mask").fadeIn(200);
		$('#userTopTitle').children("i").addClass('triggered')
	} else {
		$("#downmenu").slideUp(200);
		$("#mask").fadeOut(200);
		$('#userTopTitle').children("i").removeClass('triggered')
	}
})