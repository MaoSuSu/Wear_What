import RPi.GPIO as GPIO
import time

dk_LED = 37 #短裤灯
GPIO.setmode(GPIO.BOARD) 
GPIO.setup(dk_LED, GPIO.OUT) 

for i in range(3): 
        GPIO.output(dk_LED, GPIO.HIGH) 
        time.sleep(0.5) 
        GPIO.output(dk_LED, GPIO.LOW) 
        time.sleep(0.5)
        
print('短裤已定位')
GPIO.cleanup() 