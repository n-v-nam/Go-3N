<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Storage;
use app\Exceptions\Handler;
use Illuminate\Support\Facades\Gate;
use App\Models\City;
use App\Models\DistanceCityVN;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as testClient;

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this->customer = new Customer();
    }

    public function login(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'phone' => 'string|required',
                'password' => 'required'
            ]);

            if ($validated->fails()) {
                return $this->failValidator($validated);
            }

            $credentials = request(['phone', 'password']);

            if (!Auth::guard('web')->attempt($credentials)) {
                return $this->badRequest('Wrong login information!');
            }

            $customer = Customer::where('phone', $request->phone)->first();

            if (!Hash::check($request->password, $customer->password, [])) {
                throw new \Exception('Wrong login information!');
            }

            $tokenResult = $customer->createToken('customerToken')->plainTextToken;
            $datas = [
                'customer_information' => $customer,
                'token' => [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer'
                ]
            ];
            return $this->withData($datas, 'Logged in successfully!');
        } catch (\Exception $error) {
            return $this->errorInternal('Login failed');
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->withSuccessMessage('Đăng xuất thành công!');
    }

    public function profile()
    {
        return $this->withData(Auth::user(), 'Thông tin người dùng');
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required|unique:customers,phone|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'password' => 'required|max:255|min:6',
            'sex' => 'required',
            'customer_type' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        try {
            $twilio->verify->v2->services($twilio_verify_sid)
                ->verifications
                ->create($request['phone'], "sms");
        }
        catch (\Exception $e) {
            return $this->sendError('invalid phone number');
        }

        if ($request->hasFile('avatar')) {
            $feature_image_name= $request['avatar']->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('public/photos/customer', $feature_image_name);
            $linkAvatar = url('/') . Storage::url($path);
            $customer = $this->customer->firstOrCreate([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'password' => Hash::make($request['password']),
                'sex' => $request['sex'],
                'customer_type' => $request['customer_type'],
                'avatar' => $linkAvatar,
            ]);
        }

        return $this->withData($customer, 'Chúng tôi đã gửi mã xác nhận đến số điện thoại của bạn!', 201);
    }

    public function activeAccount(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        try {
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create($data['verification_code'], array('to' => $data['phone']));
        }
        catch (\Exception $e) {
            return $this->sendError("Mã code không chính xác hoặc đã được sử dụng!");
        }
        if ($verification->valid) {
            $user = tap(Customer::where('phone', $request['phone']))->update([
                'is_verified' => true,
                'phone_verified_at' => Carbon::now(),
            ]);
            /* Authenticate user */
            return $this->withSuccessMessage('Kích hoạt tài khoản thành công!');
        }
        return $this->sendError('Mã code không chính xác!');
    }

    public function updateProfile(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'sex' => 'required',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = Auth::user();
        DB::beginTransaction();
        try {
            $customer->update([
                'name' => $request['name'],
                'sex' => $request['sex'],
            ]);
            if ($request->hasFile('avatar')) {
                $feature_image_name= $request['avatar']->getClientOriginalName();
                $path = $request->file('avatar')->storeAs('public/photos/customer', $feature_image_name);
                $linkAvatar = url('/') . Storage::url($path);
                $customer->avatar = $linkAvatar;
                $customer->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Lỗi Khi thêm update tin khách hàng');
        }

        return $this->withData(Auth::user(), 'Thông tin đã được cập nhật!');
    }

    public function forgetPassword(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'phone' => 'string|required|max:12',
        ]);

        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = $this->customer->where('phone', $request['phone'])->where('is_verified', 1)->first() ?? null;
        if ($customer) {
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_sid = getenv("TWILIO_SID");
            $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            $twilio = new Client($twilio_sid, $token);
            try {
                $twilio->verify->v2->services($twilio_verify_sid)
                    ->verifications
                    ->create($request['phone'], "sms");
            }
            catch (\Exception $e) {
                return $this->sendError('invalid phone number');
            }
        } else {
            return $this->sendError("số điện thoại này chưa được đăng ký");
        }

        return $this->withSuccessMessage("Chúng tôi đã gửi mã xác nhận đến số điện thoại của bạn!");
    }

    public function verifiedPhone(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        try {
            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create($data['verification_code'], array('to' => $data['phone']));
        }
        catch (\Exception $e) {
            return $this->sendError("The code is incorrect or has been used!");
        }
        if ($verification->valid) {
            /* Authenticate user */
            return $this->withSuccessMessage('Mã xác nhận chính xác!');
        }
        return $this->sendError('Mã xác nhận không chính xác!');
    }

    public function newPassword (Request $request)
    {
        $validated = Validator::make($request->all(), [
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'newPassword' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = $this->customer->where('phone', $request['phone'])->firstOrFail();
        $customer->password = Hash::make($request['newPassword']);
        $customer->save();

        return $this->withData($customer, "Đổi mật khẩu thành công!");
    }

    public function changePassword(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'oldPassword' => 'required|max:255',
            'newPassword' => 'required|max:255',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = Auth()->user();
        if (!password_verify($request['oldPassword'], $customer->password))
        {
            return $this->sendError('Mật khẩu cũ không chính xác!');
        }
        $customerUpdatePassword = $customer->update([
            'password' => Hash::make($request['newPassword']),
        ]);

        return $this->withData($customer, 'Đổi mật khẩu thành công!');
    }

}
