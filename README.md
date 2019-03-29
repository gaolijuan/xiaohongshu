laravelSpider.php
创建命令
1. 运行命令行创建命令
php artisan make:command laravelSpider --command=test:multithreading-request

2. 注册命令
编辑 app/Console/Kernel.php，在 $commands 数组中增加：
Commands\MultithreadingRequest::class,

3.运行：
php artisan test:multithreading-request


=====================================================




工具：python+Charles+appium+mitmproxy+xpath

通过对小红书微信小程序进行抓包分析，得到如下api,但是会过期
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

