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

You can add a single term:
```php
$model->addTerm('Foo');
$model->addTerm('Bar');
// $model would now have the tags Foo and Bar
```

You can add a multiple term:
```php
$model->addTerms(['Hello', 'World']);
// $model would now have the tags  Hello and World
```

You can sync term:
```php
$model->syncTerms('Foo');
// $model would now only have the tag Foo

$model->syncTerms(['Hello', 'World']);
// $model would now only have the tags Hello and World
```

You can remove terms:
```php
$model->detachTerms(['Hello', 'World']);
$model->detachTerms('Foo');
```

## Associating Terms to a Taxonomy

By default terms do not get added to a taxonomy, but you can associate a new term or move association of an existing term to any taxonomy.
```php
$tag = Term::create('Foo');
$taxonomy->attachTerms($tag);
$anotherTaxonomy->attachTerms($tag);
```
```php
$tag = Term::find('Foo');
$anotherTaxonomy->attachTerms($tag);
```

The $tag with name `Foo` is now associated to the `$anotherTaxonomy`.
