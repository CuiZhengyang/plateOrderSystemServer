<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2 0002
 * Time: 15:15
 */
error_reporting(0);
require_once "./CommomUtil.php";

if(!isset($_POST["product"]))
{
    $returnRes = array(
        'statusCode' => '99999',
        "msg"=>'参数出错',
    );
    echo json_encode($returnRes);
    exit(-1);
}

$standard=CommomUtil::getCmmStandard($_POST["product"]);

$returnRes = array(
    'statusCode' => '000000',
    "msg"=>'成功',
    "data"=>array(
        "standard" => $standard
    )
);
echo json_encode($returnRes);