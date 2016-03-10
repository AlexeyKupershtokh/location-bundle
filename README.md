# location-bundle

[![Build Status](https://travis-ci.org/AlexeyKupershtokh/location-bundle.svg?branch=master)](https://travis-ci.org/AlexeyKupershtokh/location-bundle)
[![Coverage Status](https://coveralls.io/repos/github/AlexeyKupershtokh/location-bundle/badge.svg?branch=master)](https://coveralls.io/github/AlexeyKupershtokh/location-bundle?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AlexeyKupershtokh/location-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AlexeyKupershtokh/location-bundle/?branch=master)

# Installation:

 1. Run `composer require alexey-kupershtokh/location-bundle dev-master`. 
 2. Then enable the bundle in the AppKernel.php:
```php
   public function registerBundles()
   {
       $bundles = [
           ...
           new \AlexeyKuperhstokh\LocationBundle\LocationBundle(),
       ];   
      
```

# Usage:

LocationBundle provides a service `location_bundle.client` so it's usage is as simple as:
```php
$versions = $container->get('location_bundle.client')->getLocations();
```
