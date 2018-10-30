# phlib/ua-classifier

[![Build Status](https://img.shields.io/travis/phlib/ua-classifier/master.svg)](https://travis-ci.org/phlib/ua-classifier)
[![Codecov](https://img.shields.io/codecov/c/github/phlib/ua-classifier.svg)](https://codecov.io/gh/phlib/ua-classifier)
[![Latest Stable Version](https://img.shields.io/packagist/v/phlib/ua-classifier.svg)](https://packagist.org/packages/phlib/ua-classifier)
[![Total Downloads](https://img.shields.io/packagist/dt/phlib/ua-classifier.svg)](https://packagist.org/packages/phlib/ua-classifier)
![Licence](https://img.shields.io/github/license/phlib/ua-classifier.svg)

## Abandoned

This package is abandoned and no longer supported. We recommend trying [matomo-org/device-detector](https://github.com/matomo-org/device-detector).

-----

A PHP ua-parser device classification project, to provide functionality that was removed in [ua-parser](https://github.com/tobie/ua-parser) 2.0
([4c77b2a](https://github.com/tobie/ua-parser/commit/4c77b2aa8d1f26f21e59c4901ea8c75bcbfb00aa))

This is a port of the original re-implementation by [Jarnar/ua-classifier](https://bitbucket.org/Jarnar/ua-classifier)

## Install

Via Composer

``` bash
$ composer require phlib/ua-classifier
```

## Usage

```php
require_once 'vendor/autoload.php';

$classifier = new \UAClassifier\Classifier();

// $result is UAParser result
$classification = $classifier->classify($result);

print $classification->isMobileDevice;
print $classification->isPhone;
print $classification->isTablet;
print $classification->isSpider;
print $classification->isComputer;
```

## License

This package is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
