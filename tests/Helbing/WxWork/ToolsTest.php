<?php

use PHPUnit\Framework\TestCase;
use Helbing\WxWork\Tools;

class ToolsTest extends TestCase
{
    public function testConvertArrayToUnderline()
    {
        $testData = [
            'TestDemoA' => 'abc',
            'TestDemoB' => [
                'TestDemoC' => 'abc'
            ]
        ];

        $result = Tools::convertArrayToUnderline($testData);

        self::assertEquals($result['test_demo_a'], $testData['TestDemoA']);
        self::assertEquals($result['test_demo_b']['test_demo_c'], $testData['TestDemoB']['TestDemoC']);
    }

    public function testConvertArrayToCamelCase()
    {
        $testData = [
            'test_demo_a' => 'abc',
            'test_demo_b' => [
                'test_demo_c' => 'abc'
            ]
        ];

        $result = Tools::convertArrayToCamelCase($testData);

        self::assertEquals($result['TestDemoA'], $testData['test_demo_a']);
        self::assertEquals($result['TestDemoB']['TestDemoC'], $testData['test_demo_b']['test_demo_c']);
    }

    public function testXml2Array()
    {
        $testData = <<<XML
<xml>
    <TestDemo>123</TestDemo>        
</xml>
XML;
        $result = Tools::xml2Array($testData);

        self::assertEquals($result['TestDemo'], 123);
    }

    public function testArray2Xml()
    {
        $testData = [
            'TestDemo' => 123
        ];

        $result = Tools::array2Xml($testData);

        self::assertEquals($result, '<xml><TestDemo>123</TestDemo></xml>');
    }
}
