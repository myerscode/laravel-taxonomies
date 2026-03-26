<?php

namespace Tests\Support;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Myerscode\Laravel\Taxonomies\HasTaxonomy;

#[Fillable([
    'slug',
    'title',
])]
class Post extends Model
{
    use HasTaxonomy;
}
