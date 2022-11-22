<?php
/**
 *用来处理前台页面提交的表单
 *通过phprpc存储到数据库中
 */
set_time_limit(0);
header("Content-type: text/html; charset=utf-8");
define("WEB_PATH", __DIR__);
define('QUERY_URL','http://adminu2dnewonesite.32sun.com/phprpc/mysql_query.php');
include_once ("core.class.php");
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_GET['type']) && $_GET['type'] == 'wzqh_pro'){
	echo apply_wzqh_pro();
}else if(isset($_GET['type']) && $_GET['type'] == 'wzqh_history'){
	echo get_wzqh_history();
}
/*
	申请三月春风行活动201803
	活动流程：
	1.查询该玩家是否已申请
	2.查询玩家 满足要求的单笔金额笔数
	3.计算玩家所属的存款等级线
	4.计算玩家应该获取哪个等级的奖金
	5.写入记录(五一活动接口)
	
	
	参数     账号
	返回值   数组 状态、礼金金额(错误提示)
 */
function apply_wzqh_pro(){
	$arr = array();
	if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
		$account = $_SESSION['account'];
		$starttime_pro = strtotime('2018-04-16 00:00:00');//活动开始时间
		$endtime_pro = strtotime('2018-08-03 23:59:59');//活动结束时间
		
		if($starttime_pro > time() && $account != 'ybtest01'){
			$arr = array("status"=>0,"info"=>'El Festival de Primavera de marzo aún no ha comenzado');
			return json_encode($arr);
		}elseif($endtime_pro < time() && $account != 'ybtest01'){
			$arr = array("status"=>0,"info"=>'El Festival de Primavera de marzo ha terminado');
			return json_encode($arr);
		}else{
			//查询今日是否已经申请 1065(没有领过)  1063(领过了)
			if(get_wzqh_history_sip($account) == 1){
				//条件参数
				$amount_reqr = array(19.7,98.5);//最低存款等级线
				$num_reqr = array(3,5,7,10);//存款笔数等级
				$amount_max = array(//最高奖金等级
					array(58,88,128,188),
					array(128,188,288,588)
				);
				
				$deposit_ytd = get_deposit($account);
				$deposit_amount_arr = array();//存款等级对应金额数组
				$deposit_min = array();//奖金最小值
				$deposit_total = 0;
				$num = array(0,0);
				$amount = 0;
				$amount_level = -1;
				$max_level = -1;
				//获得对应等级的存款数组
				if(is_array($deposit_ytd)){
					foreach($deposit_ytd as $v){
						$deposit_total += $v['amount'];
						$i = 0;
						foreach($amount_reqr as $reqr){
							if($v['amount'] >= $reqr){
								$num[$i]++;
								if($num[$i] == 1){
									$deposit_min[$i] = $v['amount'];
								}else if($v['amount'] < $deposit_min[$i]){
									$deposit_min[$i] = $v['amount'];
								}
								$deposit_amount_arr[$i][] = $v['amount'];
							}
							$i++;
						}
					}
				}
				//计算玩家存款属于哪个等级
				foreach($num as $n){
					if($n >= 3){
						$amount_level++;
					}
				}
				//计算玩家应该获取哪个等级的奖金
				if($amount_level > -1){
					foreach($num_reqr as $v){
						if($num[$amount_level] >= $v){
							$max_level++;
						}else{
							break;
						}
					}
					if($num[$amount_level] >= 5){
						$rand_num = mt_rand(0,$num[$amount_level]-1);
						$amount = $deposit_amount_arr[$amount_level][$rand_num];
					}else{
						$amount = $deposit_min[$amount_level];
					}
					$max_cur = $amount_max[$amount_level][$max_level];
					if($amount > $max_cur){
						$amount = $max_cur;
					}
				}else{
					$arr = array("status"=>0,"info"=>'Tienes menos de 3 depósitos con un solo depósito mayor a 20 ayer, no puedes aplicar para esta actividad');
					return json_encode($arr);
				}
				$core = new core();
				$re = $core->apply_unlimited_promotion($account,$amount,$deposit_total,1);
				if($re == 1){
					$arr = array("status"=>1,"info"=>'¡Felicidades! Regalo gratis en marzo<span style="color:red">'.$amount.'</span>Mex$ ha sido depositado en su cuenta principal');
					return json_encode($arr);
				}elseif($re == 1063){
					$arr = array("status"=>0,"info"=>'Ya has reclamado esta oferta hoy');
					return json_encode($arr);
				}else{
					$arr = array("status"=>0,"info"=>'Vaya, hay un problema desconocido, actualice y vuelva a intentarlo');
					return json_encode($arr);
				}
			}else{
				$arr = array("status"=>0,"info"=>'Ya has reclamado esta oferta hoy');
				return json_encode($arr);
			}
			
		}
	}else{
		$arr = array("status"=>0,"info"=>'Inicia sesión en tu cuenta de juego primero');
		return json_encode($arr);
	}
}


