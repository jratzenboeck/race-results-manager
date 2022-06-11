<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function create()
    {
        return view('races.create');
    }
}
