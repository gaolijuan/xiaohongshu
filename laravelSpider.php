<?php 
namespace App\Console\Commands;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
class laravelSpider extends Command
{
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 1;  // 同时并发抓取
    private $users = ['session.1553674958987583475400', 'session.1553674958987583475401', 'session.1553674958987583475402', 'session.1553674958987583475403',
        'session.1553674958987583475404', 'session.1553674958987583475405', 'session.1553674958987583475406'];
    protected $signature = 'test:multithreading-request';
    protected $description = 'Command description';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $this->totalPageCount = count($this->users);
        $client = new Client();
        $requests = function ($total) use ($client) {
            foreach ($this->users as $key => $user) {
                $uri = 'https://www.xiaohongshu.com/sapi/wx_mp_api/sns/v1/user/5c9b3275000000001603de47/followings?start=&sid='.$user;
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };
        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => function ($response, $index){
                $res = json_decode($response->getBody()->getContents());
                print_r($res);
                //如果接口能用，或者返回HTML页面，这里就能进行数据提取，保存到数据库，但是小红书的API加密了，比较难处理，建议使用python3+appium。
                $this->countedAndCheckEnded();
            },
            'rejected' => function ($reason, $index){
                $this->error("rejected" );
                $this->error("rejected reason: " . $reason );
                $this->countedAndCheckEnded();
            },
        ]);
        // 开始发送请求
        $promise = $pool->promise();
        $promise->wait();
    }
    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            $this->counter++;
            return;
        }
        $this->info("请求结束！");
    }
}
