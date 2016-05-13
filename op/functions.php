<?php

/**
 * 接口定义(公共方法)
 * @author	 HJ
 * @date	2012-12-10
 * */
interface icommon
{
	public static function mk_time();
	
	public static function getIps();
	
	public static function &env($key, $default = null);
	
	public static function getuuid();
	
	public static function logs($method, $msg);
	
	public static function get_real_ip();
	
	public static function str_replaces($str, $type=0);
	
	public static function curl_file_get_contents($durl);
	
}

/**
 * 接口定义(功能方法)
 * @author	 HJ
 * @date	2012-12-10
 * */
interface ifunc
{
	/*测试方法*/
	public static function test($_info);
}

/**
 * 接口定义(多个继承)
 * @author	 HJ
 * @date	2012-12-10
 * */
interface iface extends icommon,ifunc{}

/**
 * 通用方法类 (抽象类)
 * @author	 HJ
 * @date	2012-09-28
 * */
abstract class common implements iface
{
	/**
	 * @todo 获得毫秒级的时间戳
	 * @return string $返回转换好的时间戳
	 */
	public static function mk_time(){
		$time = explode ( " ", microtime () );
		$hm = $time [0] * 1000;
		$_hm_arr = explode(".",$hm);

		if (strlen($_hm_arr[0]) == 1){
			$_hm_arr[0] = "00".$_hm_arr[0];
		}

		if (strlen($_hm_arr[0]) == 2){
			
			$_hm_arr[0] = "0".$_hm_arr[0];
		}

		$time2 = $time [1] . $_hm_arr[0];
		
		return $time2;
	}

	/**
	 * @todo 获得访问ip
	 * @return null|string $返回地区名称
	 */
	public static function getIps(){
		$val = self::env('REMOTE_ADDR');
		if ($val === self::env('SERVER_ADDR') && $ip = self::env('HTTP_PC_REMOTE_ADDR')) {
			return $ip;
		}

		return $val;
	}
	
	public static function &env($key, $default = null){
		$val = null;
		if (isset ($_SERVER[$key])) {
			$val = &$_SERVER[$key];
		}elseif (isset ($_ENV[$key])) {
			$val = &$_ENV[$key];
		}elseif (getenv($key) !== false) {
			$val = &getenv($key);
		}

		if ($val !== null) {
			return $val;
		} else {
			return $default;
		}
	}

	/**
	 * @todo 生成UUID
	 * @return string $唯一的UUID
	 */
	public static function getuuid(){
		mt_srand((double)microtime()*10000);
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
		return $uuid;
	}

	/**
	 * @todo 接口执行输出日志
	 * @param $method $接口名称
	 * @param $msg $操作说明
	 * @param string $_file
	 */
	public static function logs($method, $msg, $_file = "../soapLog.md"){
		$dt = date('Y-m-d H:i:s');
		$content = "\n";
		$content .= "【方法名:{$method}   {$dt}】\n";
		$content .= "{$msg}\n";
		
		if ( ! file_exists($_file)){
			$_f = fopen($_file,'w');
		}else{
			$_f = fopen($_file,'a');
		}
		
		flock($_f,LOCK_EX);
		fwrite($_f,$content);
		flock($_f,LOCK_UN);
		fclose($_f);
	}

	/**
	 * @todo 获取客户端IP
	 * @return bool
	 */
	public static function get_real_ip(){
		$ip=false;

		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if($ip){
				 array_unshift($ips, $ip); $ip = FALSE;
			}

			for($i = 0; $i < count($ips); $i++){
				if(!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
					$ip = $ips[$i];
					break;
				}
			}
		}

		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}

	/**
	 * @todo 过滤登录名特殊字符
	 * @param $str $需要过滤的字符
	 * @param int $type $过滤类型
	 * @return string
	 */
	public static function str_replaces($str, $type=0){
		if (intval($type) > 0){
			$str = str_replace("@","__",$str);
		}
		
	   $str = str_replace(":","",$str);
	   $str = str_replace("'","",$str);
	   $str = str_replace("\\","",$str);
	   $str = str_replace(" ","",$str);
	   $str = str_replace("%20","",$str);
	   $str = str_replace(";","",$str);
	   $str = str_replace(",","",$str);
	   $str = str_replace("\"","",$str);
	   $str = str_replace("?","",$str);
	   //增加过滤部分中文特殊字符，如：双引／单引
	   $str = str_replace("“","",$str);
	   $str = str_replace("’","",$str);
	   $str = str_replace("　","",$str);
	   $str = str_replace(">","",$str);
	   $str = str_replace("<","",$str);
	   
	   return trim($str);
	}

	/**
	 * @todo curl文件内容抓取
	 * @param $durl $抓取的URL
	 * @return mixed
	 */
	public static function curl_file_get_contents($durl){
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $durl);
	   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	   curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	   curl_setopt($ch, CURLOPT_REFERER, 1);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   $r = curl_exec($ch);
	   curl_close($ch);
	
	   return $r;
	}
}

/**
 * @todo 接口方法类
 * Class method
 */
abstract class method extends common {
	/**
	 * @todo 接口参数验证
	 * @param $_info $接口参数
	 * @param bool $_json $是否返回json串
	 * @return array|mixed|string $返回验证状态
	 */
	private static function validate($_info, $_json=true){
		if (is_string($_info)){
			$_info = json_decode($_info, true);
		}

		if ( ! is_array($_info)){
			return method::output(0,'参数不正确！','601',$_json);
		}

		$_info = method::addslash_deep($_info);
		
		if ( ! isset($_info['keycode'])){
			return method::output(0,'不存在验证码！','602',$_json);
		}

		if ( ! method::check_keycode($_info['keycode'])){
			return method::output(0,'非法调用接口！','603',$_json);
		}

		return $_info;
	}

	/**
	 * @todo 请求变量进行处理函数系列
	 * @param $val
	 * @param bool $force
	 * @return array|string
	 */
	private static function addslash_deep($val, $force = false){
		if (!get_magic_quotes_gpc() || $force){
			if(is_array($val)){
				foreach($val as $key => $value){
					if(is_array($value)){
						$val[$key] = method::addslash_deep($value, $force);
					}else{
						$val[$key] = addslashes($value);
					}
				}
			}else{
				$val = addslashes($val);
			}
		}
		return $val;
	}

	/**
	 * @todo 检查是否非法调用接口
	 * @param $keycode $加密串
	 * @return bool $返回 true or false
	 */
	private static function check_keycode($keycode){
		$ckg = "gzRN53VWRF9BYUXo";
		
		if ($keycode != $ckg){
			return false;
		}
		
		return true;
	}

	/**
	 * @todo 接口状态信息
	 * @param $_flag $状态标记
	 * @param $_msg $状态
	 * @param $_stats_tag $是否转换为json串输出
	 * @param bool $_is_json $是否转换为json串输出
	 * @return string $状态信息
	 */
	private static function output($_flag, $_msg, $_stats_tag, $_is_json = false){
		$_arr = array(
			'flag' => $_flag,
			'msg' => $_msg,
			'info' => $_stats_tag
		);
		
		if ($_is_json){
			return json_encode($_arr);
		}else{
			return json_decode($_arr);
		}
	}

	/**
	 * @todo 测试
	 * @param $_info
	 * @return string
	 */
	public static function test($_info){
		$_json = true;
		try{
			$_message = method::validate($_info, $_json);
			if (is_string($_message)){
				return $_message;
			}
			
			$_info = $_message;

			return method::output(0, $_info, '500', $_json);
			
		}catch (cusException $e){
			return method::output(0, $e->getMessage(), '505', $_json);
		}
	}
	
}