# Laravel API Response
> A fluent helper to provide a consistent shaped API responses in Laravel

[![Latest Stable Version](https://poser.pugx.org/myerscode/laravel-taxonomies/v/stable)](https://packagist.org/packages/myerscode/laravel-taxonomies)
[![Total Downloads](https://poser.pugx.org/myerscode/laravel-taxonomies/downloads)](https://packagist.org/packages/myerscode/laravel-taxonomies)
[![License](https://poser.pugx.org/myerscode/laravel-taxonomies/license)](https://packagist.org/packages/myerscode/laravel-taxonomies)

## Why is this package helpful?

This package ensures your API will always return the same envelope shape, so consuming apps always know what to expect!

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

## Usage

To make an Eloquent model taggable just add the `\Myerscode\Laravel\Taxonomies\HasTaxonomy` trait to it:
```php
class Post extends Model
{
    use \Myerscode\Laravel\Taxonomies\HasTaxonomy;
    
    ...
}
```

### Creating Terms

You can create a simple term using the `add` helper method, and passing in a name. A slug of the name will be created for you.
```php
Term::add('Foo');
```

You can create a term with an alternative slug by passing in a data array, the same as using the `create` helper method.
```php
Term::add(['slug' => 'bar', 'name' => 'Foo']);
```

### Adding terms to a model

You can add a single tag:
```php
$model->addTag('Foo');
```

You can add a multiple tag:
```php
$model->addTags(['Hello', 'World']);
```

You can sync tag:
```php
$model->syncTags('Foo');
$model->syncTags(['Hello', 'World']);
```

You can remove tags:
```php
$model->detachTags(['Hello', 'World']);
$model->detachTags('Foo');
```

### Associating Tags

You can associate a new tag with a taxonomy or move association to another taxonomy
```php
$tag = Tag::create(['name' => 'Foo']);
$taxonomy->attachTag($tag);
$anotherTaxonomy->attachTag($tag);
// $tag is now associated to $anotherTaxonomy
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
