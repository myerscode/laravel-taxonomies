<?php

use Myerscode\Laravel\Taxonomies\Taxonomy;
use Myerscode\Laravel\Taxonomies\Term;

return [
    'taxonomy' => [
        'model' => Taxonomy::class,
    ],
    'term' => [
        'model' => Term::class,
    ],
];
