<?php 

include("pnrapi.class.php");
$apikey = 'c7aead78d33c317158cea6f2e135067f';
$apisecret  = '7f31241814514fd091902aa600b930e6';

$railObj = new MyRailApi($apikey, $apisecret);

$data = ($railObj->getPnrStatus('1245678902'));

echo "<pre>";

if($data->response_code==200){

print_r($data);

}

