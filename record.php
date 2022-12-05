<?php 
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *");
include_once("client/phprpc_client.php");
 //echo 526;exit;

 $lang = json_decode(file_get_contents("./language/".(isset($data->lang) ? $data->lang : "en").".json"));
 $lang = $lang->record;
 
	$api_key='fghrtrvdfger';
    $time = substr(time(),0,-3);
	
    $auth_check = md5($time.$api_key);
    $auth = $_POST['auth'];
 
    if($auth_check != $auth)
	{
	
	echo json_encode(array('status'=>0,'info'=>$lang->verification_failed));
		exit();
	}
        
        $starttime=date("Y-m-d",strtotime('-7 day'));
		$endtime=date('Y-m-d',strtotime('+1 day'));

if(isset($_POST['type']) && $_POST['type'] == "ag")
	{
		echo agrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "bti")
	{
		echo btirecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "im")
	{
		echo imrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	} elseif(isset($_POST['type']) && $_POST['type'] == "ibc")
	{
		echo ibcrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "leihuo")
	{
		echo leihuorecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "sgwin")
	{
		echo sgwinrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "lb")
	{
		echo lbrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "tcg")
	{
		echo tcgrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "avia")
	{
		echo aviarecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "kychess")
	{
		echo kychessrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "imdj")
	{
		echo imdjrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "imchess")
	{
		echo imchessrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "imnative")
	{
		echo imnativerecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}elseif(isset($_POST['type']) && $_POST['type'] == "ebet")
	{
		echo ebetrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	}
    
    elseif(isset($_POST['type']) && $_POST['type'] == "bg")
	{
		echo bgrecord($_POST['account'],$_POST['page'],$starttime,$endtime);
	} elseif(isset($_POST['type']) && $_POST['type'] == "gd")
	{
		echo gdrecord($_POST['account'],$_POST['page']);
	}
    elseif(isset($_POST['type']) && $_POST['type'] == "ab")
	{
        // Notes: Startime is best at 1 hour ago.(or 30days ago, 29days ago, and so on..)
        // limit - means limit the return data.(e.g. 10 then it will return only 10 bet record data.)
        // $starttime example format: 2015-12-24 23:01:59
        // Notes: The return data is from $starttime to ($startime + 1 hour)
		// echo abrecord($_POST['account'], $_POST['starttime'], $_POST['limit']);

        echo abrecord($_POST['account'],$_POST['page']);
	}
    elseif(isset($_POST['type']) && $_POST['type'] == "cq")
	{
		echo cqrecord($_POST['account'],$_POST['page']);
	}   
    
    elseif(isset($_POST['type']) && $_POST['type'] == "pt")
	{
        // direct access to API PlayTech PT SLOT
		echo ptrecord($_POST['account'], $_POST['page'], $_POST['perpage']);
	}   

    elseif(isset($_POST['type']) && $_POST['type'] == "mg")
	{
        // direct access to API MicroGaming API
		echo mgrecord($_POST['account'], $_POST['page'], $_POST['perpage']);
	}   

    elseif(isset($_POST['type']) && $_POST['type'] == "vr")
	{
        // direct access to API MicroGaming API
		echo vrrecord($_POST['account'], $_POST['page']);
	}
    elseif(isset($_POST['type']) && $_POST['type'] == "cmd")
	{
        
		echo cmdrecord($_POST['account'], $_POST['page']);
	}  

    elseif(isset($_POST['type']) && $_POST['type'] == "sbo")
	{
        // direct access to API of TCG SBO
		echo tcg_sbo_record($_POST['account'], $_POST['page']);
	}elseif(isset($_POST['type']) && $_POST['type'] == "unite")
	{
        // direct access to API of TCG SBO
		echo uniterecord($_POST['account'], $_POST['page']);
	}  
        
function agrecord($account,$page,$starttime,$endtime){
    global $lang;
 //   echo 526;exit;
		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->agrecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
		// print_r($list);exit;
                $memberlist=$list['a'];
             //   $count=$list['b'][0]['count(*)'];
   
                if(isset($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->agrecord->no_data));    
                    
                }
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}

