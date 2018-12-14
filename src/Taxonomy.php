<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Myerscode\Utilities\Strings\Utility as Strings;

class Taxonomy extends Model
{
    use HasTaxonomy;

    protected $fillable = [
        'slug',
        'name',
    ];

    /**
     * Terms associated with the taxonomy
     *
     * @return HasMany
     */
    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }

    /**
     * @param string $taxonomy
     * @return Taxonomy
     */
    public static function findOrAdd(string $taxonomy)
    {
        $slug = (new Strings($taxonomy))->toSlug()->value();

        return self::firstOrCreate(['slug' => $slug], ['name' => $taxonomy]);
    }

    public function attachTerm(Term $term)
    {
        $this->terms()->save($term);

        return $this;
    }

    /**
     * @param Term[] $terms
     * @return $this
     */
    public function attachTerms($terms)
    {
        $terms = collect($terms)->filter(function ($term) {
            return ($term instanceof Term);
        });

        $this->terms()->saveMany($terms);

        return $this;
    }

    public function addTerm(string $term)
    {
        return $this->addTerms([$term]);
    }

    public function addTerms(array $terms)
    {
        $terms = $this->collectTerms($terms);

        $this->terms()->saveMany($terms);

        return $this;
    }
}