<?php
namespace hsC;
// 衣服推荐
class clothesre{
	public function getT(){
		exec('sudo python3 ./py/get_local_temperature.py',$out);
        # 所有文件的起始路径以index.php的位置为准！
        $T=array();
        foreach($out as $value){
            array_push($T,$value);
        }
        exit(jsonCode('ok', $T));
	}

	public function reoccasion(){
		$_GET['pid'] = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
		$db = \hsTool\db::getInstance('rec_occasion');
		if(empty($_GET['pid'])){
			$rec_occasion = $db->order('ro_order asc')->fetchAll();
		}else{
			$rec_occasion = $db->order('ro_order asc')->where('ro_pid = ?', array($_GET['pid']))->fetchAll();
		}
		if(empty($rec_occasion)){exit(jsonCode('empty', ''));}
		$occasion = array();
		foreach($rec_occasion as $t){
			$occasion[$t['ro_id']] = $t['ro_name']; 
		}
		exit(jsonCode('ok', $occasion));
	}

	public function re(){
		$dbClo = \hsTool\db::getInstance('clothes');
		$clothedata = $dbClo->where('clo_u_name = ?', array($_POST['uname']))->fetchAll();
		#读入衣服数量
		$clotheCountNumber = $dbClo->where('clo_u_name = ?', array($_POST['uname']))->count();
		#读入衣服id
		$cid = '';
		for ($i=0; $i<$clotheCountNumber; $i++){
			if ($i==0){
				$cid = "'".$clothedata[$i][clo_id];
			}   
			elseif($i==($clotheCountNumber-1)){
				$cid = $cid.",".$clothedata[$i][clo_id]."'";
			}
			else{
				$cid = $cid.",".$clothedata[$i][clo_id]; 
			} 
		}
		#读入衣服type
		$ctype = '';
		for ($i=0; $i<$clotheCountNumber; $i++){
			if ($clothedata[$i][clo_type]=='短袖'){
				$clothedata[$i][clo_type]=1;
			}
			elseif($clothedata[$i][clo_type]=='长袖'){
				$clothedata[$i][clo_type]=2;
			}
			elseif($clothedata[$i][clo_type]=='长裤'){
				$clothedata[$i][clo_type]=3;
			}
			elseif($clothedata[$i][clo_type]=='短裤'){
				$clothedata[$i][clo_type]=4;
			}
			else{
				$clothedata[$i][clo_type]=5;
			}
			if ($i==0){		
				$ctype = "'".$clothedata[$i][clo_type];
			}   
			elseif($i==($clotheCountNumber-1)){
				$ctype = $ctype.",".$clothedata[$i][clo_type]."'";
			}
			else{
				$ctype = $ctype.",".$clothedata[$i][clo_type]; 
			} 
		}
		#读入衣服color
		$ccolor = '';
		for ($i=0; $i<$clotheCountNumber; $i++){
			if ($clothedata[$i][clo_color]=='黑色'){
				$clothedata[$i][clo_color]=1;
			}
			elseif($clothedata[$i][clo_color]=='白色'){
				$clothedata[$i][clo_color]=2;
			}
			elseif($clothedata[$i][clo_color]=='灰色'){
				$clothedata[$i][clo_color]=3;
			}
			elseif($clothedata[$i][clo_color]=='蓝色'){
				$clothedata[$i][clo_color]=4;
			}
			elseif($clothedata[$i][clo_color]=='黄色'){
				$clothedata[$i][clo_color]=5;
			}
			elseif($clothedata[$i][clo_color]=='绿色'){
				$clothedata[$i][clo_color]=6;
			}
			elseif($clothedata[$i][clo_color]=='红色'){
				$clothedata[$i][clo_color]=7;
			}
			elseif($clothedata[$i][clo_color]=='橙色'){
				$clothedata[$i][clo_color]=8;
			}
			elseif($clothedata[$i][clo_color]=='棕色'){
				$clothedata[$i][clo_color]=9;
			}
			else{
				$clothedata[$i][clo_color]=10;
			}
			if ($i==0){
				$ccolor = "'".$clothedata[$i][clo_color];
			}   
			elseif($i==($clotheCountNumber-1)){
				$ccolor = $ccolor.",".$clothedata[$i][clo_color]."'";
			}
			else{
				$ccolor = $ccolor.",".$clothedata[$i][clo_color]; 
			} 
		}
		#送出数据
		exec("sudo python3 ./py/推荐算法/main.py {$clotheCountNumber} {$cid} {$ctype} {$ccolor} {$_POST['o']}",$out);
		#返回推荐衣服id
		$T=array();
        foreach($out as $value){
            array_push($T,$value);
        }
        exit(jsonCode('ok', $T));
	}
}