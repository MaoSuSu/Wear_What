import RPi.GPIO as GPIO

purple_lamp = 11     #紫光灯

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(purple_lamp, GPIO.OUT)

GPIO.output(Purple_lamp,GPIO.LOW)
GPIO.cleanup()
print('紫光灯关闭')
