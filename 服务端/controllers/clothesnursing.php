<?php
namespace hsC;
class clothesnursing{
    
    public function GetState(){
        $db = \hsTool\db::getInstance('clothes_nursing');
		$state = $db->where('id = ?', array(1))->fetch();
        exit(jsonCode('ok', $state));
	}

    public function TemperatureHumidity(){
        exec('sudo python3 ./py/temperature_and_humidity.py',$out);
        # 所有文件的起始路径以index.php的位置为准！
        $TH=array();
        foreach($out as $value){
            array_push($TH,$value);
        }
        exit(jsonCode('ok', $TH));
    }

    public function IntelligentNursing(){

        $db = \hsTool\db::getInstance('clothes_nursing');
        $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
        if(empty($intelligent_nursing)){
            //开始
            //将id为1的intelligent_nursing 状态置为1
            $data = array('intelligent_nursing' => 1);
            $db->where('id = ?', array(1))->update($data);

            //杀菌30分钟
            exec("sudo python3 ./py/purple_lamp_on.py");
            sleep(1800);
            //检查智能护理是否被关闭
            $db = \hsTool\db::getInstance('clothes_nursing');
            $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
            if(empty($intelligent_nursing)){exit(jsonCode('ok', '进入手动模式,智能护理进程中断'));}
            exec("sudo python3 ./py/purple_lamp_off.py");

            //通风10分钟
            exec("sudo python3 ./py/ventilator_on.py");
            sleep(600);
            //检查智能护理是否被关闭
            $db = \hsTool\db::getInstance('clothes_nursing');
            $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
            if(empty($intelligent_nursing)){exit(jsonCode('ok', '进入手动模式,智能护理进程中断'));}
            exec("sudo python3 ./py/ventilator_off.py");

            //结束
            exit(jsonCode('ok', '智能护理完成'));
        }else{
            //将id为1的intelligent_nursing 状态置为0
            $data = array('intelligent_nursing' => 0);
            $db->where('id = ?', array(1))->update($data);
            //关闭所有设备
            exec("sudo python3 ./py/ventilator_off.py");
            exec("sudo python3 ./py/purple_lamp_off.py");
            exit(jsonCode('ok', '智能护理已关闭'));
        }

    }

    public function PurpleLamp(){

        $db = \hsTool\db::getInstance('clothes_nursing');
        //将id为1的intelligent_nursing 状态置为0
        $data = array('intelligent_nursing' => 0);
        $db->where('id = ?', array(1))->update($data);
        
        $purple_lamp = $db->where('purple_lamp = ?', array(1))->fetch();
        if(empty($purple_lamp)){
           exec("sudo python3 ./py/purple_lamp_on.py");
           $data = array('purple_lamp' => 1);
           $db->where('id = ?', array(1))->update($data);

           if($_POST['xd']){
            sleep((int)$_POST['xd']*60);
            exec('sudo python3 ./py/purple_lamp_off.py');
            $data = array('purple_lamp' => 0);
            $db->where('id = ?', array(1))->update($data);
            exit(jsonCode('ok', '紫光灯已持续'.$_POST['xd'].'分钟，现已关闭'));
           }

           exit(jsonCode('ok', '紫光灯已开启'));
        }else{
           exec('sudo python3 ./py/purple_lamp_off.py');
           $data = array('purple_lamp' => 0);
           $db->where('id = ?', array(1))->update($data);
           exit(jsonCode('ok', '紫光灯已关闭'));
        }
    }

	public function Ventilator(){
 
         $db = \hsTool\db::getInstance('clothes_nursing');
         //将id为1的intelligent_nursing 状态置为0
         $data = array('intelligent_nursing' => 0);
         $db->where('id = ?', array(1))->update($data);

         $ventilator = $db->where('ventilator = ?', array(1))->fetch();
         if(empty($ventilator)){
            exec("sudo python3 ./py/ventilator_on.py");
            $data = array('ventilator' => 1);
            $db->where('id = ?', array(1))->update($data);

            if($_POST['cyw']){
                sleep((int)$_POST['cyw']*60);
                exec('sudo python3 ./py/ventilator_off.py');
                $data = array('ventilator' => 0);
                $db->where('id = ?', array(1))->update($data);
                exit(jsonCode('ok', '通风机已持续'.$_POST['cyw'].'分钟，现已关闭'));
               }

            exit(jsonCode('on', '通风机已开启'));
         }else{
            exec("sudo python3 ./py/ventilator_off.py");
            $data = array('ventilator' => 0);
            $db->where('id = ?', array(1))->update($data);
            exit(jsonCode('ok', '通风机已关闭'));
         }
        
	}
    
    public function Heater(){

        $db = \hsTool\db::getInstance('clothes_nursing');
        //将id为1的intelligent_nursing 状态置为0
        $data = array('intelligent_nursing' => 0);
        $db->where('id = ?', array(1))->update($data);
        
        $heater = $db->where('heater = ?', array(1))->fetch();
        if(empty($heater)){
           exec("sudo python3 ./py/heater_on.py");
           $data = array('heater' => 1);
           $db->where('id = ?', array(1))->update($data);

           if($_POST['hgyf']){
            sleep((int)$_POST['hgyf']*60);
            exec('sudo python3 ./py/heater_off.py');
            $data = array('heater' => 0);
            $db->where('id = ?', array(1))->update($data);
            exit(jsonCode('ok', '暖风机已持续'.$_POST['hgyf'].'分钟，现已关闭'));
           }

           exit(jsonCode('on', '暖风机已开启'));
        }else{
           exec("sudo python3 ./py/heater_off.py");
           $data = array('heater' => 0);
           $db->where('id = ?', array(1))->update($data);
           exit(jsonCode('ok', '暖风机已关闭'));
        }
    }
}
?>