function uniterecord($account,$page,$starttime,$endtime){
    global $lang;
 //   echo 526;exit;
		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->uniterecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
		// print_r($list);exit;
                $memberlist=$list['a'];
             //   $count=$list['b'][0]['count(*)'];
   
                if(isset($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->uniterecord->no_data));    
                    
                }
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}
function btirecord($account,$page,$starttime,$endtime){
    global $lang;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->btirecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
		// print_r($list);exit;
                $memberlist=$list['a'];
                $count=$list['b'][0]['count(*)'];
                                   foreach($memberlist as $key=>$val){
            unset($memberlist[$key]['other1']);
              unset($memberlist[$key]['other2']);
             
            
        }
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']=$count;
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->btirecord->no_data));    
                    
                }
                
    
    
    
}


function imrecord($account,$page,$starttime,$endtime){
    global $lang;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->imtyrecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
                //
        // print_r($_POST);
	//	 print_r($list);exit;
                $memberlist=$list['a'];
                $count=$list['b'][0]['count(*)'];
                                   foreach($memberlist as $key=>$val){
            unset($memberlist[$key]['other1']);
              unset($memberlist[$key]['other2']);
             
            
        }
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']=$count;
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->imrecord->no_data));    
                    
                }
                
    
    
    
}

function imnativerecord($account,$page,$starttime,$endtime){
    global $lang;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->imnativerecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
                //
        // print_r($_POST);
	//	 print_r($list);exit;
                $memberlist=$list['a'];
                $count=$list['b'][0]['count(*)'];
                                   foreach($memberlist as $key=>$val){
            unset($memberlist[$key]['other1']);
              unset($memberlist[$key]['other2']);
             
            
        }
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']=$count;
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->imnativerecord->no_data));    
                    
                }
                
    
    
    
}
function ibcrecord($account,$page,$starttime,$endtime){
    global $lang;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->ibcrecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
		// print_r($list);exit;
                $memberlist=$list['a'];
              //  $count=$list['b'][0]['count(*)'];
        
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->ibcrecord->no_data));    
                    
                }
                
    
    
    
}



function leihuorecord($account,$page,$starttime,$endtime){
    
   $_game = "LeiHuo";   // just change this game to LeiHuo, IMBoardGame( 不用), SGWIN（双赢彩票）, LB, SABA（沙巴电竞是U没有）, TCG, AVIA（泛亚电竞）
        $url =  "api.egame58.com/API" . "/" . $_game . "/BetRecord";

    
        $dateNow = date('Y-m-d H:i:s', strtotime(' - 30 days'));
        $date3 = date('Y-m-d H:i:s', time());
     
      
$page=1;
$pages=500; 
//print_r($dateNow);exit;
        $params = [
            'merchantCode' =>'Mx3WQufwEjf',
             'username' => 'u2d'.$account,
            'startDate' => $dateNow,
            'endDate' => $date3,
            'pageIndex' => $page,
            'pageSize' => $pages,
            'timestamp' => time(), //$conf['timestamp'],
        ];

        $b64 = base64($params);

        $sign = sign('t2Oesa1uZFiofHy2vnYmGvKzMFlXa7ZAmFxFkizANXqSEWQXNS0aE8bQxG124BxfDKun', $b64);
        $requestParams = $b64 . "." . $sign;
//print_r($params);
        $res = json_decode(httpRequest($requestParams, $url, "post", false));
           
                    
                    $info['list']=$res;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
            
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}
function sgwinrecord($account,$page,$starttime,$endtime){
    
   $_game = "SGWIN";   // just change this game to LeiHuo, IMBoardGame( 不用), SGWIN（双赢彩票）, LB, SABA（沙巴电竞是U没有）, TCG, AVIA（泛亚电竞）
        $url =  "api.egame58.com/API" . "/" . $_game . "/BetRecord";

    
        $dateNow = date('Y-m-d H:i:s', strtotime(' - 30 days'));
        $date3 = date('Y-m-d H:i:s', time());
     
      
$page=1;
$pages=500; 
//print_r($dateNow);exit;
        $params = [
            'merchantCode' =>'Mx3WQufwEjf',
             'username' => 'u2d'.$account,
            'startDate' => $dateNow,
            'endDate' => $date3,
            'pageIndex' => $page,
            'pageSize' => $pages,
            'timestamp' => time(), //$conf['timestamp'],
        ];

        $b64 = base64($params);

        $sign = sign('t2Oesa1uZFiofHy2vnYmGvKzMFlXa7ZAmFxFkizANXqSEWQXNS0aE8bQxG124BxfDKun', $b64);
        $requestParams = $b64 . "." . $sign;
//print_r($params);
        $res = json_decode(httpRequest($requestParams, $url, "post", false));
           
                    
                    $info['list']=$res;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
            
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}

