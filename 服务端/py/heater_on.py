import RPi.GPIO as GPIO

purple_lamp = 22     #暖风机

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(purple_lamp, GPIO.OUT)
GPIO.output(purple_lamp,GPIO.HIGH)
print('暖风机开启')