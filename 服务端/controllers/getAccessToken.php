<?php
namespace hsC;
class getAccessToken{
	public function index(){
		$db = \hsTool\db::getInstance('access_tokens');
		$token = array(
            'token' => uniqid(),
            'time'  => date("Y年m月d日,H:i:s",time())
        );
        $db->add($token);
		exit(jsonCode('ok', $token));
	}	
}