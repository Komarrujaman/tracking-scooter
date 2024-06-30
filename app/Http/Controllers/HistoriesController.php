<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = History::all();
        $passengers = $latestPassengers = Passenger::select('passengers.*', 'scooters.scooter', 'scooters.status')
            ->join('scooters', 'passengers.scooter_id', '=', 'scooters.id')
            ->where('scooters.status', false)
            ->whereIn('passengers.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('passengers')
                    ->groupBy('scooter_id');
            })
            ->get();
        return view('history', compact('histories', 'passengers'));
    }
    public function history($passenger_id)
    {
        $histories = History::where('passenger_id', $passenger_id)->get(['latitude', 'longitude']);
        // dd($histories);
        return view('map', compact('histories'));
    }
}
