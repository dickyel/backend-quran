<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    //
      /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,member',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 'member';
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['role'] = $user->role;
   
        return $this->sendResponse($success, 'User registered successfully.');
    }

       
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
 
        return $this->sendResponse([], 'User logged out successfully.');
    }


      /**
     * View profile api
     *
     * @return \Illuminate\Http\Response
     */
    public function viewProfile(Request $request): JsonResponse
    {
        $user = $request->user();
   
        return $this->sendResponse($user, 'User profile retrieved successfully.');
    }


    /**
     * Edit profile api
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6',
            'c_password' => 'sometimes|required_with:password|same:password|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        
        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $user->update($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        $success['role'] = $user->role;

        return $this->sendResponse($success, 'User profile updated successfully.');
    }
}
