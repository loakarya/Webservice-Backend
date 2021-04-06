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
            'address' => 'required|max:200',
            'zip_code' => 'required|max:10',
            'city' => 'required|max:190',
            'province' => 'required|max:90',
            'country' => 'required|max:90'
        ]);
        
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = new User;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->country = $request->country;

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
            'address' => 'required|max:200',
            'zip_code' => 'required|max:10',
            'city' => 'required|max:190',
            'province' => 'required|max:90',
            'country' => 'required|max:90'
        ]);
     
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = User::find( Auth::id() );

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->country = $request->country;

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
            'new_password' => 'required',
            'new_password_confirm' => 'required',
        ]);
     
        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        if ( Hash::check( $request->old_password, auth()->user()->password  ) ) {
            if ( $request->new_password == $request->new_password_confirm ) {
                $user = User::find( auth()->id() );
                $user->password = Hash::make($request->new_password);
                auth()->logout();
                return $this->sendActionResult( $user->save() );
            } else 
                return response()->json([
                    'status' => false,
                    'message' => 'The password and the confirmation field is not same.'
                ], 422 );
        } else
            return response()->json([
                'status' => false,
                'message' => 'Incorrect old password.'
            ], 401);
    }

    /**
     * Update the user username
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUsername(Request $request)
    {

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

    public function login(Request $request) {
        $user = User::withTrashed()
                                ->where('username', $request->username);

        if ( $user->doesntExist() )
            return response()->json([
                'status' => false,
                'message' => 'Credential not found or incorrect.'
            ], 401);

        $user = $user->first();

        if ( Hash::check($request->password, $user->password) ) {
            if ( $user->trashed() )
                return response()->json([
                    'status' => false,
                    'message' => 'The user has been deleted.'
                ], 410);

            $apiToken = Str::random(128);

            $token = User::where( 'username', $request->username )
                        ->first()
                        ->token()
                        ->first();
            $token->api = $apiToken;
            $token->api_generated = time();
            $user->token()->save($token);

            $user->last_ip = $request->ip();

            $aliveFor = null;
            $role = null;
            switch( $user->role ){
                case $this->role_regular:
                    $aliveFor = '7 days';
                    $role = 'regular';
                break;

                case $this->role_admin:
                    $aliveFor = '3 days';
                    $role = 'admin';
                break;

                case $this->role_master:
                    $aliveFor = '1 day';
                    $role = 'master';
                break;
            }

            if ( $user->save() )
                return response()->json([
                    'status' => true,
                    'data' => [
                        'api_token' => $apiToken,
                        'role' => $role,
                        'alive_for' => $aliveFor,
                ]]);
            else
                $this->sendServerError();
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Credential not found or incorrect.'
            ]);
        }
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

    public function EmailLogIn() {
        // $response = Http::get('https://email.loakarya.co');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://staff.loakarya.co/email/roundcube/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_COOKIEFILE, '');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '');
        $response = curl_exec($ch);

        preg_match('|<input type="hidden" name="_token" value="([A-z0-9]*)">|', $response, $matches);

        // if($matches) {
        //     return $matches[1];
        // }
        // else {
        //     return FALSE;
        // }

        $token = $matches[1];

        // dd($token);

        $email = "friansh@loakarya.co";
        $password = 'JXd4@z4e*9q4';

        $post_params = array(
            '_token' => $token,
            '_task' => 'login',
            '_action' => 'login',
            '_timezone' => '_default_',
            '_url' => '_task=login',
            '_user' => $email,
            '_pass' => $password
        );

        curl_setopt($ch, CURLOPT_URL, "https://staff.loakarya.co/email/roundcube/". '?_task=login');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_params));
        $response = curl_exec($ch);
        $response_info = curl_getinfo($ch);
        
        if($response_info['http_code'] == 302)
        {
            // find all relevant cookies to set (php session + rc auth cookie)
            preg_match_all('/set-cookie: (.*)\b/', $response, $cookies);
            $cookie_return = array();

            foreach($cookies[1] as $cookie)
            {
                preg_match('|([A-z0-9\_]*)=([A-z0-9\_\-]*);|', $cookie, $cookie_match);
                if($cookie_match) {
                    $cookie_return[$cookie_match[1]] = $cookie_match[2];
                }
            }

            return $cookie_return;
        }
        else
        {
            throw new RoundCubeException('Login failed, please check your credentials.');
        }
    }
}
