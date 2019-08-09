# NavigationBundle

[![Latest Stable Version](https://poser.pugx.org/damienharper/navigation-bundle/v/stable)](https://packagist.org/packages/damienharper/navigation-bundle)
[![Latest Unstable Version](https://poser.pugx.org/damienharper/navigation-bundle/v/unstable)](https://packagist.org/packages/damienharper/navigation-bundle)
[![Build Status](https://travis-ci.com/DamienHarper/NavigationBundle.svg?branch=master)](https://travis-ci.com/DamienHarper/NavigationBundle)
[![License](https://poser.pugx.org/damienharper/navigation-bundle/license)](https://packagist.org/packages/damienharper/navigation-bundle)
[![Maintainability](https://api.codeclimate.com/v1/badges/67943a505dab66ff0899/maintainability)](https://codeclimate.com/github/DamienHarper/NavigationBundle/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/67943a505dab66ff0899/test_coverage)](https://codeclimate.com/github/DamienHarper/NavigationBundle/test_coverage)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DamienHarper/NavigationBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DamienHarper/NavigationBundle/?branch=master)<br/>
[![Total Downloads](https://poser.pugx.org/damienharper/navigation-bundle/downloads)](https://packagist.org/packages/damienharper/navigation-bundle)
[![Monthly Downloads](https://poser.pugx.org/damienharper/navigation-bundle/d/monthly)](https://packagist.org/packages/damienharper/navigation-bundle)
[![Daily Downloads](https://poser.pugx.org/damienharper/navigation-bundle/d/daily)](https://packagist.org/packages/damienharper/navigation-bundle)

This bundle provides navigation features such as routing, distance matrix etc.
It relies on providers to provide such features. Included providers are:
- Here (see [https://developer.here.com](https://developer.here.com))
- [not yet available] Google Maps (see [https://developers.google.com/maps/documentation/](https://developers.google.com/maps/documentation/))

Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```bash
composer require damienharper/navigation-bundle
```

Applications that don't use Symfony Flex
----------------------------------------    

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
composer require damienharper/navigation-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new DH\NavigationBundle\DHNavigationBundle(),
        );

        // ...
    }

    // ...
}
```


Configuration
=============

### Providers

By default, NavigationBundle won't audit any entity, you have to configure 
which entities have to be audited.

```yaml
// config/packages/dh_navigation.yaml
dh_navigation:
    providers:
        here:
            factory: DH\NavigationBundle\Provider\Here\HereFactory
            options:
                app_id: "HERE_APP_ID"
                app_code: "%HERE_APP_CODE"
                use_cit: false
        google_maps:
            factory: DH\NavigationBundle\Provider\GoogleMaps\GoogleMapsFactory
            options:
                api_key: "GOOGLE_MAPS_TOKEN"
```


Usage
=====

```php
sample code here
```


Contributing
============

NavigationBundle is an open source project. Contributions made by the community are welcome. 
Send us your ideas, code reviews, pull requests and feature requests to help us improve this project.

Do not forget to provide unit tests when contributing to this project. 
To do so, follow instructions in [this dedicated README](tests/README.md)


License
=======

NavigationBundle is free to use and is licensed under the 
[MIT license](http://www.opensource.org/licenses/mit-license.php)
