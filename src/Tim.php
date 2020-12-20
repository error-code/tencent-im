<?php
namespace errorcode\tim;
use errorcode\tim\Sign;
use GuzzleHttp\Client;
class Tim{

    private $appid = '';
    private $key = '';
    private $userid = '';
    private $sign = '';
    private $service = [
        'im_open_login_svc',
        'openim',
        'all_member_push',
        'profile',
        'sns',
        'group_open_http_svc',
        'openconfigsvr',
        'open_msg_svc',
        'ConfigSvc'
    ];

    private $url = 'https://console.tim.qq.com/v4/';

    public function __construct($appid = '',$key = ''){
        if(!$appid||!$key){
            throw new \Exception('appid 和 key 不能为空');
        }
        $this->appid = $appid;
        $this->key = $key;
    }

    /**
     * 请求
     */
    public function query($service,$command,$data = []){
        if(!$service){
            throw new \Exception('服务名不能为空');
        }
        if(!$command){
            throw new \Exception('业务名不能为空');
        }
        if(!in_array($service,$this->service)){
            throw new \Exception("业务[$service]不存在");
        }
        
        return $this->post($service,$command,$data);
    }

    /**
     * 手动配置UserSig
     * @param userid
     * @param sign
     */
    public function setConfig($userid,$sign){
        $this->userid = $userid;
        $this->sign = $sign;
        return $this;
    }

    /**
     * 获取UserSig
     * @param string userid - 用户id，限制长度为32字节，只允许包含大小写英文字母（a-zA-Z）、数字（0-9）及下划线和连词符。
     * @param string expire - UserSig 票据的过期时间，单位是秒，比如 86400 代表生成的 UserSig 票据在一天后就无法再使用了。
     */
    public function sign($userid = '',$expire = 86400*180){
        if(!$userid){
            throw new \Exception('userid 不能为空');
        }
        $sign = new Sign($this->appid,$this->key);
        $this->userid = $userid;
        $this->sign =  $sign->genUserSig($userid);
        return $this->sign;
    }

    /**
     * Post提交数据
     */
    public function post($servicename,$command,$data){
        
        $rand1 = rand(10000,55555);
        $rand2 = rand(10000,55555);
        $random = $rand1.$rand2;

        $client = new Client([
            'base_uri'=>$this->url,
            'timeout'  => 5.0,
        ]);

        $query['sdkappid'] = $this->appid;
        $query['identifier'] = $this->userid;
        $query['usersig'] = $this->sign;
        $query['random'] = $random;
        $query['contenttype'] = 'json';

        $response = $client->post("$servicename/$command",[
            'query'=>$query,
            'json'=>$data
        ]);

        $body = (string)$response->getBody();
        if(!$body){
            return false;
        }
        return json_decode($body,true);
    }
}