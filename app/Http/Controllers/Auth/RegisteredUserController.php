<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if(!empty($request->usertype)){
            $userType = $request->usertype;
        }else{
            $userType = 'user';
        }

        if(!empty($request->designation)){
            $designation = $request->designation;
        }else{
            $designation = Null;
        }
        $email = $request->email;
        $username = $request->username;
        $user = User::withTrashed()
        ->where(function ($query) use ($email, $username) {
            $query->where('email', $email)->orWhere('username', $username);
        })
        ->first();
        if($user){
            $message = trans('messages.login_form');
            return redirect()->back()->withErrors(['message' => $message]);
        }
        else{
            $user = User::create([
                'username' => $username ?? null,
                'first_name' => $request->first_name ?? null,
                'last_name' => $request->last_name ?? null,
                'contact_number' => $request->phone_number ?? null,
                'user_type' => $userType,
                'display_name' => $request->first_name." ".$request->last_name ?? null,
                'email' => $email ?? null,
                'password' => Hash::make($request->password) ?? null,
                'designation' => $request->designation,
                "usertype" => $request->usertype,
                "provider_id" => $request->provider_id,
                "providertype_id" => $request->providertype_id,
                "handymantype_id" => $request->handymantype_id,
                'status' => $request->status ?? 0,
            ]);
            if ($user->user_type == 'user' || $user->user_type == 'provider' || $user->user_type == 'handyman') {
                $id = $user->id;
                $user->assignRole($user->user_type);
                $verificationLink = route('verify',['id' => $id]);
                Mail::to($user->email)->send(new VerificationEmail($verificationLink));
                $message = 'Email Verification link has been sent to your email. Please Check your inbox';
                return redirect(route('auth.login'));
            }
        }
        event(new Registered($user));

        if(!empty($userType)){
            $user->assignRole($userType);
        }else{
            $user->assignRole('user');
        }

        if($request->register === 'user_register'){
            return redirect(RouteServiceProvider::FRONTEND);
        }else{
            return redirect(route('auth.login'));
        }
    }
}
