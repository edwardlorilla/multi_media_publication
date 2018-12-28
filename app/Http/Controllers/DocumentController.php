<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Document::orderBy(request('column') ? request('column') : 'updated_at', request('direction') ? request('direction') : 'desc')->search(request('search'))->paginate());

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
        $document = new Document(['details' => $input['details']]);
        $product = \App\Product::whereId($input['product_id'])->first();
        $created = $product->documents()->save($document);
        foreach ($product->subscriptions as $subscription) {
            $subscription->user->notify(new \App\Notifications\ProductWasUpdated($product, $created, 'Document', 'edit-document'));
        }
        return response()->json($document);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        return response()->json(Document::whereId($document->id)->with(['products'])->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $input = $request->validate([
            'details' => 'required',
            'product_id' => 'required'
        ]);
        $product = \App\Product::whereId($input['product_id'])->first();
        $document->details = $input['details'];
        $document->save();
        foreach ($product->subscriptions as $subscription) {
            $subscription->user->notify(new \App\Notifications\ProductWasUpdated($product, $document, 'Document', 'edit-document'));
        }
        return response()->json($document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        return response()->json($document->delete());
    }
}
