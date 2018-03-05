<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 19:40
 */
error_reporting(0);
require_once './CommomUtil.php';


if (!(isset($_POST['name'])) || !(isset($_POST['password']))) {
    $returnRes = array(
        'statusCode' => '99999',
        "msg"=>'请输入用户名和密码',
    );
    echo json_encode($returnRes);
    exit(-1);
}

$name = $_POST['name'];
$pwd = $_POST['password'];
$db = MySQL::getInstance();

$sql = " SELECT * from user WHERE name='" . $name . "' and password='" . $pwd . "'";
$result = $db->getRowsArray($sql);
if (count($result) == 1) {
    setcookie("lsbcSessionID",$result[0]["ruid"],time()+3600,'/');
    setcookie("lsbcSessionType",$result[0]["role"],time()+3600,'/');
    $userInfo=CommomUtil::getUserInfo($result[0]["role"],$result[0]["ruid"]);
    $returnRes = array(
        'statusCode' => '000000',
        "msg"=>'登录成功',
        "data"=>array(
            "name"=>$userInfo[0]["name"],
            "tel"=>$userInfo[0]["tel"],
            "province"=>$userInfo[0]["province"],
            "city"=>$userInfo[0]["city"],
            "detailAddr"=>$userInfo[0]["detailAddr"],
        )
    );
} else {
    $returnRes = array(
        'statusCode' => '99999',
        "msg"=>'无此用户',
    );
}

echo json_encode($returnRes);