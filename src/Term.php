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
     * @param  array<string, mixed>|string  $term
     */
    public static function addToTaxonomy(array|string $term, string $taxonomy): static
    {
        $model = self::add($term);

        if ($model instanceof Term) {
            Taxonomy::findOrAdd($taxonomy)->attachTerm($model);
        }

        return new static();
    }

    /** @return BelongsTo<Taxonomy, $this> */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
}
