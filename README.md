# Laravel API Response
> A fluent helper to provide a consistent shaped API responses in Laravel

[![Latest Stable Version](https://poser.pugx.org/myerscode/laravel-taxonomies/v/stable)](https://packagist.org/packages/myerscode/laravel-taxonomies)
[![Total Downloads](https://poser.pugx.org/myerscode/laravel-taxonomies/downloads)](https://packagist.org/packages/myerscode/laravel-taxonomies)
[![License](https://poser.pugx.org/myerscode/laravel-taxonomies/license)](https://packagist.org/packages/myerscode/laravel-taxonomies)

## Why is this package helpful?

This package allow you to create taxonomies and terms and relate them to Laravel models.

## Install

You can install this package via composer:

``` bash
composer require myerscode/laravel-taxonomies
```

## Setup

If using Laravel 5.5 or above, the service provider will automatically be registered.

If using Laravel 5.4 add `Myerscode\Laravel\Taxonomies\ServiceProvider` to the `providers` array in `config/app.php`

Publish the migration with:
```bash
php artisan vendor:publish --provider="Myerscode\Laravel\Taxonomies\ServiceProvider" --tag="migrations"
```

After the migration has been published you can create the `tags`, `taxonomies` and `taggables` tables by running the migrations.

Publish the config file with:
```bash
php artisan vendor:publish --provider="Myerscode\Laravel\Taxonomies\ServiceProvider" --tag="config"
```

## [Usage](docs/model.md)

To make an Eloquent model taggable just add the `\Myerscode\Laravel\Taxonomies\HasTaxonomy` trait to it:
```php
class Post extends Model
{
    use \Myerscode\Laravel\Taxonomies\HasTaxonomy;
    
    ...
}
```


## [Terms](docs/terms.md)

Are the meta tags you want to add to your model, giving them a definable characteristic.


## [Taxonomies](docs/taxonomies.md)

Are are a way of grouping your terms together, categorising your collection.

For example a `Taxonomy` called `Colours` could contain terms such as `Red`, `Yellow`, `Green` and `Blue`. 


## [Localisation](docs/localisation.md)

You can get translated names from your terms and taxonomies by setting localised language files.


## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
