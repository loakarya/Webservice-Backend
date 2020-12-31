<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ArticleCategory as Category;

class ArticleCategoryController extends Controller
{
    public function indexCategory() {
        return response()->json( Category::all() );
    }

    public function store( Request $request ) {
        $validation = Validator::make( $request->all(), [
            'name' => 'required'
        ]);

        if ($validation->fails()) return $this->sendValidationError( $validation->errors() );

        $category = new Category;
        $category->name = $request->name;
        
        return $this->sendActionResult( Auth::user()->articleCategories()->save( $category ) );
    }

    public function destroy( $id ) {
        $category = Auth::user()->articleCategories()->find( $id );
        if ( $category == null ) return $this->sendInvalidId('article category');

        return $this->sendActionResult( $category->delete() );
    }

    public function update( Request $request, $id ) {
        $validation = Validator::make( $request->all(), [
            'name' => 'required'
        ]);

        if ($validation->fails()) return $this->sendValidationError( $validation->errors() );

        $category = Auth::user()->articleCategories()->find( $id );
        if ( $category == null ) return $this->sendInvalidId('article category');

        $category->name = $request->name;
        return $this->sendActionResult( Auth::user()->articleCategories()->save( $category ) );
    }

    
}
