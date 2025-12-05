<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class InfoController extends Controller
{
    public function index()
    {
        return view('staff.info');
    }

    public function genz()
    {
        return view('staff.info-genz');
    }
}
