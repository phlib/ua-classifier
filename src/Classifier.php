<?php

namespace UAClassifier;

class Classifier
{
    private $result;

    /**
     * @var string UA-Parser element used for match: 'device', 'os', 'ua'
     */
    public $matchType;
    public $is_mobileDevice = false;
    public $is_mobile   = false;
    public $is_tablet   = false;
    public $is_spider   = false;
    public $is_computer = true;

    public function __construct($parseResult)
    {
        $this->result = $parseResult;

        $this->classify();
    }

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
            $this->matchType = 'device';
            $this->is_tablet       = true;
            $this->is_mobileDevice = true;
            $this->is_computer     = false;
            return;
        }

        if (in_array(strtolower($this->result->device->family), $mobileDevices)) {
            $this->matchType = 'device';
            $this->is_mobileDevice = true;
            $this->is_mobile       = true;
            $this->is_computer     = false;
            return;
        }

        if (strtolower($this->result->device->family) == 'spider') {
            $this->matchType = 'device';
            $this->is_spider   = true;
            $this->is_computer = false;
            return;
        }

        if (in_array(strtolower($this->result->os->family), $mobileOSs)) {
            $this->matchType = 'os';
            $this->is_mobileDevice = true;
            $this->is_mobile       = true;
            $this->is_computer     = false;
            return;
        }

        if (in_array(strtolower($this->result->ua->family), $mobileBrowsers)) {
            $this->matchType = 'ua';
            $this->is_mobileDevice = true;
            $this->is_mobile       = true;
            $this->is_computer     = false;
            return;
        }

        return;
    }
}