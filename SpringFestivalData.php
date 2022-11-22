<?php 
header("Content-type: text/html; charset=utf-8");
define("WEB_PATH", __DIR__);
include_once("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}


if(isset($_GET['type']) && $_GET['type'] == 'apply_sf_pro'){
	echo apply_sf_pro();
}elseif(isset($_GET['type']) && $_GET['type'] == 'get_sf_history'){
	echo get_sf_history($_GET['cake_type']);
}elseif(isset($_GET['type']) && $_GET['type'] == 'apply_newYear_promotion')
{
	echo apply_newYear_promotion();
}elseif(isset($_GET['type']) && $_GET['type'] == 'check_newYear_pro')
{
	echo check_newYear_pro($_SESSION['account']);
}





/**
  *申请粽子雷阵雨活动201801
  *1.是否在活动时间内
  *2.在哪个时间段内 0(00:00-02:00) / 1(08:00-10:00) / 2(13:00-15:00) /3(20:00-22:00)
  *3.查询该时间段是否已经领过
  *4.查询是否满足该时间段的领取充值条件的最低值
  *5.
  */
function apply_sf_pro(){
	if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
		$account = $_SESSION['account'];
		$starttime = strtotime('2018-06-01 12:00:00');
		$endtime = strtotime('2018-06-30 23:59:59');
		$arr = array();
		if($starttime > time() && $account != 'feng12345'){
			$arr = array("status"=>0,"info"=>'La actividad de la lluvia de Zongzi aún no ha comenzado');
			return json_encode($arr);
		}elseif($endtime < time() && $account != 'feng12345'){
			$arr = array("status"=>0,"info"=>'El evento de tormenta Zongzi ha terminado');
			return json_encode($arr);
		}else{
			//竖着的是$deposit_type,横着的是$amount_id(根据公式随机获得)
			//00:00-02:00 奖金分布列表
			$prize_type = array(
				//00:00-02:00 奖金分布列表
				array(
					array(8,12,18),
					array(8,12,18,22),
					array(12,18,22,28,58),
					array(18,28,36,58,68),
					array(28,58,88,128,188),
					array(58,68,128,188,228)
				),
				//08:00-10:00 奖金分布列表
				array(
					array(8,12,18),
					array(8,12,18,22),
					array(12,18,22,28,58),
					array(18,28,36,58,68),
					array(28,58,88,128,188),
					array(58,68,128,188,228)
				),
				//13:00-15:00 奖金分布列表
				array(
					array(8,12,18),
					array(8,12,18,22),
					array(12,18,22,28,58),
					array(18,28,36,58,68),
					array(28,58,88,128,188),
					array(58,68,128,188,228)
				),
				//20:00-22:00 奖金分布列表
				array(
					array(8,12,18),
					array(8,12,18,22),
					array(12,18,22,28,58),
					array(18,28,36,58,68),
					array(28,58,88,128,188),
					array(58,68,128,188,228)
				)
			);
			//随机函数数组
			/*
			 *45-40-15
			 *40-35-20-5
			 *35-35-20-5-5
			 *30-30-20-10-10
			 */
			$rand_arr = array(
				array(45,85,100),
				array(40,75,95,100),
				array(35,70,90,95,100),
				array(30,60,80,90,100)
			);
			//随机函数对应矩阵
			$to_rand = array(
				array(0,1,2,2,2,3),//$cake_type=0
				array(0,1,2,2,2,3),//$cake_type=1
				array(0,1,2,2,2,3),//$cake_type=2
				array(0,1,2,2,2,3)//$cake_type=3
			);
			
			//存款等级列表
			$deposit_list = array(
				array(0,100,500,1000,3000,5000),
				array(0,100,500,1000,3000,5000),
			);
			//粽子种类(-1表示不在活动时间)
			$cake_type = -1;
			//存款等级(-1表示今日没有存款)
			$deposit_level = -1;
			$hour = date('H',time());
			
			if($hour == 0 || $hour == 1){
				$cake_type = 0;
			}elseif($hour == 8 || $hour == 9){
				$cake_type = 1;
			}elseif($hour == 13 || $hour == 14){
				$cake_type = 2;
			}elseif($hour == 20 || $hour == 21){
				$cake_type = 3;
			}else{
				//不在活动时间段内
				$arr = array("status"=>0,"info"=>'La tormenta eléctrica zongzi aún no ha llegado, espere pacientemente un momento');
				return json_encode($arr);
			}
			//查询今天的这个粽子是不是已经领过了 1065(没有领过)  1063(领过了)
			$history_re = get_sf_history($cake_type);
			
			//查询昨日是否有存款
			/*$stime = date("Y-m-d",strtotime("-1 day"));
			$etime = date("Y-m-d 23:59:59",strtotime("-1 day"));
			$time_yest = array($stime,$etime);
			$deposit_yest = get_total_deposit($account,$time_yest);
			*/
			
			
			if($history_re == 1065){
				$deposit_today = get_total_deposit($account);
				
				if($deposit_today > 0){
					if($deposit_today < 500){
						if($cake_type == 0 || $cake_type == 1){
							$deposit_level = 0;
						}
						if($cake_type == 2){
							$history_re0 = get_sf_history(0);
							if($history_re0 == 1065){
								$deposit_level = 0;
							}
						}
							
						if($cake_type == 3){
							$history_re1 = get_sf_history(1);
							if($history_re1 == 1065){
								$deposit_level = 0;
							}
						}
					}else{
						if($cake_type == 1){
								$deposit_arr = $deposit_list[1];
						}else{
							$deposit_arr = $deposit_list[0];
						}
						foreach($deposit_arr as $d){
							if($deposit_today >= $d){
								$deposit_level++;
							}else{
								break;
							}
						}
					}
				}else{
					/*if($deposit_yest >0){
						//今天没存钱、但是昨天有存款的用户
						if($cake_type == 1){
							$deposit_level = 0;
						}elseif($cake_type == 3){
							$history_re1 = get_sf_history(1);
							if($history_re1 == 1065){
								$deposit_level = 0;
							}
						}
					}else{
						//今天、昨天都没存过钱的用户
						$arr = array("status"=>201,"info"=>'Es una pena que Zongzi haya sido robado por otros o que su cuenta de miembro vuelva a ser perezosa y no tenga actividad. ¡Haga clic en Quiero depositar para recuperarla!');
						return json_encode($arr);
					}*/
					//今天没存过钱的用户
					$arr = array("status"=>201,"info"=>'Lo siento mucho, Zongzi fue robado por otros o su cuenta de miembro es lenta nuevamente, falta de actividad, haga clic en Quiero depositar para recuperarla！');
					return json_encode($arr);
				}

				if($deposit_level > -1){
					//--------------------------
					//得到对应的随机函数id
					$functon_rand_id = $to_rand[$cake_type][$deposit_level];
					//随机数
					$rand_num = mt_rand(1,100);
					//得到的结果

					$amount_id = 0;
					foreach($rand_arr[$functon_rand_id] as $d){
						if($rand_num > $d){
							$amount_id++;
						}else{
							break;
						}
					}
					//最终得到随机获得的钱
					$amount = $prize_type[$cake_type][$deposit_level][$amount_id];
					$client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);
					$result = $client->apply_duanwu_promotion($account,$amount,$deposit_today,$cake_type);
					
					if($result == 1){
						$arr = array("status"=>1,"info"=>'¡Felicidades! Tienes el regalo de la suerte de Zongzi, ¡compruébalo!','amount'=>$amount."元");
						return json_encode($arr);
					}elseif($result ==1063){
						$arr = array("status"=>0,"info"=>'Ya has participado en las actividades de este periodo de tiempo, vuelve en el próximo periodo de tiempo');
						return json_encode($arr);
					}else{
						$arr = array("status"=>0,"info"=>'Vaya, hay un problema desconocido, actualice y vuelva a intentarlo');
						return json_encode($arr);
					}
				}else{
					//今天没存过钱的用户,0或者1已经领过
					$arr = array("status"=>201,"info"=>'Es una pena que Zongzi haya sido robado por otros o que su cuenta de miembro vuelva a ser perezosa y no tenga actividad. ¡Haga clic en Quiero depositar para recuperarla!');
					return json_encode($arr);
				}
			}else{
				$arr = array("status"=>0,"info"=>'Ya has participado en las actividades de este periodo de tiempo, vuelve en el próximo periodo de tiempo');
				return json_encode($arr);
			}
		}
	}else{
		$arr = array("status"=>0,"info"=>'Por favor inicie sesión en su cuenta primero');
		return json_encode($arr);
	}
}



