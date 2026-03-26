<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Myerscode\Utilities\Strings\Utility as Strings;

#[Fillable([
    'slug',
    'name',
])]
class Taxonomy extends Model
{
    use HasTaxonomy;

    /**
     * Terms associated with the taxonomy
     */
    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }

    /**
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
     * @return $this
     */
    public function attachTerm(Term $term): static
    {
        return $this->attachTerms([$term]);
    }

    /**
     * Attach known terms to the taxonomy
     *
     * @param Collection | Term[] $attach | Term $attach
     * @return $this
     */
    public function attachTerms($attach): static
    {
        $terms = collect($attach)->filter(fn($term): bool => $term instanceof Term);

        $this->terms()->saveMany($terms);

        return $this;
    }

    /**
     * Find or create a term and attach it to the taxonomy
     */
    public function addTerm(string $term): static
    {
        return $this->addTerms([$term]);
    }

    /**
     * Find or create terms and attach it to the taxonomy
     *
     * @return $this
     */
    public function addTerms(array $terms): static
    {
        $terms = $this->collectTerms($terms);

        $this->attachTerms($terms);

        return $this;
    }
}
