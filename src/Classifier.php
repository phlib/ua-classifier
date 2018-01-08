<?php
declare(strict_types=1);

namespace UAClassifier;

use UAParser\Result\Client;

/**
 * UAClassifier Classifier
 *
 * @package UAClassifier
 */
class Classifier
{
    /**
     * @var array
     */
    public $mobileOSs = ['windows phone 6.5','windows ce','symbian os'];

    /**
     * @var array
     */
    public $mobileBrowsers = [
        'firefox mobile','opera mobile','opera mini','mobile safari','webos','ie mobile','playstation portable',
        'nokia','blackberry','palm','silk','android','maemo','obigo','netfront','avantgo','teleca','semc-browser',
        'bolt','iris','up.browser','symphony','minimo','bunjaloo','jasmine','dolfin','polaris','brew','chrome mobile',
        'uc browser','tizen browser'
    ];

    /**
     * @var array
     */
    public $tablets = ['kindle','ipad','playbook','touchpad','dell streak','galaxy tab','xoom'];

    /**
     * @var array
     */
    public $mobileDevices = [
        'iphone','ipod','ipad','htc','kindle','lumia','amoi','asus','bird','dell','docomo','huawei','i-mate','kyocera',
        'lenovo','lg','kin','motorola','philips','samsung','softbank','palm','hp ','generic feature phone','generic smartphone'
    ];

    /**
     * Get classification result for given Parser result
     *
     * @param Client $parseResult
     * @return Result
     */
    public function classify(Client $parseResult)
    {
        $classifyResult = new Result();

        switch (true) {
            case (in_array(strtolower($parseResult->device->family), $this->tablets)):
                $classifyResult->matchType      = 'device';
                $classifyResult->isTablet       = true;
                $classifyResult->isMobileDevice = true;
                $classifyResult->isComputer     = false;
                break;

            case (in_array(strtolower($parseResult->device->family), $this->mobileDevices)):
                $classifyResult->matchType      = 'device';
                $classifyResult->isMobileDevice = true;
                $classifyResult->isMobile       = true;
                $classifyResult->isComputer     = false;
                break;

            case (strtolower($parseResult->device->family) == 'spider'):
                $classifyResult->matchType  = 'device';
                $classifyResult->isSpider   = true;
                $classifyResult->isComputer = false;
                break;

            case (in_array(strtolower($parseResult->os->family), $this->mobileOSs)):
                $classifyResult->matchType      = 'os';
                $classifyResult->isMobileDevice = true;
                $classifyResult->isMobile       = true;
                $classifyResult->isComputer     = false;
                break;

            case (in_array(strtolower($parseResult->ua->family), $this->mobileBrowsers)):
                $classifyResult->matchType      = 'ua';
                $classifyResult->isMobileDevice = true;
                $classifyResult->isMobile       = true;
                $classifyResult->isComputer     = false;
                break;
        }

        return $classifyResult;
    }
}
