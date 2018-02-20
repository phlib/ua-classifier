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
     * @var array Array of regex rules with matching classifications
     */
    private $rules = [];

    /**
     * Get classification result for given Parser result
     *
     * @param Client $parseResult
     * @return Result
     */
    public function classify(Client $parseResult) : Result
    {
        $matchClass = '';

        foreach ($this->getRules() as $rule) {
            if (isset($rule['device']) &&
                preg_match('/^' . $rule['device'] . '$/i', $parseResult->device->family) === 0
            ) {
                continue;
            }
            if (isset($rule['os']) &&
                preg_match('/^' . $rule['os'] . '$/i', $parseResult->os->family) === 0
            ) {
                continue;
            }
            if (isset($rule['ua']) &&
                preg_match('/^' . $rule['ua'] . '$/i', $parseResult->ua->family) === 0
            ) {
                continue;
            }

            $matchClass = $rule['class'];
            break;
        }

        return new Result($matchClass);
    }

    /**
     * Gets array of regex rules with matching classifications
     *
     * @return array
     */
    private function getRules() : array
    {
        if ($this->rules) {
            return $this->rules;
        }

        // Spider Rules
        $this->rules[] = [
            'device' => 'spider',
            'class'  => 'spider'
        ];

        // Device Rules
        foreach (Rules\Phone::$device as $phoneDevice) {
            $this->rules[] = [
                'device' => $phoneDevice,
                'class'  => 'phone'
            ];
        }
        foreach (Rules\Tablet::$device as $tabletDevice) {
            $this->rules[] = [
                'device' => $tabletDevice,
                'class'  => 'tablet'
            ];
        }
        foreach (Rules\Desktop::$device as $desktopDevice) {
            $this->rules[] = [
                'device' => $desktopDevice,
                'class'  => 'desktop'
            ];
        }

        // Operating System Rules
        foreach (Rules\Phone::$os as $phoneOS) {
            $this->rules[] = [
                'os'    => $phoneOS,
                'class' => 'phone'
            ];
        }
        foreach (Rules\Tablet::$os as $tabletOS) {
            $this->rules[] = [
                'os'    => $tabletOS,
                'class' => 'tablet'
            ];
        }
        foreach (Rules\Desktop::$os as $desktopOS) {
            $this->rules[] = [
                'os'    => $desktopOS,
                'class' => 'desktop'
            ];
        }

        // Browser Rules
        foreach (Rules\Phone::$browser as $phoneBrowser) {
            $this->rules[] = [
                'ua'    => $phoneBrowser,
                'class' => 'phone'
            ];
        }
        foreach (Rules\Tablet::$browser as $tabletBrowser) {
            $this->rules[] = [
                'ua'    => $tabletBrowser,
                'class' => 'tablet'
            ];
        }
        foreach (Rules\Desktop::$browser as $desktopBrowser) {
            $this->rules[] = [
                'ua'    => $desktopBrowser,
                'class' => 'desktop'
            ];
        }

        return $this->rules;
    }
}
