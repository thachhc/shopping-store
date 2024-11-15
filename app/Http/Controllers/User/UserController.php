<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //method open page dashboard
    public function index(){
        return view('dashboard');
    }
}
