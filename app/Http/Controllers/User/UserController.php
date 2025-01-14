<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class UserController extends Controller
{
    //method open page dashboard
    public function index()
    {
        return view('dashboard');
    }
    public function home()
    {
        return view('welcome');
    }
    public function welcome()
    {
        $saleProducts = Product::whereHas('tag', function ($query) {
            $query->where('name', 'sale');
        })->get();

        return view('welcome', compact('saleProducts'));
    }
}
