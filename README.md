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
* PHP 5.3 or greater

## Installation

To easily install Dubizzle, simply:

```bash
composer require osoobe/dubizzle
```

If you don't have compose install, see [how to install and use composer](https://getcomposer.org/doc/00-intro.md)


## Quickstart

```php

use Dubizzle;

$params = ["country"=>'uae', "city"=>"dubai", "section"=>"motor"];
$uea = new Dubizzle\Search($params);
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


# Find average price of year 2007 and above Nissan Altimas in Dubai
import dubizzle

results = dubizzle.search(keyword='altima', country='uae', city='dubai', section='motors',
                          category='cars', make='nissan', min_year=2007, num_results='all')

total_price, result_count = 0, len(results)

for result in results:
    total_price += result['price']

print float(total_price) / result_count # Prints 39239.94
```

```python
# Use the above results to find distribution of post-2007 Altima colors
from collections import Counter

colors = [result['features']['color'] for result in results]
distribution = Counter(colors)

print distribution['white'] # Prints 52
```

```python
# Retrieve a single listing from Dubizzle UAE
import dubizzle

listing = dubizzle.listing('http://dubai.dubizzle.com/motors/used-cars/nissan/tiida/2013/9/25/easy-installment-new-and-used-cars-0563276-2/', country='uae')

print listing
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
