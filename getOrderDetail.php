<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 15:28
 */
error_reporting(0);
require_once "./CommomUtil.php";

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

if (!isset($_POST['orderNum'])) {
    $returnRes = array(
        'statusCode' => '99999',
        "msg"=>'订单不存在，无法查找',
    );
    echo json_encode($returnRes);
    exit(-1);
}

$orderNum=$_POST["orderNum"];

$dtail=CommomUtil::getOrderDetail($orderNum,$role,$uid);

$returnRes = array(
    'statusCode' => '000000',
    "msg"=>'',
    "data"=>$dtail
);
echo json_encode($returnRes);