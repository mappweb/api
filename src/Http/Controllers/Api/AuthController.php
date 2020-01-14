<?php

namespace Mappweb\Api\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mappweb\Api\Helpers\ApiResponseHelper;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request){

        $result = $this->validateLogin($request);
        if(!$result->fails()) {
            if (Auth::attempt($this->credentials($request))) {
                return $this->sendLoginResponse($request);
            }else{
                return $this->sendFailedLoginResponse($request);
            }
        }else{
           return $this->sendFailedValidationResponse($result);
        }
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->username()       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        return $validator;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return (new ApiResponseHelper(200, 'Success', [
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        ]))->response();
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return (new ApiResponseHelper(410, 'Unauthorized', []))->response();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return (new ApiResponseHelper(200, 'Successfully logged out', []))->response();
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $result = $this->validateRegister($request->all());
        if($result->fails()) {
            return $this->sendFailedValidationResponse($result);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('Personal Access Token');

        return (new ApiResponseHelper(200, 'Success', [
            'access_token' => $success['token']->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($success['token']->token->expires_at)->toDateTimeString(),
        ]))->response();
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateRegister(array $data)
    {
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return $validator;
    }

    /**
     * Get the failed login response instance.
     *
     * @param $result
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function sendFailedValidationResponse($result)
    {
        return (new ApiResponseHelper(422, 'The given data was invalid.', $result->errors()->all()))->response();
    }
}
