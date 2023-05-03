#### 使用说明
    1. 运行java签名工具
  ```
    cd vendor/cmbc_sign/ext
   java -jar  sm2-1.0.1.jar

    
    $cmbc = new \Owlet\CmbcSign\Cmbc();
    $config = require_once "../src/config/sign.php";
    $cmbc->config($config);
    
    $result = $cmbc->request("https://api.cmbchina.com/xft/itax/tax/v2/TXQRYORG","POST",array());
    
    var_dump($result);die();

  ```


