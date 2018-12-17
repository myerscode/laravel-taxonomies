# Terms

## Creating Terms

You can create a simple term using the `add` helper method on the `Myerscode\Laravel\Taxonomies\Term` model, and passing in a name.
```php
Term::add('Foo');
```

A slug of the name will be created for you, however an alternative slug can be set by passing it in a data array.
```php
Term::add(['slug' => 'bar', 'name' => 'Foo']);
```

## Adding terms to a model

You can add a single tag:
```php
$model->addTag('Foo');
$model->addTag('Bar');
// $model would now have the tags Foo and Bar
```

You can add a multiple tag:
```php
$model->addTags(['Hello', 'World']);
// $model would now have the tags  Hello and World
```

You can sync tag:
```php
$model->syncTags('Foo');
// $model would now only have the tag Foo

$model->syncTags(['Hello', 'World']);
// $model would now only have the tags Hello and World
```

You can remove tags:
```php
$model->detachTags(['Hello', 'World']);
$model->detachTags('Foo');
```

## Associating Terms to a Taxonomy

By default terms do not get added to a taxonomy, but you can associate a new term or move association of an existing term to any taxonomy.
```php
$tag = Tag::create('Foo');
$taxonomy->attachTerms($tag);
$anotherTaxonomy->attachTerms($tag);
```
```php
$tag = Tag::find('Foo');
$anotherTaxonomy->attachTerms($tag);
```

The $tag with name `Foo` is now associated to the `$anotherTaxonomy`.
