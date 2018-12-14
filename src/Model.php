<?php

namespace Myerscode\Laravel\Taxonomies;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Myerscode\Laravel\Taxonomies\Exceptions\UnsupportedModelDataException;

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
     * Find the record by its slug
     *
     * @param string $slug
     * @return mixed
     */
    public static function findByName(string $slug)
    {
        return self::where('name', '=', $slug)->get()->first();
    }

    /**
     * Add a new record
     *
     * @param $term
     * @return self
     * @throws UnsupportedModelDataException
     */
    public static function add($term)
    {
        if (is_array($term)) {
            return static::firstOrCreate($term);
        } else {
            if (is_string($term)) {
                $slug = str_slug($term);
                return static::firstOrCreate(['slug' => $slug], ['name' => $term]);
            }
        }

        throw new UnsupportedModelDataException();
    }
}