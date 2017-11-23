<?php

namespace Helbing\WxWork;

use GuzzleHttp\Client;

class WxWork
{
    private $client = null;
    private $config = [];

    public function __construct($options = null, $config = [])
    {
        $this->config = $config;

        if (!is_null($options)) {
            $this->client = new Client();
        } else {
            $this->client = new Client($options);
        }
    }

    /**
     * 设置GuzzleHttp配置
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * 处理响应数据
     * @param \Psr\Http\Message\ResponseInterface $result
     * @return mixed
     */
    public function response($result)
    {
        return json_decode($result->getBody()->getContents(), true);
    }

    /**
     * 获取应用套件凭证
     * @param $suiteId
     * @param $suiteSecret
     * @param $suiteTicket
     * @return mixed
     */
    public function getSuiteToken($suiteId, $suiteSecret, $suiteTicket)
    {
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_suite_token';

        $params = [
            'json' => [
                'suite_id' => $suiteId,
                'suite_secret' => $suiteSecret,
                'suite_ticket' => $suiteTicket
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }

    /**
     * 获取预授权码
     * @param $suiteId
     * @param $suiteAccessToken
     * @return mixed
     */
    public function getPreAuthCode($suiteId, $suiteAccessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/service/get_pre_auth_code?suite_access_token={$suiteAccessToken}";

        $params = [
            'json' => [
                'suite_id' => $suiteId
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }


    /**
     * 设置授权配置
     * @param $preAuthCode
     * @param array $appId
     * @param int $authType
     * @param $suiteAccessToken
     * @return mixed
     */
    public function setSessionInfo($preAuthCode, $appId = [], $authType = 0, $suiteAccessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/service/set_session_info?suite_access_token={$suiteAccessToken}";

        $params = [
            'json' => [
                'pre_auth_code' => $preAuthCode,
                'session_info' => [
                    'appid' => $appId,
                    'auth_type' => $authType
                ]
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }


    /**
     * 获取企业永久授权码
     * @param $suiteId
     * @param $preAuthCode
     * @param $suiteAccessToken
     * @return mixed
     */
    public function getPermanentCode($suiteId, $preAuthCode, $suiteAccessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/service/get_permanent_code?suite_access_token={$suiteAccessToken}";

        $params = [
            'json' => [
                'suite_id' => $suiteId,
                'auth_code' => $preAuthCode
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }

    /**
     * 获取企业授权信息
     * @param $suiteId
     * @param $authCorpId
     * @param $permanentCode
     * @param $suiteAccessToken
     * @return mixed
     */
    public function getAuthInfo($suiteId, $authCorpId, $permanentCode, $suiteAccessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/service/get_auth_info?suite_access_token={$suiteAccessToken}";

        $params = [
            'json' => [
                'suite_id' => $suiteId,
                'auth_corpid' => $authCorpId,
                'permanent_code' => $permanentCode
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }

    /**
     * 获取企业access_token
     * @param $suiteId
     * @param $authCorpId
     * @param $permanentCode
     * @param $suiteAccessToken
     * @return mixed
     */
    public function getCorpToken($suiteId, $authCorpId, $permanentCode, $suiteAccessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/service/get_corp_token?suite_access_token={$suiteAccessToken}";

        $params = [
            'json' => [
                'suite_id' => $suiteId,
                'auth_corpid' => $authCorpId,
                'permanent_code' => $permanentCode
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }

    /**
     * 获取服务商的provider_access_token
     * @param $corpId
     * @param $providerSecret
     * @return mixed
     */
    public function getProviderToken($corpId, $providerSecret)
    {
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_provider_token';

        $params = [
            'json' => [
                'corpid' => $corpId,
                'provider_secret' => $providerSecret
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }

    /**
     * 获取用户登录信息
     * @param $authCode
     * @param $providerAccessToken
     * @return mixed
     */
    public function getLoginInfo($authCode, $providerAccessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info?access_token={$providerAccessToken}";

        $params = [
            'json' => [
                'auth_code' => $authCode
            ]
        ];

        return $this->response($this->client->request('POST', $url, array_merge($params, $this->config)));
    }
}