function lbrecord($account,$page,$starttime,$endtime){
    
   $_game = "LB";   // just change this game to LeiHuo, IMBoardGame( 不用), SGWIN（双赢彩票）, LB, SABA（沙巴电竞是U没有）, TCG, AVIA（泛亚电竞）
        $url =  "api.egame58.com/API" . "/" . $_game . "/BetRecord";

    
        $dateNow = date('Y-m-d H:i:s', strtotime(' - 30 days'));
        $date3 = date('Y-m-d H:i:s', time());
     
      
$page=1;
$pages=500; 
//print_r($dateNow);exit;
        $params = [
            'merchantCode' =>'Mx3WQufwEjf',
             'username' => 'u2d'.$account,
            'startDate' => $dateNow,
            'endDate' => $date3,
            'pageIndex' => $page,
            'pageSize' => $pages,
            'timestamp' => time(), //$conf['timestamp'],
        ];

        $b64 = base64($params);

        $sign = sign('t2Oesa1uZFiofHy2vnYmGvKzMFlXa7ZAmFxFkizANXqSEWQXNS0aE8bQxG124BxfDKun', $b64);
        $requestParams = $b64 . "." . $sign;
//print_r($params);
        $res = json_decode(httpRequest($requestParams, $url, "post", false));
           
                    
                    $info['list']=$res;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
            
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}

function tcgrecord($account,$page,$starttime,$endtime){
    
   $_game = "TCG";   // just change this game to LeiHuo, IMBoardGame( 不用), SGWIN（双赢彩票）, LB, SABA（沙巴电竞是U没有）, TCG, AVIA（泛亚电竞）
        $url =  "api.egame58.com/API" . "/" . $_game . "/BetRecord";

    
        $dateNow = date('Y-m-d H:i:s', strtotime(' - 30 days'));
        $date3 = date('Y-m-d H:i:s', time());
     
      
$page=1;
$pages=500; 
//print_r($dateNow);exit;
        $params = [
            'merchantCode' =>'Mx3WQufwEjf',
             'username' => 'u2'.$account,
            'startDate' => $dateNow,
            'endDate' => $date3,
            'pageIndex' => $page,
            'pageSize' => $pages,
            'timestamp' => time(), //$conf['timestamp'],
        ];

        $b64 = base64($params);

        $sign = sign('t2Oesa1uZFiofHy2vnYmGvKzMFlXa7ZAmFxFkizANXqSEWQXNS0aE8bQxG124BxfDKun', $b64);
        $requestParams = $b64 . "." . $sign;
//print_r($params);
        $res = json_decode(httpRequest($requestParams, $url, "post", false));
           
                    
                    $info['list']=$res;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
            
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}

function aviarecord($account,$page,$starttime,$endtime){
    
   $_game = "AVIA";   // just change this game to LeiHuo, IMBoardGame( 不用), SGWIN（双赢彩票）, LB, SABA（沙巴电竞是U没有）, TCG, AVIA（泛亚电竞）
        $url =  "api.egame58.com/API" . "/" . $_game . "/BetRecord";

    
        $dateNow = date('Y-m-d H:i:s', strtotime(' - 30 days'));
        $date3 = date('Y-m-d H:i:s', time());
     
      
$page=1;
$pages=500; 
//print_r($dateNow);exit;
        $params = [
            'merchantCode' =>'Mx3WQufwEjf',
             'username' => 'u2d'.$account,
            'startDate' => $dateNow,
            'endDate' => $date3,
            'pageIndex' => $page,
            'pageSize' => $pages,
            'timestamp' => time(), //$conf['timestamp'],
        ];

        $b64 = base64($params);

        $sign = sign('t2Oesa1uZFiofHy2vnYmGvKzMFlXa7ZAmFxFkizANXqSEWQXNS0aE8bQxG124BxfDKun', $b64);
        $requestParams = $b64 . "." . $sign;
//print_r($params);
        $res = json_decode(httpRequest($requestParams, $url, "post", false));
           
                    
                    $info['list']=$res;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
            
                
                
                //print_r($list);
                //echo 1;exit;
      
    
    
    
    
}


