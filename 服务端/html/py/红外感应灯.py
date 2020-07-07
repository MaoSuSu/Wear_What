import RPi.GPIO as GPIO
import time

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BOARD)

GPIO_PIR = 16 #感应器
LED = 11 # 灯

print ("按下 CTRL-C 退出程序")

GPIO.setup(GPIO_PIR,GPIO.IN)      
GPIO.setup(LED, GPIO.OUT)

Current_State  = 0
Previous_State = 0
try:
  print ("PIR正在初始化...")
  while GPIO.input(GPIO_PIR)==1:
    Current_State  = 0   
  print ("开始检测")
  
  while True :
    Current_State = GPIO.input(GPIO_PIR)
  
    if Current_State==1 and Previous_State==0:
      print ("检测到人体，开灯")
      GPIO.output(LED, GPIO.HIGH)
      Previous_State=1
      print ("LED灯将持续亮1分钟")
      time.sleep(60)   
    elif Current_State==0 and Previous_State==1:
      print ("未检测到人体，关灯")
      GPIO.output(11, GPIO.LOW)
      print ("重新检测")
      Previous_State=0  
     
except KeyboardInterrupt:
  print ("已退出")
  GPIO.cleanup()