<?php
namespace hsC;
class my{

	public function info(){		
		$dbClo = \hsTool\db::getInstance('clothes');
		// 查询用户衣服总数
		$clotheCountNumber = $dbClo->where('clo_u_name = ?', array($_POST['uname']))->count();
		$user['clotheCount'] = $clotheCountNumber;
		// 查询用户喜欢的衣服数量
		$clotheLikeNumber = $dbClo->where('clo_u_name = ? and clo_iflike = 1', array($_POST['uname']))->count();
		$user['clotheLike'] = $clotheLikeNumber;
		exit(jsonCode('ok', $user));
	}
}