function imchessrecord($account,$page,$starttime,$endtime){
    global $lang;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
                $account='u2da'.$account;
$list= $client->imchessrecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
		// print_r($list);exit;
                $memberlist=$list['a'];
              //  $count=$list['b'][0]['count(*)'];
        
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->imchessrecord->no_data));    
                    
                }
                
    
    
    
}
 function httpRequest($data, $url, $method, $isFormData)
    {
        if ($isFormData) {
            $postvars = http_build_query($data) . "&amp;";
            $header = [];
        } else {
            $header[] = 'Content-Type:text/plain';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if ($method === 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $isFormData ? $postvars : $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    function base64($params)
    {
        ksort($params);
        $b64 = base64_encode(json_encode($params));
        return $b64;
    }
    function sign($hash, $b64)
    {
        return md5($hash . $b64);
    }
    
    
    
    function kychessrecord($account,$page,$starttime,$endtime){
        global $lang;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
                $account='u2d'.$account;
$list= $client->kyqprechord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
		// print_r($list);exit;
                $memberlist=$list['a'];
              //  $count=$list['b'][0]['count(*)'];
        
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->kychessrecord->no_data));    
                    
                }
                
    
    
    
}


function imdjrecord($account,$page,$starttime,$endtime){
    global $lang;
    $account='u2da'.$account;
    		$client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
                
                
               
                
                if($starttime==''||$endtime==''){
			
			$starttime=date("Y-m-d");
			$endtime=date('Y-m-d',strtotime('+1 day'));
		}
$list= $client->imdjrecord($account,$page,$starttime,$endtime);
		//$list= $client->qprechord($account,$page,$starttime,$endtime);
                //
        // print_r($_POST);
	//	 print_r($list);exit;
                $memberlist=$list['a'];
                //$count=$list['b'][0]['count(*)'];
              
                if(!empty($memberlist)){
                    
                    $info['list']=$memberlist;
                    $info['total']='';
                    
                 echo    json_encode(array('status'=>'1','info'=>$info));
                    
                }else{
                    
                 echo    json_encode(array('status'=>'0','info'=>$lang->imdjrecord->no_data));    
                    
                }
                
    
    
    
}


  function imnativerecordbuyongle($account,$page,$starttime,$endtime){
      
      
       $date = date("Y-m-d h:m:s A");
    // $startTime = date("Y-m-d h:m:s A", strtotime($date) - (10 * 60) );
    $startTime = date("Y-m-d h:m:s A", (strtotime("-30 days")));

    $postData = [
        'timeStamp' => generate_imsb_time_stamp(return_timestamp(),'cae2e1ed7185b841'),
        'memberCode' => $account,
        'currencyCode' => "RMB",
        'sportsId' => 1,
        'dateFilterType' => "1",
        'startDateTime' => $startTime,
        'endDateTime' => $date,
        'betStatus' => 1,
        'languageCode' => "CHS"
    ];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://u2d668.sfsbws.imapi.net/api/getBetDetails",
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POST => 1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => array(
            'Content-Type:application/json; charset=utf-8'
        ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);

   // print_r(json_decode($response));
        echo    json_encode(array('status'=>'1','info'=>json_decode($response))); 
  }
  function ebetrecord($account,$page,$starttime,$endtime){
      date_default_timezone_set('Asia/Hong_Kong');
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
      include_once("phpseclib/Crypt/RSA.php");
//echo 123;exit;
$privateKey = '-----BEGIN PRIVATE KEY-----
MIIBVQIBADANBgkqhkiG9w0BAQEFAASCAT8wggE7AgEAAkEAnsIsZSTjVIktHV9k
O/+PVh8h49zYflhnghpKpOToW5URJAKR1M35rEWs2Vq7wlx8G9xWGA2S05IpVOjV
vobCCwIDAQABAkAWhrCr7U8ASLKJD2b2iG17J9G0NjrVuo99S2O5/+zkSYjbueny
6npBAOFtJ7JJwzasOdQhpqrzNq+HJ2HAr1tRAiEA+xpWvosMGvj18idV0UtsQbnJ
my6a2gN0AjhP+ChM/DkCIQCh2stNB55vMPtBgg8P64p+SYCztqIxwTvyeqQ867r4
YwIhANAvxju0jRTP1RowArbEEb1si/pdaYXX1xcAGU1mHG4BAiBi/8cWSLC55kXo
3bqEzFebwy27vtwafs1CFY3bzXxBbQIhANDI9bw83vAy40VVJvS3SODA3qTw1gwf
VeOe19MPeXGZ
-----END PRIVATE KEY-----';

$api_url = "http://u2dcny.ebet.im:8888/";
//$account = 'test02';

$endTime = date('Y-m-d H:m:s');
$startTime = date('Y-m-d H:m:s', strtotime('-30 days', strtotime(date('Y-m-d 00:00:00'))));

$url = 'api/userbethistory';
$time = time();

$rsa = new Crypt_RSA();
$rsa->loadKey($privateKey);    
$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
$rsa->setHash("md5");
//  Change signature if account search
  $signature = $rsa->sign($account.$time);
  //print_r($signature);exit;
//$signature = $rsa->sign($time);
$encrypted= base64_encode($signature);
$params = [
        "channelId" => 1340,
        "timestamp" => $time,
        "signature" => $encrypted,
         "username" => $account,
        "currency" => "USD",
        // "subChannelId" => 0,
        "startTimeStr" => $startTime,
        "endTimeStr" => $endTime,
        "pageNum" => 1,
        "pageSize" => 50,
       // "betStatus" => 1,
        "gameType" => 1,
        "isSeparate" => false,
        "judgeTime" => 0,
];
        
    $data = json_encode($params);

    $curl = curl_init($api_url.$url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        )
    );

    $result = curl_exec($curl);
    //print_r($result);exit;
      echo    json_encode(array('status'=>'1','info'=>json_decode($result))); 
  //  return json_decode($result);
      
  }



