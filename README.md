## 说明

php 简单封装 <b>腾讯即时通讯IM</b> 服务端，便于自己方便使用

IM版本：v4

composer 安装
```
composer require errorcode\tim
```

#### 1.使用
```
$tim = new errorcode\tim\Tim($config);
$config['appid']    必须，腾讯即时通讯获得
$config['key']      必须，腾讯即时通讯获得
$config['userid']    必须，用户名或ID，一般应用管理员
```
###### 初始化后会自动校验和缓存UserSig


#### 2.获取UserSig
```
/**
* 获取UserSig
*/
$tim->getSign();
```

##### 临时手动切换账号与UserSig
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
以数组的形式返回结果
```
//结果示例
[
   "ActionStatus"=>"OK",
   "ErrorInfo"=>"",
   "ErrorCode"=>0
]
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

>具体业务逻辑与返回数据以官方文档为准
更多使用方法参考官方文档 https://cloud.tencent.com/document/product/269/1519
