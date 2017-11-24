<?php

namespace Helbing\WxWork;

require_once './php/WXBizMsgCrypt.php';

class WxWorkReceiver
{
    private $token = null;
    private $encodingAesKey = null;
    // 是否要自动将转下划线命名
    // 建议开启自动转下划线命名，原因在于接口返回的时候下划线命名，而推送过来的却是驼峰命名，如果两者统一了的话，可以省去很多麻烦事
    private $autoConvert = false;

    public function __construct($token, $encodingAesKey, $autoConvert = false)
    {
        $this->autoConvert = $autoConvert;

        $this->token = $token;
        $this->encodingAesKey = $encodingAesKey;
    }

    /**
     * 接收企业微信发送过来的信息
     * 企业微信发送过来的数据是进行加密的，需要先进行解密
     * https://work.weixin.qq.com/api/doc#10514
     * @return mixed
     */
    public function receiver()
    {
        $content = file_get_contents('php://input');

        $data = Tools::xml2Array($content);

        $wxCpt = new \WXBizMsgCrypt($this->token, $this->encodingAesKey, $data['ToUserName']);

        $errCode = $wxCpt->DecryptMsg($_GET['msg_signature'], $_GET['timestamp'], $_GET['nonce'], $content, $msg);

        if ($errCode == 0) {
            return $this->result(0, Tools::xml2Array($msg));
        }

        return $this->result($errCode, null);
    }

    /**
     * 统一处理返回
     * @param $errorCode
     * @param null $data
     * @return array
     */
    private function result($errorCode, $data = null)
    {
        if ($this->autoConvert) {
            $data = Tools::convertArrayToUnderline($data);
        }
        return [
            $errorCode,
            $data
        ];
    }
}
