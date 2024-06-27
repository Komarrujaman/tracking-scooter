<?php

namespace App\Http\Controllers;

use App\Models\Scooter;
use Illuminate\Http\Request;

class ScooterController extends Controller
{

    public function index()
    {
        $scooter = Scooter::all();
        return view('scooter', compact('scooter'));
    }
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'scooter' => 'required|unique:scooters|max:255',
        ]);

        $scooter = new Scooter;
        $scooter->scooter = $request->scooter;
        $scooter->save();

        return redirect()->back();
    }
}
