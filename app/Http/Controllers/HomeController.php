<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('beranda.index');
    }

    public function about()
    {
        return view('beranda.about');
    }
}
