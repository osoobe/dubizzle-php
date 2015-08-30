## Introduction

[Dubizzle](http://www.dubizzle.com/) is an online classifieds website. This project aims to
become a simple and complete PHP scraping-based API for Dubizzle.

This project was enspired by python's [Dubizzle scraping API](https://github.com/Cyph0n/dubizzle/).

## Notice

This is still a work in progress. There is much left to do until this becomes what it should be. I will however make sure that the `master` branch functions as expected. Any help would be greatly appreciated, obviously.

Another thing to point out is that the main focus for the time being is on Dubizzle UAE and specifically Motors search within it.

## Prerequisites

* [php-html-parse](https://github.com/paquettg/php-html-parser)
* [php-curl-class](https://github.com/php-curl-class/php-curl-class)
* [tidy extension for PHP](http://php.net/manual/en/book.tidy.php)
* PHP 5.3 or greater

## Installation

To easily install Dubizzle, simply:

```bash
composer require osoobe/dubizzle
```

If you don't have compose install, see [how to install and use composer](https://getcomposer.org/doc/00-intro.md)


## Quickstart

```php

use Dubizzle\Search;

$params = ["country"=>'uae', "city"=>"dubai", "section"=>"motor"];
$uea = new Search($params);
$query = $uea->search();
$query->fetch();

$results = $query->get_results();

```

The `$results` variable is a array of associated data for each result item on dubizzle:

```php

var_dump($results);

[
    ['title' => '...',
     'location' => '...',
     'url' => '...',
     'price' => '...',
     'category' => '...'
    ],
    ['title' => '...',
     'location' => '...',
     'url' => '...',
     'price' => '...',
     'category' => '...'
    ],
    ...
]
```

See [Demo 1](http://www.osoobe.com/devlabs/php/demo/dubizzle-php/demo/demo.php) for data output.


## Example

Find average price of year 2007 and above Nissan Altimas in Dubai ([Live Demo](http://www.osoobe.com/devlabs/php/demo/dubizzle-php/demo/demo2.php))

```php
require_once "../vendor/autoload.php";

use Dubizzle\Search;

$params = [
    "keyword"=>'altima',
    "country"=>'uae',
    "city"=>'dubai',
    "section"=>'motors',
    "category"=>'cars',
    "make"=>'nissan',
    "min_year"=>2007,
    "num_results"=>'all'];

$uae = new Search($params);

$query = $uae->search();
$query->fetch();
$results = $query->get_results();

$result_count = count($results);
$total_price = 0;
foreach($results as $result){
    $total_price += $result["price"];
}

echo "Num. Results:   ".$result_count;
echo "<br/>";
echo "<br/>";
echo "Average price:  ".(intval($total_price / $result_count)); # Prints 39239.94
```

## Search Parameters

### General

* `country` - string; defaults to 'uae'
* `keyword` - string
* `city` - string
* `section` - string
* `min_price` and `max_price` - integers
* `category` - string
* `added_days` - choices are 0, 3, 7, 14, 30, 90, or 180
* `num_results` - integer; 'all' fetches all results available
* `detailed` (not implemented) - if set to `True`, fetches full listing data for each result; slower, obviously

### Motors

* `make` - a long list can be found in `regions.py`
* `min_year` and `max_year` - integers
* `min_kms` and `max_kms` - integers
* `seller` - 'dealer' or 'owner'
* `fuel` - 'gasoline', 'hybrid', 'diesel', or 'electric'
* `cylinders` - 3, 4, 5, 6, 8, 10, or 12
* `transmission` - 'automatic' or 'manual'

## Listing Parameters

* `url` - string, **required**
* `country` - string; defaults to 'uae'

## Issues

Please use the [Issues](https://github.com/Osoobe/dubizzle-php/issues) page for that.
