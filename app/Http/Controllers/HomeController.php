<?php

namespace App\Http\Controllers;

use App\Models\Scooter;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $listScooter = Scooter::where('status', 1)->get();
        // dd($listScooter);
        $dataScooter = Scooter::all();
        $passengers = $latestPassengers = Passenger::select('passengers.*', 'scooters.scooter', 'scooters.status')
            ->join('scooters', 'passengers.scooter_id', '=', 'scooters.id')
            ->where('scooters.status', false)
            ->whereIn('passengers.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('passengers')
                    ->groupBy('scooter_id');
            })
            ->get();

        // dd($passengers);
        return view('home', compact('listScooter', 'dataScooter', 'passengers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'scooter_id' => 'required|exists:scooters,id',
            'email' => 'nullable|email|unique:passengers,email',
            'name' => 'required|string|max:255',
            'duration' => 'required|string',
            'start' => 'required|date_format:Y-m-d\TH:i',
            'end' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Set status scooter to false (in use)
        $scooter = Scooter::find($data['scooter_id']);
        $scooter->status = false;
        $scooter->save();

        Passenger::create($data);

        return redirect()->back()->with('success', 'Passenger created successfully!');
    }
}
