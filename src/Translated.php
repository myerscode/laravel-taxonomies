<?php

namespace Myerscode\Laravel\Taxonomies;

use Override;

class Translated extends Model
{
    /** @var list<string> */
    #[Override]
    protected $fillable = [];

    protected string $locale = '';

    protected string $type = '';

    public function __construct(string $locale = '', ?Model $model = null)
    {
        if ($model instanceof Model) {
            $this->fillable = array_values($model->getFillable());
            parent::__construct($model->toArray());
            $this->table = $model->getTable();
            $this->locale = $locale;
            $this->type = $this->table;
        } else {
            parent::__construct();
        }
    }

    #[Override]
    public function getAttributeValue($key): mixed
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
