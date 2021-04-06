<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index( Request $request ) {
        return $this->optionalPagination($request, User::where('acl', 1)->orWhere('acl', 2)->with('employee'));
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

    public function store( Request $request, $id ) {
        $validation = Validator::make( $request->all(), [
            'bank_account_number' => 'required',
            'bank_account_provider' => 'required|max:100',
            'division' => 'required|max:100',
            'title' => 'required|max:100',
        ]);

        if ($validation->fails())
            return $this->sendValidationError( $validation->errors() );

        $user = User::where( 'id', $id );

        if ( $user->doesntExist() )
            return $this->sendInvalidId('user');

        $user = $user->first();

        if ($user->employee()->exists())
            return response()->json(['message' => 'There is a record exists for this user.'], 400);

        $employee = new Employee;
        $userCompanyEmailPassword = Str::random(40);

        $employee->company_email_password = $userCompanyEmailPassword;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->bank_account_provider = $request->bank_account_provider;
        $employee->division = $request->division;
        $employee->title = $request->title;

        if ( $user->employee()->save( $employee ) )
            return response()->json([
                'message' => "The employee's data has been saved.",
                'data' => [
                    'company_email_password' => $userCompanyEmailPassword
                ]]);
        else
            return response()->json(['message' => "Failed to save the employee's data"], 500);
    }

    public function patch() {

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
}
