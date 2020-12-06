<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvents;

class Events extends Controller
{
    public function index(Request $req) {
        print_r($req);
    }
}
