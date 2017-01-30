# phlib/ua-classifier

[![Build Status](https://img.shields.io/travis/phlib/ua-classifier/master.svg)](https://travis-ci.org/phlib/ua-classifier)
[![Codecov](https://img.shields.io/codecov/c/github/phlib/ua-classifier.svg)](https://codecov.io/gh/phlib/ua-classifier)
[![Latest Stable Version](https://img.shields.io/packagist/v/phlib/ua-classifier.svg)](https://packagist.org/packages/phlib/ua-classifier)
[![Total Downloads](https://img.shields.io/packagist/dt/phlib/ua-classifier.svg)](https://packagist.org/packages/phlib/ua-classifier)

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
print $classification->isMobile;
print $classification->isTablet;
print $classification->isSpider;
print $classification->isComputer;
```
