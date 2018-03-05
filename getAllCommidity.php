<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2 0002
 * Time: 14:45
 */
error_reporting(0);
require_once "./CommomUtil.php";

$commditys=CommomUtil::getAllCommodityName();

$standard=CommomUtil::getCmmStandard($commditys[0]);

$returnRes = array(
    'statusCode' => '000000',
    "msg" => '成功',
    "data" => array(
        "products" => $commditys,
        "standard" => $standard
    )
);
echo  json_encode($returnRes);