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
     * @var string
     */
    public $matchClass;

    public function __construct(string $matchClass = '')
    {
        $this->matchClass = $matchClass;
    }

    public function isMobileDevice() : bool
    {
        return in_array($this->matchClass, ['tablet', 'phone']);
    }

    public function isPhone() : bool
    {
        return $this->matchClass === 'phone';
    }

    public function isTablet() : bool
    {
        return $this->matchClass === 'tablet';
    }

    public function isSpider() : bool
    {
        return $this->matchClass === 'spider';
    }

    public function isComputer() : bool
    {
        return $this->matchClass === 'computer';
    }
}
