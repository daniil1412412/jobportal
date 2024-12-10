<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
