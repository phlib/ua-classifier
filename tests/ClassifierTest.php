<?php

namespace UAClassifer\Test;

use UAClassifier\Classifier;
use UAParser\Parser;
use Symfony\Component\Yaml\Yaml;

/**
 * Test Classifier
 *
 * @package Phlib\UAClassifier
 */
class ClassiferTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = Parser::create();
    }

    /**
     * @dataProvider providerClassifier
     */
    public function testClassifier($ua, $isComputer, $isMobileDevice, $isMobile, $isTablet, $isSpider)
    {
        $parseResult = $this->parser->parse($ua);
        $classifer = new Classifier($parseResult);

        $msgString = " check for '{$parseResult->toString()}' (match {$classifer->matchType})";

        $this->assertEquals($isComputer, $classifer->is_computer, 'Computer' . $msgString);
        $this->assertEquals($isMobileDevice, $classifer->is_mobileDevice, 'Mobile device' . $msgString);
        $this->assertEquals($isMobile, $classifer->is_mobile, 'Mobile' . $msgString);
        $this->assertEquals($isTablet, $classifer->is_tablet, 'Tablet' . $msgString);
        $this->assertEquals($isSpider, $classifer->is_spider, 'Spider' . $msgString);
    }

    public function providerClassifier()
    {
        $testCasesData = Yaml::parse(__DIR__ . '/test-cases.yaml');

        $data = [];
        foreach ($testCasesData['testCases'] as $testCase) {
            $data[] = [
                $testCase['userAgent'],
                $testCase['isComputer'],
                $testCase['isMobileDevice'],
                $testCase['isMobile'],
                $testCase['isTablet'],
                $testCase['isSpider']
            ];
        }

        return $data;
    }
}
