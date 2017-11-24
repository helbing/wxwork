<?php

namespace Helbing\WxWork;

class Tools
{
    /**
     * 将数组里的key转下划线命名
     * @param $data
     * @param string $separator
     * @return array
     */
    public static function convertArrayToUnderline($data, $separator = '_')
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[self::convertToUnderline($key, $separator)] = self::convertArrayToUnderline($value);
            } else {
                $result[self::convertToUnderline($key, $separator)] = $value;
            }
        }

        return $result;
    }

    /**
     * 驼峰命名转下划线命名
     * @param $camelCaps
     * @param string $separator
     * @return string
     */
    public static function convertToUnderline($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps));
    }

    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    public static function xml2Array($xml)
    {
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    /**
     * 响应success给企业微信
     */
    public static function echoSuccess()
    {
        die('success');
    }
}
