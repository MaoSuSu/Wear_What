<?php
namespace hsC;
class clothesdata{

	public function getList(){
        $dbClothes = \hsTool\db::getInstance('clothes');
		$clothedata = $dbClothes->where('clo_u_name = ?', array($_POST['u_name']))->fetchAll();
        exit(jsonCode('ok', $clothedata));
	}
	
	public function lightdx(){
		exec('sudo python3 ./py/light_dx.py',$out);
		# 所有文件的起始路径以index.php的位置为准！
		foreach($out as $value){
        }
        exit(jsonCode('ok', $value));
	}

	public function lightcx(){
		exec('sudo python3 ./py/light_cx.py',$out);
        # 所有文件的起始路径以index.php的位置为准！
		foreach($out as $value){
        }
        exit(jsonCode('ok', $value));
	}

	public function lightwt(){
		exec('sudo python3 ./py/light_wt.py',$out);
        # 所有文件的起始路径以index.php的位置为准！
		foreach($out as $value){
        }
        exit(jsonCode('ok', $value));
	}

	public function lightdk(){
		exec('sudo python3 ./py/light_dk.py',$out);
        # 所有文件的起始路径以index.php的位置为准！
		foreach($out as $value){
        }
        exit(jsonCode('ok', $value));
	}

	public function iflike(){
		//检查参数是否正常
		if(empty($_POST['iflike_clo_name'])){exit(jsonCode('error', 'iflike_clo_name 不存在'));}
		$dbClothes = \hsTool\db::getInstance('clothes');
		$clothedata = $dbClothes->where('clo_name = ?', array($_POST['iflike_clo_name']))->fetch();
		//查询衣服是否存在
		if(empty($clothedata)){exit(jsonCode('error', '要收藏的衣服不存在'));}
		if($_POST['clo_iflike'] == 0){
			//收藏
			$data = array('clo_iflike' => 1);
			$dbClothes->where('clo_iflike = ? and clo_name = ?', array($_POST['clo_iflike'],$_POST['iflike_clo_name']))->update($data);
			exit(jsonCode('ok', '收藏成功'));
		}else{
			//取消收藏
			$data = array('clo_iflike' => 0);
			$dbClothes->where('clo_iflike = ? and clo_name = ?', array($_POST['clo_iflike'],$_POST['iflike_clo_name']))->update($data);
			exit(jsonCode('ok', '取消收藏成功'));
		}
	}

	public function remove(){
		//检查参数是否正常
		if(empty($_POST['remove_clo_name'])){exit(jsonCode('error', 'remove_clo_name 不存在'));}
		$dbClothes = \hsTool\db::getInstance('clothes');
		$clothedata = $dbClothes->where('clo_name = ?', array($_POST['remove_clo_name']))->fetch();
		//查询衣服是否存在
		if(empty($clothedata)){exit(jsonCode('error', '要删除的衣服不存在'));}
		//删除衣服记录
		$dbClothes->where('clo_name = ?', array($_POST['remove_clo_name']))->delete();
		//删除衣服图片
		$str1 = $clothedata['clo_image'];
		$str2 = str_replace('http://maosu.vicp.io/imgs/','imgs/',$str1);
		$file_path = $str2;
		$res = unlink($file_path);
		if($res){
			exit(jsonCode('ok', '删除成功'));
		}else{
			exit(jsonCode('ok', '信息删除成功，原图删除失败'));
		}
	}

	public function addData(){
		// 验证签名
		checkSign();
		// 提交主要信息
		$dbClothes = \hsTool\db::getInstance('clothes');
		//解析衣服类型
		if(intval($_POST['t'])==1){
			$type='短袖';
		}
		elseif(intval($_POST['t'])==2){
			$type='长袖';
		}elseif(intval($_POST['t'])==3){
			$type='外套';
		}elseif(intval($_POST['t'])==4){
			$type='短裤';
		}elseif(intval($_POST['t'])==5){
			$type='长裤';
		}elseif(intval($_POST['t'])==6){
			$type='连衣裤';
		}elseif(intval($_POST['t'])==7){
			$type='连衣裙';
		}elseif(intval($_POST['t'])==8){
			$type='半裙';
		}elseif(intval($_POST['t'])==9){
			$type='长裙';
		}elseif(intval($_POST['t'])==10){
			$type='鞋子';
		}elseif(intval($_POST['t'])==11){
			$type='包包';
		}elseif(intval($_POST['t'])==12){
			$type='首饰';
		}elseif(intval($_POST['t'])==13){
			$type='帽子';
		}else{
			$type='其他';
		}
		//解析衣服颜色
		if(intval($_POST['c'])==1){
			$color='红色';
		}
		elseif(intval($_POST['c'])==2){
			$color='橙色';
		}elseif(intval($_POST['c'])==3){
			$color='黄色';
		}elseif(intval($_POST['c'])==4){
			$color='绿色';
		}elseif(intval($_POST['c'])==5){
			$color='青色';
		}elseif(intval($_POST['c'])==6){
			$color='蓝色';
		}elseif(intval($_POST['c'])==7){
			$color='紫色';
		}elseif(intval($_POST['c'])==8){
			$color='灰色';
		}elseif(intval($_POST['c'])==9){
			$color='粉色';
		}elseif(intval($_POST['c'])==10){
			$color='黑色';
		}elseif(intval($_POST['c'])==11){
			$color='白色';
		}else{
			$color='棕色';
		}
		//解析衣服季节
		if(intval($_POST['s'])==1){
			$season='春季';
		}
		elseif(intval($_POST['s'])==2){
			$season='夏季';
		}elseif(intval($_POST['s'])==3){
			$season='秋季';
		}else{
			$season='冬季';
		}
		$addData = array(
			'clo_name'       => $_POST['name'],
			'clo_u_name'     => $_POST['u_name'],
			'clo_type'       => $type,
			'clo_color'      => $color,
			'clo_season'     => $season,
			//默认未喜欢 0
			'clo_iflike'     => 0,
			'clo_image'    => $_POST['image'],
			'clo_createtime' => date("Y年m月d日,H:i:s",time())
		);
		$clotheId = $dbClothes->add($addData);
		if(!$clotheId){exit(jsonCode('error', '提交失败'));}
		exit(jsonCode('ok', '提交完成'));
	}
}
