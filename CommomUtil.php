<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27 0027
 * Time: 15:08
 */
require_once './MySQL.class.php';
require_once './Consts.class.php';
date_default_timezone_set("Asia/Shanghai");


class CommomUtil
{
    //加密
    public static function string2secret($str)
    {
        $key = "123";
        $td = mcrypt_module_open(MCRYPT_DES, '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);

        $key = substr(md5($key), 0, $ks);
        mcrypt_generic_init($td, $key, $iv);
        $secret = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $secret;
    }

    //解密
    public static function secret2string($sec)
    {
        $key = "123";
        $td = mcrypt_module_open(MCRYPT_DES, '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);

        $key = substr(md5($key), 0, $ks);
        mcrypt_generic_init($td, $key, $iv);
        $string = mdecrypt_generic($td, $sec);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return trim($string);
    }

    public static function setCookieTime($id, $role)
    {

        $id = self::string2secret($id);
        $role = self::string2secret($role);
        setcookie("lsbcSessionID", $id, time() + 3600, '/');
        setcookie("lsbcSessionType", $role, time() + 3600, '/');
    }

    public static function getMetalByProduct($product)
    {
        $db = MySQL::getInstance();
        $sql = " SELECT DISTINCT material FROM board WHERE `name`='" . $product . "'";
        $result = $db->getRowsArray($sql);
        $material = array();
        if (count($result) != 0) {
            foreach ($result as $value) {
                array_push($material, $value['material']);
            }
        }
        return $material;
    }

    public static function getColorByPM($product, $material)
    {
        $db = MySQL::getInstance();
        $sql = " SELECT DISTINCT color FROM board WHERE `name`='" . $product . "' AND material='" . $material . "'";
        $result = $db->getRowsArray($sql);
        $arr = array();
        if (count($result) != 0) {
            foreach ($result as $value) {
                array_push($arr, $value['color']);
            }
        }
        return $arr;
    }

    public static function getUserInfo($role, $id)
    {
        $db = MySQL::getInstance();
        if ($role == 0) {
            $sql = "SELECT * from merchant WHERE muid=" . $id;
        } else {
            $sql = "SELECT * FROM salesperson WHERE suid=" . $id;
        }
        $userInfo = $db->getRowsArray($sql);
        return $userInfo;
    }

    public static function addOrderList($orderNum, $type, $list)
    {

        $db = MySQL::getInstance();
        if ($type == "1") {
            foreach ($list as $item) {
                $sql = "replace INTO orders_board(orderNum,name,material,color,count) VALUES ('" . $orderNum . "','" . $item['product'] . "','" . $item['metal'] . "','" . $item['color'] . "'," . $item['count'] . ")";
                $db->uidRst($sql);
            }
        } else {
            foreach ($list as $item) {
                $sql = "replace INTO orders_commodity(orderNum,name,standard,brand,count) VALUES ('" . $orderNum . "','" . $item['product'] . "','" . $item['metal'] . "','" . $item['color'] . "'," . $item['count'] . ")";
                $db->uidRst($sql);
            }
        }

    }

    /**
     * 添加订单
     * @param $orderNum
     * @param $uid
     * @param $role 0 :商户，  1：销售员
     * @param $type 1:板材    2：五金
     * @param $name
     * @param $tel
     * @param $rdtailAddr
     * @param $remarks
     */
    public static function addOrder($orderNum, $uid, $role, $type, $name, $tel, $rdtailAddr, $remarks)
    {
        $db = MySQL::getInstance();
        $userinfo = self::getUserInfo($role, $uid);
        $userinfo = $userinfo[0];
        if ($role == "0") {
            //商户的订单
            $columns = "orderNum,muid,suid,rname,rtel,rdetailAddr,type,remarks,complete,insertTime";
            if ($name == null) {
                $name = $userinfo["name"];
            }

            if ($tel == null) {
                $tel = $userinfo["tel"];
            }

            if ($rdtailAddr == null) {
                $rdtailAddr = $userinfo["province"] . $userinfo["city"] . $userinfo["detailAddr"];
            }

            if ($remarks == null) {
                $remarks = "";
            }
            $values = "'" . $orderNum . "'," . $userinfo["muid"] . "," . $userinfo["suid"] . ",'" . $name . "','" . $tel . "','" . $rdtailAddr . "'," . $type . ",'" . $remarks . "',0,now()";
        } else {
            //商户的订单
            $columns = "orderNum,suid,rname,rtel,rdetailAddr,type,remarks,complete,insertTime";
            if ($name == null) {
                $name = $userinfo["name"];
            }

            if ($tel == null) {
                $tel = $userinfo["tel"];
            }

            if ($rdtailAddr == null) {
                $rdtailAddr = $userinfo["province"];
            }

            if ($remarks == null) {
                $remarks = "";
            }
            $values = "'" . $orderNum . "'," . $userinfo["suid"] . ",'" . $name . "','" . $tel . "','" . $rdtailAddr . "'," . $type . ",'" . $remarks . "',0,now()";
        }


        $sql = "replace INTO orders(" . $columns . ") VALUES (" . $values . ")";
        $db->uidRst($sql);
    }

    /**
     * 添加订单状态
     * @param $orderNum
     * @param $describe
     */
    public static function addOrderState($orderNum, $describe)
    {
        $db = MySQL::getInstance();
        $sql = "REPLACE INTO orders_state set orderNum='" . $orderNum . "', describes='" . $describe . "', time=NOW();";
        $db->uidRst($sql);
    }

    /**
     * 获取所有的 订单列表
     *
     * @param $role 0 :商户，  1：销售员
     * @param $uid
     */
    public static function getAllOrderList($role, $uid)
    {
        $db = MySQL::getInstance();
        $completeList = array();
        $unCompleteList = array();
        if ($role == "0") {
            //商户的订单列表查询 不需要商户名称
            $sql = "SELECT * from orders WHERE muid=" . $uid . " and insertTime>DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ORDER BY insertTime DESC";
            $orderlist = $db->getRowsArray($sql);
            if (count($orderlist) > 0) {
                foreach ($orderlist as $item) {
                    if ($item["complete"] == "0") {
                        array_push($unCompleteList, array(
                            "orderNum" => $item["orderNum"]
                        ));
                    } else {
                        array_push($completeList, array(
                            "orderNum" => $item["orderNum"]
                        ));
                    }
                }
            }
        } else {

            $userInfo = self::getUserInfo($role, $uid);
            $sql = "SELECT orderNum,merchant.name as mName,complete from orders LEFT JOIN merchant on orders.muid=merchant.muid WHERE  orders.suid=" . $uid . " and insertTime>DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ORDER BY insertTime,orders.muid DESC ";
            $orderlist = $db->getRowsArray($sql);
            if (count($orderlist) > 0) {
                foreach ($orderlist as $item) {
                    if ($item["mName"] != null) {
                        if ($item["complete"] == "0") {
                            array_push($unCompleteList, array(
                                "orderNum" => $item["orderNum"],
                                "name" => $item["mName"],
                            ));
                        } else {
                            array_push($completeList, array(
                                "orderNum" => $item["orderNum"],
                                "name" => $item["mName"],
                            ));
                        }
                    } else {
                        if ($item["complete"] == "0") {
                            array_push($unCompleteList, array(
                                "orderNum" => $item["orderNum"],
                                "name" => $userInfo[0]["name"],
                            ));
                        } else {
                            array_push($completeList, array(
                                "orderNum" => $item["mName"],
                                "name" => $userInfo[0]["name"],
                            ));
                        }
                    }

                }
            }
        }

        return array(
            "completeList" => $completeList,
            "unCompleteList" => $unCompleteList,
        );
    }

    /**
     * 获取订单详细信息
     * @param $orderNum
     * @param $role
     * @param $uid
     * @return array
     */
    public static function getOrderDetail($orderNum, $role, $uid)
    {
        $db = MySQL::getInstance();

        $detail = array();
        $states = array();
//        if ($role == "0") {
        //商户的订单列表查询 不需要商户名称
        $sql = "SELECT * from orders WHERE orderNum='" . $orderNum . "' ORDER BY insertTime DESC";
        $orderlist = $db->getRowsArray($sql);
        if (count($orderlist) == 1) {
            $detail["name"] = $orderlist[0]["rname"];
            $detail["tel"] = $orderlist[0]["rtel"];
            $detail["rdetailAddr"] = $orderlist[0]["rdetailAddr"];
            $detail["remarks"] = $orderlist[0]["remarks"];
        }

//        } else {
//
//
//        }
        //获取订单状态
        $sql = "SELECT describes,time from orders_state WHERE orderNum='" . $orderNum . "' ORDER BY time DESC";
        $orderstatelist = $db->getRowsArray($sql);
        $detail["states"] = $orderstatelist;

        //获取订单商品
        if ($orderlist[0]["type"] == "1") {
            //板材
            $sql = "SELECT name,material,color,count from orders_board WHERE  orderNum='" . $orderNum . "'";

        } else {
            //五金
            $sql = "SELECT name,standard as material,brand as color,count from orders_commodity WHERE  orderNum='" . $orderNum . "'";
        }
        $orderHasList = $db->getRowsArray($sql);
        $detail["goods"] = $orderHasList;

        return $detail;
    }

    /*
     * 查询五金产品的种类
     */
    public static function getCommodityTypes()
    {
        $db = MySQL::getInstance();
        $sql = "SELECT DISTINCT type from commodity ORDER By type";
        $result = $db->getRowsArray($sql);
        $types = array();
        if (count($result) > 0) {
            foreach ($result as $item) {
                array_push($types, $item["type"]);
            }
        }
        return $types;
    }

    /*
     * 查询五金产品的库存
     */
    public static function getCommoditys()
    {
        $db = MySQL::getInstance();
        $sql = "SELECT name,type,standard,stockState from commodity ORDER BY type";
        $result = $db->getRowsArray($sql);
        return $result;
    }

    /**
     * 获取所有五金产品的名字
     */
    public static function getAllCommodityName()
    {
        $db = MySQL::getInstance();
        $commodityNames = array();
        $sql = "SELECT DISTINCT`name` FROM commodity";
        $result = $db->getRowsArray($sql);
        if (count($result) != 0) {
            foreach ($result as $item) {
                array_push($commodityNames, $item['name']);
            }
        }

        return $commodityNames;
    }

    public static function getCmmStandard($cmm)
    {
        if ($cmm == null) {
            return array();
        }

        $db = MySQL::getInstance();
        $standard = array();
        $sql = "SELECT DISTINCT standard FROM commodity WHERE `name`='" . $cmm . "'";
        $result = $db->getRowsArray($sql);
        if (count($result) != 0) {
            foreach ($result as $item) {
                array_push($standard, $item['standard']);
            }
        }
        return $standard;
    }
}