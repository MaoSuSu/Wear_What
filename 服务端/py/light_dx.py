import RPi.GPIO as GPIO
import time

dx_LED = 29 #短袖灯
GPIO.setmode(GPIO.BOARD) 
GPIO.setup(dx_LED, GPIO.OUT) 

for i in range(3): 
        GPIO.output(dx_LED, GPIO.HIGH) 
        time.sleep(0.5) 
        GPIO.output(dx_LED, GPIO.LOW) 
        time.sleep(0.5)
        
print('短袖已定位')
GPIO.cleanup() 