/**
 * Get the BG betting Records
 *
 * @param string $account
 * @param int $page
 * @param time $starttime
 * @param time $endtime
 * @return mixed
 */
function bgrecord($account,$page,$starttime,$endtime) {
    global $lang;
    // http://newrecordliushui.ybet102.com/bg.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");

    if($starttime==''||$endtime=='') {
        $starttime=date("Y-m-d");
        $endtime=date('Y-m-d',strtotime('+1 day'));
    }
    
    // chinese project prefix is u2d. if english then prefix "ind"
    $account='u2d'.$account;

    $list = $client->bgrecord($account,$page,$starttime,$endtime);

    $memberlist = $list['a'];
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    if(!empty($memberlist)) {
        $info['list']= $memberlist;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->bgrecord->no_data));    
    }
} // End of bgrecord Function

function gdrecord($account, $page = 1) {
    global $lang;
    // http://newrecordliushui.ybet102.com/bg.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");

    // if($starttime==''||$endtime=='') {
    //     $starttime=date("Y-m-d");
    //     $endtime=date('Y-m-d',strtotime('+1 day'));
    // }
    
    // chinese project prefix is u2d. if english then prefix "ind"
    $account='u2d'.$account;

    $list = $client->gdrecord($account,$page);

    $memberlist = $list['a'];
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    if(!empty($memberlist)) {
        $info['list'] = $memberlist;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->gdrecord->no_data));    
    }
} // End of gdrecord Function

function abrecord($account, $page = 1) {
    global $lang;
    // http://newrecordliushui.ybet102.com/bg.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");

    // ALL BET data response from their client name is exacly same as our user account name
    // chinese project prefix is u2d. if english then prefix "ind"
    // $account='u2d'.$account;

    $list = $client->abrecord($account,$page);

    $memberlist = $list['a'];
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    if(!empty($memberlist)) {
        $info['list'] = $memberlist;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->abrecord->no_data));    
    }
} // End of abrecord Function

function ptrecord($account, $page = 1, $perpage = 200) {
    global $lang;
    // http://newrecordliushui.ybet102.com/bg.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    // $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");

    // ALL BET data response from their client name is exacly same as our user account name
    // chinese project prefix is u2d. if english then prefix "ind"
    // $account='u2d'.$account;

    $entityKey = '0f84478f1a36c9ac8365a5af2805da975ba4944524b178f54c5b313a9caf66e784fc7ab55c9e0af49dbfa926aa0d5029dad2b6aac14f60f20c5487787d0c7de1';


    $api = 'https://kioskpublicapi-am.hotspin88.com/player/games';

    date_default_timezone_set('Asia/Shanghai');

    $prefix = 'u2d';
    $account = $prefix.$account;
    
    // Start date in yyyy- mm-dd hh:ii:ss format
    $startdate = date('Y-m-d H:m:s', strtotime('-30 days', strtotime(date('Y-m-d H:m:s'))));
    $enddate = date('Y-m-d H:m:s');
    
    $params = [
        'playername' => $account,
        // 'exitgame' => 1,
        // 'showdetailedinfo' => 1,
        // 'showbonustype' => 1,
        // 'excludezero' => 1,
        // 'progressiveonly' => 1,
        'startdate' => $startdate,
        'enddate' => $enddate,
        // 'clientinfo' => 1,
        'page' => $page,
        'perPage' => $perpage,
        // 'showgameshortname' => 1,
    ];



    //print_r($params);
    $header[] = "X_ENTITY_KEY:$entityKey";
    $ch = curl_init();


    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER , $header );
    curl_setopt($ch, CURLOPT_SSLCERT , 'CNY/CNY.pem' );
    curl_setopt($ch, CURLOPT_SSLKEY , 'CNY/CNY.key' );
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $list = json_decode($result);
    
    if(isset($list->result)) {
        // echo "<pre>";
        // print_r($list->result);
        // echo "</pre>";


        if(!empty($list->result)) {
            $info['list'] = $list->result;
            // $info['total']=$count;
            echo json_encode(array('status'=>'1','info'=>$info));
        } else {
            echo json_encode(array('status'=>'0','info'=>$lang->ptrecord->no_data));    
        }
    }
    

        // $memberlist = $list;
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    
} // End of ptrecord Function

