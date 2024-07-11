<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }
}
