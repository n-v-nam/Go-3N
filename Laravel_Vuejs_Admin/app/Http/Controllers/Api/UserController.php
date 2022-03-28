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
use Illuminate\Support\Facades\Gate;
use App\Models\PasswordReset;
use Carbon\Carbon;
use App\Notifications\ResetPasswordRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SMTPValidateEmail\Validator as SmtpEmailValidator;

class UserController extends BaseController
{
    public function __construct(Request $request, UserServiceInterface $userService)
    {
        $this->UserService = $userService;
        $this->user = new User();
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
            return $this->withData($datas, 'Logged in successfully!');
        } catch (\Exception $error) {
            return $this->errorInternal('Login failed');
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->withSuccessMessage('Log out!');
    }

    public function index()
    {
        $users = $this->user->all();
        return $this->withData($users, 'List User');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users,email|max:255',
            'password' => 'required|max:255|min:6',
            'type' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $checkMailValid = $this->checkValidatedMail($request['email']);
        if (!$checkMailValid) {
            return $this->sendError('This email is not valid!');
        }
        if ($request->hasFile('avatar')) {
            $feature_image_name= $request['avatar']->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('public/photos/personnel', $feature_image_name);
            $linkAvatar = url('/') . Storage::url($path);
            $user = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'type' => $request['type'],
                'avatar' => $linkAvatar,
            ]);
        }

        return $this->withData($user, 'Create user successfully!', 201);
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        return $this->withData($user, 'User Detail');
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'type' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = $this->user->findOrFail($id);
        if ($user->type == 1 && Auth::user()->id != $id) {
            return $this->unauthorizedResponse();
        }
        if ($request->hasFile('avatar')) {
            $feature_image_name= $request['avatar']->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('public/photos/personnel', $feature_image_name);
            $linkAvatar = url('/') . Storage::url($path);
            $userupdate = $user->update([
                'name' => $request['name'],
                'type' => $request['type'],
                'avatar' => $linkAvatar,
            ]);
        }

        return $this->withData($userupdate, 'User has been updated!');
    }

    public function destroy($id)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $user = $this->user->findOrFail($id);
        if ($user->type == 1 || Auth::user()->id == $id) {
            return $this->unauthorizedResponse();
        }
        $user->delete();

        return $this->withSuccessMessage('User has been deleted!');
    }

    public function search(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'email|required|max:255'
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = User::where('email', $request['email'])->first() ?? null;

        return $this->withData($user, 'Search done');
    }

    public function profile()
    {
        return $this->withData(auth()->user(), 'User profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'type' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = Auth::user();
        if ($request->hasFile('avatar')) {
            $feature_image_name= $request['avatar']->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('public/photos/personnel', $feature_image_name);
            $linkAvatar = url('/') . Storage::url($path);
            $userupdate = $user->update([
                'name' => $request['name'],
                'type' => $request['type'],
                'avatar' => $linkAvatar,
            ]);
        }

        return $this->withData($userupdate, 'User has been updated!');
    }

    public function changePasswordProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'passwordOld' => 'required|max:255|min:6',
            'passwordNew' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = auth()->user();
        if (!password_verify($request['passwordOld'], $user->password))
        {
            return $this->sendError('Wrong old password!');
        }
        $user->update([
            'password' => Hash::make($request['passwordNew']),
        ]);

        return $this->withData($user, 'Password has been updated!');
    }

    public function changePassword(Request $request, $userId)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'password' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user = $this->user->findOrFail($userId);
        if ($user->type == 1 || Auth::user()->id == $userId) {
            return $this->unauthorizedResponse();
        }
        $user->update([
            'password' => Hash::make($request['password']),
        ]);

        return $this->withData($user, 'Password has been updated!');
    }

    public function sendMail(Request $request)
    {
        $checkMailValid = $this->checkValidatedMail($request->email);
        if (!$checkMailValid) {
            return $this->sendError('This email is not valid!');
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            $sendMail = $user->notify(new ResetPasswordRequest($passwordReset->token));
        }

        return $this->withSuccessMessage('We have e-mailed your password reset link!');
    }

    public function resetPassword(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 422);
        }
        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $validated = Validator::make($request->all(), [
            'password' => 'required|max:255|min:6',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $user->update([
            'password' => Hash::make($request['password'])
        ]);
        //$passwordReset->delete();

        return $this->withData($user, 'Password reset successful!');
    }

    public function checkValidatedMail($email)
    {
        $sender    = 'namxuanhoapro@gmail.com';
        $validator = new SmtpEmailValidator($email, $sender);
        $results   = $validator->validate();
        return $results[$email];
    }

}
