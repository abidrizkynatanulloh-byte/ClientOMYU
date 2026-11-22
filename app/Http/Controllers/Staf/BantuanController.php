<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;

class BantuanController extends Controller
{
    public function index()
    {
        return view('panel.staf.bantuan.index');
    }
}
