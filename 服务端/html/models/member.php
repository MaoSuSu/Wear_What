<?php
namespace hsModel;

class member{
	
	public function login(){
		// 查询用户是否存在
		$db = \hsTool\db::getInstance('members');
		$member = $db->where('u_name = ?', array($_POST['name']))->fetch();
		// 用户不存在
		if(empty($member)){
			exit(jsonCode('none_user', '该用户不存在！'));
		}
		//用户密码错误
		if($member['u_pass'] != $_POST['pass']){
			exit(jsonCode('key_error', '密码输入错误！'));
		}	
		// 登录成功
		exit(jsonCode('ok', $member));
	}
	
	public function register(){
		// 查询用户是否已经注册
		$db = \hsTool\db::getInstance('members');
		$member = $db->where('u_name = ?', array($_POST['name']))->fetch();
		//用户名已被注册占用
		if($member['u_name']){
			exit(jsonCode('registered', '该用户名已被占用！'));
		}	
		// 用户未注册
		if(empty($member)){
			$member = array();
			$member['u_name']   = $_POST['name'];
			$member['u_pass']   = $_POST['pass'];
			$member['u_regtime']= date("Y年m月d日,H:i:s",time());
			$member['u_id']     = $db->add($member);
		}
		//注册失败
		if(empty($member['u_id'] )){
			exit(jsonCode('error', '注册失败，请返回重试'));
		}
		//注册成功
		exit(jsonCode('ok', $member));
	}
}