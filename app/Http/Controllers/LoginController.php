<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        abort_if(!$token, 401, __('auth.failed'));

        return [
            'access_token' => $token,
            'user' => Auth::user()
        ];
    }
}
