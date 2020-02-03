import RPi.GPIO as GPIO

ventilator = 18      #通风机

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(ventilator, GPIO.OUT) 

GPIO.output(ventilator,GPIO.LOW)
GPIO.cleanup()
print('通风机关闭')

