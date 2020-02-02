<?php
namespace hsC;
class clothesseason{
	
	public function index(){
		$_GET['pid'] = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
		$db = \hsTool\db::getInstance('clothes_season');
		if(empty($_GET['pid'])){
			$clothes_season = $db->order('s_order asc')->fetchAll();
		}else{
			$clothes_season = $db->order('s_order asc')->where('s_pid = ?', array($_GET['pid']))->fetchAll();
		}
		if(empty($clothes_season)){exit(jsonCode('empty', ''));}
		$season = array();
		foreach($clothes_season as $s){
			$season[$s['s_id']] = $s['s_name']; 
		}
		exit(jsonCode('ok', $season));
	}
	
}