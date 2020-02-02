<?php
namespace hsC;
class clothestype{
	public function index(){
		$_GET['pid'] = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
		$db = \hsTool\db::getInstance('clothes_type');
		if(empty($_GET['pid'])){
			$clothes_type = $db->order('t_order asc')->fetchAll();
		}else{
			$clothes_type = $db->order('t_order asc')->where('t_pid = ?', array($_GET['pid']))->fetchAll();
		}
		if(empty($clothes_type)){exit(jsonCode('empty', ''));}
		$type = array();
		foreach($clothes_type as $t){
			$type[$t['t_id']] = $t['t_name']; 
		}
		exit(jsonCode('ok', $type));
	}
	
}