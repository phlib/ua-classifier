<?php
declare(strict_types=1);

namespace UAClassifier\Rules;

/**
 * UAClassifier Rules Desktop
 *
 * @package UAClassifier
 */
class Desktop
{
    /**
     * @var array Array of regular expressions which match desktop devices
     */
    static $device = [
        /**
         * Consoles
         */
        'nintendo (wii|wii u)',
        'playstation (3|4)',
        'xbox',
        'xbox xbox one',

        /**
         * Laptops
         */
        'asus dn.*'
    ];

    /**
     * @var array Array of regular expressions which match desktop operating systems
     */
    static $os = [
        /**
         * Microsoft Desktop Operating Systems
         *
         * @link https://en.wikipedia.org/wiki/List_of_Microsoft_operating_systems
         */
        'windows (2000|3\.1|95|98|me|nt|xp|vista|7|8|8.1|10)',

        /**
         * Apple Desktop Operating Systems
         *
         * @link https://en.wikipedia.org/wiki/MacOS
         */
        'mac os x'
    ];

    /**
     * @var array Array of regular expressions which match desktop browsers
     */
    static $browser = [];
}
