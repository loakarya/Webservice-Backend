<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\CompanyAccount;
use App\Http\Resources\CompanyAccountResource;

class CompanyAccountController extends Controller
{
    public function index(Request $request) {
        return $this->optionalPagination($request,  CompanyAccount::select(['id', 'name', 'description', 'created_at', 'updated_at', 'deleted_at']) );
    }

    public function store(Request $request) {
        $validation = Validator::make( $request->all(), [
            'name' => 'required|max:100',
            'description' => 'max:200',
            'email' => 'required|max:200',
            'username' => 'max:200',
            'password' => 'required|max:200',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $companyAccount = new CompanyAccount;

        $companyAccount->name = $request->name;
        $companyAccount->description = $request->description;
        $companyAccount->email = $request->email;
        $companyAccount->username = $request->username;
        $companyAccount->password = Crypt::encryptString($request->password);

        Log::info( auth()->user()->first_name . ' ' . auth()->user()->last_name . '(' . auth()->id() . ') created ' . $companyAccount->name . ' password record.');

        return $this->sendActionResult( auth()->user()->companyaccount()->save($companyAccount) );
    }

    public function show(Request $request, $id) {
        $validation = Validator::make( $request->all(), [
            'password' => 'required',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        if (!Hash::check($request->password, auth()->user()->password))
            return response()->json(['message' => 'Invalid user password'], 401); 

        $companyAccount = CompanyAccount::where( 'id', $id );

        if ( $companyAccount->doesntExist() )
            return $this->sendInvalidId('company account');

        $companyAccount = $companyAccount->first();

        Log::info( auth()->user()->first_name . ' ' . auth()->user()->last_name . '(' . auth()->id() . ') accessed ' . $companyAccount->name . ' password record.');

        return response()->json([
            'data' => new CompanyAccountResource( $companyAccount )
        ]);
    }

    public function update(Request $request, $id) {
        $validation = Validator::make( $request->all(), [
            'name' => 'required|max:100',
            'description' => 'max:200',
            'email' => 'required|max:200',
            'username' => 'max:200',
            'password' => 'required|max:200',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $companyAccount = auth()->user()->companyaccount()->where( 'id', $id );

        if ( $companyAccount->doesntExist() )
            return $this->sendInvalidId('company account');
        
        $companyAccount = $companyAccount->first();

        $companyAccount->name = $request->name;
        $companyAccount->description = $request->description;
        $companyAccount->email = $request->email;
        $companyAccount->username = $request->username;
        $companyAccount->password = Crypt::encryptString($request->password);

        Log::info( auth()->user()->first_name . ' ' . auth()->user()->last_name . '(' . auth()->id() . ') updated ' . $companyAccount->name . ' password record.');

        return $this->sendActionResult( $companyAccount->save() );


    }

    public function destroy($id) {
        $companyAccount = auth()->user()->companyaccount()->where( 'id', $id );

        if ( $companyAccount->doesntExist() )
            return $this->sendInvalidId('company account');

        $companyAccount = $companyAccount->first();

        Log::info( auth()->user()->first_name . ' ' . auth()->user()->last_name . '(' . auth()->id() . ') removed ' . $companyAccount->name . ' password record.');

        return $this->sendActionResult( $companyAccount->delete() );
    }
}
