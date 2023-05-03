<?php

namespace Owlet\CmbcSign;

use GuzzleHttp\Client;

class Cmbc
{
    protected $config;

    /**
     * @var NormalQuery
     */
    protected $normalQuery;

    public function config(array $config): Cmbc
    {
        $this->config = $config;
        if (empty($this->config[''])) {
            $this->config['sign_url'] = "localhost:8080/api/sign";
        }

        $this->normalQuery = (new NormalQuery())->config($config);
        return $this;
    }

    private $url;
    private $method;

    public function request(string $url, string $method, array $data): array
    {
        $this->url = $url;
        $this->method = $method;

        return $this->sign($data);

    }

    function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * @desc 计算签名
     * @param string $method
     * @return string
     */
    public function sign(array $data): array
    {
        $micTime = time();
        $query = $this->normalQuery->getQuery();
        if(empty($data)){
            $bodyStr = "{}";
        }else{
            $bodyStr = json_encode($data);
        }
        $urlInfo = parse_url($this->url);
        $document = "POST " . ($urlInfo["path"] ?? "") . "?" . http_build_query($query) .
            "\nx-alb-digest: " . $bodyStr .
            "\nx-alb-timestamp: " . $micTime;

        $privateKey = $this->config['authority_secret'] ?? "";
        $sign = $this->curl_post($this->config['sign_url'], array("sign_content" => $document, 'private_key' => $privateKey, 'user_id' => 1), ["Content-Type:application/json"]);
        $header = [
            "appid:" . $this->config['csc_app_uid'] ?? "",
            "x-alb-digest:" . $this->sm3Sign($bodyStr),
            "x-alb-timestamp:" . $micTime,
            "apisign:" . $sign,
            "x-alb-verify:" . "sm3withsm2",
            "Content-Type:application/json",
        ];

        $da = $this->curl_post($this->url . "?" . http_build_query($query), $bodyStr, $header);
        return json_decode($da, true);
    }

    /**
     * @name unknown curl请求
     * @param unknown $data 请求参数
     * @param unknown $url 请求地址
     * @param number $header 报文头
     * @return shihandong
     */
    public function curl_post($url, $params, $header)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        if (is_array($params)) {
            $params = json_encode($params);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $data = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (empty($data)) {
            var_dump($status);
            var_dump(curl_error($curl));
        }
        curl_close($curl);//关闭cURL会话

        return $data;
    }

    protected function sm3Sign(string $data): string
    {
        $sm3 = new \Rtgm\sm\RtSm3();
        return $sm3->digest($data);
    }

    /**
     *
     */
    protected function parseUrl()
    {
        $urlInfo = parse_url($this->url);
        return $urlInfo['path'] ?? "";
    }

}
