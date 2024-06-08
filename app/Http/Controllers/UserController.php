<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    
    public function create(){
        // create/view user form 
        return view('users.register');
    }
    // store user
    public function store(Request $request){
        // validate user
        $formfields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:3'],
        ]);
        //hash password
        $formfields['password']=bcrypt($formfields['password']);
        // create user
        $user = User::create($formfields);
        // login user
        auth()->login($user);
        // redirect to home
        return redirect('/')->with('message','User created and logged in');


    }
    // logout user
    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message','Logged out successfully');
    }
    // login user
    public function login(){
        return view('users.login');
    }
    public function authenticate(Request $request){
        // validate user
        $formfields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', ]

        ]);
        // check user
        if(auth()->attempt($formfields)){
            $request->session()->regenerate();
            return redirect('/')->with('message','Logged in successfully');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

}
