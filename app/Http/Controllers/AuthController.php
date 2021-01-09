<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\API\BaseController;
use Validator;

use App\Models\User;

/**
 * Authenticate user with credentials or google account
 */
class AuthController extends BaseController
{
    /**
     * Redirect to google provider
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Provide informations coming from google user account
     * create an account if user not have account
     * and return response with access token
     */
    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $userFound = User::where('email', $user->email)->first();

            if ($userFound) {
                Auth::login($userFound);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt($this->generateRandomPassword())
                ]);
                Auth::login($newUser);
            }
            $connectedUser = Auth::user();
            $token =  $connectedUser->createToken('accessToken')->accessToken;
            return redirect()->to(
                env('FRONTEND_GOOGLE_LOGIN_REDIRECT')
                    . '?google_id=' . $user->id
                    . '&token=' . $token
            );
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Generate a random password for new user
     */
    private function generateRandomPassword()
    {
        return '123456dummy';
    }

    /**
     * Create new user account
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->errorsResponse($validator->errors(), 'Data invalid');
        }

        $userExist = User::where('email', $request->input('email'))->first();
        if ($userExist) {
            return $this->errorsResponse(null, 'email address already use', 409);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data['token'] = $user->createToken('accessToken')->accessToken;
        $data['name'] = $user->name;

        return $this->successResponse($data, 'User created successfully', 201);
    }

    /**
     * Login user from credentiels
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorsResponse($validator->errors(), 'Invalid data');
        }

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {

            $user = Auth::user();
            $data['token'] = $user->createToken('accessToken')->accessToken;

            return $this->successResponse($data, 'Login request successfully');
        } else {
            return $this->errorsResponse(null, 'Incorrect credentials', 401);
        }
    }
}
