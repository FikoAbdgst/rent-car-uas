<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    public function dashboard()
    {
        $mobils = Mobil::orderBy('created_at', 'desc')->get();

        return view('home', compact('mobils'));
    }
}
