
工具：python+Charles+appium+mitmproxy+xpath

通过对小红书微信小程序进行抓包分析，得到如下api
（Through the small red book WeChat small program catch package analysis, get the following API）

Host：https://www.xiaohongshucom

获取个人资料（Access to personal data）：
Host/sapi/wx_mp_api/sns/v1/user/me?sid=session.1553674958987583475400
{
	"result": 0,
	"success": true,
	"data": {
		"banner_image": "http://s4.xiaohongshu.com/static/huati/6d46171e066c82db069e0978716269b9.jpg",
		"birthday": "2019-03-27",
		"collected": 0,
		"desc": "",
		"fans": 0,
		"atme_notes_num": 0,
		"collected_notes_num": 0,
		"collected_tags_num": 0
"imageb":
"https://img.xiaohongshu.com/avatar/5c9b33665d5cde0001f2aa50.jpg@750w_750h_92q_1e_1c_1x.jpg",
		"images": "https://img.xiaohongshu.com/avatar/5c9b33665d5cde0001f2aa50.jpg@160w_160h_92q_1e_1c_1x.jpg",
		(其他字段省略)……
}		
}



获取收藏内容（Get favorite content）：
Host/sapi/wx_mp_api/sns/v1/note/faved?num=10&sid=session.1553674958987583475400



获取笔记列表（Get a list of notes）：
Host/sapi/wx_mp_api/sns/v1/note/user/5c9b3275000000001603de47?sid=session.1553674958987583475400&page=1&page_size=15



获取关注名单（Get attention list）：
Host/sapi/wx_mp_api/sns/v1/user/5c9b3275000000001603de47/followings?start=&sid=session.1553674958987583475400

{
		"rid": "5b5e81d7f7e8b934027d74a7",
		"userid": "5b5e81d7f7e8b934027d74a7",
		"nickname": "当幸福来敲门.",
		"images": "https://img.xiaohongshu.com/avatar/5b5e81d7f7e8b934027d74a7.jpg@80w_80h_90q_1e_1c_1x.jpg",
		"fstatus": "follows",
		"fans": 7,
		"likes": 0,
		"ndiscovery": 0,
		"desc": "粉丝·7",
		"red_official_verified": false,
		"red_official_verify_type": 0,
		"show_red_official_verify_icon": false
}

请求头信息（Request header）：
{
auth-sign : 750aa5cd3a55fe5d8fc780d799d73c2c  

Authorization : 65c4bc2e-6a89-428d-837c-dad2fdf71750  

referrer :  https://servicewechat.com/wxffc08ac7df482a27/217/page-frame.html 

auth: eyJoYXNoIjoibWQ0IiwiYWxnIjoiSFMyNTYiLCJ0eXAiOiJKV1QifQ.eyJzaWQiOiI0YjE1ZjY5M2MxYWM0NjFiYzY3NWY1NjVhNjg3MmJhNSIsImV4cGlyZSI6MTU1MzY5NTAxOH0.2S8yNKUIRjDT3N9gpRMpeRY0siKlYwSEMBJi0t-pXE0  

User-Agent : Mozilla/5.0 (Linux; Android 8.0.0; PRA-AL00X Build/HONORPRA-AL00X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/70.0.3538.110 Mobile Safari/537.36 MicroMessenger/7.0.3.1400(0x2700033B) Process/appbrand2 NetType/WIFI Language/zh_CN  
}





获取app客户端个人资料，因为api加密的原因，需要通过Appium进行抓取。
（Obtain personal data of app client, because of API encryption, it needs to be captured through Appium）
代码如下：
import time
from appium import webdriver
from pymongo import MongoClient
import threading
caps = {
    "platformName": "Android",
    "deviceName": "HONORPRA-AL00X",
    "appPackage": "com.xingin.xhs",
    "platformVersion": "5.1.1",
    "appActivity": ".activity.SplashActivity",
    "noReset": True,  # 免登陆TRUE
    "unicodeKeyboard": True  # 解决不能输入中文的问题
    }


# 获得屏幕尺寸数据
def getSize():
    x = driver.get_window_size()['width']
    y = driver.get_window_size()['height']
    return (x, y)

def swipeUp():
    l = getSize()
    x1 = int(l[0] * 0.5)  # x坐标
    y1 = int(l[1] * 0.75)  # 起始y坐标
    y2 = int(l[1] * 0.25)  # 终点y坐标
    driver.swipe(x1, y1, x1, y2)
def swipeDown():
    l = getSize()
    x1 = int(l[0] * 0.5)  # x坐标
    y1 = int(l[1] * 0.25)  # 起始y坐标
    y2 = int(l[1] * 0.75)  # 终点y坐标
    driver.swipe(x1, y1, x1, y2)
def data():
    title = driver.find_element_by_id("com.xingin.xhs:id/bdb").text
    content = driver.find_element_by_id("com.xingin.xhs:id/bbo").text
    print("标题------>", title)
    print("内容------>", content)
    swipeUp()
    # swipeUp(3500)

    try:
        collect = driver.find_element_by_id("com.xingin.xhs:id/bcc").text
    except Exception as e:
        print(e)
        collect = '无收藏'

    try:
        like_num = driver.find_element_by_id("com.xingin.xhs:id/bd3").text
    except Exception as e:
        print(e)
        like_num = '无点赞'

    try:
        comments = driver.find_elements_by_id("com.xingin.xhs:id/bbo")[1].text
    except Exception as e:
        print(e)
        comments = '无评论'

    data = {'title': title, 'content': content, 'comments': comments, 'collect': collect, 'like_num': like_num}
    print(data)
    # cur.insert(data)
    print('存入成功。。。。。。。。。。')


if __name__ == '__main__':
 # 链接app
    driver = webdriver.Remote('http://localhost:4723/wd/hub', caps)
    time.sleep(2)
    # 首页输入框
    t = driver.find_element_by_id("com.xingin.xhs:id/we").click()
    time.sleep(2)
    # 真实输入框
    text = driver.find_element_by_id("com.xingin.xhs:id/aiw")
    time.sleep(2)
    text.send_keys(u'NBA')
    time.sleep(2)
    # 搜索按键
    driver.find_element_by_id("com.xingin.xhs:id/aiz").click()
    time.sleep(2)
    # 向下滑动，刷新数据
    swipeDown()
    # swipeDown(500)
    time.sleep(2)
 while True:
        print("begin======-------=======")
        try:
            # 点击进入详情页面
            driver.find_element_by_id('com.xingin.xhs:id/ahd').click()
            time.sleep(3)
            data()
            swipeDown()
            # swipeDown(1000)
            time.sleep(2)
            # 返回上一页
            driver.find_element_by_class_name('android.widget.ImageButton').click()
            # time.sleep(2)
            swipeUp()
            # swipeUp(200)
            # 测试第二次进入
            driver.find_element_by_id('com.xingin.xhs:id/ahd').click()
            time.sleep(3)
            data()

            swipeDown()
            # swipeDown(1000)
            # 返回上一页
            driver.find_element_by_class_name('android.widget.ImageButton').click()
            swipeUp()
            # swipeUp(200)
            # time.sleep(2)
        except:
            driver.find_element_by_id('com.xingin.xhs:id/a4g').click()
            time.sleep(2)
            swipeUp()
