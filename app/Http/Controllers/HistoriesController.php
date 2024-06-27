<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index($passenger_id)
    {
        $histories = History::where('passenger_id', $passenger_id)->get(['latitude', 'longitude']);
        // dd($histories);
        return view('map', compact('histories'));
    }
}
