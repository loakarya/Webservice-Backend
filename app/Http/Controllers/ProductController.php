<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\FeaturedProduct;
use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
        /**
     * Display a listing of the resource.
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->optionalPagination(
            $request,
            Product::select()
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexAll(Request $request)
    {
        return $this->optionalPagination( 
            $request,
            Product::withTrashed()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexTrashed(Request $request)
    {
        return $this->optionalPagination( 
            $request,
            Product::onlyTrashed()
        );
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexOwned(Request $request)
    {
        $this->optionalPagination( 
            $request,
            User::find( Auth::id() )->products()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComingSoon(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'picture_1' => 'required|image|max:2048',
            'picture_2' => 'image|max:2048',
            'picture_3' => 'image|max:2048',
            'picture_4' => 'image|max:2048',
            'picture_5' => 'image|max:2048',
            'title' => 'required|max:200',
            'description' => 'required|max:200',
            'order_link' => 'required|url|max:200',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );
        
        if ( !$request->picture->isValid() )
            return response()->json( [
                'status' => false,
                'message' => 'Invalid image file.'], 400 );

        $fileName = 'images/' . Auth::id() . '-' . time() . '.' . $request->picture_1->getClientOriginalExtension();
        $path = $request->picture->move( env('UPLOAD_DIR') . '\\images', $fileName );

        $product = new Product;
        $product->user_id = Auth::id();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->order_link = $request->order_link;
        $product->picture_url = $fileName;

        return $this->sendActionResult( $product->save() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'title' => 'required|max:200',
            'slug' => 'required|max:210',
            'detail' => 'required|max:2000',
            'material' => 'required|max:200',
            'thumbnail_url' => 'required|max:200',
            'picture_url_1' => 'required|max:200',
            'picture_url_2' => 'max:200',
            'picture_url_3' => 'max:200',
            'picture_url_4' => 'max:200',
            'picture_url_5' => 'max:200',
            'price' => 'required|integer',
            'discount' => 'required|integer',
            'category' => 'required|integer|gte:0|lte:2',
            'tokopedia_order_link' => 'max:200',
            'shopee_order_link' => 'max:200',
            'bukalapak_order_link' => 'max:200',
        ]);

        if ($validation->fails())
        return $this->sendValidationError( $validation->errors() );
    
        $product = new Product;
        $product->user_id = Auth::id();
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->detail = $request->detail;
        $product->material = $request->material;
        $product->thumbnail_url = $request->thumbnail_url;
        $product->picture_url_1 = $request->picture_url_1;
        $product->picture_url_2 = $request->picture_url_2;
        $product->picture_url_3 = $request->picture_url_3;
        $product->picture_url_4 = $request->picture_url_4;
        $product->picture_url_5 = $request->picture_url_5;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->category = $request->category;
        $product->tokopedia_order_link = $request->tokopedia_order_link;
        $product->shopee_order_link = $request->shopee_order_link;
        $product->bukalapak_order_link = $request->bukalapak_order_link;

        return $this->sendActionResult( $product->save() );
    }

    public function uploadImage( Request $request ) {
        $validation = Validator::make( $request->all(), [
            'upload' => 'required|image|max:2048',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );
        
        if ( !$request->upload->isValid() )
            return response()->json( [
                'status' => false,
                'message' => 'Invalid image file.'], 400 );

        $path = $request->file('upload')->store('public/product');

        return response()->json( [
            'url' => url(Storage::url($path))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $product = Product::where( 'id', $id )
                        ->select();

        if ( $product->exists() )
            return response()->json([
                'status' => true,
                'data' => $product->first()
            ]);
        else 
            return $this->sendInvalidId('product');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer',
            'picture' => 'image|max:2048',
            'title' => 'max:200',
            'description' => 'max:200',
            'order_link' => 'url|max:200',
        ]);

        if ( $validation->fails() )
            return $this->sendValidationError( $validation->errors() );

        $product = User::find( Auth::id() )->products()->where( 'id', $request->id );

        if ( $product->doesntExist() )
            return $this->sendInvalidId('product');

        $product = $product->first();

        if ( $request->has('title') and $request->title != '' )
            $product->title = $request->title;

        if ( $request->has('description')  and $request->description != '' )
            $product->description = $request->description;

        if ( $request->has('order_link')  and $request->order_link != '' )
            $product->order_link = $request->order_link;

        if ( $request->hasFile('picture') and $request->picture->isValid() )
            $product->picture_url = $request->picture_url;

        return $this->sendActionResult( $product->save() );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $product = User::find( Auth::id() )->products()->where( 'id', $id );

        if ( $product->doesntExist() )
            return $this->sendInvalidId('product');

        return $this->sendActionResult( $product->first()->delete() );
    }

    public function setFeatured(Request $request) {
        $validation = Validate::make( $request, [
            'id' => 'required|integer'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $product = Product::where( 'id', $request->id );

        if ( $product->doesntExist() )
            return $this->sendInvalidId('product');

        $product = $product->first();

        $featured = new FeaturedProduct;
        $featured->user_id = Auth::id();

        return $this->sendActionResult( $product->featured()->save( $featured ) );
    }

    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showFeatured()
    {
        return response()->json([
            'status' => true,
            'data' => FeaturedProduct::with('product:id,thumbnail_url,title')
                        ->get()
        ]);
    }
}
