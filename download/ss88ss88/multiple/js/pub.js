 var mySwiper = new Swiper('.swiper-container', {
      loop: true,
      autoplay: 5000,
      effect: 'fade',
      autoplayDisableOnInteraction: false,
      // 如果需要前进后退按钮
      nextButton: '.swiper-button-next',
      prevButton: '.swiper-button-prev',
    })
    function is_weixin() {
      var ua = navigator.userAgent.toLowerCase();
      if (ua.indexOf("micromessenger") > 0) {
        document.getElementById("mask").style.display = "block";
      }
    }
    window.onload = function () {
      is_weixin();
      document.documentElement.style.height = window.innerHeight + 'px';
      var sUserAgent = navigator.userAgent.toLowerCase();
      var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
      var bIsAndroid = sUserAgent.match(/android/i) == "android";
      // if (!bIsIphoneOs && !bIsAndroid) {
      //   window.location = 'http://qq365go.com/cnhk8/plugin.php?id=s8_app:intro'
      // }
      if (bIsAndroid) {
        $('body').addClass('Android');
		$('#androidOnly').show();
        $('.AndroidtwoBtn').show()
      } else {
        $('body').addClass('ios')
        $('.iostwoBtn').show()
		$('#androidOnly').hide();
        $('.iosdestail').append(
          '<img src="multiple/images/ios_04.png" alt="">' +
          '<img src="multiple/images/ios_05.jpg" alt="">' +
          '<img src="multiple/images/ios_06.jpg" alt="">' +
          '<img src="multiple/images/ios_07.jpg" alt="">' +
          '<img src="multiple/images/ios_08.jpg" alt="">'
        )
      }
    }
    function getQueryString(name) {
      var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
      var r = window.location.search.substr(1).match(reg);
      if (r != null) return unescape(r[2]); return null;
    }
    function opento() {
      var siteid = getQueryString('siteid') ? getQueryString('siteid') : ''
      var category = getQueryString('category') ? getQueryString('category') : ''
      var cc = getQueryString('cc') ? getQueryString('cc') : ''
      var sys = getQueryString('sys') ? getQueryString('sys') : ''
      if (siteid || category || cc || sys) {
        window.open('https://m.yuebet100.com/' + category + '&pcc=' + cc + '&psys=' + sys + '&psiteid=' + siteid, '_blank')
      } else {
        window.open('https://m.yuebet100.com/', '_blank')
      }
    }