
[![Build Status](https://travis-ci.org/isholao/middleware.svg?branch=master)](https://travis-ci.org/isholao/middleware)

Install
-------

To install with composer:

```sh
composer require isholao/middleware
```

Requires PHP 7.1 or newer.

Usage
-----

Here's a basic usage example:

```php

<?php

require '/path/to/vendor/autoload.php';

class Dummy 

{
    function __construct(string $dummy)
    {
        $this->dummy = $dummy;
    }
}

$manager = new \Isholao\Middleware\Manager(function($dummy){
    return new Dummy($dummy);
},Dummy::class);

$manager->register(function($dummy,$next){
    return $next($dummy);
});

$manager->call('dummy');

```
