<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Support\Collection;
use Myerscode\Utilities\Bags\Utility as Bag;
use Myerscode\Utilities\Strings\Utility as Strings;
use Override;

/**
 * @property string $slug
 * @property string $name
 */
class Model extends LaravelModel
{
    /**
     * Add a new record
     *
     * @param  array<string, mixed>|string  $data
     * @return static|Collection<int, mixed>
     */
    public static function add(array|string $data): static|Collection
    {
        if (is_array($data)) {
            if (new Bag($data)->isIndexed()) {
                /** @var Collection<int, mixed> */
                return collect($data)->each(fn (array|string $record): static|Collection => static::add($record));
            }

            return static::firstOrCreate($data); // @phpstan-ignore return.type
        }

        $slug = (string) new Strings($data)->toSlug();

        return static::firstOrCreate(['slug' => $slug], ['name' => $data]); // @phpstan-ignore return.type
    }

    public static function findByName(string $name): ?static
    {
        return static::where('name', '=', $name)->first(); // @phpstan-ignore return.type
    }

    public static function findBySlug(string $slug): ?static
    {
        return static::where('slug', '=', $slug)->first(); // @phpstan-ignore return.type
    }

    #[Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Model $model): void {
            if (empty($model->slug)) {
                $model->slug = (string) new Strings($model->name)->toSlug();
            }
        });
    }

    public function translate(?string $lang = null): Translated
    {
        $lang ??= app()->getLocale();

        return new Translated($lang, $this);
    }
}
