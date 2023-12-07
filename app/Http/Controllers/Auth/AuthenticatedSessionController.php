<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // return view('auth.login');
        return view('welcome');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // $request->authenticate();

        
        // $request->session()->regenerate();

        // $user = $request->userInfo();

        // if ($user['name']=="kawajp") {
        //     return redirect()->intended(RouteServiceProvider::HOME);
        // } else {
        //     return redirect()->intended(RouteServiceProvider::LOGIN);
        // }






        $name = $request->name;
        $password = $request->password;
        $user = User::where('name',$name)->first();
        if (Auth::attempt(['name' => $name,'password' => $password])) {
            $user = User::find($user->id)->update(['status' => 1]);
            return redirect('/home');
        } else {
          return back();
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

        $user = Auth::user();
        User::find($user->id)->update(['status' => 0]);
    
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
