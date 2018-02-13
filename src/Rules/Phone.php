<?php
declare(strict_types=1);

namespace UAClassifier\Rules;

/**
 * UAClassifier Rules Phone
 *
 * @package UAClassifier
 */
class Phone
{
    /**
     * @var array Array of regular expressions which match phone devices
     */
    static $device = [
        'generic feature phone',
        'generic smartphone',

        /**
         * Apple Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_iOS_devices
         */
        'iphone.*',
        'ipod.*',

        /**
         * Samsung Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Samsung_Galaxy
         */
        'samsung (sm-n|sm-g9|sm-g850x|sm-a|sm-c[579]|sgh-n075t|sm-j|sm-g(150|1600)|sm-g362h|sm-c115|sm-g7|sm-g750f|sm-g3|sm-e|g(313hz|sm-g318)|sm-z|sgh|sph|yp|shw|shv|sec|sch|sc).*',
        'samsung (gt-s|gt-i|gt-n7).*',
        'samsung s',

        /**
         * Nokia Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_Nokia_products
         */
        'lumia \d.*',
        'nokia( |_)(asha|lumia|x).*',
        'nokia (1|2|3|5|6|7|8|9)\d{2,3}.*',
        'nokia (c|e|n|x)\d.*',
        'nokia \d{3,4}.*',

        /**
         * Motorola Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_Motorola_products
         */
        'motorola (a|c|e|em|ex|i|m|mpx|q|zn|t|v|w|xt|krzr|k|pebl|u|razr|rizr|rokr|z|slvr|l|wx|zine)\d{0,4}.*',

        /**
         * LG Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_LG_mobile_phones
         */
        'lg (a|ad|ax|b|bd|bl|bp|bx|c|cb|cd|ce|cf|cg|cm|cp|ct|cu|cx|d|f|g|gb|gc|gd|gm|gr|gs|gt|gu|gw|kb|kc|ke|kf|kg|km|kp|ks|ku|lg|lu|lx|m|me|mg|mx|p|pm|s|su|t|td|tg|tm|te|tu|u|un|us|ux|v|vi|vn|vm|vs|vx|x)\d{2,5}.*',

        /**
         * HTC Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Comparison_of_HTC_devices
         */
        'htc (s|t|p|x)\d{0,4}.*',
        'htc (htc(_| )?)?(mozart.*|schubert.*|gold.*|spark.*|mondrian.*|radar.*|eternity.*|radiant.*|accord.*|rio.*|rome.*|freestyle.*|canary.*|tanager.*|voyager.*|typhoon.*|feeler.*|sonata.*|amadeus.*|hurricane.*|tornado.*|faraday.*|douton.*|startrek.*|breeze.*|oxygen.*|monet.*|excalibur.*|vox.*|libra.*|cavalier.*|iris.*|phoebus.*|wings.*|rose.*|converse.*|willow.*|maple.*|cedar.*|raphael.*|opal.*|jade.*|herman.*|blackstone.*|quartz.*|iolite.*|topaz.*|rhodium.*|tungsten.*|citrine.*|barium.*|leo.*|mega.*|whitestone.*|qilin.*|tachi.*|photon.*|wallaby.*|falcon.*|himalaya.*|blueangel.*|harrier.*|magician.*|alpine.*|gemini.*|apache.*|galaxy.*|wizard.*|prophet.*|charmer.*|hermes.*|trinity.*|artemis.*|herald.*|census.*|love.*|gene.*|panda.*|atlas.*|elf.*|titan.*|vogue.*|wave.*|kaiser.*|nike.*|elfin.*|sedna.*|polaris.*|neon.*|pharos.*|diamond.*|fuwa.*|victor.*|imagio.*|universal.*|athena.*|clio.*|rosella.*|kiwi.*|greatwall.*|dextrous.*|roadster.*|bali.*|beetles.*|sable.*|eden.*|cheetah.*|panther.*|kovsky.*|passion.*|magic.*|tattoo.*|eris.*|desire.*|wildfire.*|aria.*|evo.*|panache.*|gratia.*|inspire.*|thunderbolt.*|incredible.*|merge.*|sensation.*|chacha.*|salsa.*|raider.*|vivid.*|velocity.*|rhyme.*|amaze.*|explorer.*|sensation.*|rezound.*|one.*|j butterfly.*|first.*|one.*|10.*|u ultra.*|u play.*|vision.*|sprint.*|shooter.*|saga.*|marvel.*|hero.*|droid.*|buzz.*|holiday.*|legend.*|7 .*|acquire.*|bahamas.*|bee.*|blizz.*|bravo.*|butterfly.*|click.*|doubleshot.*|dream.*|endeavoru.*|espresso.*|europe.*|glacier.*|golfu.*|jewel.*|joke.*|kingdom.*|lexikon.*|liberty.*|marshall.*|mazaa.*|mecha.*|mytouch.*|touch.*|nexus one.*|omega.*|sapphire.*|schuber.*|status.*|supersonic.*|surround).*',

        /**
         * Huawei Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Category:Huawei_mobile_phones
         */
        'huawei (g|m|p|t|u|v)\d{1,4}.*',
        'huawei ideos ?(x|u).*',

        /**
         * Sony Ericsson Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/List_of_Sony_Ericsson_products
         */
        'ericsson (c|ck|e|f|g|j|k|lt|m|mt|p|r|s|t|tm|u|v|w|wt|x|z)\d{1,4}.*',

        /**
         * Siemens Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Siemens_Mobile
         */
        'siemens (a|af|ap|al|ax|c|cc|cf|cl|cx|e|ic|m|mc|me|mt|p|s|sf|sfg|sg|sk|sl|sp|st|sx|sxg|u).*',

        /**
         * Asus Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Asus#Smartphones
         */
        'asus (a|j|z|v|m|k|ww|p|z)\d{1,3}.*',
        'asus galaxy.*',

        /**
         * Lenovo Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Lenovo_smartphones
         */
        'lenovo (a|e|i|k|p|s|v)\d{2,3}.*',

        /**
         * Nintendo Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/Nintendo_video_game_consoles#Portable_consoles
         */
        'nintendo (ds|dsi|3ds)',

        /**
         * PlayStation Phone Devices
         *
         * @link https://en.wikipedia.org/wiki/PlayStation
         */
        'playstation (portable|vita)'
    ];

    /**
     * @var array Array of regular expressions which match phone operating systems
     */
    static $os = [
        /**
         * Microsoft Phone Operating Systems
         *
         * @link https://en.wikipedia.org/wiki/Windows_Mobile
         */
        'windows (phone|mobile|ce).*',

        'symbian os',

        'blackberry os'
    ];

    /**
     * @var array Array of regular expressions which match phone browsers
     */
    static $browser = [
        'chrome mobile', 'firefox mobile','opera mobile','opera mini','mobile safari','webos','ie mobile',
        'playstation portable','nokia','blackberry','palm','android','maemo','obigo','netfront','avantgo',
        'teleca','semc-browser','iris','up.browser','symphony','minimo','bunjaloo','jasmine','dolfin','polaris',
        'brew','uc browser','tizen browser'
    ];
}
