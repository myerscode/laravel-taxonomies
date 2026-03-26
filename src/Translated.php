<?php

namespace Myerscode\Laravel\Taxonomies;

class Translated extends Model
{
    protected $fillable = [];

    protected string $locale = '';

    protected string $type = '';

    public function __construct(string $locale = '', ?Model $model = null)
    {
        if ($model !== null) {
            $this->fillable = $model->getFillable();
            parent::__construct($model->toArray());
            $this->table = $model->getTable();
            $this->locale = $locale;
            $this->type = $this->table;
        } else {
            parent::__construct();
        }
    }

    public function getAttributeValue($key)
    {
        if ($key === 'name' && $this->locale !== '' && $this->type !== '') {
            $localeKey = $this->type . '.' . $this->slug;

            if (trans()->hasForLocale($localeKey, $this->locale)) {
                return trans($localeKey, [], $this->locale);
            }
        }

        return parent::getAttributeValue($key);
    }
}
