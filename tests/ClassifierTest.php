<?php

namespace UAClassifier\Test;

use UAClassifier\Classifier;
use Symfony\Component\Yaml\Yaml;

/**
 * Test Classifier
 *
 * @package Phlib\UAClassifier
 */
class ClassifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Classifier
     */
    protected $classifier;

    /**
     * @var \UAParser\Result\Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResult;

    public function setUp()
    {
        $this->classifier = new Classifier();

        $this->mockResult = $this->getMockBuilder('UAParser\Result\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockResult->device = $this->getMock('UAParser\Result\Device');
        $this->mockResult->os = $this->getMock('UAParser\Result\OperatingSystem');
        $this->mockResult->ua = $this->getMock('UAParser\Result\UserAgent');
    }

    /**
     * @dataProvider providerClassifier
     */
    public function testClassifier($key, $device, $os, $ua, $class)
    {
        $this->mockResult->device->family = $device;
        $this->mockResult->os->family = $os;
        $this->mockResult->ua->family = $ua;

        $c12n = $this->classifier->classify($this->mockResult);

        $msgString = " check for '{$key}' ('{$class}' match by '{$c12n->matchType}')";

        $this->assertEquals(
            $class === 'desktop',
            $c12n->isComputer,
            'Computer' . $msgString
        );
        $this->assertEquals(
            in_array($class, ['tablet', 'mobile']),
            $c12n->isMobileDevice,
            'Mobile device' . $msgString
        );
        $this->assertEquals(
            $class === 'tablet',
            $c12n->isTablet,
            'Tablet' . $msgString
        );
        $this->assertEquals(
            $class === 'mobile',
            $c12n->isMobile,
            'Mobile' . $msgString
        );
        $this->assertEquals(
            $class === 'spider',
            $c12n->isSpider,
            'Spider' . $msgString
        );
    }

    public function providerClassifier()
    {
        $testData = Yaml::parse(file_get_contents(__DIR__ . '/test-cases.yaml'));

        $validClass = ['desktop', 'tablet', 'mobile', 'spider'];
        $data = [];
        foreach ($testData as $key => $testCase) {
            if (empty($testCase['c'])) {
                continue;
            }
            if (!in_array($testCase['c'], $validClass)) {
                throw new \RuntimeException("Invalid class '{$testCase['c']}' for '{$key}'");
            }
            $data[] = [
                $key,
                $testCase['d'],
                $testCase['o'],
                $testCase['u'],
                $testCase['c']
            ];
        }

        return $data;
    }
}
