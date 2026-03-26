<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Myerscode\Utilities\Strings\Utility as Strings;

trait HasTaxonomy
{

    /**
     * @var Taxonomy
     */
    protected static $taxonomy;

    /**
     * @var Term
     */
    protected static $term;

    public static function bootHasTaxonomy(): void
    {
        self::$taxonomy = config('taxonomies.taxonomy.model');
        self::$term = config('taxonomies.term.model');
    }

    public function terms(): MorphToMany
    {
        return $this->morphToMany(self::$term, 'taggable');
    }

    public function scopeHasAnyTerms(Builder $builder, $terms, string $taxonomy = null): Builder
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        return $builder->whereHas('terms', function (Builder $query) use ($terms): void {
            $ids = $terms->pluck('id');
            $query->whereIn('terms.id', $ids);
        });
    }

    public function scopeHasAllTerms(Builder $builder, $terms, string $taxonomy = null): Builder
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        $terms->each(function ($term) use ($builder): void {
            $builder->whereHas('terms', function (Builder $query) use ($term): void {
                $query->where('terms.id', $term->id);
            });
        });

        return $builder;
    }

    /**
     * Find or create terms which need to be associated to the model
     *
     * @param $terms
     * @param $taxonomy
     */
    private function collectTerms($terms, $taxonomy = null): Collection
    {
        $term = self::$term;

        return collect($terms)->map(function ($name) use ($term, $taxonomy) {
            $slug = (new Strings($name))->toSlug()->value();
            $findBy = ['slug' => $slug, 'taxonomy_id' => null];

            if (!empty($taxonomy)) {
                if (is_int($taxonomy)) {
                    $findBy['taxonomy_id'] = $taxonomy;
                } elseif (is_string($taxonomy)) {
                    $findBy['taxonomy_id'] = (self::$taxonomy::findOrAdd($taxonomy))->id;
                } elseif ($taxonomy instanceof Taxonomy) {
                    $findBy['taxonomy_id'] = $taxonomy->id;
                }
            }

            return $term::firstOrCreate($findBy, ['name' => $name]);
        });
    }

    public function addTerm(string $term, $taxonomy = null)
    {
        return $this->addTerms([$term], $taxonomy);
    }

    public function addTerms(array $terms, $taxonomy = null)
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        $this->terms()->syncWithoutDetaching($terms->pluck('id')->toArray());

        return $this;
    }

    public function syncTerms($terms, $taxonomy = null)
    {
        $terms = $this->collectTerms($terms, $taxonomy);

        $this->terms()->sync($terms->pluck('id')->toArray());

        return $this;
    }

    public function detachTerms($terms, $taxonomy = null)
    {
        $removeTerms = $this->collectTerms($terms, $taxonomy)->pluck('id')->toArray();

        $this->terms()->detach($removeTerms);

        return $this;
    }

    public static function withAnyTerms($terms, $taxonomy = null)
    {
        return self::hasAnyTerms($terms, $taxonomy)->get();
    }

    public static function withAllTerms($terms, $taxonomy = null)
    {
        return self::hasAllTerms($terms, $taxonomy)->get();
    }
}
