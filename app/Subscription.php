<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'product_id'];
    protected $appends = ['isSubscribe'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->morphToMany(\App\Product::class, 'taggable');
    }
    public function getIsSubscribeAttribute()
    {
        return $this->whereUserId(auth()->id())->exists();
    }
}
