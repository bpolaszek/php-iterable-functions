[![Latest Stable Version](https://poser.pugx.org/bentools/iterable-functions/v/stable)](https://packagist.org/packages/bentools/iterable-functions)
[![GitHub Actions][GA master image]][GA master]
[![Code Coverage][Coverage image]][CodeCov Master]
[![Shepherd Type][Shepherd Image]][Shepherd Link]
[![Total Downloads](https://poser.pugx.org/bentools/iterable-functions/downloads)](https://packagist.org/packages/bentools/iterable-functions)

Iterable functions
==================

Provides additional functions to work with [iterable](https://wiki.php.net/rfc/iterable) variables (even on PHP5.3+).

iterable_to_array()
-------------------

PHP offers an `iterator_to_array()` function to export any iterator into an array.

**But when you want to transform an `iterable` to an array, the `iterable` itself can already be an array.**

When using `iterator_to_array()` with an iterable, that happens to be an array, PHP will throw a `TypeError`.

If you need an iterable-agnostic function, try our `iterable_to_array()`:

```php
use function BenTools\IterableFunctions\iterable_to_array;

var_dump(iterable_to_array(new \ArrayIterator(['foo', 'bar']))); // ['foo', 'bar']
var_dump(iterable_to_array(['foo', 'bar'])); // ['foo', 'bar']
```

iterable_to_traversable()
-------------------------
Useful when you have a `Traversable` type-hint, and you don't know wether or not your argument will be an array or an iterator.

If your variable is already an instance of `Traversable` (i.e. an `Iterator`, an `IteratorAggregate` or a `Generator`), the function simply returns it directly.

If your variable is an array, the function converts it to an `ArrayIterator`.

Usage:

```php
use function BenTools\IterableFunctions\iterable_to_traversable;

var_dump(iterable_to_traversable(['foo', 'bar'])); // \ArrayIterator(['foo', 'bar'])
var_dump(iterable_to_traversable(new \ArrayIterator(['foo', 'bar']))); // \ArrayIterator(['foo', 'bar'])
```

iterable_map()
--------------

Works like an `array_map` with an `array` or a `Traversable`.

```php
use function BenTools\IterableFunctions\iterable_map;

$generator = function () {
    yield 'foo';
    yield 'bar';
};

foreach (iterable_map($generator(), 'strtoupper') as $item) {
    var_dump($item); // FOO, BAR
}
```

iterable_reduce()
--------------

Works like an `reduce` with an `iterable`.

```php
use function BenTools\IterableFunctions\iterable_reduce;

$generator = function () {
    yield 1;
    yield 2;
};

$reduce = static function ($carry, $item) {
    return $carry + $item;
};

var_dump(
    iterable_reduce($generator(), $reduce, 0))
); // 3
```

iterable_filter()
--------------

Works like an `array_filter` with an `array` or a `Traversable`.

```php
use function BenTools\IterableFunctions\iterable_filter;

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
use function BenTools\IterableFunctions\iterable_filter;

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

Iterable fluent interface
=========================

The `iterable` function allows you to wrap an iterable and apply some common operations.

With an array input:

```php
use function BenTools\IterableFunctions\iterable;
$data = [
    'banana',
    'pineapple',
    'rock',
];

$iterable = iterable($data)->filter(fn($eatable) => 'rock' !== $eatable)->map('strtoupper'); // Traversable of ['banana', 'pineapple']
```

With a traversable input:

```php
use function BenTools\IterableFunctions\iterable;
$data = [
    'banana',
    'pineapple',
    'rock',
];

$data = fn() => yield from $data;

$iterable = iterable($data())->filter(fn($eatable) => 'rock' !== $eatable)->map('strtoupper'); // Traversable of ['banana', 'pineapple']
```

Array output:

```php
$iterable->asArray(); // array ['banana', 'pineapple']
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
php vendor/bin/pest
```

[GA master]: https://github.com/bpolaszek/php-iterable-functions/actions?query=workflow%3A%22Continuous+Integration%22+branch%3A2.0.x-dev

[GA master image]: https://github.com/bpolaszek/php-iterable-functions/workflows/Continuous%20Integration/badge.svg

[CodeCov Master]: https://codecov.io/gh/bpolaszek/php-iterable-functions/branch/2.0.x-dev

[Coverage image]: https://codecov.io/gh/bpolaszek/php-iterable-functions/branch/2.0.x-dev/graph/badge.svg

[Shepherd Image]: https://shepherd.dev/github/bpolaszek/php-iterable-functions/coverage.svg

[Shepherd Link]: https://shepherd.dev/github/bpolaszek/php-iterable-functions
