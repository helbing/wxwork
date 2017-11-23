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
     * 请求数据
     * @param $method
     * @param $url
     * @param $params
     * @return mixed
     */
    public function result($method, $url, $params = [])
    {
        $response = $this->client->request($method, $url, array_merge($params, $this->config));

        return json_decode($response->getBody()->getContents(), true);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
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

        return $this->result('POST', $url, $params);
    }

    //
    // 通讯录管理
    //

    /**
     * 通过$corpId, $secret获取企业微信的access_token
     * @param $corpId
     * @param $secret
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function getAccessToken($corpId, $secret)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corpId}&corpsecret={$secret}";

        return $this->result('GET', $url);
    }

    /**
     * 创建用户
     * {
     *     "userid": "zhangsan",
     *     "name": "张三",
     *     "english_name": "jackzhang",
     *     "mobile": "15913215421",
     *     "department": [1, 2],
     *     "order":[10,40],
     *     "position": "产品经理",
     *     "gender": "1",
     *     "email": "zhangsan@gzdev.com",
     *     "isleader": 1,
     *     "enable":1,
     *     "avatar_mediaid": "2-G6nrLmr5EC3MNb_-zL1dDdzkd0p7cNliYu9V5w7o8K0",
     *     "telephone": "020-123456"，
     *     "extattr": {"attrs":[{"name":"爱好","value":"旅游"},{"name":"卡号","value":"1234567234"}]}
     *  }
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userCreate($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 通过用户ID获取用户信息
     * @param string $accessToken 企业微信的access_token
     * @param $userId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userGet($accessToken, $userId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token={$accessToken}&userid={$userId}";

        return $this->result('GET', $url);
    }

    /**
     * 更新用户
     * https://work.weixin.qq.com/api/doc#10020
     * {
     *     "userid": "zhangsan",
     *     "name": "张三",
     *     "english_name": "jackzhang",
     *     "mobile": "15913215421",
     *     "department": [1, 2],
     *     "order":[10,40],
     *     "position": "产品经理",
     *     "gender": "1",
     *     "email": "zhangsan@gzdev.com",
     *     "isleader": 1,
     *     "enable":1,
     *     "avatar_mediaid": "2-G6nrLmr5EC3MNb_-zL1dDdzkd0p7cNliYu9V5w7o8K0",
     *     "telephone": "020-123456"，
     *     "extattr": {"attrs":[{"name":"爱好","value":"旅游"},{"name":"卡号","value":"1234567234"}]}
     *  }
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userUpdate($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/update?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 删除单个用户
     * @param $accessToken
     * @param $userId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userDelete($accessToken, $userId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/delete?access_token={$accessToken}&userid={$userId}";

        return $this->result('GET', $url);
    }

    /**
     * 批量删除用户
     * @param $accessToken
     * @param $userIds string 例子: ["zhangsan", "lisi"]
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userBatchDelete($accessToken, $userIds)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete?access_token={$accessToken}";

        $params = [
            'json' => [
                'useridlist' => $userIds
            ]
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 获取部门成员
     * @param $accessToken
     * @param $departmentId
     * @param $fetchChild 1/0：是否递归获取子部门下面的成员
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userSimpleList($accessToken, $departmentId, $fetchChild)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist?access_token={$accessToken}&department_id={$departmentId}&fetch_child={$fetchChild}";

        return $this->result('GET', $url);
    }

    /**
     * 获取部门成员详情
     * @param $accessToken
     * @param $departmentId
     * @param $fetchChild
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userList($accessToken, $departmentId, $fetchChild = 0)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/list?access_token={$accessToken}&department_id={$departmentId}&fetch_child={$fetchChild}";

        return $this->result('GET', $url);
    }

    /**
     * userid转openid
     * https://work.weixin.qq.com/api/doc#11279
     * @param $accessToken
     * @param $userId
     * @param $agentId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userConvertToOpenId($accessToken, $userId, $agentId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/convert_to_openid?access_token={$accessToken}";

        $params = [
            'json' => [
                'userid' => $userId,
                'agentid' => $agentId
            ]
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * openid转userid
     * https://work.weixin.qq.com/api/doc#11279
     * @param $openId
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userConvertToUserId($accessToken, $openId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/convert_to_userid?access_token={$accessToken}";

        $params = [
            'json' => [
                'openid' => $openId
            ]
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 二次验证
     * https://work.weixin.qq.com/api/doc#11378
     * @param $userId
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function userAuthsucc($accessToken, $userId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/authsucc?access_token={$accessToken}&userid={$userId}";

        return $this->result('GET', $url);
    }

    /**
     * 创建部门
     * {
     *     "name": "广州研发中心",
     *     "parentid": 1,
     *     "order": 1,
     *     "id": 2
     * }
     * @param $data
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function departmentCreate($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/department/create?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 更新部门
     * https://work.weixin.qq.com/api/doc#10077
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function departmentUpdate($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/department/update?access_token=$accessToken";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 删除部门
     * @param $accessToken
     * @param $id
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function departmentDelete($accessToken, $id)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/department/delete?access_token={$accessToken}&id={$id}";

        return $this->result('GET', $url);
    }

    /**
     * 获取部门列表
     * @param $accessToken
     * @param null $id 部门id。获取指定部门及其下的子部门。 如果不填，默认获取全量组织架构
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function departmentList($accessToken, $id = null)
    {
        if (is_null($id)) {
            $url = "https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token={$accessToken}";
        } else {
            $url = "https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token={$accessToken}&id={$id}";
        }

        return $this->result('GET', $url);
    }

    /**
     * 创建标签
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagCreate($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/create?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 更新标签名字
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagUpdate($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/update?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 删除标签
     * @param $accessToken
     * @param $tagId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagDelete($accessToken, $tagId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/delete?access_token={$accessToken}&tagid={$tagId}";

        return $this->result('GET', $url);
    }

    /**
     * 获取标签成员
     * @param $accessToken
     * @param $tagId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagGet($accessToken, $tagId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/get?access_token={$accessToken}&tagid={$tagId}";

        return $this->result('GET', $url);
    }

    /**
     * 增加标签成员
     * https://work.weixin.qq.com/api/doc#10923
     * {
     *     "tagid": 12,
     *     "userlist":[ "user1","user2"],
     *     "partylist": [4]
     * }
     * @param $data
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagAddTagUsers($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/addtagusers?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 删除标签成员
     * https://work.weixin.qq.com/api/doc#10925
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagDelTagUsers($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/deltagusers?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 获取标签列表
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function tagList($accessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/tag/list?access_token={$accessToken}";

        return $this->result('GET', $url);
    }

    /**
     * 增量更新成员
     * https://work.weixin.qq.com/api/doc#10138/增量更新成员
     * @param $mediaId
     * @param $callbackUrl
     * @param $token
     * @param $encodingAesKey
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function batchSyncUser($accessToken, $mediaId, $callbackUrl, $token, $encodingAesKey)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/batch/syncuser?access_token={$accessToken}";

        $params = [
            'json' => [
                'media_id' => $mediaId,
                'callback' => [
                    'url' => $callbackUrl,
                    'token' => $token,
                    'encodingaeskey' => $encodingAesKey
                ]
            ]
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 全量覆盖成员
     * https://work.weixin.qq.com/api/doc#10138/全量覆盖成员
     * @param $accessToken
     * @param $mediaId
     * @param $callbackUrl
     * @param $token
     * @param $encodingAesKey
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function batchReplaceUser($accessToken, $mediaId, $callbackUrl, $token, $encodingAesKey)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/batch/replaceuser?access_token={$accessToken}";

        $params = [
            'json' => [
                'media_id' => $mediaId,
                'callback' => [
                    'url' => $callbackUrl,
                    'token' => $token,
                    'encodingaeskey' => $encodingAesKey
                ]
            ]
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 全量覆盖部门
     * https://work.weixin.qq.com/api/doc#10138/全量覆盖部门
     * @param $accessToken
     * @param $mediaId
     * @param $callbackUrl
     * @param $token
     * @param $encodingAesKey
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function batchReplaceParty($accessToken, $mediaId, $callbackUrl, $token, $encodingAesKey)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/batch/replaceparty?access_token={$accessToken}";

        $params = [
            'json' => [
                'media_id' => $mediaId,
                'callback' => [
                    'url' => $callbackUrl,
                    'token' => $token,
                    'encodingaeskey' => $encodingAesKey
                ]
            ]
        ];

        return $this->result('POST', $url, $params);
    }

    //
    // 应用管理
    //

    /**
     * 获取应用
     * https://work.weixin.qq.com/api/doc#10087
     * @param $accessToken
     * @param $agentId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function agentGet($accessToken, $agentId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/agent/get?access_token={$accessToken}&agentid={$agentId}";

        return $this->result('GET', $url);
    }

    /**
     * 设置应用
     * https://work.weixin.qq.com/api/doc#10088
     * {
     *     "agentid": 5,
     *     "report_location_flag": 0,
     *     "logo_mediaid": "xxxxx",
     *     "name": "NAME",
     *     "description": "DESC",
     *     "redirect_domain": "xxxxxx",
     *     "isreportenter":0,
     *     "home_url":"http://www.qq.com"
     * }
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function agentSet($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/agent/set?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 获取应用列表
     * @param $accessToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function agentList($accessToken)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/agent/list?access_token={$accessToken}";

        return $this->result('GET', $url);
    }

    /**
     * 创建菜单
     * https://work.weixin.qq.com/api/doc#10786
     * @param $accessToken
     * @param $agentId
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function menuCreate($accessToken, $agentId, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/menu/create?access_token={$accessToken}&agentid={$agentId}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 获取菜单
     * @param $accessToken
     * @param $agentId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function menuGet($accessToken, $agentId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/menu/get?access_token={$accessToken}&agentid={$agentId}";

        return $this->result('GET', $url);
    }

    /**
     * 删除菜单
     * @param $accessToken
     * @param $agentId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function menuDelete($accessToken, $agentId)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/menu/delete?access_token={$accessToken}&agentid={$agentId}";

        return $this->result('GET', $url);
    }

    //
    // 消息推送
    //

    /**
     * 发送消息
     * https://work.weixin.qq.com/api/doc#10167
     * @param $accessToken
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function messageSend($accessToken, $data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token={$accessToken}";

        $params = [
            'json' => $data
        ];

        return $this->result('POST', $url, $params);
    }

    /**
     * 发送文本消息
     * @param $accessToken
     * @param $agentId
     * @param $content
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function textMessageSend($accessToken, $agentId, $content, $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'text',
            'agentid' => $agentId,
            'content' => $content,
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }

    /**
     * 发送图片消息
     * @param $accessToken
     * @param $agentId
     * @param $mediaId
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function imageMessageSend($accessToken, $agentId, $mediaId, $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'image',
            'agentid' => $agentId,
            'image' => [
                'media_id' => $mediaId
            ],
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }

    /**
     * 发送语音消息
     * @param $accessToken
     * @param $agentId
     * @param $mediaId
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function voiceMessageSend($accessToken, $agentId, $mediaId, $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'voice',
            'agentid' => $agentId,
            'voice' => [
                'media_id' => $mediaId
            ],
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }

    /**
     * 发送视频消息
     * @param $accessToken
     * @param $agentId
     * @param $mediaId
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function videoMessageSend($accessToken, $agentId, $mediaId, $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'video',
            'agentid' => $agentId,
            'video' => [
                'media_id' => $mediaId
            ],
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }

    /**
     * 发送文件消息
     * @param $accessToken
     * @param $agentId
     * @param $mediaId
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function fileMessageSend($accessToken, $agentId, $mediaId, $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'file',
            'agentid' => $agentId,
            'file' => [
                'media_id' => $mediaId
            ],
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }

    /**
     * 发送文本卡片消息
     * @param $accessToken
     * @param $agentId
     * @param $title
     * @param $description
     * @param $url
     * @param string $btnTxt
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function textCardMessageSend($accessToken, $agentId, $title, $description, $url, $btnTxt = '详情', $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'textcard',
            'agentid' => $agentId,
            'textcard' => [
                'title' => $title,
                'description' => $description,
                'url' => $url,
                'btntxt' => $btnTxt
            ],
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }

    /**
     * 发送图文消息
     * https://work.weixin.qq.com/api/doc#10167/图文消息（mpnews）
     * @param $accessToken
     * @param $agentId
     * @param array $articles
     * @param null $toUser
     * @param null $toParty
     * @param null $toTag
     * @param int $safe
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function mpNewsMessageSend($accessToken, $agentId, $articles = [], $toUser = null, $toParty = null, $toTag = null, $safe = 0)
    {
        $data = [
            'msgtype' => 'mpnews',
            'agentid' => $agentId,
            'mpnews' => $articles,
            'safe' => $safe,
            'touser' => is_null($toUser) ? '@all' : $toUser,
            'toparty' => is_null($toParty) ? '@all' : $toParty,
            'totag' => is_null($toTag) ? '@all' : $toTag
        ];

        return $this->messageSend($data, $accessToken);
    }
}
