<?php

namespace Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Myerscode\Laravel\Taxonomies\HasTaxonomy;

class Post extends Model
{
    use HasTaxonomy;

    protected $fillable = [
        'slug',
        'title',
    ];
}