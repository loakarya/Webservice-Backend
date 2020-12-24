<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\FeaturedArticle;
use App\Models\Article;
use App\Models\User;

class ArticleController extends Controller
{
        /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->optionalPagination(
            $request,
            Article::with([ 'user' => function($q) {
                $q->select('id', 'first_name', 'last_name');
            }])->select([ 'id', 'thumbnail_url', 'slug', 'title', 'subtitle', 'category', 'content', 'created_at'])
        );
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexAll(Request $request)
    {
        return $this->optionalPagination(
            $request,
            Article::withTrashed() 
        );
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexTrashed(Request $request)
    {
        return $this->optionalPagination(
            $request,
            Article::onlyTrashed()
        );
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexOwned(Request $request)
    {
        return $this->optionalPagination( 
            $request,
            Auth::user()->articles()
        );
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
            'thumbnail_url' => 'required|max:200',
            'slug' => 'required|max:60',
            'title' => 'required|max:50',
            'subtitle' => 'required|max:50',
            'category' => 'required|integer',
            'content' => 'required'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $article = new Article;
        $article->user_id = Auth::id();
        $article->title = $request->title;
        $article->category = $request->category;
        $article->content = $request->content;
        $article->slug = $request->slug;
        $article->subtitle = $request->subtitle;
        $article->thumbnail_url = $request->thumbnail_url;

        return $this->sendActionResult( $article->save() );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $article = Article::where( 'id', $id );

        if ( $article->exists() )
            return response()->json([
                'status' => true,
                'data' => $article->first()
            ]);
        else 
            $article = Article::where( 'slug', $id );

            if ( $article->exists() )
                return response()->json([
                    'status' => true,
                    'data' => $article->first()
                ]);
            else 
                return $this->sendInvalidId('article');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make( $request->all(), [
            'title' => 'required|max:200',
            'subtitle' => 'required|max:100',
            'content' => 'required',
            'thumbnail_url' => 'required',
            'category' => 'required|integer|gt:0',
            'slug' => 'required|max:50'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $article = Auth::user()->articles()->where( 'id', $request->id );

        if ( $article->doesntExist() )
            return $this->sendInvalidId('article');

        $article = $article->first();

        $article->title = $request->title;
        $article->subtitle = $request->subtitle;
        $article->content = $request->content;
        $article->thumbnail_url = $request->thumbnail_url;
        $article->category = $request->category;
        $article->slug = $request->slug;


        return $this->sendActionResult( $article->save() );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $article = Auth::user()->articles()->where( 'id', $id );

        if ( $article->doesntExist() )
            return $this->sendInvalidId('article');

        return $this->sendActionResult( $article->first()->delete() );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSomebody(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer',
            'title' => 'required|max:50',
            'category' => 'required|integer',
            'content' => 'required'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $article = Article::where( 'id', $request->id );

        if ( $article->doesntExist() )
            return $this->sendInvalidId('article');

        $article = $article->first();

        if ( $request->has('title') and $request->title != '' )
            $article->title = $request->title;

        if ( $request->has('category') and $request->category != '' )
            $article->category = $request->category;

        if ( $request->has('content') and $request->content != '' )
            $article->content = $request->content;

        $article->intervention = Auth::id();

        return $this->sendActionResult( $article->save() );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroySomebody(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $article = Article::where( 'id', $request->id );

        if ( $article->doesntExist() )
            return $this->sendInvalidId('article');

        $article = $article->first();
        $article->intervention = Auth::id();
        $article->save();

        return $this->sendActionResult( $article->delete() );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function nukeSomebody(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $article = Article::onlyTrashed()->where( 'id', $request->id );

        if ( $article->doesntExist() )
            return $this->sendInvalidId('article');

        return $this->sendActionResult( $article->first()->forceDelete() );
    }

    public function setFeatured(Request $request) {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $article = Article::where( 'id', $request->id );

        if ( $article->doesntExist() )
            return $this->sendInvalidId('article');

        $article = $article->first();

        $featured = new FeaturedArticle;
        $featured->user_id = Auth::id();

        return $this->sendActionResult( $article->featured()->save( $featured ) );
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
            'data' => FeaturedArticle::with([ 'article' => function($q) {
                        $q->select([ 'id', 'thumbnail_url', 'slug', 'title', 'subtitle', 'category', 'content', 'created_at']);
                        }])
                        ->select([ 'id', 'article_id'])
                        ->get()
        ]);
    }

    public function uploadImage( Request $request ) {
        $path = $request->file('upload')->store('public/article');

        return response()->json( [
            'url' => env('APP_URL') . Storage::url($path)
        ]);
    }
}
