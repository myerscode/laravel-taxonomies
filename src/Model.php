<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Myerscode\Laravel\Taxonomies\Exceptions\UnsupportedModelDataException;
use Myerscode\Utilities\Bags\Utility as Bag;
use Myerscode\Utilities\Strings\Utility as Strings;

class Model extends LaravelModel
{

    #[\Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Model $model): void {
            if (empty($model->slug)) {
                $model->slug = (string)(new Strings($model->name))->toSlug();
            }
        });
    }

    /**
     * Find the record by its slug
     *
     * @return mixed
     */
    public static function findBySlug(string $slug)
    {
        return self::where('slug', '=', $slug)->first();
    }

    /**
     * Find the record by its name
     *
     * @return mixed
     */
    public static function findByName(string $name)
    {
        return self::where('name', '=', $name)->first();
    }

    /**
     * Add a new record
     *
     * @param $data
     * @return self
     * @throws UnsupportedModelDataException
     */
    public static function add($data)
    {
        if (is_array($data)) {
            if ((new Bag($data))->isIndexed()) {
                return collect($data)->each(fn($record) => self::add($record));
            }

            return static::firstOrCreate($data);
        }

        if (is_string($data)) {
            $slug = (string)(new Strings($data))->toSlug();
            return static::firstOrCreate(['slug' => $slug], ['name' => $data]);
        }

        throw new UnsupportedModelDataException();
    }

    /**
     * Convert a model into a translatable instance of itself
     *
     * @param $lang
     */
    public function translate($lang = null): Translated
    {
        $lang ??= app()->getLocale();

        return new Translated($lang, $this);
    }
}
