<?php 
error_reporting(0);
@unlink(getcwd() . '/fblogin.log');

trait DARKNETHOST{
	
	public function pisahin($var1="", $var2="", $pool){

		$temp1 = strpos($pool,$var1)+strlen($var1);
		$result = substr($pool,$temp1,strlen($pool));
		$dd=strpos($result,$var2);
		if($dd == 0){
			$dd = strlen($result);
		}

		return substr($result,0,$dd);
	}
	
	public function CurlEngine($url, $post=false, $headers=false){
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (J2ME/MIDP; Opera Mini/5.0 (Linux; U; Android 2.2; fr-lu; HTC Legend Build/24.838; U; en) Presto/2.5.25 Version/10.54');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/fblogin.log');
		curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/fblogin.log');
		if($post){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if($headers)
		{
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
		}
		$response = curl_exec($ch);
		curl_close($ch);
		return  $response;
	}
	
}

class FACEBOOK{

	use DARKNETHOST;
	
	public function LoginFB($username, $password){
		
		$get = DARKNETHOST::CurlEngine('https://mbasic.facebook.com');
		
		$post = array(
			'lsd' => DARKNETHOST::pisahin('<input type="hidden" name="lsd" value="', '" autocomplete="off" />', $get),
			'm_ts' => DARKNETHOST::pisahin('<input type="hidden" name="m_ts" value="', '" />', $get),
			'li' => DARKNETHOST::pisahin('<input type="hidden" name="li" value="', '" />', $get),
			'try_number' => '0',
			'unrecognized_tries' => '0',
			'email' => $username,
			'pass' => $password,
			'login' => 'Masuk',
			'_fb_noscript' => 'true'
		);
		$login = DARKNETHOST::CurlEngine('https://mbasic.facebook.com/login.php', http_build_query($post));
		if(preg_match('#/login/device-based/update-nonce/|/login/save-device/|checkpointSubmitButton|MCheckpointController#', $login)){
			return "OK";
		}else{
			return "NO";
		}
	}
}

$darknethost = new FACEBOOK();