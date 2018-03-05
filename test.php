<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/12 0012
 * Time: 11:12
 */
require_once './MySQL.class.php';
require_once './Consts.class.php';
date_default_timezone_set("Asia/Shanghai");

$db = MySQL::getInstance();

$sql = " SELECT * from `user`";
$result = $db->getRowsArray($sql);
if ($result != '' && count($result) > 0) {
    $name=$result[0]['name'];
    $password=$result[0]['password'];
    $returnRes = array(
        'statusCode' => '00',
        'data' => array(
            'password' => $password,//用户正常
            'name' => $name,
        )
    );
    echo json_encode($returnRes);
}
else{
    echo "error";
}