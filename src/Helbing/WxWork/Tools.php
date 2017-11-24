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
     * 将数组里的key转驼峰命名
     * @param $data
     * @param string $separator
     * @return array
     */
    public static function convertArrayToCamelCase($data, $separator = '_')
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[self::convertToCamelCase($key, $separator)] = self::convertArrayToCamelCase($value);
            } else {
                $result[self::convertToCamelCase($key, $separator)] = $value;
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
     * 下划线命名转驼峰命名
     * @param $unCamp
     * @param string $separator
     * @return string
     */
    public static function convertToCamelCase($unCamp, $separator = '_')
    {
        $unCampWord = $separator . str_replace($separator, ' ', strtolower($unCamp));
        return ltrim(str_replace(' ', '', ucwords($unCampWord)), $separator);
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
     * 数组转xml
     * @param $data
     * @return string
     */
    public static function array2Xml($data)
    {
        $xml = '<xml>';
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $xml .= "<{$key}>{$value}</{$key}>";
            } else {
                $xml .= "<{$key}><![CDATA[{$value}]]></{$key}>";
            }
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * 响应success给企业微信
     */
    public static function echoSuccess()
    {
        die('success');
    }
}
