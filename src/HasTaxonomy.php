<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Myerscode\Utilities\Strings\Utility as Strings;

trait HasTaxonomy
{
    /** @var class-string<Taxonomy> */
    protected static string $taxonomy;

    /** @var class-string<Term> */
    protected static string $term;

    public static function bootHasTaxonomy(): void
    {
        self::$taxonomy = config('taxonomies.taxonomy.model');
        self::$term = config('taxonomies.term.model');
    }

    /**
     * @param  array<int, string>|string  $terms
     * @return EloquentCollection<int, static>
     */
    public static function withAllTerms(array|string $terms, ?string $taxonomy = null): EloquentCollection
    {
        return self::hasAllTerms($terms, $taxonomy)->get();
    }

    /**
     * @param  array<int, string>|string  $terms
     * @return EloquentCollection<int, static>
     */
    public static function withAnyTerms(array|string $terms, ?string $taxonomy = null): EloquentCollection
    {
        return self::hasAnyTerms($terms, $taxonomy)->get();
    }

    public function addTerm(string $term, int|string|Taxonomy|null $taxonomy = null): static
    {
        return $this->addTerms([$term], $taxonomy);
    }

    /**
     * @param  array<int, string>  $terms
     */
    public function addTerms(array $terms, int|string|Taxonomy|null $taxonomy = null): static
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        $this->terms()->syncWithoutDetaching($terms->pluck('id')->toArray());

        return $this;
    }

    /**
     * @param  array<int, string>|string  $terms
     */
    public function detachTerms(array|string $terms, int|string|Taxonomy|null $taxonomy = null): static
    {
        $removeTerms = $this->collectTerms($terms, $taxonomy)->pluck('id')->toArray();

        $this->terms()->detach($removeTerms);

        return $this;
    }

    /**
     * @param  Builder<static>  $builder
     * @param  array<int, string>|string  $terms
     * @return Builder<static>
     */
    public function scopeHasAllTerms(Builder $builder, array|string $terms, ?string $taxonomy = null): Builder
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        $terms->each(function (Term $term) use ($builder): void {
            $builder->whereHas('terms', function (Builder $builder) use ($term): void {
                $builder->where('terms.id', $term->id);
            });
        });

        return $builder;
    }

    /**
     * @param  Builder<static>  $builder
     * @param  array<int, string>|string  $terms
     * @return Builder<static>
     */
    public function scopeHasAnyTerms(Builder $builder, array|string $terms, ?string $taxonomy = null): Builder
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        return $builder->whereHas('terms', function (Builder $builder) use ($terms): void {
            $ids = $terms->pluck('id');
            $builder->whereIn('terms.id', $ids);
        });
    }

    /**
     * @param  array<int, string>|string  $terms
     */
    public function syncTerms(array|string $terms, int|string|Taxonomy|null $taxonomy = null): static
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        $this->terms()->sync($terms->pluck('id')->toArray());

        return $this;
    }

    /** @return MorphToMany<Term, $this> */
    public function terms(): MorphToMany
    {
        return $this->morphToMany(self::$term, 'taggable');
    }

    /**
     * @param  array<int, string>|string  $terms
     * @return Collection<int, Term>
     */
    private function collectTerms(array|string $terms, int|string|Taxonomy|null $taxonomy = null): Collection
    {
        $term = self::$term;

        return collect((array) $terms)->map(function (string $name) use ($term, $taxonomy): Term {
            $slug = new Strings($name)->toSlug()->value();
            /** @var array<string, mixed> $findBy */
            $findBy = ['slug' => $slug, 'taxonomy_id' => null];

            if (! empty($taxonomy)) {
                if (is_int($taxonomy)) {
                    $findBy['taxonomy_id'] = $taxonomy;
                } elseif (is_string($taxonomy)) {
                    $findBy['taxonomy_id'] = (self::$taxonomy::findOrAdd($taxonomy))->id;
                } elseif ($taxonomy instanceof Taxonomy) {
                    $findBy['taxonomy_id'] = $taxonomy->id;
                }
            }

            /** @var Term */
            return $term::firstOrCreate($findBy, ['name' => $name]);
        });
    }
}
