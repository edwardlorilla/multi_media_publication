<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Article::orderBy(request('column') ? request('column') : 'updated_at', request('direction') ? request('direction') : 'desc')->search(request('search'))->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'details' => 'required',
            'product_id' => 'required'
        ]);
        $article = new Article(['details' => $input['details']]);
        $product = \App\Product::whereId($input['product_id'])->first();
        $product->articles()->save($article);
        foreach ($product->subscriptions as $subscription) {
            $subscription->user->notify(new \App\Notifications\ProductWasUpdated($product, $article, 'Article', 'edit-article'));
        }
        return response()->json($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json(Article::whereId($article->id)->with(['products'])->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $input = $request->validate([
            'details' => 'required',
            'product_id' => 'required'
        ]);
        $product = \App\Product::whereId($input['product_id'])->first();
        $article->details = $input['details'];
        $article->save();
        foreach ($product->subscriptions as $subscription) {
            $subscription->user->notify(new \App\Notifications\ProductWasUpdated($product, $article, 'Article', 'edit-article'));
        }
        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        return response()->json($article->delete());
    }
}
