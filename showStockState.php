<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 17:25
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


$types=CommomUtil::getCommodityTypes();
$commondy=CommomUtil::getCommoditys();

$returnRes = array(
    'statusCode' => '000000',
    "msg" => "OK",
    "data"=>array(
        "labels"=>$types,
        "list"=>$commondy
    )
);
echo json_encode($returnRes);