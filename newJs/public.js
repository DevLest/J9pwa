//判断是否可以访问
/*$.getJSON('/ajax_data.php',{'type':'get_address'},function(data){
		if(data.code != 1){
			window.location.href='/ip_block.html#'+data.ip;
		}
});*/
$(function(){
	//元旦红包
	$.getJSON("ajax_data.php",{type:"washcodeself_list"},function(data){
		if(data != null){
			//测试数据
			//data.info.gid1903 = parseInt(Math.random()*100)+1;
			if(data.info.gid1903 > 0){
				parent.showRedBag(data.info.gid1903);
			}
		}
	}); 
});
//查询余额
function get_balance(gameid)
{
	var balaid = $("#balance"+gameid);
	if(gameid==0){
		balaid.html('<i class="fa fa-spinner fa-spin"></i>');
	}else{
		balaid.html('<i class="fa fa-spinner fa-spin fa-load"></i>');
	}
	balaid.siblings().find("i").hide();
	//this.childNodes[0].addClass("fa-spin");
	$.get("ajax_data.php",{type: "get_balance",gameid:gameid},function(data){
			$("#balance"+gameid).html(data);
			balaid.siblings().find("i").show();
	});
}
get_balance(0);

	$(document).ready(function(){
		var timeStamp = new Date().getTime();
		var startTime = "2017-08-26 00:00:00";
		var timestamp1 = Date.parse(new Date(startTime));
		var endTime = "2017-08-28 23:59:59";
		var timestamp2 = Date.parse(new Date(endTime));
		if(timeStamp > timestamp1 && timeStamp < timestamp2){
			$('body').append('<div class="zongzi_dwin animated" id="zongzi_dwin"><a href="7x_day.html" target="_blank" class="goto7x"></a></div>');
			$('#zongzi_dwin').click(function(){
				$(this).addClass("bounceOutUp");
			});
		}
		
	});
//获取公告
$.get("ajax_data.php",{type:'get_notice',num:'1'},function(data){
		$("#notice_content").html(data);	
	});
//关闭弹窗
$('.md-close').click(function(){
		$(".md-modal").removeClass("md-show");
		$(".cover").removeClass("blur-in");
	});
//获取优惠
	function getpromotion(){
		$('.radio-group').html('优惠加载中...');
		$.ajax({
			url:"action.php?code="+Math.random(),
			type:"POST",
			data:{
				act:"getPromotion"
			},
			dataType:'json',
			timeout: 10000,
			success: function(data){
				if(data.status == true){
					$('.radio-group').html(data.data);
				}
				$("input[name=autopromo]").change(function(){
					var status = $(this).val();
					if(status != 0){
					   zdalert('系统提示','点击申请优惠视同已了解优惠条款，详见优惠细则');
					}				
				});
			}
		})			
	}
