<?php
    header("Content-type: text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials:true");
    define("WEB_PATH", __DIR__);
    include_once ("core.class.php");

	if(!isset($_SESSION))
	{
		session_start();
	}
	
	//Throttling | 5  mins per IP payment request
	IPThrottling();

    $data = (object) $_POST;

	//check auth
    $api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $data->auth;
 
    // if($auth_check != $auth)
	// {
	// 	echo json_encode(array('status'=>0,'info'=>"校验不成功"), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	// 	exit();
	// }
	
    switch ( $data->type )
	{
		case "member_login":
			echo memberLogin($data);
			break;
        case "onlinepay_list_v1":
            echo onlinepay_list_v1($data);
            break;
        case "monlinepay_bank":
            echo monlinepay_bank($data);
            break;
        case "pay_limit":
            echo onlinepay_limit($data);
            break;
        case "submitpay":
            echo submitpay($data);
            break;
        case "allowed_amounts":
            echo allowed_amounts($data);
            break;
		case "announcements":
			echo announcements($data);
			break;
		case "autopromotion_list":
			echo autopromotion_list($data);
			break;
		case "checkdeposit":
			echo checkdeposit($data);
			break;
		case "submitdeposit":
			echo submitdeposit($data);
			break;
    }
    
	function onlinepay_list_v1($data)
	{
		if (checkLogin($data) != true) exit();

		if (!isset($data->payType) || $data->payType == null)
		{
			echo json_encode(['status' => 0, 'info' => "payType not defined"], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}

		$core = new core();
	    $info = $core->onlinepay_list(array("common_id"=>$data->payType,"member_type"=>$_SESSION['users'][$data->account]['member_type']));
		$output = [];
		
        foreach($info as $v)
        {  
			if($member_type > 0)
            {
				if( !in_array($v['id'], [32, 33, 35, 36]) )
                {
					// $v['pay_id'] = "mpay_".$v['id']."_".$v['pay_type']."_".$v['line_type'];
                    array_push($output, $v);
                }
			}
            else
            {
				// $v['pay_id'] = "mpay_".$v['id']."_".$v['pay_type']."_".$v['line_type'];
				array_push($output, $v);
			}
		}
		return json_encode(['status'=>1,'info'=>$output], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	
	function onlinepay_limit($data)
	{
		$amount_low = 10;
		$amount_up = 99999;

		switch ( $data->platform )
		{
			case "onlinepay":
				if($data->lineType != "")
				{
					if($data->lineType == 1)
					{
						$amount_low = 200;
					}
					elseif($data->lineType == 2||$data->lineType == 10)
					{
						$amount_low = 500;
					}
					elseif( $data->lineType ==3)
					{
						$amount_low=1000; 
					}
					elseif($data->lineType == 11)
					{
						$amount_low = 500;
					}
					else
					{
					}
				}
				break;
			case "mobilepay":
				break;
			case "weixinpay":
				$amount_up = 3000;
				break;
			case "alipay":
				break;
			case "bankpay":
				break;
			case "tenpay":
				break;
		}
		return json_encode(array( 'status' => 1, 'info' => ['minimum' => $amount_low, 'maximum' => $amount_up ] ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	function IPThrottling()
	{
		$core = new core();
		$ips = $core->ip_information();
		
		if ( isset($_SESSION['throttle'][$ips['ip']]['depostRequest']) && $_SESSION['throttle'][$ips['ip']]['depostRequest'] == 1 )
		{
		
			$throttle = $_SESSION['throttle'];
			if ( isset($throttle[$ips['ip']]) )
			{
				$now = new DateTime();
				$then = new DateTime(date('Y-m-d',strtotime($throttle[$ips['ip']]['date'])));
				$diff = $now->diff($then);
		
				if ( $diff->format('%i') <= 5 )
				{
					echo json_encode(['status' => 0, 'info' => $diff->format('您好，为了给您提供更好的用户体验，保证您账号的使用安全，规避IP监控。您上次请求是在 %i 分钟 %s 秒前，请等待 5 分钟后再发起充值请求或联系在线客服协助。祝您游戏愉快！')], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
					die;
				}
				else
				{
					unset($_SESSION['throttle'][$throttle[$ips['ip']]]);
					$_SESSION['throttle'][$ips['ip']]['date'] = date('Y-m-d H:i:s');
					$_SESSION['throttle'][$ips['ip']]['depostRequest'] = 0;
				}
			}
			else
			{
				$_SESSION['throttle'][$ips['ip']]['date'] = date('Y-m-d H:i:s');
				$_SESSION['throttle'][$ips['ip']]['depostRequest'] = 0;
			}
		}
		else
		{
			$_SESSION['throttle'][$ips['ip']]['depostRequest'] = 0;
		}
	}

	function memberLogin($data)
	{
		$login = new core();
		$re = $login->member_login($data->account,$data->password);

		if(is_array($re))
		{	
			$_SESSION['users'][$re['account']]['account'] = $re['account'];
			$_SESSION['users'][$re['account']]['balance'] = $re['balance'];
			$_SESSION['users'][$re['account']]['member_name'] = $re['realName'];
			$_SESSION['users'][$re['account']]['member_type'] = $re['memberType'];
			return json_encode(array( 'status' => 1, 'info' => '成功!' ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
		elseif($re == 1001)
		{
			return json_encode(array( 'status' => 0, 'info' => '游戏帐号或密码错误!' ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
		elseif($re == 1002)
		{
			return json_encode(array( 'status' => 0, 'info' => '帐号被锁定，请联系在线客服!' ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
		else
		{
			return json_encode(array( 'status' => 0, 'info' => '系统异常，请稍后再试!' ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
	}
	
	function checkLogin($data)
	{
		if ( isset($_SESSION['users'][$data->account]) )
		{
			return true;
		}
		else
		{
			$return = memberLogin($data);
			if (!json_decode($return)->status)
			{
				echo $return;
				return false;
			}
			return true;
		}
	}

	function submitpay($data)
	{
		if (checkLogin($data) != true) exit();

		switch ( $data->pay_type )
		{
			case 1:
				return onlinepayment($data);
				break;
			case 2:
				return banktransfer($data);
				break;
		}
	}

	function monlinepay_bank($data)
	{
		$bank_config = include("onlinebankV2.config.php");
		$bank_type = $data->bank_type;
		$line_type = $data->line_type;
		$info = [];

		if($line_type == 10||$line_type == 21 ||$line_type == 22)
		{
			//乐盈支付宝
			array_push($info, ['value'=>'zfb', 'name'=>'支付宝']);
		}
		elseif($line_type == 174 ||$line_type == 20)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'网银转账']);
		}
		elseif($line_type == 1||$line_type == 3 ||$line_type == 6 ||$line_type == 10 ||$line_type == 13 ||$line_type == 14 ||$line_type == 15||$line_type == 18 ||$line_type == 19 ||$line_type == 23 ||$line_type == 24||$line_type == 32)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'支付宝']);
		}
		elseif($line_type == 2)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'云闪付']);
		}
		elseif($line_type == 4 ||$line_type == 12 ||$line_type == 31 ||$line_type == 34)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'网银转账']);
		}
		elseif($line_type == 5 ||$line_type == 8)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'银联扫码']);
		}
		elseif($line_type == 9 ||$line_type == 11 ||$line_type == 33)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'微信']);
		}
		elseif($line_type == 16)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'快捷']);
		}
		elseif($line_type == 26||$line_type == 27||$line_type == 29  )
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'进去选银行']);
		}
		elseif($line_type == 30)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'泰达币USDT']);
		}
		elseif($line_type == 35||$line_type == 36)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'支付宝']);
		}
		elseif($line_type == 12||$line_type == 13||$line_type == 14||$line_type == 15||$line_type == 16)
		{
			//共用的
			array_push($info, ['value'=>'6011', 'name'=>'支付']);
		}
		elseif($line_type == 70)
		{
			//智付点卡
			$list = $bank_config[101];

			if(is_array($list))
			{
				foreach($list as $v)
				{
					array_push($info, $v);
				}
			}
		}else{
			//普通在线支付银行信息
			$list = $bank_config[$bank_type];
			if(is_array($list))
			{
				foreach($list as $v)
				{
					array_push($info, $v);
				}
			}
		}

		return json_encode(array( 'status' => 1, 'info' => $info ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	function allowed_amounts($data)
	{
		if (checkLogin($data) != true) exit();

		$data->bank_type;
		$data->line_type;
		$amounts = [ '100', '200', '500', '1000' ];

		return json_encode(array( 'status' => 1, 'info' => $amounts ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	function announcements($data)
	{
		if (checkLogin($data) != true) exit();

		return json_encode(array( 'status' => 1, 'info' => "尊敬的U体育会员：由于年关将至各银行智能数据系统联合支付宝官方风控进一步加强，我司不断更新充值方式，尽最大的努力规避风控。您若支付遇到风控情况，请使用手机银行进行转账支付也可选择虚拟币存款" ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	function autopromotion_list($data)
	{
		if (checkLogin($data) != true) exit();

		$client = new PHPRPC_Client(SERVER_URL);
		$results = unserialize($client->web_autopromotion_active($data->account,$data->amount));
		if(is_array($results))
		{
			unset($results['status']);
			foreach ($results as $index => $result)
			{	
				$output[$index]['id'] = $result['id'];
				$output[$index]['title'] = $result['title'];
			}
			return json_encode(["status"=>1,"info"=>$output]);
		}
		else
		{
			return json_encode(["status"=>0,"info"=>'无优惠'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
	}

	function checkdeposit($data)
	{
		if (checkLogin($data) != true) exit();

		$core = new core();
		$info = $core->checkdeposit($data->account);
		
		if(is_array($info))
		{
			$bank_info_arr = $core->deposit_bank(0,$_SESSION['users'][$data->account]['member_type']);
			$bank_info = $bank_info_arr[0];

			if(count($bank_info_arr) < 2)
			{
				$arrs = [
					"id" => $bank_info['id'],
					"bank_name" => $bank_info['bank_name'],
					"account_name" => $bank_info['username'],
					"bank_no" => $bank_info['card_no'],
					"amount" => $info['money'],
					"code" => mt_rand(100000,999999),
					"yunsfurl" => ''
				];
				return json_encode([ 'status' => 1, 'info' => $arrs ]);
				exit();
			}
			else
			{
				return json_encode( ['status' => 0, 'info' => $bank_info_arr ]);
				exit();
			}
		}
		else
		{
			return json_encode( ['status' => 0, 'info' => "No banks found" ]);
		}
	}

	function onlinepayment($data)
	{
		$core = new core();

		$account = $data->account;
		$rand_monlinepay = $core->random(6);
		$billno = "n".date("YmdHis").$rand_monlinepay;

		$amount = $data->amount;
		
		if($amount < 10)
		{
			return json_encode(array( 'status' => 0, 'info' => '存款金额不正确，请输入正确的存款金额' ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}

		$bankco = $data->bank_type;
		$pay_id = $data->pay_id;
		$line_type = $data->line_type;

		$monlinepay_info = $core->monlinpay_detail($pay_id);
		
		//判断支付银行选项是否正确
		$zf_bank_id = array("ABC","ICBC","CCB","BCOM","BOC","CMB","CMBC","CEBB","SHB","NBB","HXB","CIB","PSBC","SPABANK","SPDB","HZB","ECITIC");
		
		if($pay_id == 600)
		{
			$zf_flag = 0;
			foreach($zf_bank_id as $v_zf)
			{
				if($bankco == $v_zf)
				{
					$zf_flag = 1;
				}
			}
			if($zf_flag == 0)
			{
				return json_encode(array( 'status' => 0, 'info' => "存款银行不正确，请重新输入！" ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				exit();
			}
		}

		if( $monlinepay_info['pay_status'] != 1 && $account != "feng12345" )
		{
			return json_encode(array( 'status' => 0, 'info' => "存款通道维护，请选择其它存款方式" ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}

		if($_SESSION['users'][$data->account]['account'] == '')
		{
			return json_encode(array( 'status' => 0, 'info' => "验证过期，请返回刷新重新进入" ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit(); 
		}

		if( $monlinepay_info['pay_type'] == 6 )
		{
			$amount= $amount*6.42;
			$amount= number_format($amount,'2',".","");
		}

		$re = $core->onlinepay($_SESSION['users'][$data->account]['account'],$amount,$billno,$monlinepay_info['pay_name'],$data->autopromo);
		if($re == 1)
		{
			$bankco='965';
			// 三方通用提交 
			$params = [
				'billno' => $billno,
				'amount' => $amount,
				'bank_code' => $bankco,
				'return_url' => $monlinepay_info['return_url']
			];

			$_SESSION['throttle'][$ips['ip']]['depostRequest'] == 1;	

		//	print_r($_SESSION);
			// $output = sendCurlRequest($monlinepay_info['submit_url'], $params);

			// return json_encode(['status' => 1, 'info' => $output]);
			//print_r($bankco);exit;
			return $core->build_form($params, $monlinepay_info['submit_url'], "POST");
			// return json_encode(array( 'status' => 0, 'info' => $core->build_form($params, $monlinepay_info['submit_url'],$method) ));
		}
		elseif($re == -1)
		{
			return json_encode(array( 'status' => 0, 'info' => "您已申请过该优惠，请查看优惠规则" ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
		else
		{
			return json_encode(array( 'status' => 0, 'info' => "存款提交失败，请刷新后重新提交" ), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
	}

	function banktransfer($data)
	{		
		include_once (WEB_PATH."/common/cache_file.class.php");

		$account = $_SESSION['users'][$data->account]['account'];
		$amount = $data->amount;
		$limit_check = 0;

		$cachFile = new cache_file();
		$data_list = $cachFile->get($account,'','data','deposit_limit');

		if($data_list == 'false')
		{
			$limit_time = array("limit_time"=>time());
			$cachFile->set($account,$limit_time,'','data','deposit_limit');
			$limit_check = 1;
		}
		else
		{
			if( (time() - $data_list['limit_time']) >30)
			{
				$limit_time = array("limit_time"=>time());
				$cachFile->set($account,$limit_time,'','data','deposit_limit');
				$limit_check = 1;
			}
		}

		if($limit_check == 0)
		{
			return json_encode(array('status'=>0,'info'=>'提交失败，请30秒后重新提交'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}

		if($amount < 10)
		{
			return json_encode(array('status'=>0,'info'=>'存款金额不正确，请输入正确的存款金额'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}

		$rs = $core->record_status($account,"deposit",2);

		if($rs > 0)
		{
			return json_encode(array('status'=>0,'info'=>'已存在未审核的存款记录，请勿重复提交'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}

		$bank_info_arr = $core->deposit_bank(0,$_SESSION['users'][$data->account]['member_type']);
		$bank_info = $bank_info_arr[0];

		if($_SESSION['users'][$data->account]['account'] == '')
		{
			return json_encode(array('status'=>0,'info'=>'验证过期，请返回刷新重新进入'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit(); 
		}

		$re = $core->backdeposit( $_SESSION['users'][$data->account]['account'], $data->amount, $bank_info['username'], $bank_info['bank_name'], $bank_info['card_no'], $data->account, $data->autopromo, 1);

		if($re[0] == 1)
		{
			if(count($bank_info_arr) < 2)
			{
				$arrs = array(
					"id"=>$bank_info['id'],
					"bank_name"=>$bank_info['bank_name'],
					"account_name"=>$bank_info['username'],
					"bank_no"=>$bank_info['card_no'],
					"amount"=>$_POST['amount'],
					"code"=>$re[1]//附言
				);

				return json_encode(array('status'=>1,'type'=>0,'info'=>$arrs));
				exit();
			}
			else
			{
				return json_encode(array('status'=>1, 'info'=>$bank_info_arr) );
				exit();
			}
		}
		elseif($re == -1)
		{
			return json_encode(array('status'=>0,'info'=>'您已申请过该优惠，请查看优惠规则'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
		else
		{
			error_log(date('YmdHis')."##".$_SESSION['users'][$data->account]['account']."##".$data->amount."##".$bank_info['id']."##".$data->account."##".$data->autopromo."\r\n", 3, 'common/log/depositerror.log');
			return json_encode(array('status'=>0,'info'=>'存款提交失败，请刷新后重新提交'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			exit();
		}
	}

	function sendCurlRequest($url, $data)
	{
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_FOLLOWLOCATION => 0,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_TIMEOUT => 3,
			CURLOPT_POST => 1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				'Content-Type:multipart/form-data; charset=utf-8'
			),
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		return $response;
	}