[![Latest Stable Version](https://poser.pugx.org/bentools/iterable-functions/v/stable)](https://packagist.org/packages/bentools/iterable-functions)
[![License](https://poser.pugx.org/bentools/iterable-functions/license)](https://packagist.org/packages/bentools/iterable-functions)
[![Build Status](https://img.shields.io/travis/bpolaszek/php-iterable-functions/master.svg?style=flat-square)](https://travis-ci.org/bpolaszek/php-iterable-functions)
[![Coverage Status](https://coveralls.io/repos/github/bpolaszek/php-iterable-functions/badge.svg?branch=master)](https://coveralls.io/github/bpolaszek/php-iterable-functions?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/bpolaszek/php-iterable-functions.svg?style=flat-square)](https://scrutinizer-ci.com/g/bpolaszek/php-iterable-functions)
[![Total Downloads](https://poser.pugx.org/bentools/iterable-functions/downloads)](https://packagist.org/packages/bentools/iterable-functions)

Iterable functions
==================

Provides additional functions to work with [iterable](https://wiki.php.net/rfc/iterable) variables (even on PHP5.3+).

is_iterable()
-------------
To check wether or not a PHP variable can be looped over in a `foreach` statement, PHP provides an `is_iterable()` function.

**But this function only works on PHP7.1+**.

This library ships a polyfill of this function for previous PHP versions.

Usage:
```php
var_dump(is_iterable(array('foo', 'bar'))); // true
var_dump(is_iterable(new DirectoryIterator(__DIR__))); // true
var_dump(is_iterable('foobar')); // false
```

iterable_to_array()
-------------------

PHP offers an `iterator_to_array()` function to export any iterator into an array.

**But when you want to transform an `iterable` to an array, the `iterable` itself can already be an array.**

When using `iterator_to_array()` with an array, PHP5 triggers a E_RECOVERABLE_ERROR while PHP7 throws a `TypeError`.

If you need an iterable-agnostic function, try our `iterable_to_array()`:

```php
var_dump(iterable_to_array(new ArrayIterator(array('foo', 'bar')))); // ['foo', 'bar']
var_dump(iterable_to_array(array('foo', 'bar'))); // ['foo', 'bar']
```

iterable_to_traversable()
-------------------------
Useful when you have a `Traversable` type-hint, and you don't know wether or not your argument will be an array or an iterator.

If your variable is already an instance of `Traversable` (i.e. an `Iterator`, an `IteratorAggregate` or a `Generator`), the function simply returns it directly.

If your variable is an array, the function converts it to an `ArrayIterator`.

Usage:
```php
var_dump(iterable_to_traversable(array('foo', 'bar'))); // ArrayIterator(array('foo', 'bar'))
var_dump(iterable_to_traversable(new ArrayIterator(array('foo', 'bar')))); // ArrayIterator(array('foo', 'bar'))
```

iterable_to_generator()
-------------------------
Generators allow checking for an empty iterable without actually iterating over it (using the valid() method). This is especially useful for one-way iterables.

**This function only works on PHP5.5+**.

This function always returns a new Generator. 

Usage:
```php
var_dump(iterable_to_generator(array('foo', 'bar'))); // Generator yielding 'foo' => 'bar'
var_dump(iterable_to_generator(new ArrayIterator(array('foo', 'bar')))); // Generator yielding 'foo' => 'bar'
```


iterable_map()
--------------

Works like an `array_map` with an `array` or a `Traversable`.

```php
$generator = function () {
    yield 'foo';
    yield 'bar';
};

foreach (iterable_map($generator(), 'strtoupper') as $item) {
    var_dump($item); // FOO, BAR
}
```

iterable_filter()
--------------

Works like an `array_filter` with an `array` or a `Traversable`.

```php
$generator = function () {
    yield 0;
    yield 1;
};

foreach (iterable_filter($generator()) as $item) {
    var_dump($item); // 1
}
```

Of course you can define your own filter:
```php
$generator = function () {
    yield 'foo';
    yield 'bar';
};

$filter = function ($value) {
    return 'foo' !== $value;
};


foreach (iterable_filter($generator(), $filter) as $item) {
    var_dump($item); // bar
}
```


Iterable factory
================

When you have an `iterable` type-hint somewhere, and don't know in advance wether you'll pass an `array` or a `Traversable`, just call the magic `iterable()` factory:

```php
interface SomeInterface
{
    /**
     * Return an iterable list of items
     * 
     * @return iterable
     */
    public function getItems(): iterable;
}

class MyService implements SomeInterface
{
    /**
     * @inheritdoc
     */
    public function getItems(): iterable
    {
        return iterable($this->someOtherService->findAll()):
    }

}
```

It even accepts a `null` value (then converting it to an `EmptyIterator`).

You may add a `filter` callable and a `map` callable to make your life easier:

```php
$data = [
    'banana',
    'pineapple',
    'potato',
];

$isFruit = function ($eatable) {
    return 'potato' !== $eatable;
};

var_dump(iterator_to_array(iterable($data)->withFilter($isFruit)->withMap('strtoupper'))); // ['banana', 'pineapple']
```

Installation
============

With composer (they'll be autoloaded):
```
composer require bentools/iterable-functions
```

Or manually:
```php
require_once '/path/to/this/library/src/iterable-functions.php';
```

Unit tests
==========
```
./vendor/bin/phpunit
```