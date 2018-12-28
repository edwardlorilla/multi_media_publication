<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Book::orderBy(request('column') ? request('column') : 'updated_at', request('direction') ? request('direction') : 'desc')->search(request('search'))->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'details' => 'required',
            'product_id' => 'required'
        ]);
        $book = new Book(['details' => $input['details']]);
        $product = \App\Product::whereId($input['product_id'])->first();
        $product->books()->save($book);
        foreach ($product->subscriptions as $subscription) {
            $subscription->user->notify(new \App\Notifications\ProductWasUpdated($product, $book, 'Book', 'edit-book'));
        }
        return response()->json($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
        {
        return response()->json(Book::whereId($book->id)->with(['products'])->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $input = $request->validate([
            'details' => 'required',
            'product_id' => 'required'
        ]);
        $product = \App\Product::whereId($input['product_id'])->first();
        $book->details = $input['details'];
        $book->save();
        foreach ($product->subscriptions as $subscription) {
            $subscription->user->notify(new \App\Notifications\ProductWasUpdated($product, $book, 'Book', 'edit-book'));
        }
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        return response()->json($book->delete());
    }
}
