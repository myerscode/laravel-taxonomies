# Taxonomies

## Creating Taxonomies

You can create a Taxonomies using the `add` helper method, and passing in a name. A slug of the name will be created for you.
```php
Taxonomy::add('Foo');
```

You can create a term with an alternative slug by passing in a data array, the same as using the `create` helper method.
```php
Term::add(['slug' => 'bar', 'name' => 'Foo']);
```

You can find unassociated `Term` records or create a new one and add them to a taxonomy using `addTerms`:
```php
$taxonomy->addTerm('Foo');
$taxonomy->addTerms(['Foo', 'Bar']);
```


