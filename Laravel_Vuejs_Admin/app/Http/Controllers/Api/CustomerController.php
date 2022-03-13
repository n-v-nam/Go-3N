<?php

namespace App\Http\Controllers\Api;

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

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this->customer = new Customer();
    }

    public function store(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
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

        return $this->withData($customer, 'Create customer profile successfully!', 201);
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
            $user = tap(Customer::where('phone', $request['phone']))->update([
                'is_verified' => true,
                'phone_verified_at' => Carbon::now(),
            ]);
            /* Authenticate user */
            return $this->withSuccessMessage('Account activated successfully!');
        }
        return $this->sendError('Incorrect code!');
    }

    public function index()
    {
        $users = $this->customer->where('is_verified', )->get();
        return $this->withData($users, 'List User');
    }

    public function show($id)
    {
        $customer = $this->customer->where('is_verified', 1)->findOrFail($id);
        return $this->withData($customer, 'Customer Detail');
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = $this->customer->findOrFail($id);
        $customer->update([
            'name' => $request['name'],
        ]);
        if ($request->hasFile('avatar')) {
            $feature_image_name= $request['avatar']->getClientOriginalName();
            $path = $request->file('avatar')->storeAs('public/photos/customer', $feature_image_name);
            $linkAvatar = url('/') . Storage::url($path);
            $customer->avatar = $linkAvatar;
            $customer->save();
        }

        return $this->withData($customer, 'Customer has been updated!');
    }

    public function destroy($id)
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $customer = $this->customer->findOrFail($id)->delete();

        return $this->withSuccessMessage('Customer has been deleted!');
    }

    public function search(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
        ]);
        if ($validated->fails()) {
            return $this->failValidator($validated);
        }
        $customer = Customer::where('phone', $request['phone'])->firstOrFail();

        return $this->withData($customer, 'Search done');
    }

    public function changePassword(Request $request, $customerId)
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
        $customer = $this->customer->findOrFail($customerId);
        $customer->update([
            'password' => Hash::make($request['password']),
        ]);

        return $this->withData($customer, 'Password has been updated!');
    }

}
