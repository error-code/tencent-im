<?php
namespace errorcode\tim;
class Notify{

    //回调列表
    private $callback = [
        'State.StateChange',                    //状态变更回调
        'Sns.CallbackFriendAdd',                //添加好友之后回调
        'Sns.CallbackFriendDelete',             //删除好友之后回调	
        'Sns.CallbackBlackListAdd',             //添加黑名单之后回调
        'Sns.CallbackBlackListDelete',          //删除黑名单之后回调
        'C2C.CallbackBeforeSendMsg',            //发单聊消息之前回调
        'C2C.CallbackAfterSendMsg',             //发单聊消息之后回调
        'Group.CallbackBeforeCreateGroup',      //创建群组之前回调
        'Group.CallbackAfterCreateGroup',       //创建群组之后回调
        'Group.CallbackBeforeApplyJoinGroup',   //申请入群之前回调
        'Group.CallbackBeforeInviteJoinGroup',  //拉人入群之前回调
        'Group.CallbackAfterNewMemberJoin',     //新成员入群之后回调
        'Group.CallbackAfterMemberExit',        //群成员离开之后回调
        'Group.CallbackBeforeSendMsg',          //群内发言之前回调
        'Group.CallbackAfterSendMsg',           //群内发言之后回调
        'Group.CallbackAfterGroupFull',         //群组满员之后回调
        'Group.CallbackAfterGroupDestroyed',    //群组解散之后回调
        'Group.CallbackAfterGroupInfoChanged',  //群组资料修改之后回调
    ];

    public $command = '';       //命令
    public $client_ip = '';     //ip
    public $platform = '';      //平台


    //获取通知
    public function notify(){
        
        $this->command = isset($_GET['CallbackCommand'])?$_GET['CallbackCommand']:'';
        $this->client_ip = isset($_GET['ClientIP'])?$_GET['ClientIP']:'';
        $this->platform = isset($_GET['OptPlatform'])?$_GET['OptPlatform']:'';
        if(!$this->command){
            throw new \Exception("Command does not exist");
        }
        if(!in_array($this->command,$this->callback)){
            throw new \Exception("Command {$this->command} does not exist");
        }

        $postData = file_get_contents("php://input");
        return \json_decode($postData,true);
    }

    //应答
    public function reply($data = []){
        if($data){
            echo \json_encode($data);
        }else{
            echo \json_encode([
                "ActionStatus" => "OK", 
                "ErrorInfo" => "", 
                "ErrorCode" => 0
            ]);
        }
    }
}