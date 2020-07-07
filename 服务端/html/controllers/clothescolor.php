<?php
namespace hsC;
class clothescolor{
	
	public function index(){
		$_GET['pid'] = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
		$db = \hsTool\db::getInstance('clothes_color');
		if(empty($_GET['pid'])){
			$clothes_color = $db->order('c_order asc')->fetchAll();
		}else{
			$clothes_color = $db->order('c_order asc')->where('c_pid = ?', array($_GET['pid']))->fetchAll();
		}
		if(empty($clothes_color)){exit(jsonCode('empty', ''));}
		$color = array();
		foreach($clothes_color as $c){
			$color[$c['c_id']] = $c['c_name']; 
		}
		exit(jsonCode('ok', $color));
	}
	
}