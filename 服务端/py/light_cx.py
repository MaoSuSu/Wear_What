import RPi.GPIO as GPIO
import time

cx_LED = 31 #长袖灯
GPIO.setmode(GPIO.BOARD) 
GPIO.setup(cx_LED, GPIO.OUT) 

for i in range(3): 
        GPIO.output(cx_LED, GPIO.HIGH) 
        time.sleep(0.5) 
        GPIO.output(cx_LED, GPIO.LOW) 
        time.sleep(0.5)
        
print('长袖已定位')
GPIO.cleanup() 