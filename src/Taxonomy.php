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

    public static function findOrAdd(string $taxonomy): static
    {
        $slug = (new Strings($taxonomy))->toSlug()->value();

        return self::firstOrCreate(['slug' => $slug], ['name' => $taxonomy]);
    }

    public function addTerm(string $term): static
    {
        return $this->addTerms([$term]);
    }

    /**
     * @param  array<int, string>  $terms
     */
    public function addTerms(array $terms): static
    {
        $terms = $this->collectTerms($terms);

        $this->attachTerms($terms);

        return $this;
    }

    public function attachTerm(Term $term): static
    {
        return $this->attachTerms(collect([$term]));
    }

    /**
     * @param  Collection<int, Term>  $attach
     */
    public function attachTerms(Collection $attach): static
    {
        $this->terms()->saveMany($attach);

        return $this;
    }

    /** @return HasMany<Term, $this> */
    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }
}
