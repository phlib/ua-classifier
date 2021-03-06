<?php
declare(strict_types=1);

namespace UAClassifier\Test;

use UAClassifier\Classifier;
use Symfony\Component\Yaml\Yaml;
use PHPUnit\Framework\TestCase;

/**
 * UAClassifier Test ClassifierTest
 *
 * @package UAClassifier
 */
class ClassifierTest extends TestCase
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
        $this->mockResult->device = $this->createMock('UAParser\Result\Device');
        $this->mockResult->os = $this->createMock('UAParser\Result\OperatingSystem');
        $this->mockResult->ua = $this->createMock('UAParser\Result\UserAgent');
    }

    /**
     * @dataProvider providerClassifier
     * @param string $key
     * @param string $device
     * @param string $os
     * @param string $ua
     * @param string $class
     */
    public function testClassifier($key, $device, $os, $ua, $class)
    {
        $this->mockResult->device->family = $device;
        $this->mockResult->os->family = $os;
        $this->mockResult->ua->family = $ua;

        $c12n = $this->classifier->classify($this->mockResult);
        $msgString = " check for '{$key}' ('{$class}' match by '{$c12n->getMatchClass()}')";

        $this->assertEquals(
            $class === 'desktop',
            $c12n->isComputer(),
            'Computer' . $msgString
        );
        $this->assertEquals(
            in_array($class, ['tablet', 'phone']),
            $c12n->isMobileDevice(),
            'Mobile device' . $msgString
        );
        $this->assertEquals(
            $class === 'tablet',
            $c12n->isTablet(),
            'Tablet' . $msgString
        );
        $this->assertEquals(
            $class === 'phone',
            $c12n->isPhone(),
            'Phone' . $msgString
        );
        $this->assertEquals(
            $class === 'spider',
            $c12n->isSpider(),
            'Spider' . $msgString
        );
    }

    public function providerClassifier()
    {
        $testData = Yaml::parse(file_get_contents(__DIR__ . '/test-cases.yaml'));

        $validClass = ['desktop', 'tablet', 'phone', 'spider'];
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
