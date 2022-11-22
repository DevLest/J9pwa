<?php 
header("Content-type: text/html; charset=utf-8");
define("WEB_PATH", __DIR__);
include_once("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}


if(isset($_GET['type']) && $_GET['type'] == 'apply_midAutumn_pro'){
	echo apply_midAutumn_pro();
}elseif(isset($_GET['type']) && $_GET['type'] == 'get_midAutumn_history'){
	echo get_midAutumn_history($_GET['cake_type']);
}





/**
  *申请月饼雷阵雨活动
  *1.是否在活动时间内
  *2.在哪个时间段内 0(00:00-02:00) / 1(08:00-10:00) / 2(13:00-15:00) /3(20:00-22:00)
  *3.查询该时间段是否已经领过
  *4.查询是否满足该时间段的领取充值条件的最低值
  *5.
  */
function apply_midAutumn_pro(){
	if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
		$account = $_SESSION['account'];
		$starttime = strtotime('2017-9-28 00:00:00');
		$endtime = strtotime('2017-10-07 23:59:59');
		$arr = array();
		if($starttime > time()){
			$arr = array("status"=>0,"info"=>'El evento de tormenta de pastel de luna aún no ha comenzado');
			return json_encode($arr);
		}elseif($endtime < time()){
			$arr = array("status"=>0,"info"=>'El evento Mooncake Thunderstorm ha terminado');
			return json_encode($arr);
		}else{
			//竖着的是$deposit_type,横着的是$amount_id(根据公式随机获得)
			//00:00-02:00 奖金分布列表
			$prize_type = array(
				//00:00-02:00 奖金分布列表
				array(
					array(8,12,18,28),
					array(8,12,18,22,28),
					array(10,18,22,28,58),
					array(12,28,36,58,68),
					array(28,58,88,128,168),
					array(58,68,188,228,320)
				),
				//08:00-10:00 奖金分布列表
				array(
					array(6.8,8.8,10),
					array(8,12,18,22,28),
					array(12,28,36,58,68)
				),
				//13:00-15:00 奖金分布列表
				array(
					array(8,12,18,28),
					array(8,12,18,22,28),
					array(10,18,22,28,58),
					array(12,28,36,58,68),
					array(28,58,88,128,168),
					array(58,68,188,228,320)
				),
				//20:00-22:00 奖金分布列表
				array(
					array(6.8,8.8,10),
					array(8,12,18,28),
					array(10,18,22,28,58),
					array(12,28,36,58,68),
					array(28,58,88,128,168),
					array(58,68,188,228,320)
				)
			);
			//随机函数数组
			/*
			 *0 75-18-5-2
			 *1 75-15-5-3-2
			 *2 70-15-7-5-3
			 *3 55-25-12-5-3
			 *4 40-40-10-5-5
			 *5 40-40-20
			 */
			$rand_arr = array(
				array(75,93,98,100),
				array(75,90,95,98,100),
				array(70,85,92,97,100),
				array(55,80,92,97,100),
				array(40,80,90,95,100),
				array(40,80,100)
			);
			//随机函数对应矩阵
			$to_rand = array(
				array(0,1,2,2,3,4),//$cake_type=0
				array(5,1,2),//$cake_type=1
				array(0,1,2,2,3,4),//$cake_type=2
				array(5,0,2,2,3,4)//$cake_type=3
			);
			
			//存款等级列表
			$deposit_list = array(
				array(0,200,500,1000,3000,5000),
				array(0,200,500)
			);
			//月饼种类(-1表示不在活动时间)
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
				$arr = array("status"=>0,"info"=>'La tormenta del pastel de luna aún no ha llegado, tenga paciencia por un tiempo');
				return json_encode($arr);
			}
			//查询今天的这个月饼是不是已经领过了 1065(没有领过)  1063(领过了)
			$history_re = get_midAutumn_history($cake_type);
			if($history_re == 1065){
				$deposit_today = get_total_deposit($account);
	
				if($_SESSION['member_type'] >0){
					if($deposit_today > 0){
						if($deposit_today < 200){
							if($cake_type == 2){
								$history_re0 = get_midAutumn_history(0);
								if($history_re0 == 1065){
									$deposit_level = 0;
								}
							}
							
							if($cake_type == 3){
								$history_re1 = get_midAutumn_history(1);
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
						//今天没存钱的用户
						if($cake_type == 1){
							$deposit_level = 0;
						}elseif($cake_type == 3){
							$history_re1 = get_midAutumn_history(1);
							if($history_re1 == 1065){
								$deposit_level = 0;
							}
						}
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
							$arr = array("status"=>1,"info"=>'¡Felicidades! Tienes un regalo de la suerte para los pasteles de luna, ¡compruébalo!','amount'=>$amount." Mex$");
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
						$arr = array("status"=>201,"info"=>'Es una pena que otra persona haya robado el pastel de luna o que su cuenta de miembro vuelva a ser lenta y no tenga actividad. Haga clic en Quiero depositar para recuperarla.');
						return json_encode($arr);
					}
				}else{
					//从来没存过钱的用户
					$arr = array("status"=>201,"info"=>'Es una pena que otra persona haya robado el pastel de luna o que su cuenta de miembro vuelva a ser lenta y no tenga actividad. Haga clic en Quiero depositar para recuperarla.');
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



//查询是否有记录
//返回值
	function get_midAutumn_history($cake_type){
		if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
			$account = $_SESSION['account'];
			$client = new PHPRPC_Client(PHPRPC_CASHIERFORMYSQLI);
			$result = $client->check_apply_duanwu($account,$cake_type);
		}else{
			$result = -1;
		}
		
		return $result;
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