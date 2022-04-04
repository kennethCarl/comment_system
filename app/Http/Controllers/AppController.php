<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppController extends Controller
{
    public function index()
    {
        return view('app');
    }
}
