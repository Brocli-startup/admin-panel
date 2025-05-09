<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = \Auth::user();

        if($user->status == 0) {
            Auth::logout();
            return redirect()->back()->withErrors(['message' =>  __('auth.account_inactive')]);
        }
        if($request->login == 'user_login' && $user->user_type === 'user'){
            return redirect(RouteServiceProvider::FRONTEND);
        } 
        elseif($request->login == 'user_login' && $user->user_type !== 'user') {
            Auth::logout();
            return redirect()->back()->withErrors(['message' => 'You are not allowed to log in from here.']);
        }
        else{
            return redirect(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(RouteServiceProvider::HOME);
        // return redirect('/');
    }
}

