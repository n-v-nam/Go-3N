<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class UserController extends BaseController
{
    public function __construct(Request $request, UserServiceInterface $UserService)
    {
        $this->UserService = $UserService;
    }

    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'required'
            ]);

            if ($validated->fails()) {
                return $this->failValidator($validated);
            }

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return $this->badRequest('Wrong login information!');
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Wrong login information!');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $datas = [
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer'
            ];
            return $this->sendResponse($datas, 'Logged in successfully!');
        } catch (\Exception $error) {
            return $this->errorInternal('Login failed');
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->withSuccessMessage('Log out!');
    }

}
