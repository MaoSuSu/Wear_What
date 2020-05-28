import requests
# 获取厦门天气
r = requests.get('http://www.weather.com.cn/data/sk/101230201.html')
r.encoding = 'utf-8'
w = float(r.json()['weatherinfo']['temp'])
print(str(w)+'°C')