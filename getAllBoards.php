<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 19:40
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

$db = MySQL::getInstance();

$sql = " SELECT DISTINCT`name` FROM board";
$result = $db->getRowsArray($sql);
if (count($result) != 0) {

    $product = array();
    foreach ($result as $value) {
        array_push($product, $value['name']);
    }

    /**
     * 查询第一个的所有材质颜色和品牌
     */
    $material = CommomUtil::getMetalByProduct($product[0]);
    $colors = CommomUtil::getColorByPM($product[0], $material[0]);
    $returnRes = array(
        'statusCode' => '000000',
        "msg" => '成功',
        "data" => array(
            "products" => $product,
            "materials" => $material,
            "colors" => $colors
        )
    );
} else {
    $returnRes = array(
        'statusCode' => '99999',
        "msg" => '成功',
        "data" => array()
    );
}

echo json_encode($returnRes);