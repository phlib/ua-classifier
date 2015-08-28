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
    public function testClassifier($ua, $class)
    {
        $parseResult = $this->parser->parse($ua);
        $classifer = new Classifier($parseResult);

        $msgString = " check for '{$parseResult->toString()}' (match {$classifer->matchType})";

        $this->assertEquals(
            $class === 'desktop',
            $classifer->is_computer,
            'Computer' . $msgString
        );
        $this->assertEquals(
            in_array($class, ['tablet', 'mobile']),
            $classifer->is_mobileDevice,
            'Mobile device' . $msgString
        );
        $this->assertEquals(
            $class === 'tablet',
            $classifer->is_tablet,
            'Tablet' . $msgString
        );
        $this->assertEquals(
            $class === 'mobile',
            $classifer->is_mobile,
            'Mobile' . $msgString
        );
        $this->assertEquals(
            $class === 'spider',
            $classifer->is_spider,
            'Spider' . $msgString
        );
    }

    public function providerClassifier()
    {
        $testCasesData = Yaml::parse(__DIR__ . '/test-cases.yaml');

        $data = [];
        foreach ($testCasesData['testCases'] as $testCase) {
            $data[] = [
                $testCase['userAgent'],
                $testCase['class']
            ];
        }

        return $data;
    }
}
