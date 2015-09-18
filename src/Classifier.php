<?php

namespace UAClassifier;

/**
 * UAClassifier Classifier
 *
 * @package UAClassifier
 */
class Classifier
{

    /**
     * Get classification result for given Parser result
     *
     * @param \UAParser\Result\Client $parseResult
     */
    public function classify(\UAParser\Result\Client $parseResult)
    {
        $mobileOSs      = array('windows phone 6.5','windows ce','symbian os');
        $mobileBrowsers = array('firefox mobile','opera mobile','opera mini','mobile safari','webos','ie mobile','playstation portable',
                                'nokia','blackberry','palm','silk','android','maemo','obigo','netfront','avantgo','teleca','semc-browser',
                                'bolt','iris','up.browser','symphony','minimo','bunjaloo','jasmine','dolfin','polaris','brew','chrome mobile',
                                'uc browser','tizen browser');
        $tablets        = array('kindle','ipad','playbook','touchpad','dell streak','galaxy tab','xoom');
        $mobileDevices  = array('iphone','ipod','ipad','htc','kindle','lumia','amoi','asus','bird','dell','docomo','huawei','i-mate','kyocera',
                                'lenovo','lg','kin','motorola','philips','samsung','softbank','palm','hp ','generic feature phone','generic smartphone');

        $classifyResult = new Result();

        switch (true) {
            case (in_array(strtolower($parseResult->device->family), $tablets)):
                $classifyResult->matchType      = 'device';
                $classifyResult->isTablet       = true;
                $classifyResult->isMobileDevice = true;
                $classifyResult->isComputer     = false;
                break;

            case (in_array(strtolower($parseResult->device->family), $mobileDevices)):
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

            case (in_array(strtolower($parseResult->os->family), $mobileOSs)):
                $classifyResult->matchType      = 'os';
                $classifyResult->isMobileDevice = true;
                $classifyResult->isMobile       = true;
                $classifyResult->isComputer     = false;
                break;

            case (in_array(strtolower($parseResult->ua->family), $mobileBrowsers)):
                $classifyResult->matchType      = 'ua';
                $classifyResult->isMobileDevice = true;
                $classifyResult->isMobile       = true;
                $classifyResult->isComputer     = false;
                break;
        }

        return $classifyResult;
    }
}