function mgrecord($account, $page = 1, $page_size = 50) {

    global $lang;
    $prefix = 'u2d';
    $account = $prefix.$account;


    $access_token= mgAuthenticate();
    if($access_token==-1){
        return -1;
    }

    
    $account_ext_ref = strtoupper($account);

    $start_time = date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))));
    $end_time = date('Y-m-d');
    $include_transfers = false;
    $include_end_round = false;
    // $page_size = $page_size;
    $company_id = 1090082;
    // $page = 1;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        // CURLOPT_URL => 'https://api.adminserv88.com/v1/feed/transaction?start_time='.$start_time.'&end_time='.$end_time.'&include_transfers='.$include_transfers.'&include_end_round='.$include_end_round.'&page_size='.$page_size.'&company_id='.$company_id.'&page='.$page.'&ext_ref='.$account_ext_ref,
        CURLOPT_URL => 'https://api.adminserv88.com/v1/feed/transaction?start_time='.$start_time.'&end_time='.$end_time.'&include_transfers='.$include_transfers.'&include_end_round='.$include_end_round.'&page_size='.$page_size.'&company_id='.$company_id.'&page='.$page.'&account_ext_ref='.$account_ext_ref,
        CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'X-DAS-TZ: UTC+8',
            'X-DAS-CURRENCY: CNY',
            'X-DAS-LANG: zh-CN',
            'X-DAS-TX-ID: TEXT-TX-ID',
            'Content-Type: application/json;charset=UTF-8',
            'Authorization: Bearer '.$access_token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // $obj = json_decode($response);
    $arr = json_decode($response, true);
    // var_dump($results->response);
    // return $results;
    
    $data = [];
    
    if(isset($arr['data']) && !empty($arr['data'])) {
       
        foreach($arr['data'] as $key => $value) {
            
            // if($data[$key]['category'] == 'WAGER') {
            if( $arr['data'][$key]['category'] == 'WAGER' && $arr['data'][$key]['account_ext_ref'] == $account_ext_ref ) {
                
                $data[] = $value;
            }

        }

        $info['list'] = $data;
        echo json_encode(array('status'=>'1','info'=>$info));

    } else {
        
        echo json_encode(array('status'=>'0','info'=>$lang->mgrecord->no_data));    
    }
    
} // End of mgrecord Function

function mgAuthenticate() {   // 认证

    $url = 'https://api.adminserv88.com/oauth/token'; 
    $apiUsername = 'ma06sa01YB_API';
    $apiPassword = '9kp6yfd8'; 
    $auth64 ='Basic R2FtaW5nTWFzdGVyMUNOWV9hdXRoOjlGSE9SUWRHVFp3cURYRkBeaVpeS1JNZ1U='; 
    $header   = array();
    $header[] = "Authorization:Basic R2FtaW5nTWFzdGVyMUNOWV9hdXRoOjlGSE9SUWRHVFp3cURYRkBeaVpeS1JNZ1U=";    //Production: R2FtaW5nTWFzdGVyMUNOWV9hdXRoOjlGSE9SUWRHVFp3cURYRkBeaVpeS1JNZ1U=
    $header[] = "X-DAS-TZ:UTC"; 
    $header[] = "X-DAS-CURRENCY:CNY"; 
    $header[] = "X-DAS-TX-ID:TEXT-TX-ID"; 
    $header[] = "X-DAS-LANG:en"; 
    
    $post_data = array ( 
        'grant_type' => 'password',
        'username' => $apiUsername,
        'password' => $apiPassword 
    ); 

    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL, $url); 

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 


    curl_setopt($ch, CURLOPT_POST, 1); 

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 



    $output = curl_exec($ch); 
    // print_r($output);exit;
    $obj = json_decode($output);
    if(isset($obj->access_token)){

        return($obj->access_token);

    } else {
        return -1;
    }
}

