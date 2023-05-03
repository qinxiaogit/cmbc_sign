<?php


namespace Owlet\CmbcSign;


class NormalQuery
{
    protected $config;

    public function config(array $config): NormalQuery
    {
        $this->config = $config;
        return $this;
    }

    public function getQuery(): array
    {
        return [
            'CSCAPPUID' => $this->config['csc_app_uid'] ?? "",
            "CSCPRJCOD" => $this->config["csc_prj_cod"] ?? "",
            "CSCREQTIM" => $this->getMillisecond(),
            "CSCUSRNBR" => $this->config["csc_user_nbr"] ?? "",
            "CSCUSRUID" => $this->config["csc_user_uid"] ?? "",
        ];
    }

    /**
     * @desc 获取毫秒
     * @return float
     */
    function getMillisecond()
    {

        list($t1, $t2) = explode(' ', microtime());

        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);

    }
}
