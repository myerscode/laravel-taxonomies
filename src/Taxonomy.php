<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
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

    /**
     * Attach a known term to the taxonomy
     *
     * @param Term $attach
     * @return $this
     */
    public function attachTerm(Term $attach)
    {
        return $this->attachTerms([$attach]);
    }

    /**
     * Attach known terms to the taxonomy
     *
     * @param Collection | Term[] $attach | Term $attach
     * @return $this
     */
    public function attachTerms($attach)
    {
        $terms = collect($attach)->filter(function ($term) {
            return ($term instanceof Term);
        });

        $this->terms()->saveMany($terms);

        return $this;
    }

    /**
     * Find or create a term and attach it to the taxonomy
     *
     * @param string $term
     * @return Taxonomy
     */
    public function addTerm(string $term)
    {
        return $this->addTerms([$term]);
    }

    /**
     * Find or create terms and attach it to the taxonomy
     *
     * @param array $terms
     * @return $this
     */
    public function addTerms(array $terms)
    {
        $terms = $this->collectTerms($terms);

        $this->attachTerms($terms);

        return $this;
    }
}
