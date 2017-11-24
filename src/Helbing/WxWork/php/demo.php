<?php

include_once "WXBizMsgCrypt.php";

$encodingAesKey = "AlLKmoWjxEFmVXC59c4yhNUExwL5cVV2wcqqELz78sM";
$token = "WQbA73rRtx";
//$corpId = "tj43a85d21cc8877ed";
$corpId = "ww6809c34a7c92a927";

// /prowork/wx/?msg_signature=e50574f96104069e2894a8a60a263ea32e2fcb66&timestamp=1511289624&nonce=1049278771
// /prowork/wx/?msg_signature=5edec736ce8cad11f15b7b997775cc710e82aa01&timestamp=1511302287&nonce=1079158719
// /prowork/wx?msg_signature=3577afc93a30635f89aef3d1762b95a51e453904&timestamp=1511306579&nonce=2066733068
$sReqMsgSig = "3577afc93a30635f89aef3d1762b95a51e453904";
$sReqTimeStamp = "1511306579";
$sReqNonce = "2066733068";

$sReqData = <<<STR
<xml><ToUserName><![CDATA[ww6809c34a7c92a927]]></ToUserName>
<Encrypt><![CDATA[NZ9nHfrZSVNBzpSBAHhujrNBLVpKTSi17x+3ORLzpzQlISCWJbShZSRn3QmBnL87nZHyOPjMFWX4Dfiay4HaIBbErTBUsPdmu3AXagy6IVow0BsZeJeEEjaMQFpCy+AVOTVulb6+tILJWH/QYh3igtzbDoICSbU6mZ9Z3K8xAFNP6leTRF7P7CibExZ02nBspvwQZPM3eZ1a9OC8Qo2EXwCZjLolKTP8+ortCOQjfIsQExWwSvVxG/WyGr9F61jrcByy2rBYFdIYbAxeCnhdbXjD6k3DxyST3IMPks5iKphSbtBibnNXtciNodcC4dr/krfcur5J5tf4gYCHwjHKeCfphHvq+Oyl61gYFSGSlLYb6xcLkBYEk0TIyWxoWxwVdPI47YA5U26n55kBGv0aXaZrewB2LzQGVV0dTbbkIhM=]]></Encrypt>
<AgentID><![CDATA[1000013]]></AgentID>
</xml>
STR;

$sMsg = "";  // 解析之后的明文
$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) {
    // 解密成功，sMsg即为xml格式的明文
    // TODO: 对明文的处理
    // For example:
    var_dump($sMsg);
    // ...
    // ...
} else {
    print("ERR: " . $errCode . "\n\n");
    //exit(-1);
}
die;
