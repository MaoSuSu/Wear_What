import time
import Adafruit_DHT
import RPi.GPIO as GPIO
 
GPIO_PIN = 4 #BCM脚位
GPIO.setwarnings(False)
h, t = Adafruit_DHT.read_retry(Adafruit_DHT.DHT11, GPIO_PIN)
if h is not None and t is not None:
    # print('{0:0.1f}°C,{1:0.1f}%'.format(t, h))
    print(str(t)+'°C')
    print(str(h)+'%')
else:
    print('读取失败，重新读取。')