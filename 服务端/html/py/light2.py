import RPi.GPIO as GPIO
import time

LED = 31 #2号灯
GPIO.setmode(GPIO.BOARD) 
GPIO.setup(LED, GPIO.OUT) 

for i in range(3): 
        GPIO.output(LED, GPIO.HIGH) 
        time.sleep(0.5) 
        GPIO.output(LED, GPIO.LOW) 
        time.sleep(0.5)
        
print('2号衣架已定位')
GPIO.cleanup() 