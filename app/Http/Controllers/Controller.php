<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $role_regular = 0;
    protected $role_admin = 1;
    protected $role_master = 2;

    public function optionalPagination($request, $data) {
        $this->validate($request, [
            'data_per_page' => 'integer'
        ]);

        if ( $request->filled('data_per_page') )
            return response()->json([
                'status' => true,
                'data' => $data->paginate($request->data_per_page)
            ]);
        else
            return response()->json([
                'status' => true,
                'data' => $data->get()
            ]);
    }

    public function sendInvalidId( $item = '<insert item name here>' ) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid ' . $item . ' id.'
        ], 404); 
    }

    public function sendServerError() {
        return response()->json([
            'status' => false,
            'message' => "Server error."
        ], 500);
    }

    public function sendSuccess() {
        return response()->json(['status' => true], 201);
    }

    public function sendActionResult( $action ) {
        if ( $action )
            return $this->sendSuccess();
        else   
            return $this->sendServerError();
    }

    public function sendData( $data ) {
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function sendNotFound( $item ) {
        return response()->json([
            'status' => false,
            'message' => ucwords($item) . ' not found.'
        ], 404);
    }

    public function sendValidationError ( $error ){
        return response()->json([
            'status' => false,
            'message' => $error
        ], 422 );
    }

    public function validateResult( \Illuminate\Http\Request $request, $rules ){
        $validation = Validator::make( $request->all(), $rules);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        return true;
    }
}
