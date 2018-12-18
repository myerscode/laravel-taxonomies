# Laravel Taxonomies
> A package for creating taxonomies and terms to categorise Eloquent models

[![Latest Stable Version](https://poser.pugx.org/myerscode/laravel-taxonomies/v/stable)](https://packagist.org/packages/myerscode/laravel-taxonomies)
[![Total Downloads](https://poser.pugx.org/myerscode/laravel-taxonomies/downloads)](https://packagist.org/packages/myerscode/laravel-taxonomies)
[![License](https://poser.pugx.org/myerscode/laravel-taxonomies/license)](https://packagist.org/packages/myerscode/laravel-taxonomies)



## Why is this package helpful?

This package allow you to create taxonomies and terms and relate them to Laravel models, similar to WordPress

* Create Taxonomy groups to categorise your models
* Add terms to models and to taxonomies
* Use your localisation files to translate taxonomy and term names
* Find all models relating to a given taxonomy or term

Unlike other popular tag packages for Laravel this one supports MariaDB.



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

After the migration has been published, run the migrations to create the `tags`, `taxonomies` and `taggables` tables.



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



## Advance Usage

You can change the default `Taxonomy` or `Term` class by publishing the config, and modifying the classes to your custom models.

Publish the config file with:
```bash
php artisan vendor:publish --provider="Myerscode\Laravel\Taxonomies\ServiceProvider" --tag="config"
```


## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
