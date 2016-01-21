<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function login()
    {
    	 return \Socialite::with('vkontakte')->redirect();
    }

    public function check(Request $request){
    	$user = \Socialite::driver('vkontakte')->user();
    	print_r($user); exit;
    }
}
