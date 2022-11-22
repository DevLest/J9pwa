<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */
set_time_limit(0);
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
define("ACTI","http://adminu2dnewonesite.32sun.com/phprpc/activity.php");
define('QUERY_URL','http://adminu2dnewonesite.32sun.com/phprpc/mysql_query.php');
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}
class ajax_data
{
	private $_response=array(
        'status'=>false,
        'message'=>'',
        'data'=>''
    );
	private function sysmessage($data){
		
		$output = [
			'status' => ($data['status']) ? 1 : 0,
			'info' => $data['message'],
			'data' => $data['data']
		];

		if ($data['data'] == "") unset($output['data']);
        echo json_encode($output);
        exit;
    }
	/*
     * 检测session  参数为空类外部检测，1 类内部检测
     * */
    public function checkSession($type){
        if($type){
            if(!isset($_SESSION['account']) || $_SESSION['account']==''){
                $this->_response['message']='Your login has expired, please login again';
                $this->sysmessage($this->_response);
            }
        }else{
            if(!isset($_SESSION['account']) || $_SESSION['account']==''){
                $this->_response['message']='Your login has expired, please login again';
            }else{
                $this->_response['status']=true;
            }
            $this->sysmessage($this->_response);
        }
    }
	/*
	 *申Por favor好友推荐礼金
	 *20180406-by Zoee
	 */
	public function applyFriendsPro($post){
		$this->checkSession(1);
		$starttime = $post['regTime'];
		$endtime = date('Y-m-d H:i:s',strtotime("+10 day",strtotime($starttime)));
		$arr = array($starttime,$endtime);
		$applymoney = 158;//推荐所得金额
		$deposit = $this->get_total_deposit($post['acc'],$arr);
		if($deposit >= 500){
			$client = new PHPRPC_Client(ACTI);
			$info = $client->check_invite($_SESSION['account'],$post['acc']);
			if($info == -1){
				$result = $client->invite_add($_SESSION['account'],$post['acc'],$applymoney);
				if($result == 1){
					$this->_response['message'] = 'The request has been submitted and we will review it for you within 24 hours.';
					$this->_response['status'] = true;
				}else{
					$this->_response['message'] = 'Sending the request failed, please try again later...';
				}
			}elseif($info == 0){
				$this->_response['message'] = 'Please be patient, your referral bonus request is now under review.';
			}else{
				$this->_response['message'] = 'Please do not reapply, your credit reference has been reviewed.';
			}
			$this->sysmessage($this->_response);
		}else{
			$this->_response['message'] = "The request could not be sent, your friend's deposit is less than 500 USDT within ten days after registration";
			$this->sysmessage($this->_response);
		}
	}
	
	//统计总存款
	function get_total_deposit($account,$arr="")
	{
	    if(is_array($arr))
	    {
	        $starttime = $arr[0];
	        $endtime = $arr[1];
	    }else{
	        $starttime = date("Y-m-d");
	        $endtime = date("Y-m-d H:i:s");
	    }
        $core = new core();
        $total_deposit = $core->get_total_deposit($account,$starttime,$endtime);
        if($total_deposit == "")
        {
            $total_deposit = 0;
        }
        return $total_deposit;	     
	}
	
	//SG返水礼金申Por favor 20180502
	//1.查询礼金是否可领取 2.领取礼金到主账户，3.转账到对应平台
	function apply_bet_bonus(){
		$this->checkSession(1);
		$account = $_SESSION['account'];
		
		include_once ("common/cache_file.class.php");
			//获取缓存数据
		$limit_check = 0;
		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','transfer_limit');
		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','transfer_limit');
			$limit_check = 1;
		}else{
			if((time() - $data_list['limit_time']) >=60){
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','transfer_limit');
				$limit_check = 1;
			}
		}
		if($limit_check == 0)
		{	
			$left_time = 60 - (time() - $data_list['limit_time']);
			$this->_response['message'] = "Please ".$left_time." Get the discount in seconds";
			$this->sysmessage($this->_response);
		}
		
