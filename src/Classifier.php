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
     * @var array Array of regular expressions which match mobile devices
     */
    private $mobileDevices = [
        'generic feature phone',
        'generic smartphone',

        /**
         * Apple Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_iOS_devices
         */
        'iphone.*',
        'ipod.*',

        /**
         * Samsung Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Samsung_Galaxy
         */
        'samsung (sm-n|sm-g9|sm-g850x|sm-a|sm-c[579]|sgh-n075t|sm-j|sm-g(150|1600)|sm-g362h|sm-c115|sm-g7|sm-g750f|sm-g3|sm-e|g(313hz|sm-g318)|sm-z|sgh|sph|yp|shw|shv|sec|sch|sc).*',
        'samsung (gt-s|gt-i|gt-n7).*',
        'samsung s',

        /**
         * Nokia Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_Nokia_products
         */
        'lumia \d.*',
        'nokia( |_)(asha|lumia|x).*',
        'nokia (1|2|3|5|6|7|8|9)\d{2,3}.*',
        'nokia (c|e|n|x)\d.*',
        'nokia \d{3,4}.*',

        /**
         * Motorola Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_Motorola_products
         */
        'motorola (a|c|e|em|ex|i|m|mpx|q|zn|t|v|w|xt|krzr|k|pebl|u|razr|rizr|rokr|z|slvr|l|wx|zine)\d{0,4}.*',

        /**
         * LG Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_LG_mobile_phones
         */
        'lg (a|ad|ax|b|bd|bl|bp|bx|c|cb|cd|ce|cf|cg|cm|cp|ct|cu|cx|d|f|g|gb|gc|gd|gm|gr|gs|gt|gu|gw|kb|kc|ke|kf|kg|km|kp|ks|ku|lg|lu|lx|m|me|mg|mx|p|pm|s|su|t|td|tg|tm|te|tu|u|un|us|ux|v|vi|vn|vm|vs|vx|x)\d{2,5}.*',

        /**
         * HTC Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Comparison_of_HTC_devices
         */
        'htc (s|t|p|x)\d{0,4}.*',
        'htc (htc(_| )?)?(mozart.*|schubert.*|gold.*|spark.*|mondrian.*|radar.*|eternity.*|radiant.*|accord.*|rio.*|rome.*|freestyle.*|canary.*|tanager.*|voyager.*|typhoon.*|feeler.*|sonata.*|amadeus.*|hurricane.*|tornado.*|faraday.*|douton.*|startrek.*|breeze.*|oxygen.*|monet.*|excalibur.*|vox.*|libra.*|cavalier.*|iris.*|phoebus.*|wings.*|rose.*|converse.*|willow.*|maple.*|cedar.*|raphael.*|opal.*|jade.*|herman.*|blackstone.*|quartz.*|iolite.*|topaz.*|rhodium.*|tungsten.*|citrine.*|barium.*|leo.*|mega.*|whitestone.*|qilin.*|tachi.*|photon.*|wallaby.*|falcon.*|himalaya.*|blueangel.*|harrier.*|magician.*|alpine.*|gemini.*|apache.*|galaxy.*|wizard.*|prophet.*|charmer.*|hermes.*|trinity.*|artemis.*|herald.*|census.*|love.*|gene.*|panda.*|atlas.*|elf.*|titan.*|vogue.*|wave.*|kaiser.*|nike.*|elfin.*|sedna.*|polaris.*|neon.*|pharos.*|diamond.*|fuwa.*|victor.*|imagio.*|universal.*|athena.*|clio.*|rosella.*|kiwi.*|greatwall.*|dextrous.*|roadster.*|bali.*|beetles.*|sable.*|eden.*|cheetah.*|panther.*|kovsky.*|passion.*|magic.*|tattoo.*|eris.*|desire.*|wildfire.*|aria.*|evo.*|panache.*|gratia.*|inspire.*|thunderbolt.*|incredible.*|merge.*|sensation.*|chacha.*|salsa.*|raider.*|vivid.*|velocity.*|rhyme.*|amaze.*|explorer.*|sensation.*|rezound.*|one.*|j butterfly.*|first.*|one.*|10.*|u ultra.*|u play.*|vision.*|sprint.*|shooter.*|saga.*|marvel.*|hero.*|droid.*|buzz.*|holiday.*|legend.*|7 .*|acquire.*|bahamas.*|bee.*|blizz.*|bravo.*|butterfly.*|click.*|doubleshot.*|dream.*|endeavoru.*|espresso.*|europe.*|glacier.*|golfu.*|jewel.*|joke.*|kingdom.*|lexikon.*|liberty.*|marshall.*|mazaa.*|mecha.*|mytouch.*|touch.*|nexus one.*|omega.*|sapphire.*|schuber.*|status.*|supersonic.*|surround).*',

        /**
         * Huawei Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Category:Huawei_mobile_phones
         */
        'huawei (g|m|p|t|u|v)\d{1,4}.*',
        'huawei ideos ?(x|u).*',

        /**
         * Sony Ericsson Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_Sony_Ericsson_products
         */
        'ericsson (c|ck|e|f|g|j|k|lt|m|mt|p|r|s|t|tm|u|v|w|wt|x|z)\d{1,4}.*',

        /**
         * Siemens Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Siemens_Mobile
         */
        'siemens (a|af|ap|al|ax|c|cc|cf|cl|cx|e|ic|m|mc|me|mt|p|s|sf|sfg|sg|sk|sl|sp|st|sx|sxg|u).*',

        /**
         * Asus Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Asus#Smartphones
         */
        'asus (a|j|z|v|m|k|ww|p|z)\d{1,3}.*',
        'asus galaxy.*',

        /**
         * Lenovo Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Lenovo_smartphones
         */
        'lenovo (a|e|i|k|p|s|v)\d{2,3}.*',

        /**
         * Nintendo Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/Nintendo_video_game_consoles#Portable_consoles
         */
        'nintendo (ds|dsi|3ds)',

        /**
         * PlayStation Mobile Devices
         *
         * @link https://en.wikipedia.org/wiki/PlayStation
         */
        'playstation (portable|vita)'
    ];

    /**
     * @var array Array of regular expressions which match mobile operating systems
     */
    private $mobileOSs = [
        /**
         * Microsoft Mobile Operating Systems
         *
         * @link https://en.wikipedia.org/wiki/Windows_Mobile
         */
        'windows (phone|mobile|ce).*',

        'symbian os',

        'blackberry os'
    ];

    /**
     * @var array Array of regular expressions which match mobile browsers
     */
    private $mobileBrowsers = [
        'chrome mobile', 'firefox mobile','opera mobile','opera mini','mobile safari','webos','ie mobile','playstation portable',
        'nokia','blackberry','palm','android','maemo','obigo','netfront','avantgo','teleca','semc-browser','iris','up.browser','symphony','minimo','bunjaloo','jasmine','dolfin','polaris','brew',
        'uc browser','tizen browser'
    ];

    /**
     * @var array Array of regular expressions which match tablet devices
     */
    private $tabletDevices = [
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

        'playbook','dell streak.*','versus touchpad.*', 'mediapad.*',
        'versus touchtab.*', 'hp touchpad.*','asus nexus.*', 'touchmate',
    ];

    /**
     * @var array Array of regular expressions which match tablet operating systems
     */
    private $tabletOSs = ['blackberry tablet os'];

    /**
     * @var array Array of regular expressions which match tablet browsers
     */
    private $tabletBrowsers = ['amazon silk', 'silk'];

    /**
     * @var array Array of regular expressions which match desktop devices
     */
    private $desktopDevices = [
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
    private $desktopOSs = [
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
    private $desktopBrowsers = [];

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
        $result = new Result();

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

            $result->isMobileDevice = in_array($rule['class'], ['tablet', 'mobile']);
            $result->isMobile       = $rule['class'] === 'mobile';
            $result->isTablet       = $rule['class'] === 'tablet';
            $result->isSpider       = $rule['class'] === 'spider';
            $result->isComputer     = $rule['class'] === 'desktop';
            $result->matchType      = $rule['class'];

            break;
        }

        return $result;
    }

    private function getRules() : array {
        if ($this->rules) {
            return $this->rules;
        }

        // Spider rules
        $this->rules[] = [
            'device' => 'spider',
            'class'  => 'spider'
        ];

        // Device rules
        foreach ($this->mobileDevices as $mobileDevice) {
            $this->rules[] = [
                'device' => $mobileDevice,
                'class'  => 'mobile'
            ];
        }
        foreach ($this->tabletDevices as $tabletDevice) {
            $this->rules[] = [
                'device' => $tabletDevice,
                'class'  => 'tablet'
            ];
        }
        foreach ($this->desktopDevices as $desktopDevice) {
            $this->rules[] = [
                'device' => $desktopDevice,
                'class'  => 'desktop'
            ];
        }

        // Operating system rules
        foreach ($this->mobileOSs as $mobileOS) {
            $this->rules[] = [
                'os'    => $mobileOS,
                'class' => 'mobile'
            ];
        }
        foreach ($this->tabletOSs as $tabletOS) {
            $this->rules[] = [
                'os'    => $tabletOS,
                'class' => 'tablet'
            ];
        }
        foreach ($this->desktopOSs as $desktopOS) {
            $this->rules[] = [
                'os'    => $desktopOS,
                'class' => 'desktop'
            ];
        }

        // Browser rules
        foreach ($this->mobileBrowsers as $mobileBrowser) {
            $this->rules[] = [
                'ua'    => $mobileBrowser,
                'class' => 'mobile'
            ];
        }
        foreach ($this->tabletBrowsers as $tabletBrowser) {
            $this->rules[] = [
                'ua'    => $tabletBrowser,
                'class' => 'tablet'
            ];
        }
        foreach ($this->desktopBrowsers as $desktopBrowser) {
            $this->rules[] = [
                'ua'    => $desktopBrowser,
                'class' => 'desktop'
            ];
        }

        return $this->rules;
    }
}