//查询新春粽子雨活动是否有记录201801
//返回值
	function get_sf_history($cake_type){
		if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
			$account = $_SESSION['account'];
			$client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);
			$result = $client->check_apply_duanwu($account,$cake_type);

		}else{
			$result = -1;
		}
		
		return $result;
	}

	//2018新春礼金活动208-01
	/*
	 *1,判断当日存款金额的对应存款等级
	 *2,根据存款等级得到对应的奖金概率分布数组
	 *3,随机并获数字(1-100),根据2的数组得到对应的概率等级
	 *4,根据存款等级和概率等级得到最后的奖金
	 */
	function apply_newYear_promotion(){
		if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
			$account = $_SESSION['account'];
			$starttime = strtotime('2018-01-25 00:00:00');
			$endtime = strtotime('2018-02-28 23:59:59');
			$arr = array();
			if($starttime > time() && $account != 'jacktest'){
				$arr = array("status"=>0,"info"=>'El evento aún no ha comenzado.');
				return json_encode($arr);
			}elseif($endtime < time() && $account != 'jacktest'){
				$arr = array("status"=>0,"info"=>'El evento ha terminado');
				return json_encode($arr);
			}else{
				
				//开始根据存款得到对应的奖金
				$deposit = 0;//昨日存款总额
				$deposit_level = -1;
				$bonus_id = 0;
				$bonus = 0;//奖金
				$deposit_arr = array(//存款等级列表
					0=>9.85,
					1=>500,
					2=>1000,
					3=>5000
				);
				$rand_arr = array(//概率列表
					0=>array(30,60,80,93,98),
					1=>array(30,60,80,90,95),
					2=>array(30,60,80,90,95),
					3=>array(30,60,80,90,95)
				);
				$bonus_arr = array(//奖金分布列表
					0=>array(6,8,12,18,22,28),
					1=>array(18,28,36,58,68,88),
					2=>array(28,36,58,68,88,128),
					3=>array(58,68,88,128,188,288)
				);
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d 23:59:59",strtotime("-1 day"));
				$time = array($starttime,$endtime);
				$deposit = get_total_deposit($account,$time);

				//获取存款等级
				foreach($deposit_arr as $d){
					if($deposit >= $d){
						$deposit_level++;
					}else{
						break;
					}
				}
				if($deposit_level > -1){
					$rand_num = mt_rand(1,100);
				
					foreach($rand_arr[$deposit_level] as $r){
						if($rand_num > $r){
							$bonus_id++;
						}else{
							break;
						}
					}
					
					$bonus = $bonus_arr[$deposit_level][$bonus_id];
					$core = new core();
					$result = $core->apply_unlimited_promotion($account,$bonus,$deposit,0);
					if($result == 1){
						$arr = array("status"=>1,"info"=>'¡Felicidades! Tienes un regalo de año nuevo chino'.$bonus.'元');
						return json_encode($arr);
					}elseif($result ==1063){
						$arr = array("status"=>0,"info"=>'Ya has recibido el regalo del Año Nuevo Chino de hoy, vuelve mañana');
						return json_encode($arr);
					}else{
						$arr = array("status"=>0,"info"=>'Vaya, hay un problema desconocido, actualice y vuelva a intentarlo');
						return json_encode($arr);
					}
				}else{
					$arr = array("status"=>0,"info"=>'No hubo dinámica de depósito ayer, por lo que no puedo participar en este evento.');
					return json_encode($arr);
				}
			}
		}else{
			$arr = array("status"=>0,"info"=>'El estado es incorrecto, actualice y haga clic para recibir');
			return json_encode($arr);
		}
	}
	
	/*
	 *查询新春礼金是否已经领取201801
	 */
	function check_newYear_pro($account){
		$starttime = strtotime('2018-01-25 00:00:00');
		$endtime = strtotime('2018-02-28 23:59:59');
		$arr = array();
		if($starttime > time() && $account != 'jacktest'){
			$arr = array("status"=>0,"info"=>'El evento aún no ha comenzado.');
			return json_encode($arr);
		}elseif($endtime < time() && $account != 'jacktest'){
			$arr = array("status"=>0,"info"=>'El evento ha terminado');
			return json_encode($arr);
		}else{
		
			$starttime = date("Y-m-d",strtotime("-1 day"));
			$endtime = date("Y-m-d 23:59:59",strtotime("-1 day"));
			$time = array($starttime,$endtime);
			$deposit = get_total_deposit($account,$time);
			if($deposit < 9.85){
				$arr = array("status"=>-1,"info"=>'No hubo dinámica de depósito ayer, por lo que no puedo participar en este evento.');
				return json_encode($arr);
			}else{
				$core = new core();
				$re = $core->special_promotion_record($account,'unlimited');
				if(is_array($re) && $re != null){
					if(date("Y-m-d",strtotime($re[0]['add_time'])) === date("Y-m-d")){
						$result = 1063;
					}else{
						$result = 1;
					}
				}else{
					$result = 1;
				}
				
				
				if($result == 1){
					$arr = array("status"=>1,"info"=>'Actualmente tiene una oportunidad de recibir un regalo de Año Nuevo Chino');
					return json_encode($arr);
				}elseif($result == 1063){
					$list = "<tr><th colspan='3'>Registro de reclamo de hoy</div></th></tr><tr><th>Tipo de regalo</th><th>regalo</th><th>Hora de recolección</th></tr>";
					if(is_array($re) && $re != null)
					{
						foreach($re as $v)
						{		
								$prize_name = $core->switch_array_view(3,$v['prize_type']);
								$list .="<tr><td>Regalos de año nuevo chino</td><td>".$v['amount']."</td><td>".$v['add_time']."</td></tr>";
						}
						
					}else{
						$list = '';
					}
					$arr = array("status"=>2,"info"=>'El regalo del Año Nuevo Chino ha sido recibido hoy.','record'=>$list);
					return json_encode($arr);
				}else{
					$arr = array("status"=>0,"info"=>'Vaya, hay un problema desconocido, actualice y vuelva a intentarlo');
					return json_encode($arr);
				}
			}
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
        $client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);
        $total_deposit = $client->get_total_deposit($account,$starttime,$endtime);
        if($total_deposit == "")
        {
            $total_deposit = 0;
        }
        return $total_deposit;	     
	}

?>