<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'slug',
    'name',
    'taxonomy_id',
])]
class Term extends Model
{

    /**
     * Add a term to a given taxonomy
     *
     * @param $term
     * @param $taxonomy
     * @throws Exceptions\UnsupportedModelDataException
     */
    public static function addToTaxonomy($term, string $taxonomy): self
    {
        $model = self::add($term);

        Taxonomy::findOrAdd($taxonomy)->attachTerm($model);

        return new static;
    }

    /**
     * Taxonomy associated with the term
     */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
}
