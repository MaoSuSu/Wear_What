<?php
namespace hsC;
class update{

	//检查客户端是否需要更新
	public function check(){		 
		$version = $_POST["version"]; 
		$rsp = array(
			//默认返回值，不需要升级  
			"status" => 0 ,
			//应用升级包下载地址  
			"url" => "https://share.weiyun.com/5Xlz4kH"
		);
		//最新版本号
		if ($version != "2.0.0") { 
			$rsp["status"] = 1;  
		}   
		exit(jsonCode('ok', $rsp));
	}

}