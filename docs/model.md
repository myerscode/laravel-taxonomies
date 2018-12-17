# Taggable Model

Once your `Model` uses the `HasTaxonomyTrait` you can start tagging it.

## Adding terms to models

Add a basic term (with no taxonomy)
```php
$post->addTerm('Hello')
```

You can associate terms with a given `Taxonomy` to the model, by passing in the `Taxonomy` name, slug, id or a `Taxonomy` model.

```php
// by  name
$post->addTerm('term a', 'Foo Bar');

// by slug
$post->addTerm('term b', 'foo-bar');

// by id
$post->addTerm('term 1', 7);

// by model
$taxonomy = Taxonomy::findOrNew('Foo Bar');
$post->addTerm('term abc', $taxonomy);
```

## Finding models with tags

### withAnyTerms

The `hasAnyTerms` helper method will return models that have one or more of the given terms attached to them.

```php
// find models that have one or more terms
Post::hasAnyTerms(['term a', 'term b']);

// find models that have one or more terms in a given taxonomy
Post::hasAnyTerms(['term 1', 'term 2'], 'foo-bar');
```

It can be used as a Query Builder scope by using  `$builder->hasAnyTerms(['term a', 'term b'])`.

### hasAllTerms

The `hasAllTerms` helper method will return models that have all of the given terms attached to them.

```php
// find models that have all terms
Post::hasAllTerms(['term a', 'tag b']);

// find models that have all terms in a given taxonomy
Post::hasAllTerms(['term 1', 'term 2'], 'foo-bar');
```

It can be used as a Query Builder scope by using  `$builder->hasAllTerms(['term 1', 'term 2'], 'foo-bar')`.