		$core = new core();
		//金额
		$amount_list = $core->washcodeself_list($account);
		$amount = $amount_list['gid1902'];
		if($amount > 0){
			$result = $core->washcodeself_receive($account,'gid1902',$ips);
			if($result == 1902){
				$this->_response['message'] = 'Gift amount is 0 and cannot be claimed';
			}else if($result == 2001){
				$this->_response['message'] = 'The status is incorrect, please update and click to receive';
			}else if($result == 1){
				//平台
				$gameid = '121001';//SG
				$re = $core->transfer($account,$amount,$gameid);
				if($re == 1)
				{	
					$this->_response['message'] = 'Received successfully'.$amount.' USDT and has been transferred to the SG platform for you!';
				}else{
					$info = $core->gameapi_active($account,'1210');
					$this->_response['message'] = 'Received successfully'.$amount.' USDT, the gift money is only for gambling on SG platform, please transfer the gift money to SG platform for gambling.';
				}
				$this->_response['status'] = true;
			}
		}else{
			$this->_response['message'] = 'Gift amount is 0 and cannot be claimed';
		}
	    $this->sysmessage($this->_response);
	}
	
	//获取端午接口记录
	/*
		type=1,返回礼金type
		type=2,返回完整记录
	*/
	function get_sgpro_record($post){
		$this->checkSession(1);
		$account = $_SESSION['account'];
		
		$startTime = date("Y-m-d");
		$endTime = date("Y-m-d H:i:s");
		$client = new PHPRPC_Client(QUERY_URL);
		$option = array(
			"table"=>"ks_special_duanwu",
			"fields"=>"amount,prize_type,add_time",
			"condition"=>"account='".$account."' and add_time between '".$startTime."' and '".$endTime."'",
			"order"=>"id desc",
		);
		$option = serialize($option);
		$result = $client->select($option);
		$result = unserialize($result);
			
		if($post['type'] == 1){
			$bonus_type = array('status'=>false,'data'=>'');
			if(count($result) > 0){
				$bonus_type['status'] = true;
				foreach($result as $r){
					$bonus_type['data'][] = $r['prize_type'];
				}
			}
			return $bonus_type;
		}else{
			$str = '<tr><td>Receive Quantity(USDT)</td><td>Pick up time</td></tr>';
			if(count($result) > 0){
				foreach($result as $r){
					$bonus_type['data'][] = $r['prize_type'];
					$str .= '<tr><td>'.$r['amount'].'</td><td>'.$r['add_time'].'</td></tr>';
				}
			}else{
				$str .= '<tr><td colspan="2">No registration for today</td></tr>';
			}
			$this->_response['status'] = true;
			$this->_response['data'] = $str;
			$this->sysmessage($this->_response);
		}
		
	}
	
	//获取sg活动领取状态列表
	/*
		1.查询今日存款，得到礼金可领取列表
		2.查询今日领取记录，得到礼金状态列表
	*/
	function get_sgpro_status(){
		$account = $_SESSION['account'];
		$starttime_pro_date = '2018-05-08 12:00:00';
		$starttime_pro = strtotime($starttime_pro_date);
		$endtime_pro = strtotime('2018-05-31 23:59:59');
		if($starttime_pro > time() && $account != 'ybtest01'){
			$this->_response['message'] = 'The SG Slots Summer Amazing Event Bonus will be'.$starttime_pro_date.' Open for pick up. In';
		}elseif($endtime_pro < time() && $account != 'ybtest01'){
			$this->_response['message'] = 'SG Slots Summer Dreadful Event ha terminado';
		}else{
			//初始化值
			$deposit_level = array(10,1000,3000,10000);//存款等级
			$bonus_status = array(0,0,0,0);//0,未满足、1,可领取、2,已领取

			$total_deposit = $this->get_total_deposit($account);
			$i = 0;
			foreach($deposit_level as $d){
				if($total_deposit >= $d){
					$bonus_status[$i] = 1;
					$i++;
				}else{
					break;
				}
			}
			$type = array('type'=>1);
			$record = $this->get_sgpro_record($type);
			if($record['status']){
				foreach($record['data'] as $v){
					$bonus_status[$v] = 2;
				}
			}
			$this->_response['status'] = true;
			$this->_response['data']['total_deposit'] = $total_deposit;
			$this->_response['data']['bonus_status'] = $bonus_status;
		}
		$this->sysmessage($this->_response);
	}
	
	//申Por favorSG存送活动
	/*
		参数：领取类型
		查询今日总存款是否满足
		是否已经领取
		领取后转账到sg平台
	*/
	function apply_sgxrkw_pro($post){
		$this->checkSession(1);
		$account = $_SESSION['account'];
		$starttime_pro_date = '2018-05-08 12:00:00';
		$starttime_pro = strtotime($starttime_pro_date);
		$endtime_pro = strtotime('2018-05-31 23:59:59');
		if($starttime_pro > time() && $account != 'ybtest01'){
			$this->_response['message'] = 'The SG Slots Summer Amazing Event Bonus will be'.$starttime_pro_date.' Open for pickup.';
		}elseif($endtime_pro < time() && $account != 'ybtest01'){
			$this->_response['message'] = 'SG Slots Summer Dreadful Event ha terminado';
		}else{
			include_once ("common/cache_file.class.php");
			//获取缓存数据
			$limit_check = 0;
			$cachFile = new cache_file();
			$data_list = $cachFile->get($account,'','data','transfer_limit');
			if($data_list == 'false')
			{
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','transfer_limit');
				$limit_check = 1;
			}else{
				if( (time() - $data_list['limit_time']) >=60){
					$limit_time = array("limit_time"=>time());
					$cachFile->set($account,$limit_time,'','data','transfer_limit');
					$limit_check = 1;
				}
			}
			if($limit_check == 0)
			{	
				$left_time = 60 - (time() - $data_list['limit_time']);
				$this->_response['message'] = "Please ".$left_time." Get the discount in seconds";
				$this->sysmessage($this->_response);
			}
			
			//初始化值
			$deposit_level = array(10,1000,3000,10000);//存款等级
			$bonus_level = array(18,58,128,288);
			$prize_type = $post['type'];
			if($prize_type == 0 || $prize_type == 1 || $prize_type == 2 || $prize_type == 3){
				$total_deposit = $this->get_total_deposit($account);
				
				if($total_deposit >= $deposit_level[$prize_type]){
					$record = $this->get_dw_history($prize_type);
					if($record == 1065){
						$amount = $bonus_level[$prize_type];
						$core = new core();
						$result = $core->apply_duanwu_promotion($account,$amount,$total_deposit,$prize_type);
						if($result == 1){
							//领取成功后转账到sg平台
							//平台
							$gameid = '121001';//SG
							$re = $core->transfer($account,$amount,$gameid);
							if($re == 1)
							{	
								$this->_response['message'] = 'The charge was successful and the transfer was transferred to the SG platform for you!';
							}else{
								$info = $core->gameapi_active($account,'1210');
								$this->_response['message'] = 'If the cashout is successful, the gift money is limited to staking on the SG platform, and please transfer the gift money to the SG platform for staking.';
							}
							$this->_response['status'] = true;
						}elseif($result ==1063){
							$this->_response['message'] = 'Could not claim, this discount level has been claimed today.';
						}else{
							$this->_response['message'] = 'Oops, there is an unknown problem, please update and try again';
						}
					}else{
						$this->_response['message'] = 'Could not claim, this discount level has been claimed today.';
					}
				}else{
					$this->_response['message'] = 'Could not claim, the total deposit today is insufficient '.$deposit_level[$prize_type].' USDT.';
				}
			}
		}
		$this->sysmessage($this->_response);
	}
	
	//查询端午接口是否已经领取20180503
	//返回值 1063\已申Por favor || 1065\未申Por favor
	function get_dw_history($type){
		if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
			$account = $_SESSION['account'];
			$client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);
			$result = $client->check_apply_duanwu($account,$type);

		}else{
			$result = -1;
		}
		return $result;
	}
	
	
	//获取自助优惠列表
	function getPromotion($post){
		//自助优惠列表
		$account = $_SESSION['account'];
		$member_type = $_SESSION['member_type'];
		
		$pro_str = '<input class="radioForpro" type="radio" name="autopromo" value="0" id="pro1" checked><label for="pro1">Do not select offer 123</label></br>';
		
		if(time() > strtotime('2018-06-01') || $account == 'zoetattoos'){
			if($member_type == 3 || $member_type == 4){
				$pro_str .= '<input class="radioForpro" type="radio" name="autopromo" value="4" id="pro2"><label for="pro2">25% discount on pen deposit</label></br>';
			}elseif($member_type == 5 || $member_type == 6){
				$pro_str .= '<input class="radioForpro" type="radio" name="autopromo" value="5" id="pro6"><label for="pro6">28% discount on pen deposit</label></br>';
			}else{
				$pro_str .= '<input class="radioForpro" type="radio" name="autopromo" value="1" id="pro3"><label for="pro3">20% discount on pen deposit</label></br>';
			}
		}else{
			$pro_str .= '<input class="radioForpro" type="radio" name="autopromo" value="1" id="pro3"><label for="pro3">20% discount on pen deposit</label></br>';
		}
		$pro_str .='<input class="radioForpro" type="radio" name="autopromo" value="2" id="pro4"><label for="pro4">50% discount on the first deposit of chess and slots every day</label></br>';
		
		if((time() > strtotime('2018-06-01') && time() < strtotime('2018-06-30 23:59:59'))|| $account == 'zoetattoos'){
			$pro_str .='<input class="radioForpro" type="radio" name="autopromo" value="3" id="pro5"><label for="pro5">100% discount at Dragon Boat Festival in June</label></br>';
		}
		$this->_response['status'] = true;
		$this->_response['data'] = $pro_str;
		$this->sysmessage($this->_response);
	}
}
?>