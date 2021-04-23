<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
Use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
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
            User::where('acl', 0)
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
            User::withTrashed()
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
            User::onlyTrashed()
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
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required',
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:50',
            'birthday' => 'required|date',
            'gender' => 'required|boolean',
            'address' => 'required|max:200',
            'zip_code' => 'required|max:10',
            'city' => 'required|max:190',
            'province' => 'required|max:90',
            'email_subs_agreement' => 'required|boolean',
        ]);
        
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = new User;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->email_subs_agreement = $request->email_subs_agreement;

        $user->save();

        event(new Registered($user));

        return $this->sendActionResult( $user->save() );
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json([
            'status' => 'true',
            'data' => Auth::user()
        ]);
    }

    /**
     * Update the user detail
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateDetails(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:50',
            'birthday' => 'required|date',
            'gender' => 'required|boolean',
            'address' => 'required|max:200',
            'zip_code' => 'required|max:10',
            'city' => 'required|max:190',
            'province' => 'required|max:90',
            'email_subs_agreement' => 'required|boolean',
        ]);
     
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = User::find( Auth::id() );

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->email_subs_agreement = $request->email_subs_agreement;

        return $this->sendActionResult( $user->save() );
    }

    /**
     * Update the user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            'new_password_confirm' => 'required|same:new_password',
        ]);
     
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        if ( Hash::check( $request->old_password, auth()->user()->password  ) ) {
            $user = User::find( auth()->id() );
            $user->password = Hash::make($request->new_password);
            auth()->logout();
            return $this->sendActionResult( $user->save() );
        } else
            return response()->json([
                'status' => false,
                'message' => 'Incorrect old password.'
            ], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->logout();
        $user = User::find( Auth::id() );
        return $this->sendActionResult( $user->delete() );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserAcl(Request $request, $id)
    {
        $validation = Validator::make( $request->all(), [
            'new_role' => 'required|integer|min:0|max:2',
        ]);
        
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );
            
        $user = User::where( 'id', $id );

        if ( $user->doesntExist() )
            return $this->sendInvalidId('user');

        $user = $user->first();
        $user->acl = $request->new_role;
        
        return $this->sendActionResult( $user->save() );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function destroyUser(Request $request, $id)
    {
        $user = User::where('id', $request->id);

        if ( $user->doesntExist() )
            return $this->sendInvalidId('user');

        $user = $user->first();
        return $this->sendActionResult( $user->delete() );
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function nukeUser(Request $request)
    {
        $this->validate($request,[
            'id' => 'required|integer'
        ]);

        $user = User::onlyTrashed()->where( 'id', $request->id );

        if ( $user->doesntExist() )
            return $this->sendInvalidId('user');

        $user = $user->first();
        return $this->sendActionResult( $user->forceDelete() );
    }

    /**
     * Display the specified resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    */
    public function showUserDetails(Request $request)
    {   
        $this->validate($request,[
            'id' => 'required|integer'
        ]);

        $user = User::where( 'id', $request->id );

        if ( $user->doesntExist() ) 
            return $this->sendInvalidId('user');

        return response()->json([
            'status' => true,
            'data' => $user->first()
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function updateUserDetails(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'id' => 'required|integer',
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:50',
            'address' => 'required|max:200',
            'zip_code' => 'required|max:10',
            'city' => 'required|max:190',
            'province' => 'required|max:90',
            'country' => 'required|max:90'
        ]);
     
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = User::where( 'id', $request->id );

        if ( $user->doesntExist() )
            return $this->sendInvalidId('user');

        $user = $user->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->country = $request->country;
        
        return $this->sendActionResult( $user->save() );
    }

    public function search( Request $request ) {
        $validation = Validator::make( $request->all(), [
            'username' => 'required_without:email',
            'email' => 'required_without:username|email',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = User::where( 'username', $request->username);
        if ( $user->exists() )
            return $this->sendData( $user->get() );

        $user = User::where( 'email', $request->email);
        if ( $user->exists() )
            return $this->sendData( $user->get() );

        return $this->sendNotFound('user');
    }
}
