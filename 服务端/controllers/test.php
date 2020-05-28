<?php
$cnum = ['1','2','3'];
$a3 = '';
for ($i=0; $i<3; $i++){
    if ($i==0){
        $a3 = "'".$cnum[$i];
        echo $a3. "<br>";
    }   
    elseif($i==2){
        $a3 = $a3.",".$cnum[$i]."'";
        echo $a3. "<br><br>";
    }
    else{
        $a3 = $a3.",".$cnum[$i]; 
        echo $a3. "<br>";
    } 
}

exec("sudo python3 ../py/test.py $a3",$out);
foreach($out as $value){
    echo $value;
}
?>