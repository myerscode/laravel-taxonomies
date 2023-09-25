# Localisation

You can use the built in feature in Laravel for translating text into other languages.

Create a `taxonomies` and `terms` language file, using the slug as the key.
If a translated value cannot be found, the original value is returned instead.

By default, `->translate()` will use the current set locale.

```php
// resources/lang/fr/taxonomies.php
return [
    'hello-world' => 'Bonjour le monde',
]
```

```php
// resources/lang/fr/terms.php
return [
    'term-one' => 'Terme un',
]
```

```php
$term = Taxonomy::add('Hello World');
$term->translate('fr')->name;
// Returns "Bonjour le monde"

$term = Term::add('Term One');
$term->translate('fr')->name;
// Returns "Terme un"
```