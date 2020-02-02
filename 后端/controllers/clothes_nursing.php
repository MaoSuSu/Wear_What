<?php
namespace hsC;
class clothes_nursing{
    
    public function get_nursing_state(){
        $db = \hsTool\db::getInstance('clothes_nursing');
		$state = $db->where('id = ?', array(1))->fetch();
        exit(jsonCode('ok', $state));
	}

    public function Temperature_and_Humidity(){
		$output=exec("sudo python /var/www/py/temperature_and_humidity.py");
        $array=explode(',',$output);
        exit(jsonCode('ok', $array));
    }
    
    public function Intelligent_nursing(){

        $db = \hsTool\db::getInstance('clothes_nursing');
        $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
        if(empty($intelligent_nursing)){
            //开始
            //通风10分钟
            exec("sudo python /var/www/py/ventilator_on.py");
            sleep(600);
            //检查智能护理是否被关闭
            $db = \hsTool\db::getInstance('clothes_nursing');
            $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
            if(empty($intelligent_nursing)){exit(jsonCode('ok', '进入手动模式,智能护理进程中断'));}
            exec("sudo python /var/www/py/ventilator_off.py");

            //杀菌30分钟
            exec("sudo python /var/www/py/purple_lamp_on.py");
            sleep(1800);
            //检查智能护理是否被关闭
            $db = \hsTool\db::getInstance('clothes_nursing');
            $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
            if(empty($intelligent_nursing)){exit(jsonCode('ok', '进入手动模式,智能护理进程中断'));}
            exec("sudo python /var/www/py/purple_lamp_off.py");

            //通风20分钟
            exec("sudo python /var/www/py/ventilator_on.py");
            sleep(1200);
            //检查智能护理是否被关闭
            $db = \hsTool\db::getInstance('clothes_nursing');
            $intelligent_nursing = $db->where('intelligent_nursing = ?', array(1))->fetch();
            if(empty($intelligent_nursing)){exit(jsonCode('ok', '进入手动模式,智能护理进程中断'));}
            exec("sudo python /var/www/py/ventilator_off.py");

            //结束
            exit(jsonCode('ok', '智能护理完成'));
        }else{
            //将 intelligent_nursing 状态置为0
            $data = array('intelligent_nursing' => 0);
            $db->where('id = ?', array(1))->update($data);
            //关闭所有设备
            exec("sudo python /var/www/py/ventilator_off.py");
            exec("sudo python /var/www/py/purple_lamp_off.py");
            exit(jsonCode('ok', '智能护理已关闭'));
        }

    }

	public function Ventilator(){
 
         $db = \hsTool\db::getInstance('clothes_nursing');
         //将 intelligent_nursing 状态置为0
         $data = array('intelligent_nursing' => 0);
         $db->where('id = ?', array(1))->update($data);

         $ventilator = $db->where('ventilator = ?', array(1))->fetch();
         if(empty($ventilator)){
            exec("sudo python /var/www/py/ventilator_on.py");
            exit(jsonCode('ok', '通风机已开启'));
         }else{
            exec("sudo python /var/www/py/ventilator_off.py");
            exit(jsonCode('ok', '通风机已关闭'));
         }
        
	}
    
    public function Purple_lamp(){

        $db = \hsTool\db::getInstance('clothes_nursing');
        //将 intelligent_nursing 状态置为0
        $data = array('intelligent_nursing' => 0);
        $db->where('id = ?', array(1))->update($data);
        
        $purple_lamp = $db->where('purple_lamp = ?', array(1))->fetch();
        if(empty($purple_lamp)){
           exec("sudo python /var/www/py/purple_lamp_on.py");
           exit(jsonCode('ok', '紫光灯已开启'));
        }else{
           exec("sudo python /var/www/py/purple_lamp_off.py");
           exit(jsonCode('ok', '紫光灯已关闭'));
        }
    }

}