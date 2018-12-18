<?php

namespace Myerscode\Laravel\Taxonomies;

class Translated extends Model
{

    protected $fillable = [];

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $type;

    public function __construct(string $locale, Model $model)
    {
        $this->fillable = $model->getFillable();

        parent::__construct($model->toArray());

        $this->table = $model->getTable();
        $this->locale = $locale;
        $this->type = $this->table;
    }

    public function getAttributeValue($key)
    {
        if ($key == 'name') {
            $localeKey = $this->type . '.' . $this->slug;

            if (trans()->hasForLocale($localeKey, $this->locale)) {
                return trans($localeKey, [], $this->locale);
            }
        }

        return parent::getAttributeValue($key);
    }
}
