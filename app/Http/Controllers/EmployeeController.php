<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Employee;
use App\Events\EmployeeRegistered;

class EmployeeController extends Controller
{
    public function index( Request $request ) {
        return $this->optionalPagination($request, Employee::select()->with('user'));
    }

    public function show($id) {
        $employee = Employee::where( 'id', $id );

        if ( $employee->doesntExist() )
            return $this->sendInvalidId('employee');
            
        $employee = $employee->with('user')->first();

        return response()->json([
            'data' => $employee
        ]);
    }

    public function showMe() {
        return ( auth()->user()->employee()->with('user')->first() );
    }

    public function store( Request $request ) {
        $validation = Validator::make( $request->all(), [
            'email' => 'required|email|max:100|unique:users,email',
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:50',
            'birthday' => 'required|date',
            'gender' => 'required|boolean',
            'address' => 'required|max:200',
            'zip_code' => 'required|max:10',
            'city' => 'required|max:190',
            'province' => 'required|max:90',
            'employee_code' => 'required|integer|gte:0|unique:employees,employee_code',
            'private_email' => 'required|email|max:100|unique:employees,private_email',
            'bank_account_number' => 'required',
            'bank_account_provider' => 'required|max:100',
            'status' => 'required|max:50',
            'phone' => 'required|gte:0',
            'role' => 'required|max:100',
            'level' => 'required|max:50',
            'chapter' => 'required|max:100'
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = new User;
        $user->password = Hash::make(config('etc.employee_default_password'));
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->city = $request->city;
        $user->province = $request->province;
        $user->email_subs_agreement = 1;
        $user->email_verified_at = now();

        $accountSaveStatus = $user->save();

        if ( !$accountSaveStatus ) return response()->json(['message' => "Failed to save the new account."], 500);

        $employee = new Employee;
        $userCompanyEmailPassword = Str::random(40);

        $employee->employee_code = $request->employee_code;
        $employee->private_email = $request->private_email;
        $employee->company_email_password = Crypt::encryptString($userCompanyEmailPassword);
        $employee->bank_account_number = $request->bank_account_number;
        $employee->bank_account_provider = $request->bank_account_provider;
        $employee->status = $request->status;
        $employee->phone = $request->phone;
        $employee->role = $request->role;
        $employee->level = $request->level;
        $employee->chapter = $request->chapter;


        $employeeSaveStatus = $user->employee()->save( $employee );
        
        if ( !$employeeSaveStatus ) return response()->json(['message' => "Failed to save the employee's data."], 500);
        
        EmployeeRegistered::dispatch($employee);

        return response()->json([
            'message' => "The employee's account has been created.",
            'data' => [
                'company_email_password' => $userCompanyEmailPassword
            ]], 201);
    }

    public function patch() {

    }

    public function destroy($id) {
        $employee = Employee::where('id', $id);

        if ( $employee->doesntExist() )
            return $this->sendInvalidId('user');

        $employee = $employee->first();
        $user = $employee->user()->id;
        
        return $this->sendActionResult( $employee->delete() && $user->delete() );
    }

    public function randomizeCompanyEmailPassword() {
        if (!auth()->user()->employee()->exists())
            return response()->json(['message' => 'There employee record does not exists for this user.'], 400);
        
        $userCompanyEmailPassword = Str::random(40);

        $employee = auth()->user()->employee()->first();
        $employee->company_email_password = $userCompanyEmailPassword;

        if ( auth()->user()->employee()->save( $employee ) )
            return response()->json([
                'message' => "The employee's email password has been randomized.",
                'data' => [
                    'company_email_password' => $userCompanyEmailPassword
                ]]);
        else
            return response()->json(['message' => "Failed to randomize the employee's email password"], 500);
    }

    public function EmailLogIn() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('etc.roundcube_url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_COOKIEFILE, '');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '');
        $response = curl_exec($ch);

        preg_match('|<input type="hidden" name="_token" value="([A-z0-9]*)">|', $response, $matches);

        if ($matches) $token = $matches[1];
        else return response()->json(['message' => 'Cannot parse the CSRF form login token.'], 500);

        $email = auth()->user()->email;
        $password = Crypt::decryptString(auth()->user()->employee->company_email_password);

        $post_params = array(
            '_token' => $token,
            '_task' => 'login',
            '_action' => 'login',
            '_timezone' => '_default_',
            '_url' => '_task=login',
            '_user' => $email,
            '_pass' => $password
        );

        curl_setopt($ch, CURLOPT_URL, config('etc.roundcube_url') . '?_task=login');
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
            preg_match_all('/Set-Cookie: (.*)\b/', $response, $cookies);
            
            $cookie_return = array();

            foreach($cookies[1] as $cookie)
            {
                preg_match('|([A-z0-9\_]*)=([A-z0-9\_\-]*);|', $cookie, $cookie_match);
                if($cookie_match) {
                    $cookie_return[$cookie_match[1]] = $cookie_match[2];
                }
            }

            return response()->json(['token' => $cookie_return]);
        }
        else return response()->json(['message' => 'Failed to log in, please check your credentials.'], 401);
    }
}
