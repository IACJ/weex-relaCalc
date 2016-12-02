<?php 
header('Access-Control-Allow-Origin: *');  
function getIP(){
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$realip = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR")) {
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$realip = getenv("HTTP_CLIENT_IP");
		} else {
			$realip = getenv("REMOTE_ADDR");
		}
	}
	return $realip;
}

$fail = json_encode(array('ok' => false));

$ip = getIP();

$ip_json=@file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ip); 
if (empty($ip_json)) {
	echo $fail;
}

$ip_arr=json_decode($ip_json,1);//JSON格式字符解码

if($ip_arr['code']==0){   
	$country = $ip_arr['data']['country'];//国家，如中国
	$area = $ip_arr['data']['area'];//地区，如华南
	$region = $ip_arr['data']['region'];//省区，如广东省
	$city = $ip_arr['data']['city'];//城市，如汕头市
	$isp = $ip_arr['data']['isp']; //运营商，如电信
	$addr = $country.$area.$region.$city;
	echo json_encode(array('ok'=>true,'addr'=>$addr));
}else{
	echo $fail;
}