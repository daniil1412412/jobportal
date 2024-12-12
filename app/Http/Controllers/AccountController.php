<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function registration(Request $request){
        return view('front.account.registration');
    }

    public function proccessRegistration(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password'
        ]);
        if($validator->passes()){
            $user = new User();
            $user ->name = $request->name;
            $user ->email = $request->email;
            $user ->password = Hash::make($request->password);
            $user ->save();
            
            session()->flash('success', 'You have registerd succesfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function login(Request $request){
        return view('front.account.login');
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->passes()){
            if(Auth::attempt([
                'email' => $request->email, 
                'password' => $request->password,
            ])){
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error', 'email or passwor is incorrect');
            }
        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    public function profile(){
        return view('front.account.profile');
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
