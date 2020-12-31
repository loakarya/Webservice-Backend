<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FAQ;

class FAQController extends Controller
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
            FAQ::select([ 'id', 'question', 'answer', 'category' ])
        );
    }

    public function indexOwned(Request $request)
    {
        return $this->optionalPagination(
            $request,
            User::find( Auth::id() )->faqs()
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
            FAQ::withTrashed()
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
            FAQ::onlyTrashed()
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
            'question' => 'required|max:200',
            'answer' => 'required|max:500'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $faq = new FAQ;
        $faq->question = $request->question;
        $faq->answer = $request->answer;

        return $this->sendActionResult( User::find( Auth::id() )->faqs()->save($faq) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer',
            'question' => 'required|max:200',
            'answer' => 'required|max:200'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $faq = User::find( Auth::id() )->faqs()->where('id', $request->id);
        
        if ( $faq->doesntExist() )
            return $this->sendInvalidId('FAQ');

        $faq = $faq->first();

        if ( $request->has('question' and $request->question != '') )
            $faq->question = $request->question;
        if ( $request->has('answer' and $request->answer != '') )
            $faq->answer = $request->answer;

        return $this->sendActionResult( User::find( Auth::id() )->faqs()->save($faq) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $faq = User::find( Auth::id() )->faqs()->where('id', $request->id);
        
        if ( $faq->doesntExist() )
            return $this->sendInvalidId('FAQ');
        
        return $this->sendActionResult( $faq->first()->delete() );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Result
     * @return \Illuminate\Http\Response
     */
    public function nuke(Request $request)
    {
        $validate = Validation::make( $request, [
            'id' => 'required|integer',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );
            
        $faq = User::find( Auth::id() )->faqs()->onlyTrashed()->where('id', $request->id);
        
        if ( $faq->doesntExist() )
            return $this->sendInvalidId('FAQ');
        
        return $this->sendActionResult( $faq->first()->forceDelete() );
    }
}
