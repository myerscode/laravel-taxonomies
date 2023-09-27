<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Term extends Model
{

    protected $fillable = [
        'slug',
        'name',
        'taxonomy_id',
    ];

    /**
     * Add a term to a given taxonomy
     *
     * @param $term
     * @param $taxonomy
     * @return self
     * @throws Exceptions\UnsupportedModelDataException
     */
    public static function addToTaxonomy($term, $taxonomy): self
    {
        $terms = self::add($term);

        Taxonomy::findOrAdd($taxonomy)->attachTerm($terms);

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
