<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)
                        ->whereDate('expiration_date', '>=', now())
                        ->first();

        if (!$coupon) {
            return back()->withErrors(['Invalid or expired coupon code.']);
        }

        session(['coupon' => $coupon]);

        return back()->with('success', 'Coupon applied successfully!');
    }
}
