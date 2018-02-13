<?php
declare(strict_types=1);

namespace UAClassifier;

/**
 * UAClassifier Result
 *
 * @package UAClassifier
 */
class Result
{
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
    public $isPhone = false;

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
}
