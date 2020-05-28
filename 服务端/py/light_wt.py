import RPi.GPIO as GPIO
import time

wt_LED = 33 #外套灯
GPIO.setmode(GPIO.BOARD) 
GPIO.setup(wt_LED, GPIO.OUT) 

for i in range(3): 
        GPIO.output(wt_LED, GPIO.HIGH) 
        time.sleep(0.5) 
        GPIO.output(wt_LED, GPIO.LOW) 
        time.sleep(0.5)
        
print('外套已定位')
GPIO.cleanup() 