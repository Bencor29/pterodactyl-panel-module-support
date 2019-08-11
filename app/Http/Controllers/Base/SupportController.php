<?php

namespace Pterodactyl\Http\Controllers\Base;

use Illuminate\Http\Request;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\Ticket;

class SupportController extends Controller
{

    public function index()
    {
        $tickets = Ticket::get();
        return view('base/support', [
            'tickets' => $tickets
        ]);
    }

}
