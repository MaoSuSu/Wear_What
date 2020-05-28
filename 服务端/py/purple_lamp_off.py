import RPi.GPIO as GPIO

purple_lamp = 12     #紫光灯

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(purple_lamp, GPIO.OUT)
GPIO.output(purple_lamp,GPIO.LOW)
GPIO.cleanup()
print('紫光灯关闭')