<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 21:15
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
$uid=CommomUtil::secret2string($uid);
$role=CommomUtil::secret2string($role);
CommomUtil::setCookieTime($uid,$role);
//生成唯一订单编号
$orderNum=date("YmdHis").'-'.uniqid();

$type=$_POST["type"];
$remarks=$_POST["remarks"];
$name=$_POST["name"];
$tel=$_POST["tel"];
$rdtailAddr=$_POST["rdetailAddr"];
$list=$_POST["list"];

//根据Type 插入对应的商品 表
CommomUtil::addOrderList($orderNum,$type,$list);

//插入board 表
CommomUtil::addOrder($orderNum,$uid,$role,$type,$name,$tel,$rdtailAddr,$remarks);
//插入board _state表

CommomUtil::addOrderState($orderNum,"创建订单");

$returnRes = array(
    'statusCode' => '000000',
    "msg" => '成功',
);
echo json_encode($returnRes);