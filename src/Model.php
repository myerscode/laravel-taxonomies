<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Myerscode\Laravel\Taxonomies\Exceptions\UnsupportedModelDataException;
use Myerscode\Utilities\Bags\Utility as Bag;

class Model extends LaravelModel
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            if (empty($model->slug)) {
                $model->slug = str_slug($model->name);
            }
        });
    }

    /**
     * Find the record by its slug
     *
     * @param string $slug
     * @return mixed
     */
    public static function findBySlug(string $slug)
    {
        return self::where('slug', '=', $slug)->get()->first();
    }

    /**
     * Find the record by its name
     *
     * @param string $name
     * @return mixed
     */
    public static function findByName(string $name)
    {
        return self::where('name', '=', $name)->get()->first();
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
                return collect($data)->each(function ($record) {
                    return self::add($record);
                });
            }
            return static::firstOrCreate($data);
        } else {
            if (is_string($data)) {
                $slug = str_slug($data);
                return static::firstOrCreate(['slug' => $slug], ['name' => $data]);
            }
        }

        throw new UnsupportedModelDataException();
    }

    /**
     * Convert a model into a translatable instance of itself
     *
     * @param $lang
     * @return Translated
     */
    public function translate($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return new Translated($lang, $this);
    }
}
