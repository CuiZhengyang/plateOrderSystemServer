<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 19:09
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

if(!isset($_POST["metal"])&&!isset($_POST["product"]))
{
    $returnRes = array(
        'statusCode' => '99999',
        "msg"=>'参数出错',
    );
    echo json_encode($returnRes);
    exit(-1);
}

$product = $_POST['product'];
$metal = $_POST['metal'];

$db = MySQL::getInstance();


$colors=CommomUtil::getColorByPM($product,$metal);

$returnRes = array(
    'statusCode' => '000000',
    "msg" => '成功',
    "data" => array(
        "colors" => $colors
    )
);
echo json_encode($returnRes);