// /**
//  * Return All Bet Records
//  * API: betlog_pieceof_histories_in30days
//  *
//  * @param string $account
//  * @param mixed $startTime
//  * @param integer $limit
//  * @return void
//  */
// function abrecord($account, $startTime = null, $limit = 20) {
//     /**
//      * LIMITATION:
//      * Call frequency: 16 Times/10 Mins (per propertyId)
//      */

//     // $starttime example format: 2015-12-24 23:01:59
    
//     if($startTime == null) {
//         //  Start time will be set to 1 hour ago.
//         $startTime = date('Y-m-d H:m:s', strtotime('-1 hours', strtotime(date('Y-m-d H:m:s'))));
//     }
     
//     $endTime = date('Y-m-d H:m:s', strtotime('+1 hour', strtotime($startTime)));

//     $url = "https://api3.abgapi.net/";
//     $api = "betlog_pieceof_histories_in30days";
//     $desKey = "wKQpoHMSic62G8iezg7Hrj2mCdqe/sDt";
//     $propertyId = "5695129";
//     $md5Key = "4P8NHRwBnKLkuG6Uv8WcOZVezbUdOaLjmRg7RKkrLBc=";
//     $agent='ks888y5';

//     // chinese project prefix is "u2d". if english then prefix "ind"
//     $account='u2d'.$account;

//     $randomNumber = mt_rand();
//     $params = [
//         'random' => $randomNumber,
//         'startTime' => $startTime,
//         'endTime' => $endTime,
//         // 'agent' = $agent
//     ];
    
//     $real_param = http_build_query($params);

//     // pkcs5Pad
//     $blocksize = mcrypt_get_block_size (MCRYPT_TRIPLEDES, MCRYPT_MODE_CBC);
//     $pad = $blocksize - (strlen($real_param) % $blocksize);
//     $pkcs5Pad = $real_param . str_repeat(chr($pad), $pad);


//     $encryptText =  mcrypt_encrypt(MCRYPT_TRIPLEDES, base64_decode($desKey), $pkcs5Pad, MCRYPT_MODE_CBC, base64_decode("AAAAAAAAAAA="));
    
//     $data = base64_encode($encryptText);

//     $to_sign = $data.$md5Key;
//     $sign = base64_encode(md5($to_sign, TRUE));
    
//     $apiUrl = $url.$api."?".http_build_query(array('data' => $data, 'sign' => $sign, 'propertyId' => $propertyId));

//     $curl = curl_init();
//     curl_setopt($curl, CURLOPT_URL, $apiUrl);
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($curl, CURLOPT_HEADER, 0);
//     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
//     $result = curl_exec($curl);

//     curl_close($curl);


//     $json = json_decode($result, true);

//     $data = [];
//     // $limit = 10;
//     $i = 0;
//     foreach ($json as $key => $value) {
//         if($key == 'histories') {
//             foreach ($value as $betKey => $betData) {
//                 if($betData['client'] == $account) {
//                     if($limit == $i) {
//                         break;
//                     }
//                     $data[] = $betData;
//                     $i += 1;
//                 }
//             }
//             break;
//         }
//     }
    
//     if(!empty($data)) {
//         $info['list'] = $data;
//         echo json_encode(array('status'=>'1','info'=>$info));
//     } else {
//         echo json_encode(array('status'=>'0','info'=>'No data'));    
//     }
    
// }


function cqrecord($account, $page = 1) {
    global $lang;
    // http://newrecordliushui.ybet102.com/cq.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");


    // chinese project prefix is u2d. if english then prefix "ind"
    $account='u2d'.$account;

    $list = $client->cqrecord($account,$page);

    $memberlist = $list['a'];
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    if(!empty($memberlist)) {
        $info['list'] = $memberlist;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->cqrecord->no_data));    
    }
} // End of cqrecord Function


function vrrecord($account, $page = 1) {
    global $lang;
    // http://newrecordliushui.ybet102.com/bg.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");

    // ALL BET data response from their client name is exacly same as our user account name
    // chinese project prefix is u2d. if english then prefix "ind"
    // $account='u2d'.$account;

    $list = $client->vrrecord($account,$page);

    $memberlist = $list['a'];
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    if(!empty($memberlist)) {
        $info['list'] = $memberlist;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->vrrecord->no_data));    
    }
} // End of vrrecord Function


