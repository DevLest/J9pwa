<?php
	/**
     *
     * 
     * 计算签名 
     * 
     * 
     * 
     * 
     * */ 
	class kakalianpay {
		/**
		  * 计算签名原文
		  * 
		  * 
		  * 
		  **/
		public function sign_src($sign_fields, $map, $md5_key) {
			// 排序-字段顺序
			sort($sign_fields);
			$sign_src = "";
			foreach($sign_fields as $field) {
				$sign_src .= $field."=".$map[$field]."&";
			}
			$sign_src .= "KEY=".$md5_key;
			
			return $sign_src;
		}
		/**
		 *
		 * 计算md5签名
		 * 
		 * 返回的签名数据位小写(给支付平台签名后的字母应为大写字母)
		 * 在上传报文的时候需要注意将小写字母转为大写字母
		 * 
		 **/	
		public function sign_mac($sign_fields, $map, $md5_key) {
			$sign_src = $this->sign_src($sign_fields, $map, $md5_key);
			return md5($sign_src);
		}
	}
?>