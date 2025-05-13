<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {

        return view('index', [
            'galleryImages' => Gallery::all(),
        ]);
    }
}
