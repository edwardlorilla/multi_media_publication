<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Product extends Model
{
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'products.name' => 1,
            'products.details' => 2,
        ],
    ];
    protected $appends = ['isSubscribe'];
    public function articles(){
        return $this->morphedByMany(Article::class, 'taggable');
    }
    public function books(){
        return $this->morphedByMany(Book::class, 'taggable');
    }
    public function documents(){
        return $this->morphedByMany(Document::class, 'taggable');
    }
    public function subscriptions(){
        return $this->morphedByMany(Subscription::class, 'taggable');
    }
    public function subscribe($userId = null){
        return $this->subscriptions()->save(new Subscription(['user_id' => $userId ?: auth()->id()]));
    }
    public function unsubscribe($userId = null){
        $this->subscriptions()->detach();
        return response()->json($this);
    }
    public function getIsSubscribeAttribute(){
        return $this->subscriptions()->whereUserId(auth()->id())->exists();
    }

}
