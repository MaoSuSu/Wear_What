import tensorflow as tf
import pandas as pd
import numpy as np
from sklearn.metrics import mean_squared_error
import os
import requests
import sys
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"

#参数设置
CSV_COLUMN_NAMES = ['id','Type','Color', 'Occasion', 'Weather','Goal']

#归一化
def normalization(x):
    max_value = np.max(x, axis=0).reshape((1, -1))
    min_value = np.min(x, axis=0).reshape((1, -1))
    outputs = (x - min_value) / (max_value - min_value)
    return outputs, max_value, min_value

# 反归一化
def inverse_normalization(x, max_value, min_value):
    interval = np.abs(max_value - min_value)
    temp = x * interval
    outputs = temp + min_value
    return outputs

#rmse测评
def rmse(predictions, targets):
    return np.sqrt(mean_squared_error(predictions, targets))

#mse测评
def mse(predictions, targets):
    return mean_squared_error(predictions, targets)

def getData():   
    # 获取厦门天气
    r = requests.get('http://www.weather.com.cn/data/sk/101230201.html')
    r.encoding = 'utf-8'
    w = float(r.json()['weatherinfo']['temp'])
    if w<=10:
            # 凉
            weather = 1
    elif 10<w and w<=18:
            # 温
            weather = 2
    elif 18<w and w<=22:
            # 暖
            weather = 3
    elif 22<w and w<=28:
            # 热
            weather = 4
    else:
            # 暑热
            weather = 5                           

    a1 = sys.argv[2]
    a1 = a1.split(',')
    a1 = list(map(int,a1))
    a2 = sys.argv[3]
    a2 = a2.split(',')
    a2 = list(map(int,a2))
    a3 = sys.argv[4]
    a3 = a3.split(',')
    a3 = list(map(int,a3))
    a4 = int(sys.argv[5])
    w = int(sys.argv[1])
    a5 = int(weather)
    a6 = 1

    basedir = os.path.dirname(__file__)
    ccpath = os.path.join(basedir, 'cc2.csv')
    train = pd.read_csv(ccpath, names=CSV_COLUMN_NAMES, header=0)
    en = train.shape[0]
    np.array(a1)
    m1 = np.array(a1)
    m2 = np.array(a2)
    m3 = np.array(a3)
    for i in range(w):
        train.loc[en+i] = (m1[i],m2[i],m3[i],a4,a5,a6)

    return train,w

# 读取文件数据
def readData(train):
    X = []
    Y = []
    for i in range(train.shape[0]):
        X.append(train.iloc[i][['id','Type','Color', 'Occasion', 'Weather']])
        Y.append(train.iloc[i]['Goal'])
    X = np.array(X).reshape((train.shape[0], 5))
    Y = np.array(Y).reshape((train.shape[0], 1))
    return X, Y

# 划分数据集
def GenerateData():
    train, w = getData()
    all_x, all_y = readData(train)
    all_x, max_allx, min_allx, = normalization(all_x)
    all_y, max_ally, min_ally = normalization(all_y)
    dataset = np.hstack((all_x, all_y))
    sample_tatal_num = dataset.shape[0]
    train_start = 0
    train_end = int(np.floor(0.8*sample_tatal_num))
    test_start = train_end + 1
    test_end = sample_tatal_num - w
    train_dataset = dataset[train_start:train_end, :]
    test_dataset = dataset[test_start:test_end, :]
    predict_dataset = dataset[test_end:, :]
    return train_dataset, test_dataset, predict_dataset, max_ally, min_ally, max_allx, min_allx, w

