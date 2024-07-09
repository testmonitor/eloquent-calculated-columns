# Eloquent Calculated Columns

[![Latest Stable Version](https://poser.pugx.org/testmonitor/eloquent-calculated-columns/v/stable)](https://packagist.org/packages/testmonitor/eloquent-calculated-columns)
[![codecov](https://codecov.io/gh/testmonitor/eloquent-calculated-columns/graph/badge.svg?token=2J7M4FNW8D)](https://codecov.io/gh/testmonitor/eloquent-calculated-columns)
[![StyleCI](https://styleci.io/repos/826287216/shield)](https://styleci.io/repos/826287216)
[![License](https://poser.pugx.org/testmonitor/eloquent-calculated-columns/license)](https://packagist.org/packages/eloquent-calculated-columns)

A Laravel package for adding calculated columns when retrieving data from an Eloquent model. This package allows you to define these columns using SQL, resulting in more performant queries compared to accessors.

It is heavily inspired by Spatie's [Query Builder](https://github.com/spatie/laravel-query-builder/) and can be used in conjunction with this package.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

This package can be installed through Composer:

```sh
$ composer require testmonitor/eloquent-calculated-columns
```

Next, publish the configuration file:

```sh
$ php artisan vendor:publish --tag=eloquent-calculated-columns
```

The configuration file allows you the change the HTTP parameter name, when desired.

## Usage

To use calculated columns in your Eloquent model, you need to:

1. Use the trait ```TestMonitor\CalculatedColumns\HasCalculatedColumns``` in your model.
2. Define the calculated columns when querying your model.

Add the CalculatedColumns trait to the models where you want to add calculated columns:

```php
use Illuminate\Database\Eloquent\Model;
use TestMonitor\CalculatedColumns\HasCalculatedColumns;

class User extends Model
{
    use HasCalculatedColumns;

    // Define your calculated columns
    public function calculatedColumns()
    {
        return [
            'total_price' => function (QueryBuilder $query) {
                $query->select(DB::raw("SUM(order_items.price) AS total_price"))
                    ->from('order_items')
                    ->whereColumn('order_items.order_id', 'orders.id');
            },
        ];
    }
}
```

Next, use the calculated columns in your queries:

```php
use App\Models\Order;

$orders = Order::query()
    ->withCalculatedColumns()
    ->get();
}
```

In this example, the total_price column calculates the total price of an order
by adding all the order item prices.

The requested columns are automatically derived from the HTTP request. You can
modify the HTTP query parameter in the configuration file. By default,
the name `calculate` is used.

## Examples

## Tests

The package contains integration tests. You can run them using PHPUnit.

```
$ vendor/bin/phpunit
```

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

- [Thijs Kok](https://www.testmonitor.com/)
- [Stephan Grootveld](https://www.testmonitor.com/)
- [Frank Keulen](https://www.testmonitor.com/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
