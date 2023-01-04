<?php
header("Content-type: text/html; charset=utf-8");
include_once("public.function.php");
/**
 *数据列表类
 *用于ajax获取显示的数据列表
 */
class record {
	//message数据列表格式
	public function message_record($result)
	{
		$str ='<tr><td><input type="checkbox" id="check_box" onclick="selectall(\'dataid[]\');"></td><td>标题</td><td>时间</td><td>状态</td></tr>';
		if(is_array($result))
		{
			foreach($result as $v)
			{
				switch($v['message_status'])
				{
					case 0: $status="<span style='color:#f00;'>未读</span>"; break;
					case 1: $status="已读"; break;
				}
				$str .="<tr><td><input type='checkbox' name='dataid[]' value='".$v['id']."' ></td><td><a href='member/msgcontent.php?id=".$v['id']."&ty=0'>".$v['subject']."</a></td><td>".$v['send_time']."</td><td><a href='member/msgcontent.php?id=".$v['id']."&ty=0'>".$status."</a></td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//washcode 数据列表格式
	public function washcode_record($result)
	{
		$str ="<tr><td>游戏项目</td><td>投注量</td><td>返水比率</td><td>返水金额</td><td>返水时间</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$str .="<tr><td>".$v['game_type']."</td><td>".$v['number']."</td><td>".$v['ratio']."%</td><td>".$v['money']."</td><td>".$v['import_date']."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//rescue 自动救援列表
	public function rescue_record($result)
	{
		$str ="<tr><td>存款金额</td><td>取款金额</td><td>账户余额</td><td>救援比例</td><td>救援金额</td><td>救援时间</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$str .="<tr><td>".$v['deposit_money']."</td><td>".$v['debit_money']."</td><td>".$v['balance_money']."</td><td>".$v['ratio_limit']."%</td><td>".$v['rescue_money']."</td><td>".$v['add_time']."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//point 积分兑换列表
	public function point_record($result)
	{
		$str ="<tr><td>兑换积分</td><td>兑换比例</td><td>兑换金额</td><td>兑换时间</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$str .="<tr><td>".$v['account_point']."</td><td>".$v['exchange_rate']."%</td><td>".$v['amount']."</td><td>".$v['apply_time']."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//promotion 优惠列表
	public function promotion_record($result)
	{
		$str ="<tr><td>优惠活动</td><td>存款金额</td><td>优惠金额</td><td>申请时间</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$str .="<tr><td>".$v['promotion_title']."</td><td>".$v['deposit_money']."</td><td>".$v['promotion_money']."</td><td>".$v['add_time']."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//transfer 转账列表
	public function transfer_record($result)
	{
		$str ="<tr><td>转账类型</td><td>转账金额</td><td>转账时间</td><td>状态</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				switch($v['transfer_status'])
				{
					case 0: $status="失败"; break;
					case 1: $status="成功"; break;
					case 2: $status="失败"; break;
				}
				$str .="<tr><td>".$v['game_name']."</td><td>".$v['amount']."</td><td>".$v['add_time']."</td><td>".$status."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//debit 取款列表
	public function debit_record($result)
	{
		$str ="<tr><td>取款银行</td><td>取款金额</td><td>请求时间</td><td>状态</td><td>操作</td><td>备注</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				switch($v['debit_status'])
				{
					case 0: $status="未审核"; break;
					case 1: $status="正在出款"; break;
					case 2: $status="审核失败"; break;
					case 3: $status="出款成功"; break;
				}
				$str .="<tr><td>".$v['bank_name']."</td><td>".$v['amount']."</td><td>".$v['add_time']."</td><td>".$status."</td>";
				if($v['debit_status'] == 0)
				{
					$str .= "<td><a href='javascript:cancel_debit(".$v['id'].")'>取消提款</a></td>";
				}else{
					$str .= "<td> </td>";
				}
				$str .="<td>".$v['remark']."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//deposit 存款列表
	public function deposit_record($result)
	{
		$str ="<tr><td>存款类型</td><td>存款金额</td><td>状态</td><td>时间</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				switch($v['bank_type'])
				{
					case 0: $paytype="工商银行"; break;
					case 1: $paytype="农业银行"; break;
					case 2: $paytype="建设银行"; break;
					case 3: $paytype="招商银行"; break;
					case 4: $paytype="交通银行"; break;
					case 5: $paytype="支付宝"; break;
					case 6: $paytype="财付通"; break;
					case 7: $paytype="微信支付"; break;
					case 100: $paytype="在线支付"; break;
					default:$paytype="银行存款";
				}
				$str .="<tr><td>".$paytype."</td><td>".$v['amount']."</td><td>成功</td><td>".$v['deposit_time']."</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//message content 站内信内容
	public function message_content($result)
	{
		if(is_array($result))
		{
			$str = "<tr><td>标题：".$result['message_title']."</td><td>发送时间：".$result['add_time']."</td></tr>";
			$str .="<tr><td colspan='2' style='text-align:left;'>内容：".$result['message_content']."</td></tr>";
			return $str;
		}else
		{
			return $str = "<tr><td colspan='2' style='text-align:left;'>内容已被删除</td></tr>";
		}
		
	}
	//sysmessage 系统站内信列表
	public function sysmessage_record($result)
	{
		$str ='<tr><td>标题</td><td>时间</td><td>状态</td><td>发信人</td></tr>';
		if(is_array($result))
		{
			foreach($result as $v)
			{	
				switch($v['group_status'])
				{
					case 0: $status="<span style='color:#f00;'>未读</span>"; break;
					case 1: $status="已读"; break;
				}
				$str .="<tr><td><a href='usercenter.php?type=105&ty=1&id=".$v['id']."'>".$v['message_title']."</a></td><td>".$v['add_time']."</td><td>".$status."</td><td>system</td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}
	//notice 公告列表
	public function notice_record($result)
	{
		$str = "<tr><td>ID</td><td>公告内容</td><td>公告时间</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$str .= "<tr><td>".$v['id']."</td><td>".$v['notice_content']."</td><td>".$v['add_time']."</td></tr>";
			}
			return $str;
		}
		
	}
	//onlinepay 在线支付线路信息
	public function onlinepay_record($result)
	{
		$str = "";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				if($v['bank_status'] == 1)
				{
					$status = "btn-success";
					$disabled = "";
					$open = "启用";
				}else{
					$status = "btn-default";
					$disabled = "disabled";
					$open = "关闭";
				}
				$btn_id = "pay_".$v['id']."_".$v['pay_type']."_".$v['line_type'];
				$str .='<li><button id="'.$btn_id.'" type="button" class="btn '.$status.' btn-sm" '.$disabled.'>'.$v['pay_name'].'('.$open.')</button></li>';
			}
		}
		return $str;
	}
	//deposit_bank 存款银行信息
	public function depositbank_record($result)
	{
		$bank_list = "<option value='-1'>请选择你的存款方式</option>";
		$bank_info = "";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$bank_list .="<option value='".$v['id']."'>".$v['bank_name']."-".$v['bank_account']."</option>";
				$bank_info .="<tr><td>".$v['bank_name']."</td><td>".$v['bank_account']."</td><td>".$v['bank_no']."</td></tr>";
			}
			$str = array($bank_list,$bank_info);
			return json_encode($str);
		}
	}
	//deposit_bank_info 存款银行详细信息
	public function depositbankinfo_record($result)
	{
		$bank_info = "";
		if(is_array($result))
		{
			if($result['deposit_type'] == 2)
			{
				//支付宝
				$bank_info .= '<tr><td>存款金额：</td><td><input class="form-control" type="text" name="amount" placeholder="请输入金额" required ></td></tr>';
				$bank_info .= '<tr><td>存款单号：</td><td><input id="other_billno" class="form-control" type="text" name="other_billno" maxlength="6" placeholder="请输入单号后6位" required ></td></tr>';
			}elseif($result['deposit_type'] == 3)
			{
				//财付通
				$bank_info .= '<tr><td>存款金额：</td><td><input class="form-control" type="text" name="amount" placeholder="请输入金额" required ></td></tr>';
				$bank_info .= '<tr><td>存款单号：</td><td><input id="other_billno" class="form-control" type="text" name="other_billno" maxlength="6" placeholder="请输入单号后6位" required ></td></tr>';
			}elseif($result['deposit_type'] == 4)
			{
				//微信支付
			}elseif($result['deposit_type'] == 1){
				//网银支付
				$bank_info .= '<tr><td>存款金额：</td><td><input class="form-control" type="text" name="amount" placeholder="请输入金额" required ></td></tr>';
				$bank_info .= '<tr><td>存款地址：</td><td><input class="form-control" type="text" name="deposit_addr" placeholder="请输入存款银行的地址" ></td></tr>';
				$bank_info .= '<tr><td>存款姓名：</td><td><input class="form-control" type="text" name="member_name" placeholder="请输入存款人的姓名" ></td></tr>';
			}
			$bank_info .= '<tr><td>优惠选择：</td><td><input type="radio" name="autopromo"  value="0" checked>不选择优惠 <br/> <input type="radio" name="autopromo"  value="1" >笔笔存20%优惠<br/> <input type="radio" name="autopromo"  value="2" >每日首存50%优惠</td></tr>';
			$bank_info .= '<tr><td>&nbsp;</td><td><button class="btn btn-primary " type="submit" id="dosubmit" >存款</button></td></tr>';
			return $bank_info;
		}
	}
	//debit_bank_info 会员取款银行详细信息
	public function debitbank_record($result)
	{
		$bank_list = "";
		$debit_bank = "";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$bank_list .="<tr><td>".$v['bank_name']."</td><td>".$v['member_name']."</td><td>".$this->str_mid_replace($v['bank_no'])."</td><td>".$v['bank_addr']."</td><td><a href='javascript:unbind_bank(".$v['id'].")' style='color:#00f' >解绑</a></td></tr>";
				$debit_bank .="<option value='".$v['id']."'>".$v['bank_name']."-".$v['member_name']."-".substr($v['bank_no'],-4)."</option>";
			}
			$str = array($bank_list,$debit_bank);
			return json_encode($str);
		}
	}
	//transfer转账列表
	public function transferlist_record($result)
	{
		$str = "<option value=''>请选择转账类型</option>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				$str .= "<option value='".$v['game_id']."'>".$v['game_name']."</option>";
			}
			
		}else{
			$str = "<option value=''>游戏平台维护</option>";
		}
		return $str;
	}
	//会员自助申请优惠 信息
	public function promoapply_record($result)
	{
		$str ="<tr><td>优惠标题</td><td>流水倍数</td><td>优惠类别</td><td>结束时间</td><td>操作</td></tr>";
		if(is_array($result))
		{
			foreach($result as $v)
			{
				switch($v['game_id'])
				{
					case 1001: $game_id="PT老虎机"; break;
					case 1002: $game_id="MG老虎机"; break;
					default:$game_id="全部游戏";
				}
				$str .="<tr><td>".$v['promotion_title']."</td><td>".$v['promotion_multiple']."</td><td>".$game_id."</td><td>".$v['end_time']."</td><td><a href='javascript:apply(".$v['id'].")'>申请</a></td></tr>";
			}
			return $str;
		}else{
			return $str;
		}
	}








 //替换中间字符
 public function str_mid_replace($string,$left=4,$right=4,$type="*")
 {
 	$length = strlen($string);
 	$left_str = substr($string,0,$left);
 	$right_str = substr($string,-1*($right));
 	$mid_str = "";
 	for($i=0;$i<($length-$left-$right);$i++)
 	{
 	$mid_str .= $type;
 	}
 	$str = $left_str.$mid_str.$right_str;
 	return $str;
 }

}
?>
