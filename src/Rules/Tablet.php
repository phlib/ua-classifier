<?php
declare(strict_types=1);

namespace UAClassifier\Rules;

/**
 * UAClassifier Rules Tablet
 *
 * @package UAClassifier
 */
class Tablet
{
    /**
     * @var array Array of regular expressions which match tablet devices
     */
    static $device = [
        'generic tablet',

        /**
         * Apple Tablet Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_iOS_devices
         */
        'ipad.*',

        /**
         * Samsung Tablet Devices
         *
         * @link https://en.wikipedia.org/wiki/Samsung_Galaxy
         */
        'samsung (gt-p|sm-t|galaxy tab|sm-p).*',

        /**
         * Amazon Tablet Devices
         *
         * @link https://en.wikipedia.org/wiki/Amazon_Kindle
         */
        'kindle fire.*',
        'kindle',

        /**
         * Motorola Tablet Devices
         */
        'motorola xoom.*',

        /**
         * Huawei Tablet Devices
         */
        'huawei (ideos ?s7|mediapad).*',

        /**
         * Kobo Tablet Devices
         */
        'kobo.*',

        /**
         * HTC Tablet Devices
         *
         * @link https://en.wikipedia.org/wiki/Comparison_of_HTC_devices
         */
        'htc (htc(_| )?)?(flyer.*)',

        /**
         * BlackBerry Tablet Devices
         */
        'blackberry playbook.*',

        /**
         * Asus Tablet Devices
         */
        'asus (pad|tablet|transformer).*',
        'asus (tf|me)\d{3}.*',

        /**
         * Lenovo Tablet Devices
         */
        'lenovo (ideapad|thinkpad|smarttab|ideatab|lifetab|B8000|a(1|2)(-| |_)).*',

        'wetab.*',

        'mediapad.*',

        'hp touchpad.*',

        'asus nexus.*',

        'versus touchtab.*',

        'dell streak.*',

        'versus touchpad.*'
    ];

    /**
     * @var array Array of regular expressions which match tablet operating systems
     */
    static $os = [
        'blackberry tablet os'
    ];

    /**
     * @var array Array of regular expressions which match tablet browsers
     */
    static $browser = [
        'amazon silk',
        'silk'
    ];
}
