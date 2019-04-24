# Coffreo/jms-translation-js-extractor-bundle

By [Coffreo](https://coffreo.biz)

![PHP compatible version](https://img.shields.io/packagist/php-v/Coffreo/jms-translation-js-extractor-bundle.svg)
[![Build Status](https://travis-ci.org/Coffreo/jms-translation-js-extractor-bundle.svg?branch=master)](https://travis-ci.org/Coffreo/jms-translation-js-extractor-bundle)
[![Coverage](https://img.shields.io/scrutinizer/coverage/g/coffreo/jms-translation-js-extractor-bundle.svg)](https://scrutinizer-ci.com/g/coffreo/jms-translation-js-extractor-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Coffreo/jms-translation-js-extractor-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Coffreo/jms-translation-js-extractor-bundle/?branch=master)

Extract translations from Javascript source files.

* **Recommended** [`willdurand/js-translation-bundle`](https://github.com/willdurand/BazingaJsTranslationBundle)

> Same bundle exists for [PHP Translation](https://php-translation.readthedocs.io/en/latest/): see [Coffreo/php-translation-js-extractor-bundle](https://github.com/Coffreo/php-translation-js-extractor-bundle)

## Installation

### Application with Symfony flex

```
composer require coffreo/jms-translation-js-extractor-bundle
```

### Application without Symfony flex

* Install bundle:

  ```
  composer require coffreo/jms-translation-js-extractor-bundle
  ```

* Enable bundle:
    
  * symfony 3.*
    ````php
    // config/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Coffreo\JMSTranslationJsExtractorBundle\CoffreoJMSTranslationJsExtractorBundle(),
            // ...
        );
    }
    ````
    
  * symfony 4.* (if not already added by `symfony/flex`)
    ````php
    // config/bundles.php
    
    return [
        // ...
        Coffreo\JMSTranslationJsExtractorBundle\CoffreoJMSTranslationJsExtractorBundle::class => ['all' => true],
    ];
    ````

  
## Usage

This bundle allow extraction of translated strings in javascript files using [Coffreo/js-translation-extractor](https://github.com/Coffreo/js-translation-extractor).

No specific command line to use, just use originals `jms/translation-bundle` commands:

````shell
$ bin/console translation:extract --config=app en
````

Translations found are automatically added to current translations files as PHP, twig ones.


### Configuration

This bundle doesn't need configuration.   
However, to extract strings from JS files, you must indicate where are stored your JS files in [`jms/translation-bundle` configuration](http://jmsyst.com/bundles/JMSTranslationBundle/master/cookbook/extraction_configs).

```
# paths below are symfony 3.X paths, make sure to change them for symfony 4.X
# app/config.yml
jms_translation:
  configs:
      app:
          dirs: [
            "%kernel.root_dir%", 
            "%kernel.root_dir%/../src", 
            "%kernel.root_dir%/../path/to/assets"   # add assets path here
          ]
          output_dir: "%kernel.root_dir%/Resources/translations"
          ignored_domains: [routes]
          excluded_names: ["*TestCase.php", "*Test.php"]
          excluded_dirs: [cache, data, logs]

```


## Developer commands

* Run tests:

```shell
composer test
```

* Apply coding standard

```shell
composer cs
```

**Coding standard must be applied before commit, TravisCI will fail otherwise**

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details
