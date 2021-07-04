<?php

namespace App\Http\Controllers\Dealer\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
class LoginController extends Controller
{
    //
    public function showDealerLoginForm()
    {
        return view('dealer.auth.login', ['url' => 'dealer']);
    }

    public function dealerLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('dealer')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended('/dealer');
        }
        return back()->withInput($request->only('email', 'remember'));
    }
}
