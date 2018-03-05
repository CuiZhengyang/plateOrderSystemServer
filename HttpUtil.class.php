<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-6-30
 * Time: 下午6:01
 * To change this template use File | Settings | File Templates.
 */
error_reporting(0);
require_once 'Consts.class.php';

class HttpUtil
{

    /**发送post请求
     * @param $url 请求路径
     * @param $data 请求数据
     * @return mixed 返回json格式数据
     */
    public static function sendPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 检查证书中是否设置域名（为0也可以，就是连域名存在与否都不验证了）
        $result = curl_exec($ch);
//        echo "result:".$result."<br>";
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;

    }

    /**发送带特定头部post请求
     * @param $url 请求路径
     * @param $data 请求数据
     * @return mixed 返回json格式数据
     */
    public static function sendPostRequestWithHeader($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_HTTPHEADER,array(
            'Authorization'=>'Basic '.base64_encode(Consts::C_CD_APP_KEY.':'.Consts::C_CD_APP_SECRET)
        ));
        $result = curl_exec($ch);
        curl_close($ch);
//        echo('body========'.$result.'<br>');
        return $result;
    }

    /**发送带特定头部post请求json格式
     * @param $url 请求路径
     * @param $data 请求数据
     * @return mixed 返回json格式数据
     */
    public static function sendPostRequestWithJsonHeader($url, $data, $accessToken)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch,CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$accessToken
        ));
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }

    public static function sendPostRequestWithHeaderIdentify($url, $data,$contentType)
    {
        $ch = curl_init();
        $proxy = '172.17.249.26:9010';//生产
        curl_setopt($ch, CURLOPT_PROXY, $proxy);//生产
        curl_setopt($ch, CURLOPT_URL, $url);//生产
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch,CURLOPT_HTTPHEADER,array(
            'Content-Type: '.$contentType,
        ));
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }

    public static function sendGetRequetWithHeaderIdentify($url,$contentType)
    {
        $ch = curl_init();
        $proxy = '172.17.249.26:9010';//生产
        curl_setopt($ch, CURLOPT_PROXY, $proxy);//生产
        curl_setopt($ch, CURLOPT_URL, $url);//生产
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:'.$contentType)
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function sendGetRequetWithHeaderIdentifyImg($url)
    {
        $ch = curl_init();
        $proxy = '172.17.249.26:9010';//生产
        curl_setopt($ch, CURLOPT_PROXY, $proxy);//生产
        curl_setopt($ch, CURLOPT_URL, $url);//生产
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:image/webp')
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    /**
     * 发送相关Get请求
     * @param $url 请求路径
     * @return mixed 返回json格式数据
     */
    public static function sendGetRequet($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json')
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function sendGetHttpsRequet($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json')
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 发送相关Get请求
     * @param $url 请求路径
     * @return mixed 返回json格式数据
     */
    public static function sendGetRequetUTF8($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'application/x-www-form-urlencoded;charset=utf-8')
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**发送post请求
     * @param $url 请求路径
     * @param $data 请求数据
     * @return mixed 返回json格式数据
     */
    public static function sendPostRequestUTF8($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //curl_exec将结果返回,而不是执行
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 检查证书中是否设置域名（为0也可以，就是连域名存在与否都不验证了）
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'application/x-www-form-urlencoded;charset=utf-8')
        );
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }

}