/*
	查询三月春风行活动是否有记录201803
 */
function get_wzqh_history(){
	if(isset($_SESSION['account']) && $_SESSION['account'] != ''){
		$account = $_SESSION['account'];
		$starttime_pro = strtotime('2018-04-16 00:00:00');//活动开始时间
		$endtime_pro = strtotime('2018-08-03 23:59:59');//活动结束时间
		
		if($starttime_pro > time() && $account != 'ybtest01'){
			$lefttime = $starttime_pro - time();
			$strDay = floor($lefttime/86400);
			$hr = floor(fmod($lefttime,86400)/3600);
			$minr = floor(fmod($lefttime,3600)/60);
			$arr = array("status"=>0,"info"=>'Todavía hay tiempo hasta que comience el evento March Spring Festival.</br>'.$strDay.'天'.$hr.'小时'.$minr.'分');
			return json_encode($arr);
		}elseif($endtime_pro < time() && $account != 'ybtest01'){
			$arr = array("status"=>0,"info"=>'El Festival de Primavera de marzo ha terminado');
			return json_encode($arr);
		}else{
			$deposit_ytd = get_deposit($account);
			$num = 0;
			$amount_reqr = 19.7;
			if(is_array($deposit_ytd)){
				foreach($deposit_ytd as $v){
					if($v['amount'] >= $amount_reqr){
						$num++;
					}
				}
			}
			if($num < 3){
				$arr = array("status"=>-1,"info"=>'Menos de 3 depósitos con una sola cantidad de depósito superior a 20 ayer</br>no pueden participar en este evento');
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
					$arr = array("status"=>1,"info"=>'Actualmente tiene una oportunidad de reclamar el regalo gratis de marzo');
					return json_encode($arr);
				}elseif($result == 1063){
					$list = "<tr><th colspan='3'>Registro de reclamo de hoy</div></th></tr><tr><th>Tipo de regalo</th><th>regalo</th><th>Hora de recolección</th></tr>" ;
					if(is_array($re) && $re != null)
					{
						foreach($re as $v)
						{		
								$list .="<tr><td>Regalo gratis en marzo</td><td>".$v['amount']."</td><td>".$v['add_time']."</td></tr>";
						}
						
					}else{
						$list = '';
					}
					$arr = array("status"=>2,"info"=>'El obsequio de marzo de hoy ha sido recibido','record'=>$list);
					return json_encode($arr);
				}else{
					$arr = array("status"=>0,"info"=>'Vaya, hay un problema desconocido, actualice y vuelva a intentarlo');
					return json_encode($arr);
				}
			}
		}
	}else{
		$result = -1;
	}	
	return $result;
}
function get_wzqh_history_sip($account){
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
	return $result;
}

/*
	获取玩家在某时间段内的存款金额
	参数     账号，开始时间(不传默认前一天00:00:00-23:59:59)，结束时间
	返回值   数组 单笔存款金额
 */
function get_deposit($account,$start_time="",$end_time="")
{
	if($start_time == ""){
		$start_time = date("Y-m-d",strtotime("-1 day"));
		$end_time = date("Y-m-d 23:59:59",strtotime("-1 day"));
	}
		
	$client = new PHPRPC_Client(QUERY_URL);
	$option = array(
		"table"=>"ks_deposit_history",
		"fields"=>"amount",
		"condition"=>"account='".$account."' and endTime between '".$start_time."' and '".$end_time."'",
		"order"=>"id desc",
	);
	$option = serialize($option);
	$result = $client->select($option);
	$result = unserialize($result);
	return $result;
}


?>