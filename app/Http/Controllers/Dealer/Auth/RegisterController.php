<?php

namespace App\Http\Controllers\Dealer\Auth;

use App\User;
use App\Models\Dealer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function showDealerRegisterForm()
    {
        return view('auth.register', ['url' => 'admin']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:dealers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    protected function createDealer(Request $request)
    {
        $this->validator($request->all())->validate();
        $dealer = Dealer::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->intended('login/dealer');
    }
}
