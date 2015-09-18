<?php

namespace UAClassifier;

/**
 * @package UAClassifier
 */
class Classifier
{
    /**
     * @var \UAParser\Result\Client
     */
    private $result;

    /**
     * @var string UA-Parser element used for match: 'device', 'os', 'ua'
     */
    public $matchType;

    /**
     * @var bool
     */
    public $isMobileDevice = false;

    /**
     * @var bool
     */
    public $isMobile = false;

    /**
     * @var bool
     */
    public $isTablet = false;

    /**
     * @var bool
     */
    public $isSpider = false;

    /**
     * @var bool
     */
    public $isComputer = true;

    /**
     * Constructor
     *
     * @param \UAParser\Result\Client $parseResult
     */
    public function __construct(\UAParser\Result\Client $parseResult)
    {
        $this->result = $parseResult;

        $this->classify();
    }

    /**
     *
     */
    protected function classify()
    {
        $mobileOSs      = array('windows phone 6.5','windows ce','symbian os');
        $mobileBrowsers = array('firefox mobile','opera mobile','opera mini','mobile safari','webos','ie mobile','playstation portable',
                                'nokia','blackberry','palm','silk','android','maemo','obigo','netfront','avantgo','teleca','semc-browser',
                                'bolt','iris','up.browser','symphony','minimo','bunjaloo','jasmine','dolfin','polaris','brew','chrome mobile',
                                'uc browser','tizen browser');
        $tablets        = array('kindle','ipad','playbook','touchpad','dell streak','galaxy tab','xoom');
        $mobileDevices  = array('iphone','ipod','ipad','htc','kindle','lumia','amoi','asus','bird','dell','docomo','huawei','i-mate','kyocera',
                                'lenovo','lg','kin','motorola','philips','samsung','softbank','palm','hp ','generic feature phone','generic smartphone');


        if (in_array(strtolower($this->result->device->family), $tablets)) {
            $this->matchType      = 'device';
            $this->isTablet       = true;
            $this->isMobileDevice = true;
            $this->isComputer     = false;
            return;
        }

        if (in_array(strtolower($this->result->device->family), $mobileDevices)) {
            $this->matchType      = 'device';
            $this->isMobileDevice = true;
            $this->isMobile       = true;
            $this->isComputer     = false;
            return;
        }

        if (strtolower($this->result->device->family) == 'spider') {
            $this->matchType  = 'device';
            $this->isSpider   = true;
            $this->isComputer = false;
            return;
        }

        if (in_array(strtolower($this->result->os->family), $mobileOSs)) {
            $this->matchType      = 'os';
            $this->isMobileDevice = true;
            $this->isMobile       = true;
            $this->isComputer     = false;
            return;
        }

        if (in_array(strtolower($this->result->ua->family), $mobileBrowsers)) {
            $this->matchType      = 'ua';
            $this->isMobileDevice = true;
            $this->isMobile       = true;
            $this->isComputer     = false;
            return;
        }

        return;
    }
}
