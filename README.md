[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# A Laravel helper to have nice enum related functions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rickgoemans/laravel-enum-helpers.svg?style=flat-square)](https://packagist.org/packages/rickgoemans/laravel-enum-helpers)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rickgoemans/laravel-enum-helpers/run-tests?label=tests)](https://github.com/rickgoemans/laravel-enum-helpers/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/rickgoemans/laravel-enum-helpers/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/rickgoemans/laravel-enum-helpers/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rickgoemans/laravel-enum-helpers.svg?style=flat-square)](https://packagist.org/packages/rickgoemans/laravel-enum-helpers)

This package contains some useful helpers for Laravel and PHP's enums.

## Installation

You can install the package via composer:

```bash
composer require rickgoemans/laravel-enum-helpers
```

## Usage

Example enum:

```php
<?php

namespace Rickgoemans\LaravelEnumHelpers\Enums;

use Rickgoemans\LaravelEnumHelpers\Traits\HasEnumHelpers;

enum CreditCheckRating: string
{
    use HasEnumHelpers;

    case AAA = 'AAA';
    case AA = 'AA';
    case A = 'A';
    case BBB = 'BBB';
    case BB = 'BB';
    case B = 'B';
    case CCC = 'CCC';
    case CC = 'CC';
    case C = 'C';
    case D = 'D';

    /** @inheritdoc */
    public static function badgeColors(): array
    {
        return [
            self::AAA->label() => 'success',
            self::AA->label()  => 'success',
            self::A->label()   => 'success',
            self::BBB->label() => 'info',
            self::BB->label()  => 'info',
            self::B->label()   => 'info',
            self::CCC->label() => 'info',
            self::CC->label()  => 'info',
            self::C->label()   => 'info',
            self::D->label()   => 'danger',
            self::NR->label()  => 'danger',
            null               => 'danger',
        ];
    }

    /** @inheritdoc */
    public static function order(): array
    {
        return [
            self::AAA,
            self::AA,
            self::A,
            self::BBB,
            self::BB,
            self::B,
            self::CCC,
            self::CC,
            self::C,
            self::D,
            self::NR,
        ];
    }
    
    /** @inheritdoc */
    public function label(): string
    {
        return $this->baseLabel('credit_checks.rating.values.');
    }
}
```

Example Laravel Nova resource with badge and select fields usage:

```php
<?php

namespace App\Nova;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Resource;
use Rickgoemans\LaravelEnumHelpers\Enums\CreditCheckRating;

class CreditCheck extends Resource {
    // ...
    
    public function fields(Request $request): array {
        return [
            // ...
            
            Badge::make(__('credit_checks.rating.label'), 'rating', fn(CreditCheckRating $rating) => $rating->label())
                ->map(CreditCheckRating::badgeColors())
                ->sortable(),
                
            Select::make(__('credit_checks.rating.label'), 'rating')
                ->options(CreditCheckRating::optionsForSelect())
                ->onlyOnForms(),
                
            // ...
        ];       
    }
    
    // ...
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rick Goemans](https://github.com/rickgoemans)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
