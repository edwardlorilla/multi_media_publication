<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Document extends Model
{
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'documents.details' => 1
        ],
    ];
    protected $fillable = ['details'];
    public function products()
    {
        return $this->morphToMany(\App\Product::class, 'taggable');
    }
}
