<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 18:42
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

if (!isset($_POST["product"])) {
    $returnRes = array(
        'statusCode' => '99999',
        "msg" => '参数出错',
    );
    echo json_encode($returnRes);
    exit(-1);
}

$product = $_POST['product'];


$db = MySQL::getInstance();

$material = CommomUtil::getMetalByProduct($product);
$colors = CommomUtil::getColorByPM($product, $material[0]);

$returnRes = array(
    'statusCode' => '000000',
    "msg" => '成功',
    "data" => array(
        "materials" => $material,
        "colors" => $colors
    )
);
echo json_encode($returnRes);