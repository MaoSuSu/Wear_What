import sys

str1 = sys.argv[1]
str1 = str1.split(',')
print (str1)
str1 = list(map(int,str1))
print (str1)