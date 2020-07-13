import RPi.GPIO as GPIO
import time

LED = 29 #1号灯
GPIO.setmode(GPIO.BOARD) 
GPIO.setup(LED, GPIO.OUT) 

for i in range(3): 
        GPIO.output(LED, GPIO.HIGH) 
        time.sleep(0.5) 
        GPIO.output(LED, GPIO.LOW) 
        time.sleep(0.5)
        
print('1号衣架已定位')
GPIO.cleanup() 