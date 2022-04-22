<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerBookTruck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->customer_type === Customer::CUSTOMER_BOOK_TRUCK) {
            dd(5);
            return $next($request);
        }
        $response = [
            'success' => false,
            'message' => "Bạn không phải tài xế",
        ];

        return response()->json($response, 401);
    }
}
