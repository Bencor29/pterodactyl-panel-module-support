<?php

namespace Pterodactyl\Http\Controllers\Base;

use Illuminate\Http\Request;
use Pterodactyl\Http\Controllers\Controller;

class SupportController extends Controller
{

    public function index()
    {
        return view('base/support');
    }

}