#主函数
def DoIt():
    trainset, testset, preSet, max_ally, min_ally, max_allx, min_allx, w = GenerateData()
    data_train = pd.DataFrame(trainset,columns=CSV_COLUMN_NAMES)
    data_test = pd.DataFrame(testset,columns=CSV_COLUMN_NAMES)
    data_pre = pd.DataFrame(preSet,columns=CSV_COLUMN_NAMES)
    #获取训练集和测试集
    x_train = data_train.iloc[:, :5]
    y_train = data_train.iloc[:, 5]
    x_test = data_test.iloc[:, :5]
    y_test = data_test.iloc[:, 5]
    x_pre = data_pre.iloc[:, :5]
    #设置参数
    n_stocks = 5
    n_neurons_1 = 128
    n_neurons_2 = 64
    n_neurons_3 = 32
    n_target = 1
    #占位符设置，预输入X，预输出值Y
    X = tf.placeholder(tf.float32, [None, n_stocks])
    Y = tf.placeholder(tf.float32, [None])
    #框架设置
    #网络设置
    weight_initializer = tf.variance_scaling_initializer()
    bias_initializer = tf.zeros_initializer()
    W1 = tf.Variable(weight_initializer([n_stocks, n_neurons_1]))
    b1 = tf.Variable(bias_initializer([n_neurons_1]))
    W2 = tf.Variable(weight_initializer([n_neurons_1, n_neurons_2]))
    b2 = tf.Variable(bias_initializer([n_neurons_2]))
    W3 = tf.Variable(weight_initializer([n_neurons_2, n_neurons_3]))
    b3 = tf.Variable(bias_initializer([n_neurons_3]))
    W_out = tf.Variable(weight_initializer([n_neurons_3, n_target]))
    b_out = tf.Variable(bias_initializer([n_target]))
    #对隐藏层,进行非线性的转换 激活
    h1 = tf.nn.relu(tf.add(tf.matmul(X, W1), b1))
    h2 = tf.nn.relu(tf.add(tf.matmul(h1, W2), b2))
    h3 = tf.nn.relu(tf.add(tf.matmul(h2, W3), b3))
    #最终的输出
    out = tf.transpose(tf.add(tf.matmul(h3, W_out), b_out))
    #定义损失函数:均方误差
    loss=tf.reduce_mean(tf.squared_difference(out, Y))
    #优化损失
    opt=tf.train.AdamOptimizer(learning_rate=0.01).minimize(loss)
    #初始化变量
    init = tf.global_variables_initializer()
    #创建saver保存模型
    saver = tf.train.Saver()
    ans = 0
    #开启会话
    with tf.Session() as sess:
        sess.run(init)
        epochs = 1000  # 训练轮数
        batch_size = 256  # 每批次训练数目
        num = 1
        is_train = 3  # 1:训练； 3:预测； 其他:测试
        if is_train == 1:
            for e in range(epochs):  # 控制轮数
                for i in range(0, len(y_train) // batch_size):
                    start = i * batch_size
                    batch_x = x_train.iloc[start:start + batch_size]
                    batch_y = y_train.iloc[start:start + batch_size]
                    _, ms = sess.run([opt, loss], feed_dict={X: batch_x, Y: batch_y})
                    # 写入每批次训练的值
                    #print('第 % s轮第 % s批次训练的损失为:%s'%(e + 1, i + 1, ms))
                    ans += ms
            # 保存模型
            print('sum%s'%(ans/epochs))
            basedir = os.path.dirname(__file__)
            datapath = os.path.join(basedir, 'data_stock_train/data_model')
            save_path = saver.save(sess, datapath)
            print("Model saved in file: ", save_path)
        elif is_train == 3:
            basedir = os.path.dirname(__file__)
            datapath = os.path.join(basedir, 'data_stock_train/data_model')
            save_path = saver.save(sess, datapath)
            predicts = sess.run(out, feed_dict={X: x_pre})
            predicts = inverse_normalization(predicts, max_ally, min_ally)

            df5 = inverse_normalization(x_pre, max_allx, min_allx)

            temp = df5.iloc[:,0]
            ans = []
            for num in temp:
                ans_float = (num * 10 + 5) / 10
                ans.append(int(ans_float))
            ans = np.array(ans)
            ans = ans.reshape(w, 1)

            predicts = predicts.reshape(w,1)
            df1 = pd.DataFrame(predicts,columns=['y'])
            df2 = pd.DataFrame(ans,columns=['id'])
            df = pd.concat([df1,df2],axis=1)
            #print(df)
            df3 = df.sort_values(by='y',ascending=False)
            #print(df3)
            aa = df3.iloc[:,1]
            #print(aa)
            #print(type(aa))
            #返回的aa是一个按照预测分值从高到低排序好的id序列
            #aa的数据类型是Series，可以使用for循环逐个打印出来
            #线性的数据结构, series是一个一维数组
            return aa
        else:
            saver.restore(sess, './data_stock_train/data_model')
            predicts = sess.run(out, feed_dict={X: x_test})
            y_test = y_test.values
            predicts = inverse_normalization(predicts,max_ally, min_ally)
            y_test = inverse_normalization(y_test, max_ally, min_ally)
            row = y_test.shape[1]
            predicts = predicts.reshape(row, 1)
            y_test = y_test.reshape(row, 1)
            rmse_only = rmse(predicts, y_test)
            mse_only = mse(predicts, y_test)
            print('test_predicted && test_y')
            CC = np.hstack((predicts, y_test))
            print(CC)
            print('Test RMSE: %.8f' % rmse_only)
            print('Test MSE: %.8f' % mse_only)



if __name__ == "__main__":
    # 至少推荐三件衣服
    cc = DoIt()
    count = 0
    for aa in cc:
        if count <=2:
            # 推荐的衣服id
            print(aa)
            count += 1
           