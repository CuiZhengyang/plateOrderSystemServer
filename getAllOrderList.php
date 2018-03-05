<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 11:31
 */
error_reporting(0);
require_once "CommomUtil.php";


$uid=$_COOKIE["lsbcSessionID"];
$role=$_COOKIE["lsbcSessionType"];

if($uid==null||$role==null)
{

    $returnRes = array(
        'statusCode' => '000001',
        "msg" => "重新登录",
    );
    echo json_encode($returnRes);
    exit(-1);
}
CommomUtil::setCookieTime($uid,$role);

$uid = $_COOKIE["lsbcSessionID"];
$role = $_COOKIE["lsbcSessionType"];
$list = CommomUtil::getAllOrderList($role, $uid);

$returnRes = array(
    'statusCode' => '000000',
    "msg" => "获取成功",
    "data" => $list
);
echo json_encode($returnRes);


