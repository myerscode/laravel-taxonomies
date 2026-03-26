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
        $model = static::add($term);

        if ($model instanceof self) {
            Taxonomy::findOrAdd($taxonomy)->attachTerm($model);

            return $model;
        }

        return new self(); // @phpstan-ignore return.type
    }

    /** @return BelongsTo<Taxonomy, $this> */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
}