function cmdrecord($account, $page = 1) {
    global $lang;
    // http://newrecordliushui.ybet102.com/bg.start.php
    // $client = new PHPRPC_Client("http://j9newrecordchaliushuiasd.32sun.com/phprpc/qprecord.php");
    $client = new PHPRPC_Client("http://newrecordliushui.ybet102.com/phprpc/qprecord.php");

    // ALL BET data response from their client name is exacly same as our user account name
    // chinese project prefix is u2d. if english then prefix "ind"
    $account='u2d'.$account;

    $list = $client->cmdrecord($account,$page);

    $memberlist = $list['a'];
    // $count=$list['b'][0]['count(*)'];
    
    // foreach($memberlist as $key=>$val){
    //     unset($memberlist[$key]['other1']);
    //     unset($memberlist[$key]['other2']);
    // }
    
    if(!empty($memberlist)) {
        $info['list'] = $memberlist;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->cmdrecord->no_data));    
    }
} // End of vrrecord Function

// --- TCG SBO START ---
function tcg_sbo_record($account, $page = 1) {
    global $lang;
    // YYYY-MM-DD HH24:MI:SS
    $format = 'Y-m-d H:i:s';

    $from = date($format, strtotime('-6 days', strtotime(date($format))));
    $to = date($format,strtotime('+1 day'));

    $account_pre = 'u2d';
    $gaccount = strtolower($account_pre.$account);

    $params = [
        'method' => 'spmbd', // Get the betting details of member sports games
        'start_date' => $from,
        'end_date' => $to,
        'username' => $gaccount,
        'page' => $page,
        // 'settled' => true,
    ];
    
    $curl = curl_init();

    $post_fields = http_build_query( tcg_sbo_buildParams( $params ) );

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://www.connect6play.com/doBusiness.do',
        CURLOPT_FOLLOWLOCATION => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_POST => 1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_TIMEOUT => 100,
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded'
        ],
    ));
    
    $response = curl_exec($curl);
    
    if (curl_errno($curl))
    {
        return $lang->tcg_sbo_record->error;
    }
    curl_close($curl);

    $response = json_decode($response);

    $betdata = [];
    foreach ($response as $key => $value) {
        if($key == 'status') {
            if($value > 0) {
                return $betdata;
            }
        }
        
        if($key == 'details') {
            foreach($value as $key2 => $betObj) {
                if($betObj->username == $gaccount) {
                    $betdata[] = $betObj;
                }
            }
            break;
        }
    }
    // return $betdata;

    if(!empty($betdata)) {
        $info['list'] = $betdata;
        // $info['total']=$count;
        echo json_encode(array('status'=>'1','info'=>$info));
    } else {
        echo json_encode(array('status'=>'0','info'=>$lang->tcg_sbo_record->no_data));    
    }
}

function tcg_sbo_buildParams($data)
{
    $params = tcg_sbo_encryptText( json_encode($data), 'm3S8LXbp' );
    $signature = hash( 'sha256', $params.'2oPvoH8Cu8qEwWY4' );

    $data = [
        'merchant_code' => 'atpu2dcny',
        'params'        => $params,
        'sign'          => $signature
    ];

    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";

    return $data;
}

function tcg_sbo_encryptText( $plainText, $key )
{
    $padded = tcg_sbo_pkcs5_pad($plainText, 8);
    $encText = openssl_encrypt($padded, 'des-ecb', $key, OPENSSL_RAW_DATA, '');
    
    return base64_encode($encText);
}

function tcg_sbo_pkcs5_pad ($text, $blocksize)
{
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}
// --- TCG SBO END ---


function generate_imsb_time_stamp($data, $secret_key)
{
    $secret_key = md5(utf8_encode($secret_key), true);
    $result = openssl_encrypt(
        $data,
        "aes-128-ecb",
        $secret_key,
        $options = OPENSSL_RAW_DATA
    );
    return base64_encode($result);
}
function return_timestamp()
{
    date_default_timezone_set("GMT");
    $date = date("D, d M Y H:i:s") . " GMT";
    return $date;
}

function formatted_date($date)
{
    date_default_timezone_set("Etc/GMT+4");
    $date = date_create($date);

    return date_format($date, "c");
}