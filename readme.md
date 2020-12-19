## 说明

php简单封装 <b>腾讯即时通讯IM</b> 服务端，便于自己方便使用
版本：v4

composer 安装
```
composer require errorcode\tim
```

#### 1.使用
```
$tim = new errorcode\tim\Tim($appid,$key);
$appid $key 必须，腾讯即时通讯获得
```
#### 2.获取UserSig
```
/**
* 获取UserSig
* @param string userid - 用户id，限制长度为32字节，只允许包含大小写英文字母（a-zA-Z）、数字（0-9）及下划线和连词符。
* @param string expire - UserSig 票据的过期时间，单位是秒，比如 86400 代表生成的 UserSig 票据在一天后就无法再使用了。
*/
$tim->sign($userid,$expire);
```

##### 注意：如果已经获得UserSig,不想调用sign，可使用如下方法手动设置
```
// $sign 为已获得的UserSig
$tim->setConfig($userid,$sign);
```

#### 3.使用API
```
// $service 内部服务名
// $command 业务名
// $data 数组 需要传入的参数
$result = $tim->query($service,$command,$data = []);
print_r($result);
```
#### 栗子：
>导入单个帐号
文档参考：https://cloud.tencent.com/document/product/269/1608

使用方法：
```
$result = $tim->query('im_open_login_svc','account_import',[
    'Identifier'=>'test',
    'Nick'=>'test',
    'FaceUrl'=>'http://www.qq.com'
]);
print_r($result);
```