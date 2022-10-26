			/*飘红包js
				前台页面需加上<ul class="couten" style="height: 948px;"></ul>
			 */
		function cake_rain(){
				//$(document).ready(function() {
				$('body').append('<ul class="couten"></ul>');
				var win = (parseInt($(".couten").css("width"))) - 60;
				$(".mo").css("height", $(document).height());
				$(".couten").css("height", $(document).height());
				$(".backward").css("height", $(document).height());
				$("li").css({});
				// 点击确认的时候关闭模态层
				$(".sen a").click(function(){
				  $(".mo").css("display", "none")
				});
				
				var del = function(){
					nums++;
					$(".li" + nums).remove();
					setTimeout(del,30)
				}
				
				var add = function() {
					var hb = parseInt(Math.random() * (5 - 1) + 1);
					var Wh = parseInt(Math.random() * (70 - 30) + 20);
					var Left = parseInt(Math.random() * (win - 0) + 0);
					var rot = (parseInt(Math.random() * (45 - (-45)) - 45)) + "deg";
					//				console.log(rot)
					num++;
					$(".couten").append("<li class='li" + num + "' ><a href='javascript:;'><img src='images/zongzi2018/z" + hb + ".png'></a></li>");
					$(".li" + num).css({
						"left": Left,
					});
					$(".li" + num + " a img").css({
						"width": Wh,
						"transform": "rotate(" + rot + ")",
						"-webkit-transform": "rotate(" + rot + ")",
						"-ms-transform": "rotate(" + rot + ")", /* Internet Explorer */
						"-moz-transform": "rotate(" + rot + ")", /* Firefox */
						"-webkit-transform": "rotate(" + rot + ")",/* Safari 和 Chrome */
						"-o-transform": "rotate(" + rot + ")" /* Opera */
					});	
					$(".li" + num).animate({'top':$(window).height()+20},5000,function(){
						//删掉已经显示的红包
						this.remove()
					});
					//点击红包的时候弹出模态层
					$(".li" + num).click(function(){
						$(".mo").css("display", "block")
					});
					setTimeout(add,100)
				}	
				
				//增加红包
				var num = 0;
				setTimeout(add,100);
			
			//})
		}