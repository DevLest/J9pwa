<?php
/**
 *公共的函数库
 */
 
 
/**
 * 定义数组工厂
 * @param $array_type    数组类型
 * @return string
 */
function array_factory($array_type)
{
	switch($array_type)
	{
		case 1://站内信 状态 status
		return array("未读","已读");
		default:return array("未定义");
	}
}

/**
 * 转换为数组中表示的值
 * @param $array_type    数组类型
 * @param $type    需要处理的type
 * @return string
 */
function switch_array_view($array_type,$type)
{
	$myarray=array_factory($array_type);
	return $myarray[$type];
}

/**
 * 转换为数组中表示的值带字体颜色
 * @param $array_type    数组类型
 * @param $type    需要处理的type
 * @return string
 */
function switch_color_view($array_type,$type)
{
	$myarray=array_factory($array_type);
	$color = "#000";
	if($type == 0)
	{
		$color = "#906";
	}elseif ($type == 1){
		$color = "#96f";
	}elseif($type == 2){
		$color = "#699";
	}
	return "<span style='color:".$color.";'>".$myarray[$type]."</span>";
}

/**
 * 将定义的数组转化为下拉框
 * @param $array_type    数组类型
 * @param $type		是否包已选择项
 * @param $isall		是否包默认项
 * @return string
 */
function bind_array_select($array_type,$type='',$isall=false)
{
	$myarray=array_factory($array_type);
	$returnstr='';

	if($isall)
	{
		$returnstr .= "<option value='-1'>请选择</option>";
	}
	foreach($myarray as $k=>$v)
	{
		if($v !='')
		{
			if($type !='' && $type == $k)
			{
				$selected = "selected";
			}
			$returnstr .= "<option value='".$k."' ".$selected.">".$v."</option>";
		}
		$selected='';
	}
	return $returnstr;
}

//加密解密
function simple_crypt($str,$type = 1)
{
    $key = md5("simple");
    $cipher = MCRYPT_RIJNDAEL_128;
    $modes = MCRYPT_MODE_ECB;
    $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher,$modes),MCRYPT_RAND);
    if($type ==1)
    {
        $ptstr = bin2hex(mcrypt_encrypt($cipher,$key,trim($str),$modes,$iv));
    }else{
        $str = pack("H*",$str);
        $ptstr = trim(mcrypt_decrypt($cipher,$key,$str,$modes,$iv));
    }
    return $ptstr;
}

?>
