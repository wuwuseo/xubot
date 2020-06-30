<?php
use Hyperf\Nano\Factory\AppFactory;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\DB\DB;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/vendor/autoload.php';

class Bot
{
    private $type = 'default';
    private $config;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setType($value = 'default'){
        $this->type = $value;
        return $this;
    }

    public function menu(){
        return $this->container->get(ConfigInterface::class)->get('bot.'.$this->type.'.menu');
    }

    public function atBot($keyword){

    }

    public function checkIn($userId){

    }

}

interface SendInterface
{
    public function send($data);
}


class CurlSend implements SendInterface
{
    /**
     * @var \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function create($options = []){
        $this->client = $this->clientFactory->create($options);
        return $this;
    }

    public function send($data,$uri = '')
    {
        $res = $this->client->post('',['form_params'=>$data]);
        return $res;
    }
}


class Wx{

    const PRIVATE_MSG = 100;//私聊消息
    const GROUP_MSG = 200;//群聊消息
    const EMPTY_MSG = 300;//暂无
    const GROOP_ADD_MSG = 400;//群成员增加
    const GROUP_DEL_MSG = 410;//群成员减少
    const ADD_MSG = 500;//收到好友请求
    const QRCODE_COLLECTION_MSG = 600;//二维码收款
    const COLLECTION_MSG = 700;//收到转账
    const APP_RUN_MSG = 800;//软件开始启动
    const NEW_LOGIN_MSG = 900;//新的账号登录完成
    const LOGOUT_MSG = 910;//账号下线

    private $host;

    private $container;

    private $sendServer;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setSend(SendInterface $send){
        $this->sendServer = $send;
        return $this;
    }

    private function send($data){

        return $this->sendServer->send(['data'=>json_encode($data)]);
    }

    public function sendTextMsg(){}

    public function sendGroupAtMsg(){}

    public function sendMsg($msg,$toWxid,$robWxid,$type = self::PRIVATE_MSG){
        $data = [
            'type'=>$type,
            'msg'=>$msg,
            'to_wxid' => $toWxid,    // 对方id（默认发送至来源的id，也可以发给其他人）
            'robot_wxid' => $robWxid,  // 账户id，用哪个账号去发送这条消息
        ];
        return $this->send($data);
    }


}
$app = AppFactory::create();
$app->config([

    'db.default' => [
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_DATABASE', 'hyperf'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
    ],
    'bot.default'=>[
        'menu'=> [
            env('BOT_MENU',"指令菜单\n----签到----\n# 签到 \n----查询----\n# 积分\n# 天气 XXX\n# 个人信息\n# 积分排行\n# 游戏资产\n----保险箱----\n# 存 n\n# 取 n\n----小游戏----\n# 猜拳 ooo\n# 劫 @xxx\n----计划开发TODO----\n# 兑换 xoxoxo\n---注意事项---\nn替换为数值\nXXX替换为自己输入关键词\n@xxx替换为自己操作@某人\nxoxoxo替换为输入自己兑换码\nooo 替换为任意一个 石头 剪刀 布"),
            "---菜单---\n#菜单\n#签到"
        ],
        'host' => env('BOT_HOST','localhost'),
    ],
    'bot.qq'=>[
        'host' => env('BOT_QQ_HOST','localhost'),
    ],
    'bot.wx'=>[
        'host' => env('BOT_WX_HOST','http://10.0.75.1:8073/send'),
    ],
]);
$container = $app->getContainer();
$container->set(Bot::class, new Bot($container));
$container->set(Wx::class, new Wx($container));
$app->addCrontab('* * * * * *', function(){
    $this->get(StdoutLoggerInterface::class)->info('execute every second!');
});


$app->get('/', function () {
    var_dump(1);
    $this->get(Bot::class)->setType();
var_dump($this->get(Bot::class)->menu());
var_dump(2);
    $user = $this->request->input('user', 'nano');
    $method = $this->request->getMethod();

    return [
        'message' => "hello {$user}",
        'method' => $method,
    ];

});
$n = 0;
$app->addRoute(['GET', 'POST'],'/wx', function () use ($n){
    $data = $this->request->all();
    $client = $this->get(CurlSend::class)->create([
        'base_uri'=>$this->get(ConfigInterface::class)->get('bot.wx.host'),
        'timeout'  => 5.0
    ]);
    switch ($data['type']){
        case Wx::PRIVATE_MSG:
            switch (true){
                case preg_match('/#菜单/',$data['msg']):
                    $menu = $this->get(Bot::class)->setType('default')->menu();
                    $this->get(Wx::class)->setSend($client)->sendMsg(urlencode($menu[1]),$data['from_wxid'],$data['robot_wxid']);
                    break;
            }
            //$res = $this->get(Wx::class)->setSend($client)->sendMsg($data['msg']);
            break;
        case Wx::GROUP_MSG:

            break;
    }
});

$app->run();