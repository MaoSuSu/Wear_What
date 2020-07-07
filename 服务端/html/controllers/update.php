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
			"url" => "https://maosu.lanzous.com/icic1ud"
		);
		//最新版本号：2.1.1,调试基座版本：9.7.14
		if ($version != "2.1.1") { 
			$rsp["status"] = 1;  
		}   
		exit(jsonCode('ok', $rsp));
	}

}