//数据分页函数
function data_page(record_type,page)
{
	$("#"+record_type+"_table").html('<span><i class="fa fa-spinner fa-spin"></i>数据正在加载中</span>');
	$.ajax({
		type: "GET",
		url:"ajax_data.php",
		data:"type=record_list&record_type="+record_type+"&page="+page,
		success:function(msg){
			$("#"+record_type+"_table").html(msg);
		}
	});
}
//获取数据分页的总页数
function count_page(record_type)
{
	$.ajax({
		type: "GET",
		url:"ajax_data.php",
		data:"type=count_record&record_type="+record_type,
		success:function(msg){
			var options = {
					bootstrapMajorVersion:3,
					currentPage: 1,//当前页面
					numberOfPages: 5,//一页显示几个按钮（在ul里面生成5个li）
					totalPages:msg, //总页数
					size:"small",
					onPageClicked:function (e, originalEvent, type, page) {  
						data_page(record_type,page); 
					}
				};
		   $('#record_pagination').bootstrapPaginator(options);
		}
	});
}
//通过id对批量数据进行删除
function delete_all(delete_type)
{
	var ids = '';
	$("input[name='dataid[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids == '')
	{
		//alert('请选择要删除的数据');
		//return false;
		confirm_z(1,'请先选择要删除的信息');
	}else{
		$(".bs-example-modal-sm").modal({backdrop:"static",keyboard:false});
		$.ajax({
			type: "GET",
			url:"ajax_data.php",
			data:"type="+delete_type+"&ids="+ids,
			success:function(msg){
				//alert(msg);
				//window.location.href='member/message.php';
				data_page("message",1); //获取站内信第一页的数据
				confirm_z(1,msg);
			}
		});
	}
}

//Opens or Closes the second menu
$(".second_a").click(function(){
	$(this).next().fadeToggle();
});
//错误提示
function check_error(obj,content)
{
	$("#"+obj).addClass("tips_error");
	$("#"+obj).text(content);
}
function check_ok(obj)
{
	$("#"+obj).removeClass("tips_error");
	$("#"+obj).text('');
}	
//live800
function cs() {
	var useraccount = getCookie('account');
	var member_name = getCookie('member_name');
	var infoValue = 'userId%3D'+useraccount+"%26name%3D"+useraccount+"/"+member_name;
    var cs = window.open("https://live836.hozpez.com/chat/chatClient/chatbox.jsp?companyID=262800&configID=68306&jid=6552507733&s=1&enterurl=yuebetapp&info="+infoValue, "livechat", "height=430, width=550, toolbar= no,directions=no,alwaysRaised=yes,hotkeys =yes, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=100,left=300");
    cs.focus();
}
//获取未读的站内消息数
function noread_message()
{
	$.ajax({
		type: "GET",
		url:"ajax_data.php",
		data:"type=noread_message",
		success:function(msg){
			$("#countmsg").html(msg);
		}
	});
}
//noread_message();

//读取 cookie
function getCookie(name){
	var strCookie=document.cookie;
	var arrCookie=strCookie.split("; ");
	for(var i=0;i<arrCookie.length;i++){
		var arr=arrCookie[i].split("=");
		if(arr[0]==name)return unescape(arr[1]);
	}
	return "";
}

//登出
function log_out(){
	confirm_z(2,'确定要登出吗?');
	$('.md-jugde').click(function(){
		$(".md-modal").removeClass("md-show");
		$(".cover").removeClass("blur-in");
		$(".md-jugde").hide();
		if($(this).index() == 1){
			window.location = 'logout.php'
		}
	});
}
	//开红包
	function openRedBag(){
		$('.newYearBag').remove();
		$('.newYearBag_after').fadeIn();
	}
	//生成红包
	function showRedBag(amount){
		$('body').append('<div class="newYear fx">\
			<div class="newYearBag zoomInDown" style="display:block">\
				<div class="ny_open flipx" onclick="openRedBag()"></div>\
			</div>\
			<div class="newYearBag_after scalex" style="display:none">\
				<div class="bonue_num">'+amount+'</div>\
				<div class="bonue_text">一元复始，万象更新。恭祝怡宝全体会员2018新年元旦快乐，感谢您的信任与支持，新年元旦礼金已经派发到你的账号，请点击领取。</div>\
				<div class="btn btnFlag" onclick="getRedBag()"></div>\
			</div>\
		</div>');
	}
	//领取红包
	function getRedBag(){
		if($('.btn').hasClass('btnFlag')){
			$('.btn').removeClass('btnFlag');
			confirm_z(0,'<div class="loding"><i class="fa fa-spinner fa-spin"></i>系统处理中，请稍后</div>');
			$.getJSON("ajax_data.php",{type:"washcodeself_receive",id:'gid1903'},function(data){
					$('.newYear').remove();
					confirm_z(1,data.info);
			}); 
		}
	}
	
	
	(function($) {  
       
    $.alerts = {         
        alert: function(title, message, callback) {  
            if( title == null ) title = 'Alert';  
            $.alerts._show(title, message, null, 'alert', function(result) {  
                if( callback ) callback(result);  
            });  
        },  
           
        confirm: function(title, message, callback) {  
            if( title == null ) title = 'Confirm';  
            $.alerts._show(title, message, null, 'confirm', function(result) {  
                if( callback ) callback(result);  
            });  
        },
		waiting: function(title, message, callback) {  
            if( title == null ) title = 'waiting';  
            $.alerts._show(title, message, null, 'waiting', function(result) {  
                if( callback ) callback(result);  
            });  
        },
		login: function(title, message, callback) {  
            if( title == null ) title = 'login';  
            $.alerts._show(title, message, null, 'login', function(result) {  
                if( callback ) callback(result);  
            });  
        },
          
        _show: function(title, msg, value, type, callback) {  
					$.alerts._hide(); 
                    var _html = "";  
					if(type =="waiting"){
						_html +='<div id="mb_box"><div id="mb_con" class="mb_con_w"><img src="images/public/loading.gif"/></br><span>处理中</span></div></div>';
					}else{
						if (type == "alert") { 
						  _html += '<div id="mb_box"><div id="mb_con" class="mb_con animated zoomIn"><span id="mb_tit">' + title + '</span>';  
						  _html += '<div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';  
						  _html += '<div id="mob_btn_ok">我知道了</div>';  
						}  
						if (type == "confirm") {
						  _html += '<div id="mb_box"><div id="mb_con" class="mb_con animated shake"><span id="mb_tit">' + title + '</span>';  
						  _html += '<div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';  
						  _html += '<div id="mb_btn_ok" class="mob_btn">确定</div>'; 
						  _html += '<div id="mb_btn_no" class="mob_btn">取消</div>';  
						  
						}
						if(type == 'login'){
							 _html += '<div id="mb_box"><div id="mb_con" class="mb_con animated shake"><span id="mb_tit">请先登录</span>';  
							 _html += '<div id="mb_msg">进行下一步操作前，请先登录您的游戏账号！</div><div id="mb_btnbox">';  
							 _html += '<div id="mb_btn_ok" class="mob_btn">去登录</div>'; 
						     _html += '<div id="mb_btn_no" class="mob_btn">回首页</div>';  
						}
						_html += '</div></div></div>';  
					}
                    //必须先将_html添加到body，再设置Css样式  
                    $("body").append(_html);
           
            switch( type ) {  
                case 'alert':  
          
                    $("#mob_btn_ok").click( function() {  
                        $.alerts._hide();  
                        callback(true);  
                    });  
                    $("#mob_btn_ok").focus().keypress( function(e) {  
                        if( e.keyCode == 13 || e.keyCode == 27 ) $("#mb_btn_ok").trigger('click');  
                    });  
                break;  
                case 'confirm':  
                     
                    $("#mb_btn_ok").click( function() {  
                        $.alerts._hide();  
                        if( callback ) callback(true);  
                    });  
                    $("#mb_btn_no").click( function() {  
                        $.alerts._hide();  
                        if( callback ) callback(false);  
                    });  
                    $("#mb_btn_no").focus();  
                    $("#mb_btn_ok, #mb_btn_no").keypress( function(e) {  
                        if( e.keyCode == 13 ) $("#mb_btn_ok").trigger('click');  
                        if( e.keyCode == 27 ) $("#mb_btn_no").trigger('click');  
                    }); 
				break;  
				case 'login':  
                     
                    $("#mb_btn_ok").click( function() {  
                        $.alerts._hide();  
                        if( callback ) callback(true);  
                    });  
                    $("#mb_btn_no").click( function() {  
                        $.alerts._hide();  
                        if( callback ) callback(false);  
                    });  
                    $("#mb_btn_no").focus();  
                    $("#mb_btn_ok, #mb_btn_no").keypress( function(e) {  
                        if( e.keyCode == 13 ) $("#mb_btn_ok").trigger('click');  
                        if( e.keyCode == 27 ) $("#mb_btn_no").trigger('click');  
                    });
                break;  
                
                 
            }  
        },  
        _hide: function() {  
             $("#mb_box,#mb_con").remove();  
        }  
    }  
    // Shortuct functions  
    zdalert = function(title, message, callback) {  
        $.alerts.alert(title, message, callback);  
    }  
       
    zdconfirm = function(title, message, callback) {  
        $.alerts.confirm(title, message, callback);  
    };  
    
	zdwaiting = function(title, message, callback) {  
        $.alerts.waiting(title, message, callback);  
    };
	zdlogin = function(title, message, callback) {  
        $.alerts.login(title, message, callback);  
    };
})(jQuery);  