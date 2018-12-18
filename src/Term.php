<?php

namespace Myerscode\Laravel\Taxonomies;

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
}
